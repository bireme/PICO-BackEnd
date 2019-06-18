<?php

require_once(realpath(dirname(__FILE__)) . '/LayerFacades/IntegrationPICOExplorer.php');

$data = filter_input(INPUT_POST, 'data');
if (!($data)) {
    $local = filter_input(INPUT_GET, 'local')?:NULL;
    $global = filter_input(INPUT_GET, 'global')?:$local;

    $data = json_encode(array(
        'local' => $local,
        'global' => $global
    ));
}

$IntegrationPICOExplore = new IntegrationPICOExplorer($data);
echo $IntegrationPICOExplore->ImportResultsNumber();
?>