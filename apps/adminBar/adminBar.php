<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 4/23/16
 * Time: 11:07 PM
 */
$adminBarOutputOptions = array(
    'wrapper' => 'Sdm_Cms_Core_Output',
    'incpages' => array( // only include on admin pages
        'admin',
        'contentManager',
        'contentManagerAddContentForm',
        'contentManagerEditContentForm',
        'contentManagerSelectPageToEditForm',
        'contentManagerUpdateContentFormSubmission',
        'contentManagerSelectPageToDeleteForm',
        'contentManagerDeletePageSubmission',
        'contentManagerSelectThemeForm',
        'contentManagerSelectThemeFormSubmission',
        'contentManagerAdministerAppsForm',
        'contentManagerAdministerAppsFormSubmission',
        'navigationManager',
        'navigationManagerAddMenuStage1', // select number of menu items
        'navigationManagerAddMenuStage2', // configure menu items
        'navigationManagerAddMenuStage3', // configure menu
        'navigationManagerAddMenuStage4', // add menu
        'navigationManagerDeleteMenuStage1', // select menu to delete
        'navigationManagerDeleteMenuStage2', // confirm selected menu should be deleted
        'navigationManagerDeleteMenuStage3', // delete menu
        'navigationManagerEditMenuStage1', // select menu to edit
        'navigationManagerEditMenuStage2', // edit menu settings or select menuItem to edit
        'navigationManagerEditMenuStage3_submitmenuchanges', // handle edit menu form submission
        'navigationManagerEditMenuStage3_editmenuitem', // edit menuItem settings
        'navigationManagerEditMenuStage3_submitmenuitemchanges', // handle edit menu item form submission
        'navigationManagerEditMenuStage3_confirmdeletemenuitem', // confirm menu item to be deleted
        'navigationManagerEditMenuStage3_deletemenuitem', // handles deletion of a menu item from a menu through submission of confrim delete menu item form
        'navigationManagerEditMenuStage3_addmenuitem', // add a menu item to a menu
        'navigationManagerEditMenuStage3_submitaddmenuitem', // submit added menu item
        'SdmAuth',
        'SdmAuthLogin',
        'SdmCoreOverview',
        'SdmErrorLog',
        'homepage',
        'Sdm Cms Documentation'
    ),
);

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
