<?php

namespace PICOExplorer\Services\AdvancedLogger;

use PICOExplorer\Services\AdvancedLogger\Processors\BaseLoggers;
use Throwable;

class AdvancedLogger extends BaseLoggers
{
    public function ClientData()
    {
        $data = [];
        $req = request();
        $data['url'] = $req->url();
        $data['ip'] = $req->getClientIp();
        $data['content'] = $req->getContent();
        $headers = $req->header();
        if ($headers) {
            unset($headers['referer']);
            unset($headers['cookie']);
        }
        $data['headers'] = $headers;
        return $data;
    }

    public function DataTester($title, $data)
    {
        $this->SimpleLog('Emergency', 'info', $title, null, ['TestData' => $data], null);
    }

    public function LogConnectionInfo()
    {
        $ClientInfo = $this->ClientData();
        $Conntitle = $ClientInfo['ip'] .' - '. $ClientInfo['url'];
        $this->SimpleLog('Connections-In', 'info', $Conntitle, null, $ClientInfo, null);
    }

    public function ExternalConnectionInfo(bool $wasSuccess,string $url, int $attempts,int $time,array $settings,string $log)
    {
        $Conntitle = '['.$time.' ms]';
        if($wasSuccess){
            $Conntitle=$Conntitle.' - Success ['.$attempts.' tries] - ';
            $level='info';
            if($attempts>1 || $time>300){
                $level='warning';
            }
        }else{
            $Conntitle=$Conntitle.' - Failure ['.$attempts.' tries] - ';
            $level='error';
        }
        $Conntitle=$Conntitle.$url;
        $connectionData=[
            'success'=>$wasSuccess,
            'url'=>$url,
            'time'=>$time,
            'log'=>$log,
            'settings'=>$settings,
            'attempts'=>$attempts,
        ];
        $this->SimpleLog('Connections-Out', $level, $Conntitle, null, $connectionData, null);
    }

    public function LogTest($info)
    {
        $this->innerLogger('Emergency', $info, 'info');
    }

    public function AdvancedLog(string $channel, string $level, array $MainData)
    {
        $this->MainDataProcessor($MainData);
        $this->SimpleLog($channel, $level, $MainData['title'], $MainData['infomsg'], $MainData['data'], $MainData['trace']);
    }

    public function AuxiliarLog($exception, string $channel, string $level, $isLogException, $MainData = null)
    {
        $erres = false;
        try {
            $AuxiliaryData = $this->AuxiliaryLoggerData($exception, $MainData);
            $this->AuxiliaryLogger($AuxiliaryData, $channel, $level, $isLogException);
            $erres = true;
        } catch (Throwable $AuxiliaryLoggerError) {
            $this->ZeroLoggerHandler($AuxiliaryLoggerError);
        } finally {
            if ($erres === false) {
                $this->ZeroLoggerHandler($exception, $MainData);
            }
        }
    }

}
