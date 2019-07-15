<?php


namespace PICOExplorer\Http\Traits;

use Illuminate\Support\Facades\Log;

trait AdvancedLoggerTrait
{
    protected function AdvancedLog(string $channel, string $level, string $title, string $info=null, array $data=null, string $StackTrace=null)
    {
        $title=str_replace('</br>',' - ',$title);
        $title=str_replace('\\r\\n',' - ',$title);
        $title=str_replace(PHP_EOL,' - ',$title);
        $basedata = [
            'stack' => $StackTrace,
            'data' => $data,
            'info' => $info,
        ];
        $finaldata = json_encode($basedata);
        $message = $title . PHP_EOL . $finaldata;
        $this->innerLogger($channel, $message, $level);
    }

    protected function innerLogger(string $channel, string $message, string $level)
    {
        $AvailableChannels = array_keys(config()->get('logging')['channels']);
        if (in_array($channel, $AvailableChannels)) {
            $ChannelObj = Log::channel($channel);
        } else {
            Log::channel('AppDebug')->critical('!!!CHANNEL NOT SET: '.$channel.PHP_EOL.$message);
            $ChannelObj = Log::channel('AppDebug');
        }
        switch($level){
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
                Log::channel('AppDebug')->critical('!!!WRONG LOG SEVERITY LEVEL: '.$level.PHP_EOL.$message);
                break;
        }
    }
}
