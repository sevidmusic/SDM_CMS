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
/** Show all defined vars with var_dump | var_dump has more access then print_r or sdmCoreReadArray() */
$vars = '<div style="font-size:.8em;width: 100%; height: 420px;padding:42px;overflow: auto; border: 3px solid #ffffff; border-radius: 9px;"><h4>Currently Defined Php Variables</h4><pre>'; // This is for correct handling of newlines
ob_start();
var_dump(get_defined_vars());
$out = ob_get_contents();
ob_end_clean();
$vars .= htmlspecialchars($out, ENT_QUOTES); // Escape every HTML special chars (especially > and < )
$vars .= '</div></pre>';

$output = $sdmassembler->sdmAssemblerAssembleHtmlElement($globals . $vars, ['id' => 'phpGlobalStateDisplay',]);
$output .= $sdmassembler->sdmAssemblerAssembleHtmlElement($buttons, ['id' => 'phpGlobalStateButtons',]);

// Secure Display //
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['wrapper' => 'Sdm_Cms_Core_Output', 'roles' => array('root')]);

// Insecure Display //
//$sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['wrapper' => 'Sdm_Cms_Core_Output',]);