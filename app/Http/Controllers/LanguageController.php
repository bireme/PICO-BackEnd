<?php

namespace PICOExplorer\Http\Controllers;

use Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Psr\Http\Message\ServerRequestInterface;

class LanguageController extends Controller
{

    //PUBLIC METHODS

    public function switchLang($lang)
    {
        $this->throttleMiddleware('switchLang');
        $this->UpdateLocale($lang);
        if(Session::has('PreviousData')){
            $PreviousData=Session::get('PreviousData');
            Session::pull('PreviousData');
        }else{
            $PreviousData=array();
        }
        Session::save();
        return view('main')->with(['PreviousData' => $PreviousData]);
    }


    public function savePreviousInfo($lang,ServerRequestInterface $request)
    {
        $this->ValidatePreviousData($lang,$request);
        Session::put('PreviousData', $request->getParsedBody());
        Session::save();
        return response('ok')->setStatusCode(200, 'Ok!');
    }

    //PRIVATE METHODS

    private function ValidatePreviousData($lang,&$request){
        if (!(array_key_exists($lang, Config::get('languages')))) {
            return abort(404);
        }
    }

    private function UpdateLocale($lang){
        if (array_key_exists($lang, Config::get('languages'))) {
            app()->setLocale($lang);
            Session::put('locale', $lang);
        }else{
            return abort(404);
        }
    }

    private function throttleMiddleware($caller){
        $this->middleware('loggedIn', ['only' => [
            'update' // Could add bunch of more methods too
        ]]);
    }

}
