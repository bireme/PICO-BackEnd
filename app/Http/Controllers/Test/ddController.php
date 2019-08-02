<?php

namespace PICOExplorer\Http\Controllers\Test;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use PICOExplorer\Facades\AdvancedLoggerFacade;
use PICOExplorer\Http\Controllers\Controller;
use Exception;

class ddController extends Controller
{

    public function index()
    {
        if (Session::has('ddinfo')) {
            $ddinfo = null;
            try {
                $data = Session::get('ddinfo');
                Session::pull('ddinfo');
                Session::save();
                $MainData = [
                    'title' => 'Ready to show dd data',
                    'data' => ['dddata' => $data],
                    'infomsg' => null,
                    'trace' => null,
                ];
                AdvancedLoggerFacade::SimpleLog('Operations', 'info', $MainData['title'], $MainData['infomsg'], $MainData['data'], $MainData['trace']);
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
            } catch (Exception $ex) {
                $data = [
                    'title' => 'Server Error',
                    'code' => 500,
                    'message' => 'Key: ' . $key . ' could not be decoded',
                ];
                return view('errortxt')->with(['data' => $data]);
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
        $MainData = [
            'title' => 'Preparing data for dd view',
            'data' => ['dddata' => $data],
            'infomsg' => null,
            'trace' => null,
        ];
        AdvancedLoggerFacade::SimpleLog('Operations', 'info', $MainData['title'], $MainData['infomsg'], $MainData['data'], $MainData['trace']);
        Session::put('ddinfo', $data);
        Session::save();
        return response('ok')->setStatusCode(200, 'Ok!');
    }

}
