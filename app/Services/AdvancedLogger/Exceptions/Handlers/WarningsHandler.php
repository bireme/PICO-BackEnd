<?php

namespace PICOExplorer\Services\AdvancedLogger\Exceptions\Handlers;

use PICOExplorer\Services\AdvancedLogger\Exceptions\Models\CustomWarning;
use PICOExplorer\Services\AdvancedLogger\Services\WarningLogger;

class WarningsHandler extends WarningLogger
{

    public function render(CustomWarning $warning)
    {
        if (is_a($warning, 'ClientError') || is_a($warning, 'ClientSuccess') || is_a($warning, 'ClientWarning') || is_a($warning, 'ClientInfo')) {
            $ClientWarnings = [
                'ClientError' => 'error',
                'ClientSuccess' => 'success',
                'ClientWarning' => 'warning',
                'ClientInfo' => 'info',
            ];
            $level = 'error';
            foreach ($ClientWarnings as $BaseType => $baselevel) {
                if (is_a($warning, $BaseType)) {
                    $level = $baselevel;
                    break;
                }
            }
            $this->WarningLogUserMessage($warning, $level);

        }else{
            $AppWarnings = [
                'AppInfo' => ['channel' => 'AppDebug', 'level' => 'error', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 0],
                'AppWarning' => ['channel' => 'Emergency', 'level' => 'emergency', 'IpPath' => 1, 'ReqContent' => 1, 'Headers' => 1],
            ];
            $ExInfo = null;
            foreach ($AppWarnings as $BaseType => $BaseInfo) {
                if (is_a($warning, $BaseType)) {
                    $ExInfo = $BaseInfo;
                    break;
                }
            }
            $channel = $ExInfo ? ($ExInfo['channel']) : ('InternalErrors');
            $level = $ExInfo ? ($ExInfo['level']) : ('error');

            $this->WarningLogBuilder($warning, $channel, $level);
        }
    }

}
