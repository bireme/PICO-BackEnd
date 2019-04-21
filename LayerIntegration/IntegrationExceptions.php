<?php

namespace LayerIntegration;

use Exception;

class IntegrationExceptions Extends Exception {

    private $showTracing;
    private $StopExcecution;
    private $userErrorCode;

    public function __construct($ExceptionType, $userErrorCode = NULL) {
        $message = $ExceptionType['message'];
        $code = $ExceptionType['code'];
        $this->userErrorCode = $code;
        if (isset($userErrorCode)) {
            $this->userErrorCode = $userErrorCode;
        }
        $AlertLevel = $ExceptionType['AlertLevel'];
        $this->StopExcecution = false;
        if ($AlertLevel > 1) {
            $this->StopExcecution = true;
        }
        $this->showTracing = $ExceptionType['showTracing'];
        $previous = NULL;
        if (array_key_exists('previous', $ExceptionType)) {
            $previous = $ExceptionType['previous'];
        }
        parent::__construct($message, $code, $previous);
    }

    public function PreviousUserErrorCode() {
        return $this->userErrorCode;
    }

    public function HandleError() {
        echo $this->getMessage();
        if ($this->showTracing == true) {
            echo 'in ' . $this->getFile() . ':' . $this->getLine();
        }
        return $this->StopExcecution;
    }

}

abstract class ExceptionTypeModel {

    private $levels = ['', 'Warning', 'Error', 'Fatal Error', 'Stoping Execution'];
    private $message;
    private $showTracing;
    private $previous;

    private function ExceptionAlertTag() {
        if ($this->AlertLevel > 0 & $this->AlertLevel < 4) {
            $leveltag = $this->levels[$this->AlertLevel];
            return '(' . $leveltag . ' [' . $this->code . ']): ';
        }
    }

    protected function getCode() {
        return $this->code;
    }

    protected function __construct(string $message, bool $showTracing, string $userMessage = "", $previous = NULL) {
        $this->message = $message;
        $this->showTracing = $showTracing;
        $this->previous = $previous;
    }

    public function build() {
        $message = $this->message;
        $showTracing = $this->showTracing;
        $previous = $this->previous;
        if (strlen($message) > 0) {
            $message = '</br></br>' . $this->ExceptionAlertTag() . $message . '</br>';
        }

        if (isset($previous)) {
            return array('code' => $this->getCode(), 'AlertLevel' => $this->AlertLevel, 'showTracing' => $showTracing, 'message' => ($message), 'previous' => $previous);
        } else {
            return array('code' => $this->getCode(), 'AlertLevel' => $this->AlertLevel, 'showTracing' => $showTracing, 'message' => ($message));
        }
    }

}

class JustReturnException Extends ExceptionTypeModel {

    protected $code = 100;
    protected $AlertLevel = 4;

    public function __construct() {
        return parent::__construct('', false);
    }

}

class ProxyConnectionException Extends ExceptionTypeModel {

    protected $code = 1;
    protected $AlertLevel = 3;

    public function __construct(string $curlerror, string $url, string $query) {
        $message = $curlerror . ' -> {url: ' . $url . ', query: ' . $query . '}';
        return parent::__construct($message, true);
    }

}

class ProxyDownloadException Extends ExceptionTypeModel {

    protected $code = 2;
    protected $AlertLevel = 3;

    public function __construct(string $url, string $query) {
        $message = 'No data found in ' . ' -> {url: ' . $url . ', query: ' . $query . '}';
        return parent::__construct($message, true);
    }

}

class XMLLoadException Extends ExceptionTypeModel {

    protected $code = 6;
    protected $AlertLevel = 3;

    public function __construct(string $message) {
        $message = 'Couldnt load XML: ' . $message;
        return parent::__construct($message,true);
    }

}

class XMLErrorException Extends ExceptionTypeModel {

    protected $code = 7;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'Error inside XML: ';
        return parent::__construct($message,true);
    }

}

class NoResponsesException Extends ExceptionTypeModel {

    protected $code = 10;
    protected $AlertLevel = 1;

    public function __construct(string $keyword, string $lang) {
        $message = 'No decsws_response tags found for: ' . $keyword . ' lang=' . $lang;
        return parent::__construct($message, true);
    }

}

class NoSynonymsFound Extends ExceptionTypeModel {

    protected $code = 10;
    protected $AlertLevel = 1;

    public function __construct(string $tree_id, string $lang) {
        $message = 'No synonyms found for tree_id: ' . $tree_id . ' lang=' . $lang;
        return parent::__construct($message, true);
    }

}

class SuspendingDeCSError Extends ExceptionTypeModel {

    protected $code = 11;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = '...Suspending DeCS importing';
        return parent::__construct($message, false);
    }

}

class NoRelatedTrees Extends ExceptionTypeModel {

    protected $code = 50;
    protected $AlertLevel = 0;

    public function __construct(string $tree_id, string $lang) {
        $message = '...Suspending DeCS importing';
        return parent::__construct($message, false);
    }

}

class NoResultsInXMLException Extends ExceptionTypeModel {

    protected $code = 12;
    protected $AlertLevel = 3;

    public function __construct(string $query) {
        $message = 'No responses tag were found for: ' . $query;
        return parent::__construct($message, true);
    }

}

class ErrorTagInXML Extends ExceptionTypeModel {

    protected $code = 13;
    protected $AlertLevel = 3;

    public function __construct(string $query) {
        $message = 'No responses tag were found for: ' . $query;
        return parent::__construct($message, true);
    }

}

class NoDescendantsNorTrees Extends ExceptionTypeModel {

    protected $code = 13;
    protected $AlertLevel = 1;

    public function __construct(string $query) {
        $message = 'No descendants nor trees were found for: ' . $query;
        return parent::__construct($message, true);
    }

}

class CouldntGetItemTreeId Extends ExceptionTypeModel {

    protected $code = 13;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'Couldnt get item tree id';
        return parent::__construct($message, true);
    }

}

class EmptyQuery Extends ExceptionTypeModel {

    protected $code = 14;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'The query is empty';
        return parent::__construct($message, true);
    }

}

class EmptyKeyword Extends ExceptionTypeModel {

    protected $code = 15;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'There is no keyword';
        return parent::__construct($message, true);
    }

}

class NoLanguages Extends ExceptionTypeModel {

    protected $code = 21;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'No languages were introduced';
        return parent::__construct($message, true);
    }

}

class NoArrLanguages Extends ExceptionTypeModel {

    protected $code = 22;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'Languages array does not exist';
        return parent::__construct($message, true);
    }

}

class UnrecognizedLanguage Extends ExceptionTypeModel {

    protected $code = 22;
    protected $AlertLevel = 3;

    public function __construct($lang) {
        $message = 'This language is not recognized: ' . $lang;
        return parent::__construct($message, true);
    }

}

class DeCSObjectNotMatch Extends ExceptionTypeModel {

    protected $code = 31;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'Variable DeCSObject is not of class ObjectDeCS ';
        return parent::__construct($message, true);
    }

}

class XMLNotBiremeType Extends ExceptionTypeModel {

    protected $code = 31;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'The XML is not of BIREME type';
        return parent::__construct($message, true);
    }

}

class KeywordTooLarge Extends ExceptionTypeModel {

    protected $code = 31;
    protected $AlertLevel = 3;

    public function __construct($size, $max) {
        $message = 'Maximum keyword length exceded: ' . $size . '(max = ' . $max . ')';
        return parent::__construct($message, true);
    }

}

class QueryTooLarge Extends ExceptionTypeModel {

    protected $code = 31;
    protected $AlertLevel = 3;

    public function __construct($size, $max) {
        $message = 'Maximum query length exceded: ' . $size . '(max = ' . $max . ')';
        return parent::__construct($message, true);
    }

}

?>