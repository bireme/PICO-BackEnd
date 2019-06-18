<?php

require_once(realpath(dirname(__FILE__)) . '/LayerFacades/IntegrationPICOExplorer.php');

$data = filter_input(INPUT_POST, 'data');
if (!($data)) {
    $inidata = filter_input(INPUT_GET, 'data');
    $ObjectKeywordData = array(
        'keyword' => $inidata['word'],
        'langs' => $inidata['lang']
    );
    $data = array(
        'caller' => 'main',
        'ObjectKeywordData' => $ObjectKeywordData
    );
}

$IntegrationPICOExplore = new IntegrationPICOExplorer($data);
echo $IntegrationPICOExplore->ImportDeCS();
?>