<?php

namespace PICOExplorer\Http\Controllers\Test;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use PICOExplorer\Http\Controllers\Controller;
use Exception;
use PICOExplorer\Http\Controllers\PICO\ControllerModel;

class ConcurrencyTestController extends Controller
{

    public function index()
    {
        if (Session::has('exploreinputinfo')) {
            $exploreinputinfo = null;
            try {
                $data = Session::get('exploreinputinfo');
                Session::save();
                $exploreinputinfo = json_decode($data, true);
            } catch (Exception $ex) {
                $info = [
                    'title' => 'Server Error',
                    'code' => 500,
                    'message' => 'The data sent could not be decoded',
                ];
                return view('vendor.laravel-log-viewer.errortxt')->with(['data' => $info]);
            }
            try {
                foreach ($exploreinputinfo as $key => &$value) {
                    $value = json_decode($value, true);
                }
            } catch (Exception $ex) {
                $data = [
                    'title' => 'Server Error',
                    'code' => 500,
                    'message' => 'Key: ' . $key . ' could not be decoded',
                ];
                return view('vendor.laravel-log-viewer.errortxt')->with(['data' => $data]);
            }
            try {
                $title=$exploreinputinfo['data']['title'];
                $input=$exploreinputinfo['data']['input'];
                $title=trim(explode(' ',$title)[2]);
                echo view()->make('vendor.laravel-log-viewer.componentdebug')->with(['title' => $title,'input'=>$input]);
                $instance = new $title();
                if($instance instanceof  ControllerModel){
                    $result=null;
                    try {
                        $res = $instance->inputtest(json_encode($input));
                        $response=$res['response'];
                        $modeldata=$res['modeldata'];
                        $result = json_decode($response->content(),true);
                        if(in_array('Error',array_keys($result))){
                            $result['modeldata']=$modeldata;
                        }
                    }catch(\Throwable $ex){
                        $result=$ex->getMessage();
                    }
                }else{
                    $result = 'This object is not a MainController but a service. Please select a MainController to review';
                }
                echo view()->make('vendor.laravel-log-viewer.partials.componentdebugresults')->with(['result'=>$result]);
            } catch (Exception $ex) {
                $data = [
                    'title' => 'Server Error',
                    'code' => 500,
                    'message' => $ex->getMessage(),
                ];
                return view('vendor.laravel-log-viewer.errortxt')->with(['data' => $data]);
            }
        } else {
            $info = [
                'title' => 'User Bad Request',
                'code' => 400,
                'message' => 'No data was sent to review',
            ];
            return view('vendor.laravel-log-viewer.errortxt')->with(['data' => $info]);
        }
    }

    public function savePreviousInfo(Request $request)
    {
        $data = $request->getContent();
        Session::put('exploreinputinfo', $data);
        Session::save();
        return response('ok')->setStatusCode(200, 'Ok!');
    }

}
