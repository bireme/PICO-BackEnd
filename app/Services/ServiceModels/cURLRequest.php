<?php

namespace PICOExplorer\Services\ServiceModels;

use Ixudra\Curl\Facades\Curl;
use Exception;
use PICOExplorer\Exceptions\Exceptions\AppError\GuzzleProxyConnectionError;
use PICOExplorer\Facades\AdvancedLoggerFacade;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;

abstract class cURLRequest extends PICOServiceModel
{

    protected function MakeRequest(string $RequestMethod, array $data, string $fullURL, array $headers, float $timeout=30.0, int $maxAttempts=3, bool $sendAsJson = false, bool $ParseJSONReceived = false)
    {

        $wasSuccesful=false;
        $log = '';
        $attempt = 1;
        $result = 'Unset';
        $totaltime = 0;
        $timer=null;
        try {
            $timer = $this->ServicePerformance->newConnectionTimer('proxytimer-' . get_class($this) . '-' . $fullURL);
            while (true) {
                $log = 'Attempt ' . $attempt . ': ';
                $locallog = 'Unknown Error';
                try {
                    $result = $this->AttemptMaker($fullURL, $timeout, $data, $headers, $sendAsJson, $ParseJSONReceived, $wasSuccesful);
                    if ($wasSuccesful) {
                        $locallog = 'Succesful connection. Result.size=' . strlen($result);
                        break;
                    }
                    if ($attempt >= $maxAttempts) {
                        $locallog = 'Max attempts [' . $attempt . '] reached';
                        break;
                    }
                } catch (Exception $ex) {
                    $locallog = $ex->getMessage();
                } finally {
                    $time = $timer->Stop();
                    $totaltime = $totaltime + $time;
                    $timetxt = ' [' . $time . ' ms]';
                    $log = $log . $locallog . $timetxt . PHP_EOL;
                }
            }
        }catch(DontCatchException $ex){
            //
        }finally{
            $info = [
                'url' => $fullURL,
                'data' => $data,
                'wasSuccesful' => $wasSuccesful,
                'log' => $log,
                'timeout' => $timeout,
                'Method' => $RequestMethod,
                'TotalTime' => $totaltime,
                'attempts' => $attempt,
                'maxattempts' => $maxAttempts,
            ];
            AdvancedLoggerFacade::ExternalConnectionInfo($wasSuccesful, $fullURL, $attempt, $time, $info, $log);
            if ($result === 'Unset') {
                throw new GuzzleProxyConnectionError($info);
            }
        }
        return $result;
    }

    private function AttemptMaker(string $fullURL, float $timeout, array $data,array $headers, bool $sendAsJson, bool $ParseJSONReceived, bool &$wasSuccesful){
        $response = Curl::to($fullURL)
            ->withData($data)
            ->withTimeout($timeout)
            ->post();
        $wasSuccesful = true;
        return $response;
    }

}
