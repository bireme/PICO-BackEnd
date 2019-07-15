<?php

namespace PICOExplorer\Http\Controllers\Test;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use PICOExplorer\Http\Controllers\Controller;
use Exception;
use PICOExplorer\Http\Traits\AdvancedLoggerTrait;

class ddController extends Controller
{
    use AdvancedLoggerTrait;

    public function index()
    {
        if (Session::has('ddinfo')) {
            $ddinfo=null;
            try {
                $data = Session::get('ddinfo');
                Session::pull('ddinfo');
                Session::save();
                $this->AdvancedLog('Operations', 'info', 'Ready to show dd data', $data, null, null);
                $ddinfo = json_decode($data, true);
            } catch (Exception $ex) {
                $info = [
                    'title' => 'Server Error',
                    'code' => 500,
                    'message' => 'The data sent could not be decoded',
                ];
                return view('vendor.laravel-log-viewer.errortxt')->with(['data' => $info]);
            }
            try {
                foreach ($ddinfo as $key => &$value) {
                    $value = json_decode($value, true);
                }
                return dd($ddinfo);
            }catch(Exception $ex){
                $data = [
                    'title' => 'Server Error',
                    'code' => 500,
                    'message' => 'Key: '.$key.' could not be decoded',
                ];
                return view('errortxt')->with(['data'=>$data]);
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
        $this->AdvancedLog('Operations', 'info', 'Preparing data for dd view', $data, null, null);
        Session::put('ddinfo', $data);
        Session::save();
        return response('ok')->setStatusCode(200, 'Ok!');
    }

}
