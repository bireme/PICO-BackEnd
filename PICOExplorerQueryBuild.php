<?php

require_once(realpath(dirname(__FILE__)) . '/LayerFacades/PICOExplorer.php');

$PICOExplorer = new PICOExplorer(filter_input(INPUT_POST, 'data'));
echo $PICOExplorer->QueryBuild();

?>