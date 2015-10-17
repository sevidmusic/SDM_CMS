<?php

function sdmStrSlice($string, $start, $end) {
    $string = " " . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) {
        return "";
    }
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

$app = 'contentManager';
$params = file($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/' . $app . '/' . $app . '.gk');
$output = '';
foreach ($params as $param) {
    $output .= '<h3>' . substr($param, 0, strpos($param, '=')) . ':</h3>';
    $output .= '<p>' . sdmStrSlice($param, '=', ';') . '</p>';
}
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output);