<?php

$options = array(
    'incpages' => array(
        'SdmDevOutput',
    ),
);
$output = '<p style="overflow:auto;">PHP INI FILE @ ' . php_ini_loaded_file() . '</p>';
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options);