<?php

namespace PICOExplorer\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use PICOExplorer\Facades\AuthHandlerFacade;
use PICOExplorer\Http\Controllers\Controller;

class AdminLogin extends Controller
{

    public function showLogin()
    {
        return View::make('vendor.laravel-log-viewer.adminlogin');
    }

    public function doLogout()
    {
        $logout = AuthHandlerFacade::Logout(); // log the user out of our application
        if ($logout === true) {
            $data = [
                'title' => 'Logged out!',
                'message' => 'Redirecting to login',
            ];
            return response()->view('vendor.laravel-log-viewer.generic-info', compact('data'), 200)
                ->header("Refresh", "1;url=/admin/login");
        } else {
            $data = [
                'title' => 'Logout failed',
                'message' => 'You were not logged in. Redirecting to Login',
            ];
            return response()->view('vendor.laravel-log-viewer.generic-info', compact('data'), 200)
                ->header("Refresh", "1;url=/admin/login");
        }
    }

    public function doLogin()
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required|alphaNum|min:3'
        );
        $validator = Validator::make(Input::all(), $rules);

// if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('admin/login')
                ->withErrors($validator)// send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            // create our user data for the authentication
            $userdata = array(
                'email' => Input::get('email'),
                'password' => Input::get('password')
            );

            // attempt to do the login

            $level = AuthHandlerFacade::Login($userdata);
            if ($level > 0) {
                $userInfo = AuthHandlerFacade::UserData();
                $data = [
                    'title' => 'Logged In! '.$userInfo,
                    'message' => 'Redirecting to admin home',
                ];
                return response()->view('vendor.laravel-log-viewer.generic-info', compact('data'), 200)
                    ->header("Refresh", "1;url=/admin/home");
            } else {
                $data = [
                    'title' => 'Login failed!',
                    'message' => 'Redirecting to login. Retry please',
                ];
                return response()->view('vendor.laravel-log-viewer.generic-info', compact('data'), 200)
                    ->header("Refresh", "1;url=/admin/login");
            }
        }
    }
}
