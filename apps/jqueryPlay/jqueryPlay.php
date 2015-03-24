<?php

/**
 * Example of onw way an app can make use of javascript.
 * Though there are many solutions, this one is very straightforward.
 * The app simply appends a <script> tag whose src attribute points
 * to the javascript file we wish execute from.
 *
 * This app can also be used to test if the core jQuery and jQuery UI
 * libraries are working. If the arent an alert message will pop up
 * on every page until you wither turn this app off, or fix the issue.
 */
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h2>jQuery Play</h2><p>This app demonstrates how jquery can be used with the SDM CMS. It will also let you know if jQuery and jQuery UI are working whenever you visit the homepage.</p>', array('wrapper' => 'main_content', 'incmethod' => 'append', 'incpages' => array('jqueryPlay')));
$options = array('incpages' => array('homepage'));
$output = '';
$output .= '<!-- jqueryPlay user app appended content -->';
// add a <script> tag whose src attribute points to the javascript file we wish execute from
$output .= '<script type="text/javascript">' . trim(file_get_contents($sdmcore->sdmCoreGetUserAppDirectoryPath() . '/jqueryPlay/jqueryPlay.js')) . '</script>';
$output .= '<!-- end jqueryplay user app appended content -->';
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, $options);