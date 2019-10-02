<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

use PICOExplorer\Exceptions\Exceptions\AppError\ErrorInUltraLoggerDevice;
use Throwable;

class UltraLoggerDevice
{

    protected $log = [];
    protected $title;

    public function __construct(String $title)
    {
        try {
            $this->title = $title;
        } catch (Throwable $ex) {
            throw new ErrorInUltraLoggerDevice(['errors' => $ex->getMessage()], $ex);
        }
    }

    public function getSentData(int $index = null)
    {
        $arrayitem = $this->log[$index] ?? null;
        $sent = $arrayitem['sent'] ?? -999;
        return $sent;
    }

    public function setSubTitle(string $text){
        $this->title = $this->title .' - '.$text;
    }

    public function UltraLogerDeviceConnection($AttemptText, $sent)
    {
        $arrayitem = ['type' => 'Connection', 'text' => $AttemptText, 'sent' => $sent];
        array_push($this->log, $arrayitem);
        $index = count($this->log) - 1;
        return $index;
    }

    public function AddToUltraLogger(string $text, int $level = 0)
    {
        $type = null;
        switch ($level) {
            case -1:
                $type = 'Attempt';
                break;
            case 0:
                $type = 'Info';
                break;
            case 1:
                $type = 'Warning';
                break;
            default:
                $type = 'Error';
                break;
        }
        if ($level === -1) {
            $text = $text . '...';
        }
        $arrayitem = ['type' => $type, 'text' => $text];
        array_push($this->log, $arrayitem);
        $index = count($this->log) - 1;
        return $index;
    }

    public function SuccessfulAttempt(int $index = null, String $PostText = null)
    {
        $this->ModifyAttempt($index, false, $PostText);
    }

    public function ModifyAttempt(int $index = null, bool $makeFail = false, String $PostText = null)
    {
        $previous = $this->log[$index] ?? null;
        $text = $previous['text'] ?? null;
        $type = $previous['type'] ?? null;
        if (!($type === 'Connection' || $type === 'Attempt')) {
            return;
        }
        if (is_string($text)) {
            if ($makeFail) {
                $text = $text . 'Error!';
                $type = 'Error';
            } else {
                $text = $text . 'Successful!';
                $type = 'Info';
            }
            if ($PostText) {
                $text = $text . PHP_EOL . $PostText;
            }
            $this->log[$index]['type'] = 'Info';
            $arrayitem = ['type' => $type, 'text' => $text];
            array_push($this->log, $arrayitem);
        }
    }

    public function get(bool $wasSuccesful)
    {
        $level = 0;

        if ($wasSuccesful) {
            $finalitem = ['type' => 'Info', 'text' => 'Operation Successful!'];
        } else {
            $finalitem = ['type' => 'Error', 'text' => 'Operation not completed'];
        }

        foreach ($this->log as $index => $arrayitem) {
            if (!($arrayitem)) {
                continue;
            }

            $type = $arrayitem['type'] ?? null;
            if ($type === 'Attempt') {
                $this->ModifyAttempt($index, true);
                $type = 'Error';
            }
            if ($type === 'Connection') {
                $PostText = 'Data sent =' . json_encode($arrayitem['sent']);
                $this->ModifyAttempt($index, true, $PostText);
                $type = 'Error';
            }
        }
        array_push($this->log, $finalitem);

        $ErrorCount = 0;
        $WarningCount = 0;
        $results = [];

        foreach ($this->log as $index => $arrayitem) {
            if (!($arrayitem)) {
                continue;
            }
            $type = $arrayitem['type'] ?? null;
            $pretext = '';

            $text = $this->log[$index]['text'] ?? null;
            if (!($text)) {
                continue;
            }
            if ($type === 'Warning') {
                if ($level < 1) {
                    $level = 1;
                }
                $pretext = ' [Warning]';
                $WarningCount++;
            } elseif ($type === 'Error') {
                if ($level < 2) {
                    $level = 2;
                }
                $pretext = ' [Error]';
                $ErrorCount++;
            }

            if (!($text) || (!(is_string($text)))) {
                continue;
            }
            $localtxt = $index . $pretext . ' => **' . $text;
            array_push($results, $localtxt);
        }

        $title = $this->title;
        if ($ErrorCount > 0) {
            $title = $title . ' - ' . $WarningCount . ' Errors';
        }
        if ($WarningCount > 0) {
            $title = $title . ' - ' . $WarningCount . ' Warnings';
        }

        return ['level' => $level,
            'log' => $results,
            'title' => $title,
        ];
    }


}
