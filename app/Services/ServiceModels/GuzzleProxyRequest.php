<?php

namespace PICOExplorer\Services\ServiceModels;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorPerformingGuzzleProxyRequest;
use GuzzleHttp\Client;
use PICOExplorer\Exceptions\Exceptions\AppError\ErrorWhileJSONDecoding;
use PICOExplorer\Services\AdvancedLogger\Services\Timer;
use PICOExplorer\Facades\AdvancedLoggerFacade;
use Exception;

abstract class GuzzleProxyRequest extends PICOServiceModel
{
    /**
     * @var Timer
     */
    protected $ConnectionTimer;

    /**
     * @var array
     */
    protected $settings = [
        'timeout' => 2,
    ];

    protected function MakeRequest(string $RequestMethod, array $data, string $url, array $headers = null, array $options = null, bool $sendAsJson = false, bool $ParseJSONReceived = false)
    {
        if ($sendAsJson) {
            $data = json_encode($data);
        }
        $settings['form_params'] =  $data;

        if ($headers) {
            $settings['headers'] = $headers;
        }

        if ($options) {
            $settings = array_replace($options, $settings);
        }

        $result = null;
        try {
            $this->ConnectionTimer = $this->CreateConnectionTimer();
            $result = null;
            $client = new Client();
            $response = $client->request($RequestMethod, $url, $settings);
            $result = (string)$response->getBody();
            $this->ProxySummary($settings, $result);
        } catch (Exception $ex) {
            $this->ProxySummary($settings);
            throw new ErrorPerformingGuzzleProxyRequest(['settings' => $settings], $ex);
        }

        if ($ParseJSONReceived) {
            try {
                $result = json_decode($result, true);
            } catch (Exception $ex) {
                throw new ErrorWhileJSONDecoding(['result.size' => strlen($result)], $ex);
            }
        }
        return $result;
    }

    protected function ProxySummary(array $settings, $results=null)
    {
        if ($results) {
            $title = 'Connection Success';
            $level = 'info';
            $settings['result.size'] = strlen($results);
        } else {
            $title = 'Connection Fail';
            $level = 'error';
        }
        $TempTime = $this->ConnectionTimer->Stop();
        $info = 'time = ' . $TempTime . ' ms]' . PHP_EOL . 'status = ';// . $response;
        $MainData = [
            'title' => $title,
            'info' => $info,
            'data' => ['settings' => $settings],
            'stack' => null,
        ];
        AdvancedLoggerFacade::AdvancedLog('Operations', $level, $MainData);
    }

}
