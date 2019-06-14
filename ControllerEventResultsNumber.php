<?php

require_once(realpath(dirname(__FILE__)) . '/PICOExplorer.php');

$PICOExplorer = new PICOExplorer(filter_input(INPUT_POST, 'data'));
echo $PICOExplorer->ResultsNumber();
?>