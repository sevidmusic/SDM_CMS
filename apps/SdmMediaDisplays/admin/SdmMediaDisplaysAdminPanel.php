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
    /* Incorporate Admin Panel. */
    $sdmassembler->sdmAssemblerIncorporateAppOutput("<div id='SdmMediaDisplaysAdminPanel' class='SdmMediaDisplaysAdminPanel'><h2>$panelName</h2>$completeFormHtml</div>", array('incpages' => array('SdmMediaDisplays'), 'roles' => array('root'), 'incmethod' => 'prepend'));

}
