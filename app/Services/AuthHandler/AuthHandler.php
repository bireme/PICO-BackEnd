<?php

namespace PICOExplorer\Services\AuthHandler;

use Illuminate\Support\Facades\Session;

class AuthHandler
{
    public final function isAdmin()
    {
        if (!(Session::has('logauth'))) {
            return 0;
        }
        return Session::get('logauth');
    }

    private final function Role($level)
    {
        switch($level){
            case 0: return 'Guest';
                break;
            case 1: return 'Admin';
                break;
            case 2: return 'Master Admin';
                break;
            default: return 'error';
        }
    }

    public final function UserData()
    {
        if (!(Session::has('logauth'))) {
            return 'Guest';
        }
        $logauth = Session::get('logauth');
        $logemail = Session::get('logemail');
        $userData = $logemail.' - '.$this->Role($logauth);
        return $userData;
    }

    public final function Logout()
    {
        if (!(Session::has('logauth'))) {
            return false;
        }
        Session::forget('logauth');
        Session::forget('logemail');
        Session::save();
        return true;
    }

    public final function Login(array $userData)
    {
        $admins = $this->getAdmins();
        $level = 0;
        $email = $userData['email'];
        $password = $userData['password'];
        $admindata= $admins[$email]??null;
        if($admindata===null){
            return 0;
        }
        $adminpass = $admindata['password'];
        if($adminpass===$password){
            $level = $admindata['level'];
            $this->SaveSessionLogin($level,$email);
            return $level;
        }else{
            return 0;
        }
    }

    private function SaveSessionLogin(int $level,string $email)
    {
        Session::put('logemail', $email);
        Session::put('logauth', $level);
        Session::save();
    }

    private function IsPasswordOk(string $Password, string $UserName, array $admins)
    {
        if ($Password === $admins[$UserName]) {
            return true;
        }
        return false;
    }


    private function UserExistsInAdminArray(string $UserName, array $admins)
    {
        if (array_key_exists($UserName, $admins)) {
            return true;
        }
        return false;
    }

    private function getAdmins()
    {

        $admin_data = config('adminconfig');
        $mastersdata = $admin_data['MASTER_ADMIN_DATA'];
        $adminsdata = $admin_data['ADMIN_DATA'];
        $masters = $this->FormatData(explode(',', $mastersdata), 2);
        $admins = $this->FormatData(explode(',', $adminsdata), 1);
        return array_merge($masters,$admins);
    }

    private function FormatData(array $data, int $level)
    {
        $res = [];
        foreach ($data as $key => $userpass) {
            $tmp = explode(':', $userpass);
            if (count($tmp) === 0) {
                continue;
            }
            $res[$tmp[0]] = ['password' => $tmp[1], 'level' => $level];
        }
        return $res;
    }

}
