<?php

namespace SimpleLife;

require_once('SimpleLifeExceptions.php');

    //------------------------------------------------------------
    //------------------------------------------------------------
    //-CONNECTION AND XML OBTAINING ERRORS------------
    //------------------------------------------------------------
    //------------------------------------------------------------


class ProxyConnectionException Extends SimpleExceptionTypeModel {

    protected $code = 11;
    protected $AlertLevel = 3;

    public function __construct(string $url, string $query) {
        $message = 'Error in connection -> {url: ' . $url . ', query: ' . $query . '}';
        return parent::__construct($message, true);
    }

}

class ProxyDownloadException Extends SimpleExceptionTypeModel {

    protected $code = 12;
    protected $AlertLevel = 3;

    public function __construct(string $url, string $query) {
        $message = 'No data found in ' . ' -> {url: ' . $url . ', query: ' . $query . '}';
        return parent::__construct($message, true);
    }

}

class XMLLoadException Extends SimpleExceptionTypeModel {

    protected $code = 13;
    protected $AlertLevel = 3;

    public function __construct(string $message) {
        $message = 'Couldnt load XML: ' . $message;
        return parent::__construct($message, true);
    }

}

class XMLErrorException Extends SimpleExceptionTypeModel {

    protected $code = 14;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'Error inside XML: ';
        return parent::__construct($message, true);
    }

}

class XMLNotBiremeType Extends SimpleExceptionTypeModel {

    protected $code = 31;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'The XML is not of BIREME type';
        return parent::__construct($message, true);
    }

}

    //------------------------------------------------------------
    //------------------------------------------------------------
    //- XML PROCESSING AND EXTRACTION ERRORS------------
    //------------------------------------------------------------
    //------------------------------------------------------------

class NoResponsesException Extends SimpleExceptionTypeModel {

    protected $code = 21;
    protected $AlertLevel = 1;

    public function __construct(string $keyword, string $lang) {
        $message = 'No decsws_response tags found for: ' . $keyword . ' lang=' . $lang;
        return parent::__construct($message, true);
    }

}

class NoSynonymsFound Extends SimpleExceptionTypeModel {

    protected $code = 22;
    protected $AlertLevel = 1;

    public function __construct(string $tree_id, string $lang) {
        $message = 'No synonyms found for tree_id: ' . $tree_id . ' lang=' . $lang;
        return parent::__construct($message, true);
    }

}

class NoRelatedTrees Extends SimpleExceptionTypeModel {

    protected $code = 23;
    protected $AlertLevel = 0;

    public function __construct(string $tree_id, string $lang) {
        $message = '...Suspending DeCS importing';
        return parent::__construct($message, false);
    }

}

class NoResultsInXMLException Extends SimpleExceptionTypeModel {

    protected $code = 24;
    protected $AlertLevel = 3;

    public function __construct(string $query) {
        $message = 'No responses tag were found for: ' . $query;
        return parent::__construct($message, true);
    }

}

class ErrorTagInXML Extends SimpleExceptionTypeModel {

    protected $code = 25;
    protected $AlertLevel = 3;

    public function __construct(string $query) {
        $message = 'No responses tag were found for: ' . $query;
        return parent::__construct($message, true);
    }

}

class NoDescendantsNorTrees Extends SimpleExceptionTypeModel {

    protected $code = 26;
    protected $AlertLevel = 1;

    public function __construct(string $query) {
        $message = 'No descendants nor trees were found for: ' . $query;
        return parent::__construct($message, true);
    }

}

class CouldntGetItemTreeId Extends SimpleExceptionTypeModel {

    protected $code = 27;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'Couldnt get item tree id';
        return parent::__construct($message, true);
    }

}

    //------------------------------------------------------------
    //------------------------------------------------------------
    //- VARIABLE TYPE ERRORS-----------
    //------------------------------------------------------------
    //------------------------------------------------------------

class EmptyQuery Extends SimpleExceptionTypeModel {

    protected $code = 41;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'The query is empty';
        return parent::__construct($message, true);
    }

}

class EmptyKeyword Extends SimpleExceptionTypeModel {

    protected $code = 42;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'There is no keyword';
        return parent::__construct($message, true);
    }

}

class NoLanguages Extends SimpleExceptionTypeModel {

    protected $code = 43;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'No languages were introduced';
        return parent::__construct($message, true);
    }

}

class LangArrNotArray Extends SimpleExceptionTypeModel {

    protected $code = 44;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'Variable LangArr is not array';
        return parent::__construct($message, true);
    }

}

class NoArrLanguages Extends SimpleExceptionTypeModel {

    protected $code = 45;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'Languages array does not exist';
        return parent::__construct($message, true);
    }

}

class UnrecognizedLanguage Extends SimpleExceptionTypeModel {

    protected $code = 46;
    protected $AlertLevel = 3;

    public function __construct($lang) {
        $message = 'This language is not recognized: ' . $lang;
        return parent::__construct($message, true);
    }

}

class KeywordTooLarge Extends SimpleExceptionTypeModel {

    protected $code = 47;
    protected $AlertLevel = 3;

    public function __construct($size, $max) {
        $message = 'Maximum keyword length exceded: ' . $size . '(max = ' . $max . ')';
        return parent::__construct($message, true);
    }

}

class QueryTooLarge Extends SimpleExceptionTypeModel {

    protected $code = 48;
    protected $AlertLevel = 3;

    public function __construct($size, $max) {
        $message = 'Maximum query length exceded: ' . $size . '(max = ' . $max . ')';
        return parent::__construct($message, true);
    }

}

class NullDeCSKeyWordList Extends SimpleExceptionTypeModel {

    protected $code = 49;
    protected $AlertLevel = 3;

    public function __construct() {

        $message = 'Error building final equation $DeCSKeywordList is null';
        return parent::__construct($message, true);
    }
}

    //------------------------------------------------------------
    //------------------------------------------------------------
    //- OPERATIONS WITH ARRAYS OBJECTS AND VARS-----------
    //------------------------------------------------------------
    //------------------------------------------------------------

class ArrayPositionExceedsBound Extends SimpleExceptionTypeModel {

    protected $code = 71;
    protected $AlertLevel = 3;

    public function __construct($pos, $Arr) {
        $message = 'Position ' . $pos . ' Exceeds bound of array:' . json_encode($Arr);
        return parent::__construct($message, true);
    }

}

class ObjectKeywordNotMatch Extends SimpleExceptionTypeModel {

    protected $code = 72;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'Variable DeCSObject is not of class ObjectKeyword ';
        return parent::__construct($message, true);
    }

}
class NoKeywords Extends SimpleExceptionTypeModel {

    protected $code = 73;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = 'There were no keywords found';
        return parent::__construct($message, true);
    }

}
class TreeIdDoesntNotExist Extends SimpleExceptionTypeModel {

    protected $code = 74;
    protected $AlertLevel = 3;

    public function __construct($tree_id) {
        $message = 'Tree_id: ' . $tree_id . 'does not exist';
        return parent::__construct($message, true);
    }

}

    //------------------------------------------------------------
    //------------------------------------------------------------
    //- FUNCTION ERRORS----------
    //------------------------------------------------------------
    //------------------------------------------------------------

class SuspendingDeCSError Extends SimpleExceptionTypeModel {

    protected $code = 101;
    protected $AlertLevel = 3;

    public function __construct() {
        $message = '...Suspending DeCS importing';
        return parent::__construct($message, false);
    }

}

class ErrorBuildingFinalEquation Extends SimpleExceptionTypeModel {

    protected $code = 102;
    protected $AlertLevel = 3;

    public function __construct($DeCSKeyword, $ErrorMessage, $Trace) {

        $message = 'Error building final equation while processing... ' . json_encode($DeCSKeyword) . ' -->' . $ErrorMessage;
        return parent::__construct($message, true);
    }

}

class BuildDeCSListFailed Extends SimpleExceptionTypeModel {

    protected $code = 103;
    protected $AlertLevel = 3;

    public function __construct($List) {

        $message = 'Error building DeCSList for KeywordList: ' . json_encode($List);
        return parent::__construct($message, true);
    }

}

class ErrorBuildingKeyWordList Extends SimpleExceptionTypeModel {

    protected $code = 104;
    protected $AlertLevel = 3;

    public function __construct($query) {

        $message = 'Error building KeywordList with query: ' . json_encode($query);
        return parent::__construct($message, true);
    }

}

class PreviousIntegrationException Extends SimpleExceptionTypeModel {

    protected $code = 105;
    protected $AlertLevel = 3;

    public function __construct($code) {
        $this->code = $code;
        $message = 'Error ' . $code . ' inherited from integration layer';
        return parent::__construct($message, true);
    }

}

class EquationMustBeString Extends SimpleExceptionTypeModel {

    protected $code = 121;
    protected $AlertLevel = 3;

    public function __construct($EqName) {
        $message = 'Error in: ' . $EqName . '. This equation must be string';
        return parent::__construct($message, true);
    }

}

class ParenthesesNumberNotMatch Extends SimpleExceptionTypeModel {

    protected $code = 122;
    protected $AlertLevel = 3;

    public function __construct($EqName) {
        $message = 'Error in: ' . $EqName . '. The number of parentheses does not match';
        return parent::__construct($message, true);
    }

}

class EqInvalidChars Extends SimpleExceptionTypeModel {

    protected $code = 123;
    protected $AlertLevel = 3;

    public function __construct($EqName, $InvalidChars) {
        $message = 'Error in: ' . $EqName . '. These InvalidChars were introduced: '.$InvalidChars;
        return parent::__construct($message, true);
    }

}


?>