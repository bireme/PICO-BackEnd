<?php

namespace PICOExplorer\Services\AdvancedLogger;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInAdvancerLoggerService;
use PICOExplorer\Services\AdvancedLogger\Processors\BaseLoggers;
use Throwable;

class AdvancedLogger extends BaseLoggers
{
    public function ClientData()
    {
        try {
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
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerLoggerService(['Error' => $ex->getMessage()]);
        }
    }

    public function DataTester($title, $data)
    {
        try {
            $this->SimpleLog('Emergency', 'info', $title, null, ['TestData' => $data], null);
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerLoggerService(['Error' => $ex->getMessage()]);
        }
    }

    public function LogConnectionInfo()
    {
        try {
            $ClientInfo = $this->ClientData();
            $Conntitle = $ClientInfo['ip'] . ' - ' . $ClientInfo['url'];
            $this->SimpleLog('Connections-In', 'info', $Conntitle, null, $ClientInfo, null);
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerLoggerService(['Error' => $ex->getMessage()]);
        }
    }

    public function DeCSLogger(string $title, string $info, array $data = null, string $level = 'info')
    {
        try{
        $ClientInfo = $this->ClientData();
        if ($level === 'warning') {
            $title = '[Warning] ' . $title;
        }
        if ($level === 'error') {
            $title = '[Error] ' . $title;
        }
        $posttitle = $ClientInfo['ip'] . ' - ' . $title;
        $this->SimpleLog('DeCS', $level, $posttitle, $info, $data, null);
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerLoggerService(['Error' => $ex->getMessage()]);
        }
    }

    public function DeCSIntegrationLogger(string $title, string $info, array $data = null, string $level = 'info')
    {
        try{
        $ClientInfo = $this->ClientData();
        if ($level === 'warning') {
            $title = '[Warning] ' . $title;
        }
        if ($level === 'error' || $level === 'critical') {
            $title = '[Error] ' . $title;
        }
        $posttitle = $ClientInfo['ip'] . ' - ' . $title;
        $this->SimpleLog('DeCSImporter', $level, $posttitle, $info, $data, null);
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerLoggerService(['Error' => $ex->getMessage()]);
        }
    }

    public function DeCSComponentError($class, array $params)
    {
        try{
        $ClientInfo = $this->ClientData();
        $posttitle = $ClientInfo['ip'] . ' - ' . 'Error';
        $posttitle = $posttitle . ' in: ' . get_class($class);
        $this->SimpleLog('Emergency', 'critical', $posttitle, null, $params, null);
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerLoggerService(['Error' => $ex->getMessage()]);
        }
    }

    public function ExternalConnectionInfo(bool $wasSuccess, string $url, int $attempts, int $time, array $settings, string $log)
    {
        try{
        $Conntitle = '[' . $time . ' ms]';
        if ($wasSuccess) {
            $Conntitle = $Conntitle . ' - Success [' . $attempts . ' tries] - ';
            $level = 'info';
            if ($attempts > 1 || $time > 300) {
                $level = 'warning';
            }
        } else {
            $Conntitle = $Conntitle . ' - Failure [' . $attempts . ' tries] - ';
            $level = 'error';
        }
        $Conntitle = $Conntitle . $url;
        $connectionData = [
            'success' => $wasSuccess,
            'url' => $url,
            'time' => $time,
            'log' => $log,
            'settings' => $settings,
            'attempts' => $attempts,
        ];
        $this->SimpleLog('Connections-Out', $level, $Conntitle, null, $connectionData, null);
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerLoggerService(['Error' => $ex->getMessage()]);
        }
    }

    public function LogTest($info)
    {
        try{
        $this->innerLogger('Emergency', $info, 'info');
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerLoggerService(['Error' => $ex->getMessage()]);
        }
    }

    public function AdvancedLog(string $channel, string $level, array $MainData)
    {
        try{
        $this->MainDataProcessor($MainData);
        $this->SimpleLog($channel, $level, $MainData['title'], $MainData['infomsg'], $MainData['data'], $MainData['trace']);
        } catch (Throwable $ex) {
            throw new ErrorInAdvancerLoggerService(['Error' => $ex->getMessage()]);
        }
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
