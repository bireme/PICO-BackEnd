<?php

namespace PICOExplorer\Services\ServiceModels;

use GuzzleHttp\Client;
use PICOExplorer\Exceptions\Exceptions\AppError\ErrorWhileJSONDecoding;
use Exception;
use PICOExplorer\Exceptions\Exceptions\AppError\GuzzleProxyConnectionError;
use PICOExplorer\Facades\AdvancedLoggerFacade;

abstract class GuzzleProxyRequest extends PICOServiceModel
{

    protected function MakeRequest(string $RequestMethod, array $data, string $url, array $headers = [], array $settings = [],int $timeout=500, int $maxAttempts=3, bool $sendAsJson = false, bool $ParseJSONReceived = false)
    {
        if ($sendAsJson) {
            $data = json_encode($data);
        }
        $settings['form_params'] = $data;
        $settings['headers'] = $headers;
        $settings['timeout'] = $timeout;
        $result = $this->PerformConnection($RequestMethod, $url, $settings, $maxAttempts);
        if ($ParseJSONReceived) {
            try {
                $result = json_decode($result, true);
            } catch (Exception $ex) {
                throw new ErrorWhileJSONDecoding(['result . size' => strlen($result)], $ex);
            }
        }
        return $result;
    }

    private function PerformConnection(string $RequestMethod, string $url, array $settings, int $maxAttempts)
    {
        $wasSuccesful=false;
        $log = '';
        $attempt = 1;
        $result = 'Unset';
        $totaltime = 0;
        $client = new Client();
        while (true) {
            $log = 'Attempt ' . $attempt . ': ';
            $locallog = 'Unknown Error';
            $timer = $this->ServicePerformance->newConnectionTimer('proxytimer-' . get_class($this) . '-' . $url);
            try {
                $result = $this->AttemptMaker($RequestMethod, $url, $settings, $client, $wasSuccesful);
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
                $time=$timer->Stop();
                $totaltime = $totaltime + $time;
                $timetxt = ' [' . $time . ' ms]';
                $log = $log . $locallog . $timetxt . PHP_EOL;
            }
        }
        $info = [
            'url' => $url,
            'wasSuccesful' => $wasSuccesful,
            'log' => $log,
            'Method' => $RequestMethod,
            'settings' => $settings,
            'TotalTime' => $totaltime,
            'attempts' => $attempt,
            'maxattempts' => $maxAttempts,
        ];
        AdvancedLoggerFacade::ExternalConnectionInfo($wasSuccesful, $url, $attempt, $time, $settings, $log);
        if ($result === 'Unset') {
            throw new GuzzleProxyConnectionError($info);
        }
        return $result;
    }

    private function AttemptMaker(string $RequestMethod, string $url, array $settings, Client $client, bool &$wasSuccesful)
    {
        $response = $client->request($RequestMethod, $url, $settings);
        $result = (string)$response->getBody();
        $wasSuccesful = true;
        return $result;
    }

}
