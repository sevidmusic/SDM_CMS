<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:00 PM
 */
if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmMediaDisplays') {
    /* Initialize the Sdm Media Displays admin form. */
    $sdmMediaDisplaysAdminForm = new SdmForm();
    /* Unpack submitted form values regularly reference in code. */
    if ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit') !== null) {
        $nameOfDisplayBeingEdited = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit');
    } elseif ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayPageName') !== null) {
        $nameOfDisplayBeingEdited = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayPageName');
    } else {
        $nameOfDisplayBeingEdited = null;
    }
    /* Determine requested panel */
    $requestedPanel = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('panel');
    $defaultPanel = 'displayCrudPanel'; // if no panel specified, show display crud.
    $currentPanel = ($requestedPanel === null ? $defaultPanel : $requestedPanel);

    /* See if there is an admin mode appended to the submitted panel value. If there is extract it and
       store it as the $adminMode. */
    $extractedPanelMode = strrpos($requestedPanel, '_');
    $adminMode = ($extractedPanelMode === false ? 'default' : substr($requestedPanel, $extractedPanelMode + 1));

    /* If there is a mode extracted, remove it from the panel name. */
    if ($extractedPanelMode !== false) {
        $currentPanel = str_replace('_' . $adminMode, '', $currentPanel);
    }

    // for dev only,  remove once out of dev
    var_dump(['nameOfDisplayBeingEdited' => $nameOfDisplayBeingEdited, 'current panel' => $currentPanel, 'admin mode' => $adminMode, 'admin mode set' => ($extractedPanelMode === false ? false : true)]);
    // for dev only,  remove once out of dev

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

    /* Display correct panel description based on $currentPanel */
    switch ($currentPanel) {
        case 'displayCrudPanel':
            $panelDescription = 'Welcome to the Sdm Media Display\'s admin panel. Use the admin panels below to manage the site\'s media displays.';
            break;
        case 'selectDisplayPanel':
            $panelName = ($adminMode === 'addDisplays' ? 'Add Display' : 'Edit Displays');
            $panelDescription = ($adminMode === 'addDisplays' ? 'Please select a page for the display to appear on. (If you don\'t see the page you are looking for there may already be a display for it, in which case return to the <a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmMediaDisplays"><b>Sdm Displays Admin Panel</b></a> and choose "Edit Displays")' : 'Select a display to edit.');
            break;
        case 'mediaCrudPanel':
            $panelDescription = 'Use the admin panels below to administer the new <span style="color:#66ff66">' . ucwords($nameOfDisplayBeingEdited) . '</span> display\'s media.';
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
