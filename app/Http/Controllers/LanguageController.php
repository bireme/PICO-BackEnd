<?php

namespace PICOExplorer\Http\Controllers;

use Config;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use AdvancedLoggerFacade;

class LanguageController extends Controller
{

    public function switchLang(string $lang)
    {
        $this->UpdateLocale($lang);
        $data = [];
        if (Session::has('PreviousData')) {
            try {
                $PreviousData = Session::get('PreviousData');
                Session::pull('PreviousData');
                $data = json_decode($PreviousData, true)['olddata'];
            } catch (Exception $ex) {
                $info = [
                    'title' => 'Server Error',
                    'code' => 500,
                    'message' => 'The data sent could not be decoded',
                ];
                return view('vendor.laravel-log-viewer.errortxt')->with(['data' => $info]);
            }
        }
        Session::save();
        return view('main')->with(['PreviousData' =>$data]);
    }


    public function savePreviousInfo(string $lang, Request $request)
    {
        $this->ValidatePreviousData($lang);
        $data = $request->getContent();
        Session::put('PreviousData', $data);
        Session::save();
        return response('ok')->setStatusCode(200, 'Ok!');
    }

    //PRIVATE METHODS

    private function ValidatePreviousData(string $lang)
    {
        if (!(array_key_exists($lang, Config::get('languages')))) {
            return abort(404);
        }
    }

    private function UpdateLocale(string $lang)
    {
        if (array_key_exists($lang, Config::get('languages'))) {
            app()->setLocale($lang);
            Session::put('locale', $lang);
            Session::save();
        } else {
            return abort(404);
        }
    }

}
