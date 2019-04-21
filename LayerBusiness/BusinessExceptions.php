<?php

namespace LayerBusiness;

use Exception;

class BusinessExceptions Extends Exception {

    public function BasicError() {
        $Err = $this->getMessage();
        switch ($Err[0]) {
            case 'W' :
                $code = 'Warning';
                break;
            case 'E' :
                $code = 'Error';
                break;
            case 'F' :
                $code = 'Fatal Error';
                break;
            case 'X' :
                echo substr($Err, 1);
                return;
            case 'R' :
                return;
            default:
                $code = '';
                break;
        }
        echo '</br></br>(' . ($code . ' ' . $this->getCode()) . '): ' . substr($Err, 1);
        echo '</br>in ' . $this->getFile() . ':' . $this->getLine();
    }

}

?>