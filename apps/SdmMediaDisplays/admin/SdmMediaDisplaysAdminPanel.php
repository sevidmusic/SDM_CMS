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
    /* Unpack submitted form values regularly referenced in code. */
    if ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit') !== null) {
        $editMode = 'edit';
        $nameOfDisplayBeingEdited = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit');
    } elseif ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayPageName') !== null) {
        $editMode = 'add';
        $nameOfDisplayBeingEdited = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayPageName');
    } else {
        $editMode = null;
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
    //$sdmassembler->sdmCoreSdmReadArray(['nameOfDisplayBeingEdited' => $nameOfDisplayBeingEdited, 'current panel' => $currentPanel, 'admin mode' => $adminMode, 'editMode' => $editMode]);
    // for dev only,  remove once out of dev

    /* SdmMediaDisplays Admin Form | Define form properties. */
    $sdmMediaDisplaysAdminForm->preserveSubmittedValues = true;
    $sdmMediaDisplaysAdminForm->excludeSubmitLabel = true; // exclude default submit label.

    /* Load form elements. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/sdmMediaDisplaysAdminFormElements.php');

    /* Load admin buttons. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/buttons/SdmMediaDisplaysAdminButtons.php');

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

    /* Display correct panel description and, if necessary, load appropriate form handler based on $currentPanel */
    switch ($currentPanel) {
        case 'displayCrudPanel':
            $panelDescription = 'Welcome to the Sdm Media Display\'s admin panel. Use the admin panels below to manage the site\'s media displays.';
            break;
        case 'selectDisplayPanel':
            $panelName = ($adminMode === 'addDisplays' ? 'Add Display' : 'Edit Displays');
            $panelDescription = ($adminMode === 'addDisplays' ? '<p>Please select a page for the display to appear on.</p><p>If you don\'t see the page you are looking for there may already be a display for it, in which case return to the <a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmMediaDisplays"><b>Sdm Displays Admin Panel</b></a> and choose "Edit Displays.</p><p>If no select list appears then all the pages available to displays must already have a display assigned to them, in which case you can simply edit the displays by page from the <a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmMediaDisplays"><b>Sdm Displays Admin Panel</b></a>.</p>' : 'Select a display to edit.');
            break;
        case 'mediaCrudPanel':
            /* Load appropriate form handler based on $editMode. */
            if ($editMode === 'edit') {
                $panelDescription = 'Select a piece of media from below and then click one of the admin buttons at the bottom of the page to edit the media you selected.';
                /* load edit handlers for this panel */
                require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formHandlers/selectDisplayPanel_editDisplays.php');
            } elseif ($editMode === 'add') {
                $panelDescription = 'Use the admin buttons below to administer the new <span style="color:#66ff66">' . ucwords($nameOfDisplayBeingEdited) . '</span> display\'s media.';
                /* load add handlers for this panel */
                require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formHandlers/selectDisplayPanel_addDisplays.php');
            } elseif ($editMode === 'delete') {
                /* load delete handlers for this panel */
            } else {
                // do nothing
            }
            break;
        case 'editMediaPanel':
            switch ($currentPanel) {
                case 'editMediaPanel':
                    $panelDescription = 'Use the form below to edit the media.';
                    break;
                case 'addMediaPanel':
                    $panelDescription = 'Use the form below to add the new media.';
                    break;
            }
            break;
        case 'deleteMediaPanel':
            $panelDescription = 'Are you sure you want to delete this media?';
            break;
        case 'deleteDisplayPanel':
            $panelDescription = 'Select a display to be deleted';
            break;
        case 'confirmDeleteDisplayPanel':
            $panelDescription = 'Are you sure you want to delete this display? WARNING: All the media that belongs to this display will also be deleted!';
            break;
    }

    /* Incorporate Admin Panel. */
    $sdmassembler->sdmAssemblerIncorporateAppOutput("<div id='SdmMediaDisplaysAdminPanel' class='SdmMediaDisplaysAdminPanel'><h2 " . ($adminMode === 'addDisplays' ? "style='margin: 0px 0px 0px 0px;padding: 10px 0px 0px 0px;-webkit-margin-after: -42px;'" : '') . ">$panelName</h2><p>$panelDescription</p><div style='margin:42px 0px 42px 0px;width:88%;min-height:10px;border-radius:9px;background:#ffffff;opacity:.72;border:2px solid #3498db;'></div>$completeFormHtml</div>", array('incpages' => array('SdmMediaDisplays'), 'roles' => array('root'), 'incmethod' => 'prepend'));
    var_dump(['$currentPanel' => $currentPanel, '$panelName' => $panelName, '$panelDescription' => $panelDescription, '$adminMode' => $adminMode, '$editMode' => $editMode]);
}
