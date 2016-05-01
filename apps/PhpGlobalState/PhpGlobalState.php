<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 4/30/16
 * Time: 1:31 PM
 */

$globals = $sdmassembler->sdmCoreSdmReadArrayBuffered([
    'Session' => $_SESSION,
    'Post' => $_POST,
    'Get' => $_GET,
    'Cookie' => $_COOKIE,
    'Env' => $_ENV,
    'Files' => $_FILES,
    'Request' => $_REQUEST,
    'Server' => $_SERVER,
]);
$buttons = '<button id="showPhpGlobalStateShowHideButton" type="button">Show/Hide Php Global State Panel</button>';
$output = $sdmassembler->sdmAssemblerAssembleHtmlElement($globals, ['id' => 'phpGlobalStateDisplay',]);
$output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($buttons, ['id' => 'phpGlobalStateButtons',]);
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['Element', 'wrapper' => 'Sdm_Cms_Core_Output']);