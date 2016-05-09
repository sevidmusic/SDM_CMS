<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:00 PM
 */
if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmMediaDisplays') {
    /* Create the Sdm Media Displays admin form. */
    $sdmMediaDisplaysAdminForm = new SdmForm();

    /* See if there is an admin mode appended to the submitted panel value. If there is store it as the $mode. */
    $devReqPanel = 'selectDisplayCrud_add';
    $devPanelMode = strrpos($devReqPanel, '_');
    $mode = ($devPanelMode === false ? $devReqPanel : substr($devReqPanel, $devPanelMode + 1));
    var_dump($mode);
    /* Determine which admin panel is currently in use. */
    $defaultPanel = 'displayCrudPanel'; // dev value placeholder for submitted form value 'panel'
    $requestedPanel = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('panel');
    $currentPanel = ($requestedPanel === null ? $defaultPanel : $requestedPanel);

    /* SdmMediaDisplays Admin Form | Define form properties. */
    $sdmMediaDisplaysAdminForm->preserveSubmittedValues = true;
    $sdmMediaDisplaysAdminForm->excludeSubmitLabel = true; // exclude default submit label.

    /* Load admin buttons. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/buttons/SdmMediaDisplaysAdminButtons.php');

    /* Load form elements. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/sdmMediaDisplaysAdminFormElements.php');

    /* Initialize $formHtml. */
    $formHtml = array();

    /* Start building form. */
    $formHtml['openingFormTags'] = $sdmMediaDisplaysAdminForm->sdmFormOpenForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
    $formHtml['formElementsHtml'] = implode(PHP_EOL, $currentPanelsFormElements);
    $formHtml['closingFormTags'] = $sdmMediaDisplaysAdminForm->sdmFormCloseForm();

    /* Display admin buttons for the current panel */
    $completeFormHtml = implode('', $formHtml) . implode('', $currentPanelsButtons);

    /* Format panel name for display */
    $panelCCName = str_replace('Crud', 'Admin', str_replace('Panel', '', $currentPanel));
    /* Convert from camel case to words. */
    preg_match_all('/((?:^|[A-Z])[a-z]+)/', $panelCCName, $panelCCNameMatches);
    /* Construct panel name string from camel case to words conversion result, use ucwords()
    so first letter of each word is capitalized. */
    $panelName = ucwords(implode(' ', $panelCCNameMatches[0]));
    switch ($currentPanel) {
        case 'displayCrudPanel':
            $panelDescription = 'Welcome to the Sdm Media Display\'s admin panel. Use the admin panels below to manage the site\s media displays.';
            break;
        case 'selectDisplayPanel':
            $panelDescription = 'Please select a page for the display to appear on.';
            break;
        case 'mediaCrudPanel':
            $panelDescription = 'Use the admin panels below to administer this display\'s media.';
            break;
        case 'editMediaPanel':
            $panelDescription = 'Configure the new or selected media.';
            break;
        case 'deleteMediaPanel':
            $panelDescription = 'Are you sure you want to delete this media?';
            break;
        case 'deleteDisplayPanel':
            $panelDescription = 'Are you sure you want to delete this display? WARNING: All the media that belongs to this display will also be deleted!';
            break;
    }
    /* Incorporate Admin Panel. */
    $sdmassembler->sdmAssemblerIncorporateAppOutput("<div id='SdmMediaDisplaysAdminPanel' class='SdmMediaDisplaysAdminPanel'><h2>$panelName</h2><p>$panelDescription</p><div style='margin:42px 0px 42px 0px;width:88%;min-height:10px;border-radius:9px;background:#ffffff;opacity:.72;border:2px solid #3498db;'></div>$completeFormHtml</div>", array('incpages' => array('SdmMediaDisplays'), 'roles' => array('root'), 'incmethod' => 'prepend'));

}
