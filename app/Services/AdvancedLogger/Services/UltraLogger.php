<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInUltraLogger;
use PICOExplorer\Facades\AdvancedLoggerFacade;
use Throwable;

class UltraLogger
{


    public function createUltraLogger(string $title, $InitialData = null)
    {
        try {
            $logger = new UltraLoggerDevice($title);
            $this->MapArrayIntoUltraLogger($logger, 'InitialData', ['InitialData' => $InitialData], 3);
            return $logger;
        } catch (Throwable $ex) {
            throw new ErrorInUltraLogger(['Error' => $ex->getMessage(), 'place' => 'crreation'], $ex);
        }
    }

    public function UltraLoggerAttempt(UltraLoggerDevice &$logger, string $AttemptText)
    {
        try {
            $index = $logger->AddToUltraLogger($AttemptText, -1);
            return $index;
        } catch (Throwable $ex) {
            throw new ErrorInUltraLogger(['Error' => $ex->getMessage(), 'AttemptText' => $AttemptText], $ex);
        }
    }

    public function UltraLoggerSuccessfulAttempt(UltraLoggerDevice &$logger, int $index = null, String $PostText = null)
    {
        try {
            if ($index !== null) {
                $logger->SuccessfulAttempt($index, $PostText);
            }
        } catch (Throwable $ex) {
            throw new ErrorInUltraLogger(['Error' => $ex->getMessage()], $ex);
        }
    }

    public function MapArrayIntoUltraLogger(UltraLoggerDevice &$logger, string $title, array $MultiData, int $maxprintdepth, String $ShownInternalKeycontent = null, int $level = 0, bool $MapIndd = false)
    {
        try {
            $text = '===========' . PHP_EOL . '[Array Map] ' . $title . PHP_EOL;
            $text = $text . $this->MultiKeyMapper($MultiData, $maxprintdepth, $ShownInternalKeycontent, $MapIndd);
            $logger->AddToUltraLogger($text, $level);
        } catch (Throwable $ex) {
            throw new ErrorInUltraLogger(['Error' => $ex->getMessage(), 'text' => $text], $ex);
        }
    }


    public function InfoToUltraLogger(UltraLoggerDevice &$logger, string $text)
    {
        try {
            $logger->AddToUltraLogger($text, 0);

        } catch (Throwable $ex) {
            throw new ErrorInUltraLogger(['Error' => $ex->getMessage(), 'text' => $text], $ex);
        }
    }

    public function WarningToUltraLogger(UltraLoggerDevice &$logger, string $text)
    {
        try {
            $logger->AddToUltraLogger($text, 1);

        } catch (Throwable $ex) {
            throw new ErrorInUltraLogger(['Error' => $ex->getMessage(), 'text' => $text]);
        }
    }

    public function setSubTitle(UltraLoggerDevice &$logger, string $text)
    {
        try {
            $logger->setSubTitle($text);

        } catch (Throwable $ex) {
            throw new ErrorInUltraLogger(['Error' => $ex->getMessage(), 'text' => $text], $ex);
        }
    }

    public function ErrorToUltraLogger(UltraLoggerDevice &$logger, string $text)
    {
        try {
            $logger->AddToUltraLogger($text, 2);

        } catch (Throwable $ex) {
            throw new ErrorInUltraLogger(['Error' => $ex->getMessage(), 'text' => $text], $ex);
        }
    }

    public function saveUltraLoggerResults(UltraLoggerDevice $logger, bool $wasSuccesful, bool $isIntegration, bool $ddtest = false)
    {
        try {
            $loggerResults = $logger->get($wasSuccesful);
            $log = $loggerResults['log'];
            switch ($loggerResults['level']) {
                case 2:
                    $level = 'critical';
                    break;
                case 1:
                    $level = 'warning';
                    break;
                default:
                    $level = 'info';
                    break;
            }

            $titlebase = $loggerResults['title'];
            $newArr = array_chunk($log, 700, true);
            foreach ($newArr as $key => $Arr) {
                $pretitle='';
                if(count($newArr)>1){
                    $pretitle='[Part '.($key+1).'/'.count($newArr).'] ';
                }
                $title=$pretitle.$titlebase;
                $info = join($Arr, PHP_EOL);
                $this->LogDeCS($title, $info, $level, $isIntegration);
            }
        } catch (Throwable $ex) {
            throw new ErrorInUltraLogger(['Error' => $ex->getMessage()], $ex);
        }
    }

    //////////////////////////////////////////////////////
    /// innner
    /// ///////////////////////////////////////////////////
    ///

    private function LogDeCS(string $title, string $info, string $level, bool $isIntegration)
    {
        if ($isIntegration) {
            AdvancedLoggerFacade::DeCSIntegrationLogger($title, $info, null, $level);
        } else {
            AdvancedLoggerFacade::DeCSLogger($title, $info, null, $level);
        }
    }


    private function MultiKeyMapper(array $dataarray, int $maxprint = null, string $ShownInternalKeycontent = null, $MapIndd = false)
    {
        $Spacer = '  ';
        $maxdepth = 5;
        if (!($maxprint)) {
            if ($ShownInternalKeycontent) {
                $maxprint = 2;
            } else {
                $maxprint = $maxdepth;
            }
        } else {
            if ($maxprint > $maxdepth) {
                $maxprint = $maxdepth;
            }
        }

        $info = '===========================' . PHP_EOL;
        foreach ($dataarray as $datatitle => $data) {
            $local = $datatitle . ' => [' . PHP_EOL;
            $cont = $this->keyExplorer($data, $Spacer, $maxdepth, $maxprint, $ShownInternalKeycontent);
            if (!($cont)) {
                $cont = 'null';
            }
            $local = $local . $Spacer . $cont;
            $local = $local . ']' . PHP_EOL;
            $local = $local . '------------' . PHP_EOL;
            $info = $info . $local;
        }
        $info = $info . '===========================';
        if ($MapIndd) {
            dd(['info' => $info, 'dataarray' => $dataarray]);
        }
        return $info;
    }


    private function PrintFinalData(string $Spacer, $data)
    {
        if (is_array($data)) {
            return $Spacer . ' {' . count(array_keys($data)) . ' items}' . PHP_EOL;
        } elseif (is_string($data)) {
            $newdata = substr($data, 0, 40);
            if ($data !== $newdata) {
                $data = $newdata . '...';
            }
            return $Spacer . '   "' . $data . '""';
        } else {
            return $Spacer . ' ' . gettype($Spacer);
        }
    }

    private function keyExplorer($data, string $Spacer, int $maxdepth, int $maxprint, string $ShownInternalKeycontent = null)
    {
        $maxdepth--;
        $maxprint--;
        if (!(is_array($data))) {
            return $this->PrintFinalData($Spacer, $data) . PHP_EOL;
        }
        if ($maxdepth <= 0) {
            if ($ShownInternalKeycontent) {
                if (in_array($ShownInternalKeycontent, $data)) {
                    $cont = $data[$ShownInternalKeycontent];
                    if (!(is_string($cont))) {
                        $res = $cont;
                    } else {
                        $res = json_encode($cont);
                    }
                    return $ShownInternalKeycontent . ' => ' . $res . PHP_EOL;
                } else {
                    return null;
                }
            } else {
                return $this->PrintFinalData($Spacer, $data) . PHP_EOL;
            }
        }
        if ($maxprint <= 0 && (!($ShownInternalKeycontent))) {
            return $this->PrintFinalData($Spacer, $data);
        }
        $tmp = $data[$ShownInternalKeycontent] ?? null;
        if ($tmp) {
            if (is_string($tmp)) {
                $res = '"' . $tmp . '"';
            } else {
                $res = json_encode($tmp);
            }
            return $ShownInternalKeycontent . ' => ' . $res . PHP_EOL;
        }

        $info = '';
        foreach ($data as $key => $content) {
            $contentdata = $this->keyExplorer($content, $Spacer . '  ', $maxdepth, $maxprint, $ShownInternalKeycontent);
            if (!($contentdata)) {
                continue;
            }
            $local = '';
            if ($maxprint <= 0 && ($ShownInternalKeycontent)) {
                $local = $Spacer . $contentdata;
            } else {
                $local = $Spacer . $key . ' => [' . PHP_EOL;
                $local = $local . $Spacer . $contentdata;
                $local = $local . $Spacer . '  ' . ']' . PHP_EOL;
            }
            $info = $info . $local;
        }
        return $info;
    }

}
