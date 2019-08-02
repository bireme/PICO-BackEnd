<?php


namespace PICOExplorer\Services\AdvancedLogger\Processors;

use Illuminate\Support\Facades\Log;
use Throwable;


class BaseLoggers extends CoreDataProcessor
{

    public function SimpleLog(string $channel, string $level, string $title, string $info = null, array $data = null, string $StackTrace = null)
    {
        $title = str_replace('</br>', ' - ', $title);
        $title = str_replace('\\r\\n', ' - ', $title);
        $title = str_replace(PHP_EOL, ' - ', $title);
        $basedata = [
            'stack' => $StackTrace,
            'data' => $data,
            'info' => $info,
        ];
        $finaldata = json_encode($basedata);
        $message = $title . PHP_EOL . $finaldata;
        $this->innerLogger($channel, $message, $level);
    }

    ////////////////////////////////////////////
    /// PROTECTED FUNCTIONS
    ////////////////////////////////////////////

    protected function ZeroLoggerHandler($exception,$MainData=null)
    {
        $errestwo = false;
        try {
            $EmergencyData = $this->AuxiliaryLoggerData($exception, $MainData);
            $this->EmergencyLogger($EmergencyData);
            $errestwo = true;
        } catch (Throwable $AuxiliaryLoggerError) {
            $this->ZeroLogger($AuxiliaryLoggerError);
        } finally {
            if ($errestwo === false) {
                $this->ZeroLogger($exception,$MainData);
            }
        }
    }

    protected function AuxiliaryLogger(array $AuxiliaryData,string $channel,string $level,bool $isLogException)
    {
        $loggermsg = 'THIS IS AUXILIARY LOGGER. SOME FATAL ERROR OCURRED IN MAIN LOGGER - ';
        $errdata=$this->AuxiliarDataProcessor($AuxiliaryData,$loggermsg,$isLogException);
        $this->SimpleLog($channel,$level, $errdata['title'] , $errdata['info'] , $errdata['data'] , $errdata['stack']);
    }

    protected function EmergencyLogger(array $AuxiliaryData)
    {
        $loggermsg = 'THIS IS EMERGENCY LOGGER. SOME FATAL ERROR OCURRED IN MAIN LOGGER AND IN AUXILIARY LOGGER - ';
        $errdata=$this->AuxiliarDataProcessor($AuxiliaryData,$loggermsg);
        $title = $errdata['title'];
        unset($errdata['title']);
        $this->innerLogger('Emergency', $title.PHP_EOL.json_encode($errdata), 'critical');
    }

    protected function ZeroLogger($ex,$MainData=null)
    {
        $loggermsg = 'THIS IS ZERO LOGGER. EVERY OTHER LOGGER FAILED - ';
        $title = ($loggermsg.get_class($ex))??'Unknown Error';
        $errdata=[
            'info'=>($ex->getMessage().PHP_EOL.$ex->getFile().'('.$ex->getLine().')')??null,
            'stack'=>($ex->getTraceAsString())??null,
        ];
        if($MainData){
            $errdata['data']=['ErrorDataInMainLogger' => $MainData];
        }
        $this->innerLogger('Emergency', $title.PHP_EOL.json_encode($errdata), 'emergency');
    }

    protected function innerLogger(string $channel, string $message, string $level)
    {
        $AvailableChannels = array_keys(config()->get('logging')['channels']);
        if (in_array($channel, $AvailableChannels)) {
            $ChannelObj = Log::channel($channel);
        } else {
            Log::channel('AppDebug')->critical('!!!CHANNEL NOT SET: ' . $channel . ' - ' . $message);
            $ChannelObj = Log::channel('AppDebug');
        }
        switch ($level) {
            case 'debug':
                $ChannelObj->debug($message);
                break;
            case 'info':
                $ChannelObj->info($message);
                break;
            case 'notice':
                $ChannelObj->notice($message);
                break;
            case 'warning':
                $ChannelObj->warning($message);
                break;
            case 'error':
                $ChannelObj->error($message);
                break;
            case 'critical':
                $ChannelObj->critical($message);
                break;
            case 'alert':
                $ChannelObj->alert($message);
                break;
            case 'emergency':
                $ChannelObj->emergency($message);
                break;
            default:
                Log::channel('AppDebug')->critical('!!!WRONG LOG SEVERITY LEVEL: ' . $level . PHP_EOL . $message);
                break;
        }
    }
}
