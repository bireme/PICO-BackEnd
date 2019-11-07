<?php


namespace PICOExplorer\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Session;
use PICOExplorer\Exceptions\Exceptions\ClientError\TheDataSentWasNotJSONEncoded;
use PICOExplorer\Facades\BIREMESearchIntegrationFacade;
use PICOExplorer\Http\Controllers\PICO\ControllerModel;
use PICOExplorer\Models\DataTransferObject;

class BIREMEIntegrationController extends ControllerModel
{
    public function getMainModel()
    {
        return DataTransferObject::getModel('basic');
    }

    public function ServiceBind()
    {
        return new BIREMESearchIntegrationFacade();
    }

    public function explore()
    {
        if (Session::has('ExploreURL')) {
            try {
                $content = Session::get('ExploreURL');
                echo $content;
            } catch (Exception $ex) {
                $info = [
                    'title' => 'Server Error',
                    'code' => 500,
                    'message' => 'Internal Error',
                ];
                return view('vendor.laravel-log-viewer.errortxt')->with(['data' => $info]);
            }
        }else{
            $info = [
                'title' => 'Client Error',
                'code' => 400,
                'message' => 'You must click results button first',
            ];
            return view('vendor.laravel-log-viewer.errortxt')->with(['data' => $info]);
        }
    }

}
