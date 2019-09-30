<?php

namespace PICOExplorer\Services\AuthHandler;

use Illuminate\Support\Facades\Session;
use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInAuthService;
use Throwable;

class AuthHandler
{
    public final function isAdmin()
    {
        try {
            if (!(Session::has('logauth'))) {
                return 0;
            }
            return Session::get('logauth');
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }

    private final function Role($level)
    {
        try {
            switch ($level) {
                case 0:
                    return 'Guest';
                    break;
                case 1:
                    return 'Admin';
                    break;
                case 2:
                    return 'Master Admin';
                    break;
                default:
                    return 'error';
            }
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }

    public final function UserData()
    {
        try {
            if (!(Session::has('logauth'))) {
                return 'Guest';
            }
            $logauth = Session::get('logauth');
            $logemail = Session::get('logemail');
            $userData = $logemail . ' - ' . $this->Role($logauth);
            return $userData;
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }

    public final function Logout()
    {
        try {
            if (!(Session::has('logauth'))) {
                return false;
            }
            Session::forget('logauth');
            Session::forget('logemail');
            Session::save();
            return true;
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }

    public final function Login(array $userData)
    {
        try {
            $admins = $this->getAdmins();
            $level = 0;
            $email = $userData['email'];
            $password = $userData['password'];
            $admindata = $admins[$email] ?? null;
            if ($admindata === null) {
                return 0;
            }
            $adminpass = $admindata['password'];
            if ($adminpass === $password) {
                $level = $admindata['level'];
                $this->SaveSessionLogin($level, $email);
                return $level;
            } else {
                return 0;
            }
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }

    private function SaveSessionLogin(int $level, string $email)
    {
        try {
            Session::put('logemail', $email);
            Session::put('logauth', $level);
            Session::save();
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }

    private function IsPasswordOk(string $Password, string $UserName, array $admins)
    {
        try {
            if ($Password === $admins[$UserName]) {
                return true;
            }
            return false;
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }


    private function UserExistsInAdminArray(string $UserName, array $admins)
    {
        try {
            if (array_key_exists($UserName, $admins)) {
                return true;
            }
            return false;
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }

    private function getAdmins()
    {
        try {
            $admin_data = config('adminconfig');
            $mastersdata = $admin_data['MASTER_ADMIN_DATA'];
            $adminsdata = $admin_data['ADMIN_DATA'];
            $masters = $this->FormatData(explode(',', $mastersdata), 2);
            $admins = $this->FormatData(explode(',', $adminsdata), 1);
            return array_merge($masters, $admins);
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }

    private function FormatData(array $data, int $level)
    {
        try {
            $res = [];
            foreach ($data as $key => $userpass) {
                $tmp = explode(':', $userpass);
                if (count($tmp) === 0) {
                    continue;
                }
                $res[$tmp[0]] = ['password' => $tmp[1], 'level' => $level];
            }
            return $res;
        } catch (Throwable $ex) {
            throw new ErrorInAuthService(['Error' => $ex->getMessage()]);
        }
    }

}
