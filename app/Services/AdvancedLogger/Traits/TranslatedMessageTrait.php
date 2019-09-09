<?php


namespace PICOExplorer\Services\AdvancedLogger\Traits;


trait TranslatedMessageTrait
{
    protected function getTranslatedMessage(string $ErrorKey, $isCustomException = true)
    {
        $tmp = explode('\\', $ErrorKey);
        $ErrorKey = end($tmp);
        $Internals = trans('Internals', [], 'en');
        $ServerTxt = trans('Server', [], 'en');
        if (array_key_exists($ErrorKey, $ServerTxt)) {
            return $ServerTxt[$ErrorKey];
        } elseif (array_key_exists($ErrorKey, $Internals)) {
            return $Internals[$ErrorKey];
        }
        if ($isCustomException) {
            return $ErrorKey;
        } else {
            return false;
        }
    }
}
