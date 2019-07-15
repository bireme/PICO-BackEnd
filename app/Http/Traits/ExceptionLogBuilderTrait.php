<?php

namespace PICOExplorer\Http\Traits;

use Throwable;
use Exception;

trait ExceptionLogBuilderTrait
{
    use ExceptionBaseData;
    use ClientRequestDataTrait;
    use AdvancedLoggerTrait;

    public function ExceptionLogBuilder(Throwable $exception, string $channel, string $level)
    {
        $res = false;
        $currentdata = null;
        try {
            $this->MainLogger($exception, $channel, $level, $currentdata);
            $res = true;
        } catch (Exception $LogError) {
        } finally {
            try {
                if ($res === false) {
                    $this->AuxiliaryLogger($exception, $currentdata);
                    $res = true;
                }
            } catch (Exception $AuxiliaryLoggerError) {
            } finally {
                if ($res === false) {
                    $this->EmergencyLogger($exception, $currentdata);
                }
            }
        }
    }

    public function MainLogger(Throwable $exception, string $channel, string $level, &$currentdata)
    {
        $this->LogTest('alfa01');
        $currentdata['reqdata'] = $this->ClientInfo() ?? [];
        $this->LogTest('alfa02');
        $currentdata = $this->getExceptionBaseData($exception, false);
        $previous = $exception->getPrevious();
        $this->LogTest('alfa03');
        $currentdata['previousdata'] = null;
        if ($previous) {
            $currentdata['previousdata'] = ($this->getExceptionBaseData($previous, true)) ?? null;
        }
        $loopdata = [
            'title' => ['domix' => 'sum', 'sep' => ' - '],
            'slug' => ['domix' => 'domix', 'sep' => '. '],
            'code' => ['domix' => 'sum', 'sep' => ' - '],
            'message' => ['domix' => 'sum', 'sep' => '. '],
        ];
        $this->LogTest('alfa04');
        foreach ($loopdata as $key => $domixsep) {
            $currentdata[$key] = $currentdata[$key] ?? null;
            $currentdata['previousdata'][$key] = $currentdata['previousdata'][$key] ?? null;
            //$this->LogTest('beggining: '.$key.PHP_EOL.json_encode(['data'=>['current'=>$currentdata[$key], 'domix'=>$domixsep['domix'], 'sep'=>$domixsep['sep'],'previous'=>$currentdata['previousdata'][$key]]]));
            $currentdata[$key] = $this->StringDifferenceResolver($domixsep['domix'], $domixsep['sep'], $currentdata[$key], $currentdata['previousdata'][$key]);
        }
        $this->LogTest('alfa05');
        $currentdata['reqdata']['ip'] = $currentdata['reqdata']['ip'] ?? null;
        $currentdata['reqdata']['ip'] = $currentdata['reqdata']['ip'] ? ('ip = "' . $currentdata['reqdata']['ip'] . '"') : null;
        $currentdata['reqdata']['url'] = $currentdata['reqdata']['url'] ?? null;
        $currentdata['reqdata']['content'] = $currentdata['reqdata']['content'] ?? null;
        $currentdata['reqdata']['headers'] = $currentdata['reqdata']['headers'] ?? null;
        $currentdata['baselocation'] = ($currentdata['file'] . '(' . $currentdata['line'] . ')');
        if ($currentdata['reqdata']['url']) {
            $currentdata['baselocation'] = $currentdata['baselocation'] . ' @ ' . $currentdata['reqdata']['url'];
        }
        $this->LogTest('alfa06');
        $currentdata['data'] = $this->MixData($currentdata['data'], $currentdata['reqdata']['content'], $currentdata['reqdata']['headers'], $currentdata['previousdata']) ?? $currentdata['data'];
        $this->LogTest('alfa06a');
        $currentdata['location'] = ($this->buildLocation($currentdata['reqdata']['url'], $currentdata, $currentdata['previousdata'])) ?? $currentdata['baselocation'];
        $this->LogTest('alfa06b');
        $currentdata['trace'] = $currentdata['trace'] ?? $currentdata['previousdata']['trace']??null;
        $this->LogTest('alfa06c');
        if ($currentdata['code']) {
            $currentdata['title'] = '[' . $currentdata['code'] . '] ' . $currentdata['title'];
        }
        $this->LogTest('alfa07');
        $message = '';
        $this->AddLine($message, 'Location:'.PHP_EOL,$currentdata['location']);
        $this->AddLine($message, 'Ip:',$currentdata['reqdata']['ip']);
        $this->AddLine($message, 'Error:',$currentdata['slug']);
        $this->AddLine($message, 'Info',$currentdata['message']);
        $currentdata['infomsg']=$message;
        $this->LogTest('alfa08');
        $this->AdvancedLog($channel, $level, $currentdata['title'], $currentdata['infomsg'], $currentdata['data'], $currentdata['trace']);
    }

    protected function AddLine(string &$message, string $title, string $str=null){
        if(!($str) || strlen($str)===0){
            return;
        }
        if(strlen($message)){
            $message=$message.PHP_EOL;
        }
        $message=$message.$title.$str;
    }

    public function AuxiliaryLogger(Throwable $exception, $currentdata)
    {
        $msg = 'THIS IS AUXILIARY LOGGER. SOME FATAL ERROR OCURRED IN MAIN LOGGER - ';
        $titleErr = $msg . $exception->getCode() . ' -  ' . get_class($exception);
        $innermsg = $exception->getMessage();
        $infoErr = '';
        $data = json_decode($innermsg, true) ?? null;
        if (!($data)) {
            if (strlen($innermsg) > 30) {
                $infoErr = $innermsg;
            } else {
                $titleErr = $titleErr . ' - ' . $innermsg;
            }
            $data = [];
        }
        $data['ErrorDataInMainLogger'] = $currentdata;
        $infoErr = $infoErr . $exception->getFile() . '(' . $exception->getLine() . ')';
        $StackTraceErr = $this->AuxiliarProcessTrace($exception);
        $this->AdvancedLog('InternalErrors', 'critical', $titleErr, $infoErr, $data, $StackTraceErr);
    }

    public function EmergencyLogger(Throwable $ex, $currentdata)
    {
        $titleErr = $ex->getCode() . ' - ' . get_class($ex);
        $innermsg = $ex->getMessage();
        $infoErr = '';
        $data = json_decode($innermsg, true) ?? null;
        if (!($data)) {
            if (strlen($innermsg) > 30) {
                $infoErr = $innermsg;
            } else {
                $titleErr = $titleErr . ' - ' . $innermsg;
            }
            $data = [];
        }
        $data['ErrorDataInMainLogger'] = $currentdata;
        $msg = 'THIS IS EMERGENCY LOGGER. SOME FATAL ERROR OCURRED IN MAIN LOGGER AND IN AUXILIARY LOGGER - ';
        $errmessage = $msg . $titleErr;
        $errmessage = $errmessage . $infoErr;
        $errmessage = $errmessage . PHP_EOL . $ex->getFile() . '(' . $ex->getLine() . ')';
        $StackTraceErr = $this->AuxiliarProcessTrace($ex);
        $errmessage = $errmessage . PHP_EOL . $StackTraceErr;
        $this->innerLogger('InternalErrors', $errmessage, 'critical');
    }

    //SECONDARY METHODS
    /////////////////////




    protected function AuxiliarProcessTrace(Throwable $ex)
    {
        $StackTraceErr = $ex->getTraceAsString();
        $stack = $this->ProcessTrace($StackTraceErr) ?? $StackTraceErr;
        $arr = explode('Stack Report:', $stack);
        if (count($arr) > 1) {
            $res = $arr[1];
        } else {
            $res =  $arr[0];
        }
        return $res;
    }

    protected function LogTest($info)
    {
        $this->innerLogger('Emergency', $info, 'info');
    }


    protected function buildLocationInner(array $arr = null)
    {
        if (!($arr)) {
            return null;
        }

        $file = $arr['file'] ?? null;
        $line = $arr['line'] ?? null;
        if ($file) {
            $fileparts = explode('\\', $file);
            $filepartsrelevant = array_slice($fileparts, -3, 3, true) ?? null;
            $file = ('...\\' . join($filepartsrelevant, '\\')) ?? null;
        }

        if ($file && $line) {
            $file = $file . '(' . $line . ')';
        }
        return $file;
    }


    protected function buildLocation(string $url = null, array $arr1 = null, array $arr2 = null)
    {
        //['title','slug','code','message','data','line','file','trace','request','ip','url','content','headers']
        $location='';
        $file1 = $arr1 ? $this->buildLocationInner($arr1) : null;
        $file2 = $arr2 ? $this->buildLocationInner($arr2) : null;
        if($file1){
            $location=$location.'current: '.$file1.PHP_EOL;
        }
        if($file2){
            $location=$location.'previous: '.$file2.PHP_EOL;
        }
        if ($url) {
            $urlparts = explode('\\', $url);
            $urlpartsrelevant = array_slice($urlparts, 0, 3, true) ?? null;
            $url = '...\\' . join($urlpartsrelevant, '\\') ?? null;
            $location=$location.'url: '.$url.PHP_EOL;
        }
        return $location;
    }

    protected function MixData(array $currentdata = null, $contentdata = null, $headers = null, array $previousdata = null)
    {
        $results = [];
        foreach ($currentdata as $key => $val) {
            $results['current.' . $key] = $val;
        }
        foreach ($previousdata as $key => $val) {
            $results['previous.' . $key] = $val;
        }
        $results['headers'] = $headers;
        $results['content'] = $contentdata;
        return $results;
    }

    protected function StringDifferenceResolver(string $domix, string $sep, string $current = null, $previous = null)
    {
        if (!($current)) {
            return $previous;
        }
        if (!($previous)) {
            return $current;
        }
        if ($domix !== 'domix') {
            if ($domix === 'sum') {
                return $current . $sep . $previous;
            } else {
                return $current;
            }
        }
        $currentArr = explode(' ', $current);
        $previousArr = explode(' ', $previous);
        $currentCount = count($currentArr);
        $previousCount = count($previousArr);
        $phraseLength = 5;
        if ($currentCount < $phraseLength || $previousCount < $phraseLength) {
            return $current . $sep . $previous;
        }
        $previous = $this->CheckInnerSimilarities($currentArr, $previousArr, $currentCount, $phraseLength);
        return $current . $sep . $previous;
    }

    protected function CheckInnerSimilarities(array $inner, array $large, $innerCount, $phraseLength)
    {
        for ($count = 0; $count < ($innerCount - $phraseLength); $count++) {
            $phrase = join(' ', array_slice($inner, $count, $phraseLength));
            $large = str_replace($phrase, '', $large);
        }
        $txt = join(' ', $large);
        return $txt;
    }

}
