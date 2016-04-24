<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 4/23/16
 * Time: 11:07 PM
 */
$adminBarOutputOptions = array('wrapper' => 'Sdm_Cms_Core_Output');

$javascriptEnabled = (filter_input(INPUT_COOKIE, 'adminBarDetectJavascriptEnabled') === 'true' ? boolval(filter_input(INPUT_COOKIE, 'adminBarDetectJavascriptEnabled')) : false);

switch ($javascriptEnabled === true) {
    case true:
        $output = '
<!-- #showAdminBar button -->
<button id="showAdminBar" class="adminShowHideButton">Show Admin Bar</button>
<!-- End #showAdminBar button -->

<!-- #adminBarDisplay -->
<div id="adminBarDisplay">

    <!-- #showAdminBar button -->
    <button id="hideAdminBar" class="adminShowHideButton">Hide Admin Bar</button>
    <!-- End #showAdminBar button -->

    <!-- #hideAdminPanel button -->
    <button id="hideAdminPanel" class="adminShowHideButton">Hide Admin Panel</button>
    <!-- End #hideAdminPanel button -->

    <!-- #showAdminPanel button -->
    <button id="showAdminPanel" class="adminShowHideButton">Show Admin Panel</button>
    <!-- End #showAdminPanel button -->

    <!-- #adminPanelDisplay -->
    <div id="adminPanelDisplay">';

        $output .= $sdmassembler->sdmAssemblerGetContentHtml('top-menu');
        $output .= $sdmassembler->sdmAssemblerGetContentHtml('main_content');
        $output .= $sdmassembler->sdmAssemblerGetContentHtml('side-bar');

        $output .= '</div><!-- End #adminPanelDisplay --></div><!-- End #adminBarDisplay. -->';

        $sdmassembler->sdmAssemblerIncorporateAppOutput($output, $adminBarOutputOptions);
        break;
    default:
        error_log('
        User App adminBar could not display app output because the
        "adminBarDetectJavascriptEnabled" cookie value indicates
         that javascript is disabled.
        ');
        break;
}
