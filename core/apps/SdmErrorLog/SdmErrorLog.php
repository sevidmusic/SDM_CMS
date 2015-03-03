<?php

// app description page | if you visit YOURSITE.com/index.php?page=SdmDevMenu this output will be dsiplayed
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<h2>Site Errors</h2><p>The SDM Error Log app displays the sites error log and color codes the errors as follows:</p><p style="border:1px solid; border-radius: 20px; color:#DD0000;padding:20px;background:#303030">Fatal</p><p style="border:1px solid; border-radius: 20px; color:#FDD017;padding:20px;background:#000000">Warning</p><p style="border:1px solid; border-radius: 20px; color:#6C7DCC;padding:20px;background:#303030">Notice</p><p style="border:1px solid; border-radius: 20px; color:#FFFFFF;padding:20px;background:#000000">Other</p>', array('wrapper' => 'main_content', 'incmethod' => 'append', 'incpages' => array('SdmErrorLog')));
// get the error log with file() so we have each line as an array item. gives us some formating flexablitly later on
$loaded_error_log = file($sdmcore->getCoreDirectoryUrl() . '/logs/sdm_core_errors.log');
// reverse the order of the elements because we want the newest errors to be at the top of the list
$error_log = array_reverse($loaded_error_log);
// display number of errors
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<p>Number of logged errors : ' . count($error_log) . '</p>', array('incpages' => array('SdmErrorLog')));
$output = '';
foreach ($error_log as $error_number => $error_message) {
    $notice_color = '#6C7DCC';
    $warning_color = '#FDD017';
    $fatal_color = '#DD0000';
    $default_text_color = '#FFFFFF';
    $background_color = ((1 + intval($error_number)) % 2 == 0 ? '#000000' : '#303030');
    // determine which error color code to use
    $output .= '<p style="overflow:auto;border:1px solid; border-radius: 20px; color:' . (strpos($error_message, 'Notice') !== false ? $notice_color : (strpos($error_message, 'Warning') !== FALSE ? $warning_color : (strpos($error_message, 'Fatal') !== FALSE || strpos($error_message, 'fatal') !== FALSE ? $fatal_color : $default_text_color))) . ';padding:20px;background:' . $background_color . '">' . strval((1 + intval($error_number))) . '. ' . $error_message . '</p>';
}
$output .= ($output === '' ? '<p style="color:lightgreen;border:1px solid; border-radius: 20px; padding:20px;background:black;">No errors to report.</p>' : '');

// incorporate devmenu
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, array('wrapper' => 'main_content', 'incmethod' => 'append', 'incpages' => array('SdmErrorLog')));

// clear error form
$clearErrorsForm = new SDM_Form();
$clearErrorsForm->form_handler = 'contentManagerAdministerAppsFormSubmission';
$clearErrorsForm->method = 'post';
$clearErrorsForm->form_elements = array();
$clearErrorsForm->form_handler = 'SdmErrorLog';
// build the form
$clearErrorsForm->__build_form($sdmcore->getRootDirectoryUrl());
// incorporate clear errors form
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<p>Clear the error log? </p>' . $clearErrorsForm->__get_form(), array('wrapper' => 'main_content', 'incmethod' => 'append', 'incpages' => array('SdmErrorLog')));
if (isset($_POST['sdm_form']['form_id'])) {
    // handle form
}