<?php

namespace PICOExplorer\Services\ServiceModels;

use GuzzleHttp\Exception\RequestException;
use PICOExplorer\Exceptions\Exceptions\AppErrors\AsyncConnectionError;
use PICOExplorer\Exceptions\Exceptions\AppErrors\ConnectionError;
use PICOExplorer\Exceptions\Exceptions\AppErrors\ErrorInHttpRequestAttributes;
use PICOExplorer\Exceptions\Exceptions\AppErrors\SentRequestWithNoData;
use PICOExplorer\Http\Traits\AdvancedLoggerTrait;
use PICOExplorer\Services\TimerService\Timer;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

trait GuzzleProxyRequestTrait
{

    use AdvancedLoggerTrait;
    /**
     * @var array
     */
    protected $ProxyAttributes = [];

    /**
     * @var Timer
     */
    protected $ConnectionTimer;

    /**
     * @var array
     */
    protected $ProxyBaseAttributes = [
        'headers' => array(),
        'async' => true,
        'baseuri' => null,
        'options' => [
            'timeout' => 2,
        ],
    ];

    protected function setAttributes()
    {
        $this->ProxyAttributes['options'] = array_replace($this->ProxyBaseAttributes['options'], $this->ProxyAttributes['options']);
        $this->ProxyAttributes = array_replace($this->ProxyBaseAttributes, $this->ProxyAttributes);
    }

    protected function MakeRequest(string $RequestMethod, array $data, bool $sendAsJson = false, bool $ParseJSONReceived = false)
    {
        if ($sendAsJson) {
            $data = json_encode($data);
        }
        $rules = ['data' => 'required'];
        $ex = $this->ValidateData(['data' => $data], $rules);
        if ($ex) {
            throw new SentRequestWithNoData();
        }
        $request = new Request($RequestMethod, $this->ProxyAttributes['baseuri'], $this->ProxyAttributes['headers'], $data);
        $response = $this->getResponse($request, $this->ProxyAttributes['async'], $this->ProxyAttributes['options']);
        if ($ParseJSONReceived) {
            $response = json_decode($data);
        }
        return $response;
    }

    protected function getResponse(Request $request, bool $async, array $options)
    {
        $this->ConnectionTimer = $this->CreateConnectionTimer();
        $client = new Client();
        if ($async === true) {
            $promise = $client->sendAsync($request, $options);
            $promise->then(
                function (ResponseInterface $res,Request $request) {
                    $this->ProxySummary($request,$res,true);
                    return $res;
                },
                function (RequestException $ex,Request $request, ResponseInterface $res) {
                    $this->ProxySummary($request, $res, false);
                    throw new AsyncConnectionError(null, $ex);
                }
            );
            return $promise->wait();
        } else {
            try {
                $response = $client->send($request, $options);
                $this->ProxySummary($request,$response,true);
                return $response;
            } catch (GuzzleException $ex) {
                $this->ProxySummary($request,$response,false);
                throw new ConnectionError(null, $ex);
            }
        }

    }

    protected function ProxySummary($request,ResponseInterface $response,$wasSucessful)
    {
        if($wasSucessful){
            $title ='Connection Success';
            $level = 'info';
        }else{
            $title ='Connection Fail';
            $level = 'error';
        }
        $TempTime = $this->ConnectionTimer->Stop();
        $info = 'time = '. $TempTime . ' ms]' .PHP_EOL. 'status = '.$response->getStatusCode();
        $request->
        $dataArray = [

        ];
        $this->AdvancedLog('Operations',$level,$title,$info,$dataArray,null);
    }

    protected function ValidateProxyAttributes()
    {
        $rules = [
            'basuri' => 'required|string',
            'async' => 'required|boolean',
        ];
        $ex = $this->ValidateData($this->ProxyAttributes, $rules);
        if ($ex) {
            throw new ErrorInHttpRequestAttributes(null, $ex);
        }
    }


}
