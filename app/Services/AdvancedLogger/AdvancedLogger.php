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

    public function DataTester($title,$data){
        $this->SimpleLog('Operations', 'warning', $title, null, ['TestData' => $data], null);
    }

    public function LogConnectionInfo()
    {
        $ClientInfo = $this->ClientData();

        $Conntitle = $ClientInfo['ip'];
        $Coninfo = 'url: ' . $ClientInfo['url'];
        $dataArray = [
            'Request' => [
                'ip' => $ClientInfo['ip'],
                'headers' => $ClientInfo['headers'],
            ],
            'content' => $ClientInfo['content'],
        ];

        $this->SimpleLog('Connections', 'info', $Conntitle, $Coninfo, null, null);
        $this->SimpleLog('Operations', 'info', $ClientInfo['url'], null, $dataArray, null);
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
