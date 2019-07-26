<?php

namespace PICOExplorer\Services\AdvancedLogger\Services;

use PICOExplorer\Services\AdvancedLogger\AdvancedLogger;
use PICOExplorer\Services\AdvancedLogger\Exceptions\Models\CustomWarning;
use PICOExplorer\Services\AdvancedLogger\Traits\TranslatedMessageTrait;
use Throwable;
use Session;

class WarningLogger extends AdvancedLogger
{

    use TranslatedMessageTrait;

    public function WarningLogUserMessage(CustomWarning $warning, string $level){
        $currentdata = $this->getWarningBaseData($warning);
        $warningMsg = $this->BuildWarningMessage($currentdata);
        if(Session::has('AdvancedLoggerWarning')) {
            $data = Session::get('AdvancedLoggerWarning');
            $keys = array_keys($data);
            if(!(in_array('error',$keys))){
                if(in_array($level,$keys)){
                    if(array_key_exists($warningMsg['title'],$data[$level])){
                        $data[$level][$warningMsg['title']]= $data[$level][$warningMsg['title']].PHP_EOL.$warningMsg['content'];
                    }else{
                        $data[$level][$warningMsg['title']]=$warningMsg['content'];
                    }
                }else{
                    $data[$level]=[$warningMsg['title'] => $warningMsg['content']];
                }
            }
        }else{
            $data=[
                $level => [$warningMsg['title'] => $warningMsg['content']],
            ];
        }
        Session::put('AdvancedLoggerWarning', $data);
        Session::save();
    }

    public function WarningLogBuilder(CustomWarning $warning, string $channel, string $level){
        $res = false;
        $MainData = null;
        try {
            $this->MainLoggerData($warning, $MainData);
            $this->AdvancedLog($channel, $level, $MainData);
            $res = true;
        } catch (Throwable $LogError) {
            $this->AuxiliarLog($LogError,'Emergency','critical',true);
        } finally {
            if ($res === false) {
                $this->AuxiliarLog($warning,$channel,$level,false,$MainData);
            }
        }
    }

    protected function MainLoggerData(CustomWarning $warning, &$currentdata)
    {
        $currentdata['reqdata'] = $this->ClientData() ?? [];
        $currentdata = $this->getWarningBaseData($warning);
        $currentdata['previousdata'] = null;
    }

    protected function getWarningBaseData(CustomWarning $warning)
    {
        return [
            'title' => get_class($warning),
            'slug' => $this->getTranslatedMessage(get_class($warning),true),
            'code' => $warning->getCode(),
            'message' => $warning->getMessage(),
            'data' => $warning->getData(),
            'line' => $warning->getLine(),
            'file' => $warning->getFile(),
            'trace' => null,
        ];
    }

    protected function BuildWarningMessage(array $warningData)
    {
        $title = ($warningData['title'].' - '.$warningData['slug'])??'Unkwnown';
        $message = $warningData['message']??null;
        $data = $warningData['data']??null;
        return [
            'title' => $title,
            'content' => [
                'message' => $message,
                'data' =>$data,
            ]
        ];
    }
}
