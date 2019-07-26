<?php


namespace PICOExplorer\Services\AdvancedLogger\Processors;


use Throwable;
use PICOExplorer\Services\AdvancedLogger\Exceptions\Models\CustomException;

class CoreDataProcessor extends BaseDataProcessors
{
    protected function AuxiliaryLoggerData($ex, $MainData = null)
    {
        $AuxiliaryData = [];
        $AuxiliaryData['titleErr'] = $ex->getCode() . ' - ' . get_class($ex);
        //$premessage = $this->getMessageAndDataAuxiliar($ex);
        $AuxiliaryData['Message'] = $ex->getMessage();
        //$AuxiliaryData['Data'] = $premessage['Data'];
        $AuxiliaryData['File'] = $ex->getFile();
        $AuxiliaryData['Line'] = $ex->getLine();
        $AuxiliaryData['StackTraceErr'] = $this->AuxiliarProcessTrace($ex);
        $AuxiliaryData['CurrentData'] = $MainData;
        return $AuxiliaryData;
    }

    protected function getMessageAndDataAuxiliar(Throwable $exception)
    {
        $premessage = $exception->getMessage();
        $message = json_decode($premessage, true);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $message;
        } else {
            return $premessage;
        }

    }


    protected function AuxiliarDataProcessor(array $EmergencyData, string $Auxiliarymsg, bool $isLogException)
    {
        $result = [];
        $result['data'] = [];
        $result['info'] = '';
        $result['title'] = $EmergencyData['titleErr'] ?? null;
        if ($result['title']) {
            $result['title'] = $Auxiliarymsg . $result['title'];
        } else {
            $result['title'] = $Auxiliarymsg . 'Unknown Error';
        }
        if($isLogException){
            if (strpos($EmergencyData['Message'], 'Stack trace:') !== false) {
                $str = (explode('Stack trace:',$EmergencyData['Message'])[1]);
                $EmergencyData['Message']=explode('#1',$str)[0];
                $result['info'] = 'Previous origin: '.$EmergencyData['Message'];
            }
        }else {
            if ($EmergencyData['Message'] && strlen($EmergencyData['Message'])) {
                $data = json_decode($EmergencyData['Message'], true) ?? null;
                if (!($data)) {
                    if (strlen($EmergencyData['Message']) > 30) {
                        $result['info'] = $EmergencyData['Message'];
                    } else {
                        $result['title'] = $result['title'] . ' - ' . $EmergencyData['Message'];
                    }
                } else {
                    $result['data'] = $data;
                }
            }
        }
        if ($EmergencyData['CurrentData']) {
            $result['data']['ErrorDataInMainLogger'] = $EmergencyData['CurrentData'];
        }
        if ($EmergencyData['File']) {
            $tmpfile = $EmergencyData['File'];
            if ($EmergencyData['Line']) {
                $tmpfile = $tmpfile . '(' . $EmergencyData['Line'] . ')';
            }
            if($result['info']){
                $result['info'] = $tmpfile.PHP_EOL.PHP_EOL.$result['info'];
            }else{
                $result['info'] = $tmpfile;
            }

        }
        if (($result['info'] === null) || strlen($result['info']) === 0) {
            unset($result['info']);
        }
        if ($EmergencyData['StackTraceErr']) {
            $result['stack'] = $EmergencyData['StackTraceErr'];
        }
        return $result;
    }

    protected function MainDataProcessor(&$MainData)
    {
        $loopdata = [
            'title' => ['domix' => 'sum', 'sep' => ' - '],
            'slug' => ['domix' => 'domix', 'sep' => '. '],
            'code' => ['domix' => 'sum', 'sep' => ' - '],
            'message' => ['domix' => 'sum', 'sep' => '. '],
        ];
        foreach ($loopdata as $key => $domixsep) {
            $MainData[$key] = $MainData[$key] ?? null;
            //$this->LogTest('beggining: '.$key.PHP_EOL.json_encode(['data'=>['current'=>$MainData[$key], 'domix'=>$domixsep['domix'], 'sep'=>$domixsep['sep'],'previous'=>$MainData['previousdata'][$key]]]));
            $MainData[$key] = $this->StringDifferenceResolver($domixsep['domix'], $domixsep['sep'], $MainData[$key], ($MainData['previousdata'][$key] ?? null));
        }

        if($MainData['reqdata']??null){
            $MainData['reqdata']['ip'] = $MainData['reqdata']['ip'] ? ('ip = "' . $MainData['reqdata']['ip'] . '"') : null;
        }
        $MainData['baselocation']=null;
        if($MainData['file']??null){
            $tmpfile = $MainData['file'];
            if($MainData['line']??null){
                $tmpfile = $tmpfile. '(' . $MainData['line'] . ')';
            }
            $MainData['baselocation']=$tmpfile;
        }
        if ($MainData['reqdata']['url']??null) {
            $MainData['baselocation'] = $MainData['baselocation'] . ' @ ' . $MainData['reqdata']['url'];
        }
        $MainData['slug'] = $this->getTranslatedMessage($MainData['title'], $MainData['slug']);
        $MainData['data'] = $this->MixData($MainData['data'], $MainData['reqdata']['content']??null, $MainData['reqdata']['headers']??null, $MainData['previousdata']??null) ?? $MainData['data'];
        $MainData['location'] = ($this->buildLocation($MainData['reqdata']['url']??null, $MainData, $MainData['previousdata']??null)) ?? $MainData['baselocation'];
        $MainData['trace'] = $MainData['trace'] ?? $MainData['previousdata']['trace'] ?? null;
        if($MainData['trace'] && strlen($MainData['trace'])){
            $MainData['trace']=$this->ProcessTrace($MainData['trace']);
        }
        if ($MainData['code']) {
            $MainData['title'] = '[' . $MainData['code'] . '] ' . $MainData['title'];
        }
        $message = '';
        $this->AddLine($message, 'Location:  ' . PHP_EOL, $MainData['location']);
        $this->AddLine($message, 'Ip:  ', $MainData['reqdata']['ip']??null);
        $this->AddLine($message, 'Error:  ', $MainData['slug']);
        $this->AddLine($message, 'Info  ', $MainData['message']);
        $MainData['infomsg'] = $message;
    }
}
