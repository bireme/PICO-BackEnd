<?php


namespace PICOExplorer\Services\AdvancedLogger\Processors;

use PICOExplorer\Services\AdvancedLogger\Traits\TranslatedMessageTrait;
use Throwable;

class BaseDataProcessors
{

    use TranslatedMessageTrait;

    protected function ProcessTrace(string $TraceAsString)
    {
        $Tracetxt = explode('#', $TraceAsString);
        $TracerArr = array();
        $vendorCount = 0;
        $illuminateCount = 0;
        $totalcount = 0;
        foreach ($Tracetxt as $txt) {
            if (strlen($txt) < 3 || $totalcount > 5) {
                continue;
            }
            if (strpos($txt, '\\') === false) {
                continue;
            }
            if (strpos($txt, 'vendor') === false) {
                if (strpos($txt, 'Illuminate') === false) {
                    array_push($TracerArr, $this->ProcessStackLine($txt));
                    $totalcount++;
                } elseif ($illuminateCount < 3) {
                    array_push($TracerArr, $this->ProcessStackLine($txt));
                    $illuminateCount++;
                    $totalcount++;
                }
            } elseif ($vendorCount < 1) {
                array_push($TracerArr, $this->ProcessStackLine($txt));
                $vendorCount++;
                $totalcount++;
            }
        }
        if(count($TracerArr)===0){
            return null;
        }
        return PHP_EOL . 'Stack Report:' . PHP_EOL . join('', $TracerArr);
    }

    protected function ProcessStackLine(string $txt)
    {
        $Tracetxt = explode('):', $txt);
        if (count($Tracetxt) > 1) {
            $newtxt = $Tracetxt[1];
            $tmp = explode('\\', $Tracetxt[0]);
            if (count($tmp) > 1) {
                $offset = min(3, count($tmp));
                $tmp3 = '...\\' . join('\\', array_slice($tmp, -$offset, $offset, true));
                $tmp2 = explode(' ', reset($tmp))[0] . $tmp3 . '):';
            } else {
                $tmp2 = explode(' ', reset($tmp))[0] . '):';
            }
            $newtxt = $tmp2 . $newtxt;
        } else {
            $newtxt = $txt;
        }
        return '#' . $newtxt;
    }

    protected function AuxiliarProcessTrace(Throwable $ex)
    {
        $StackTraceErr = $ex->getTraceAsString();
        $stack = $this->ProcessTrace($StackTraceErr) ?? $StackTraceErr;
        $arr = explode('Stack Report:', $stack);
        if (count($arr) > 1) {
            $res = $arr[1];
        } else {
            $res = $arr[0];
        }
        return $res;
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
        $location = '';
        $file1 = $arr1 ? $this->buildLocationInner($arr1) : null;
        $file2 = $arr2 ? $this->buildLocationInner($arr2) : null;
        if ($file1) {
            $location = $location . 'current: ' . $file1 . PHP_EOL;
        }
        if ($file2) {
            $location = $location . 'previous: ' . $file2 . PHP_EOL;
        }
        if ($url) {
            $urlparts = explode('\\', $url);
            $urlpartsrelevant = array_slice($urlparts, 0, 3, true) ?? null;
            $url = '...\\' . join($urlpartsrelevant, '\\') ?? null;
            $location = $location . 'url: ' . $url . PHP_EOL;
        }
        return $location;
    }

    protected function MixData(array $MainData = null, $contentdata = null, $headers = null, array $previousdata = null)
    {
        $results = [];
        foreach ($MainData??[] as $key => $val) {
            $results['currentData']['current.' . $key] = $val;
        }
        foreach ($previousdata??[] as $key => $val) {
            $results['previousData']['previous.' . $key] = $val;
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

    //////////////////////////////////////////
    /// //////////////////////////////////
    /// MISC FUNCTIONS
    /// /////////////////////////////////
    ////////////////////////////////////////////

    protected function AddLine(string &$message, string $title, string $str = null)
    {
        if (!($str) || strlen($str) === 0) {
            return;
        }
        if (strlen($message)) {
            $message = $message . PHP_EOL;
        }
        $message = $message . $title . $str;
    }
}
