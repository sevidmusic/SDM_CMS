<?php

// app description page | if you visit YOURSITE.com/index.php?page=SdmDevMenu this output will be dsiplayed
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<h2>Site Errors</h2><p>The SDM Erro Log app displays the sites error log</p>', array('wrapper' => 'main_content', 'incmethod' => 'overwrite', 'incpages' => array('SdmErrorLog')));
// get the error log with file() so we have each line as an array item. gives us some formating flexablitly later on
$loaded_error_log = file($sdmcore->getCoreDirectoryUrl() . '/logs/sdm_core_errors.log');
// reverse the order of the elements because we want the newest errors to be at the top of the list
$error_log = array_reverse($loaded_error_log);
$output = '';
foreach ($error_log as $error_number => $error_message) {
    $notice_color = '#6C7DCC';
    $warning_color = '#FDD017';
    $fatal_color = '#DD0000';
    $default_text_color = '#FFFFFF';
    $background_color = ((1 + intval($error_number)) % 2 == 0 ? '#000000' : '#303030');

    // determine which error color code to use
    $output .= '<p style="border:1px solid; border-radius: 20px; color:' . (strpos($error_message, 'Notice') !== false ? $notice_color : (strpos($error_message, 'Warning') !== FALSE ? $warning_color : (strpos($error_message, 'Fatal') !== FALSE ? $fatal_color : $default_text_color))) . ';padding:20px;background:' . $background_color . '">' . strval((1 + intval($error_number))) . '. ' . $error_message . '</p>';
}
$output .= ($output === '' ? '<p style="color:lightgreen;border:1px solid; border-radius: 20px; padding:20px;background:black;">No errors to report.</p>' : '');

// incorporate devmenu
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, array('wrapper' => 'main_content', 'incmethod' => 'prepend', 'incpages' => array('SdmErrorLog')));