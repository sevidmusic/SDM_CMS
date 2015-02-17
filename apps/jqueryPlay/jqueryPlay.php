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
$sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<!-- jqueryPlay user app appended content -->';
// add a <script> tag whose src attribute points to the javascript file we wish execute from
$sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<script type="text/javascript">' . trim(file_get_contents($sdmcore->getUserAppDirectoryPath() . '/jqueryPlay/jqueryPlay.js')) . '</script>';
$sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '<!-- end jqueryplay user app appended content -->';
