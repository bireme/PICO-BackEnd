<?php

namespace PICOExplorer\Services\ServiceModels;

use DOMDocument;
use Exception;
use DOMXPath;
use Ixudra\Curl\Facades\Curl;
use PICOExplorer\Exceptions\Exceptions\AppError\GuzzleProxyConnectionError;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLDOMLoadingError;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLDOMXPathError;
use PICOExplorer\Exceptions\Exceptions\AppError\XMLProcessingError;
use PICOExplorer\Facades\AdvancedLoggerFacade;
use PICOExplorer\Services\AdvancedLogger\Exceptions\DontCatchException;
use ServicePerformanceSV;

trait ToExternalSourceTrait
{

    protected function Connect(ServicePerformanceSV $ServicePerformance, string $RequestMethod, array $data, string $fullURL, array $headers, float $timeout=30.0, int $maxAttempts=3,bool $sendAsJson = false, bool $ParseJSONReceived = false,$extraArgument=null)
    {
        $imported =$this->MakeRequest($ServicePerformance,$RequestMethod, $data, $fullURL, $headers, $timeout, $maxAttempts, $sendAsJson, $ParseJSONReceived);
        $results = null;
        $XMLText=null;
        $DOM = new DOMDocument();
        try {
            $DOM->loadXML($imported);
        } catch (Exception $ex) {
            throw new XMLDOMLoadingError(['Imported' => $imported], $ex);
        }
        try {
            $DOMXpath = new DOMXPath($DOM);
        } catch (Exception $ex) {
            throw new XMLDOMXPathError(['Imported' => $imported], $ex);
        }
        try {
            $XMLText=$DOM->saveXML();
            $results = $this->processXML($DOMXpath,$XMLText,$extraArgument);

            return $results;
        } catch (Exception $ex) {
            throw new XMLProcessingError(['Imported' => $imported], $ex);
        }
    }

    protected function MakeRequest(ServicePerformanceSV $ServicePerformance, string $RequestMethod, array $data, string $fullURL, array $headers, float $timeout=30.0, int $maxAttempts=3, bool $sendAsJson = false, bool $ParseJSONReceived = false)
    {
        $wasSuccesful=false;
        $log = '';
        $attempt = 1;
        $result = 'Unset';
        $totaltime = 0;
        $timer=null;
        try {
            $timer = $ServicePerformance->newConnectionTimer('proxytimer-' . get_class($this) . '-' . $fullURL);
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

    abstract protected function processXML(DOMXPath $DOMXpath, string $XMLText,$extraArgument=null);
}
