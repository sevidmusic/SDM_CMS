<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/18/16
 * Time: 5:31 PM
 */

/** Build Admin Panels **/

/* If on SdmMediaDisplays page and $initialSetup has already occurred, i.e, $initialSetup is set to false,
   build admin panels. */
if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmMediaDisplays' && $initialSetup === false) {
    /* Initialize the Sdm Media Displays admin form. */
    $sdmMediaDisplaysAdminForm = new SdmForm();

    /* Panel & Mode Determination. */

    /* Determine requested panel. */
    $requestedPanel = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('panel');

    /* Default panel. */
    $defaultPanel = 'displayCrudPanel';

    /* Determine current admin panel. If $requestedPanel is set use it, otherwise use $defaultPanel. */
    $currentPanel = ($requestedPanel === null ? $defaultPanel : $requestedPanel);

    /* See if there is an admin mode appended to the submitted panel value. If there is extract it. */
    $extractedPanelMode = strrpos($requestedPanel, '_');

    /* Set admin mode. If admin mode was extracted, use it, otherwise set to default. */
    $adminMode = ($extractedPanelMode === false ? 'default' : substr($requestedPanel, $extractedPanelMode + 1));

    /* If a mode was extracted, remove it from the panel name. i.e., PANEL_ADMINMODE becomes PANEL. */
    if ($extractedPanelMode !== false) {
        $currentPanel = str_replace('_' . $adminMode, '', $currentPanel);
    }

    /** Determine Edit Mode **/

    /* If a display has been selected for editing, and admin mode is not 'confirmDeleteMedia' or
       'deleteSelectedDisplay' set $editMode to 'edit'. */
    if ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit') !== null && $adminMode !== 'confirmDeleteMedia' && $adminMode !== 'deleteSelectedDisplay') {
        $editMode = 'edit';
        $nameOfDisplayBeingEdited = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit');
    } /* Otherwise, if a page has been chosen to have a new display created for it, and admin mode
         is not set to 'confirmDeleteMedia' or 'deleteSelectedDisplay' set $editMode to 'add'. */
    elseif ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayPageName') !== null && $adminMode !== 'confirmDeleteMedia' && $adminMode !== 'deleteSelectedDisplay') {
        $editMode = 'add';
        $nameOfDisplayBeingEdited = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayPageName');
    } /* Otherwise, if admin mode is equal to 'confirmDeleteMedia' or 'deleteSelectedDisplay' set
         $editMode to 'delete'. */
    elseif ($adminMode === 'confirmDeleteMedia' || $adminMode === 'deleteSelectedDisplay') { // deleteDisplays
        $editMode = 'delete';
        $nameOfDisplayBeingEdited = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit');
    } /* Default, set $editMode and $nameOfDisplayBeingEdited to null. */
    else {
        $editMode = null;
        $nameOfDisplayBeingEdited = null;
    }

    /* SdmMediaDisplays Admin Form | Define form properties. */

    /* Determine if submitted form values should be preserved */
    $sdmMediaDisplaysAdminForm->preserveSubmittedValues = true;

    /* Exclude default submit label, custom buttons will be used instead.  */
    $sdmMediaDisplaysAdminForm->excludeSubmitLabel = true;

    /* Load admin form elements. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/sdmMediaDisplaysAdminFormElements.php');

    /* Load custom admin buttons. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/buttons/SdmMediaDisplaysAdminButtons.php');

    /* Load Admin Panel Form */
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
            /* Display crud panel description. */
            $panelDescription = 'Welcome to the Sdm Media Display\'s admin panel. Use the admin panels below to manage the site\'s media displays.';

            /* If $adminMode is confirmDeleteDisplay, load delete display form handler. */
            if ($adminMode === 'confirmDeleteDisplay') {
                require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formHandlers/displayCrudPanel_deleteDisplay.php');
            }
            break;
        case 'selectDisplayPanel':
            /* Set panel name based on $adminMode. */
            $panelName = ($adminMode === 'addDisplays' ? 'Add Display' : 'Edit Displays');

            /* Set panel description based on $adminMode. */
            $panelDescription = ($adminMode === 'addDisplays' ? '<p>Please select a page for the display to appear on.</p><p>If you don\'t see the page you are looking for there may already be a display for it, in which case return to the <a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmMediaDisplays"><b>Sdm Displays Admin Panel</b></a> and choose "Edit Displays.</p><p>If no select list appears then all the pages available to displays must already have a display assigned to them, in which case you can simply edit the displays by page from the <a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmMediaDisplays"><b>Sdm Displays Admin Panel</b></a>.</p>' : 'Select a display to edit.');

            /* If adminMode is edit displays, make sure there are displays to edit. If there are not, then
               rewrite $panelDescription to indicate that to user. */
            if ($adminMode === 'editDisplays') {
                /* Check if there are displays available to edit. */
                if (!empty($displaysAvailableToEdit) === true || isset($displaysAvailableToEdit) === true) {
                    /* If there are displays, set $displaysExist to true. */
                    $displaysExist = true;
                } else {
                    /* If there are no displays, set $displaysExist to false. */
                    $displaysExist = false;
                }
            }
            /* Create an appropriate panel description if there are no editable displays. */
            if (isset($displaysExist) && $displaysExist === false) {
                /* If displays exist is set to false, create $panelDescription to indicate to user that
                   there are no editable displays. */
                $panelDescription = "<span>There are no displays to edit. To create one go to the <a href='{$sdmassembler->sdmCoreGetRootDirectoryUrl()}/index.php?page=SdmMediaDisplays'>main Sdm Media Display admin panel</a> and click the \"Add Display\" button.</span>";
            }
            break;
        case 'mediaCrudPanel':
            /* Load appropriate form handler based on $editMode. */
            if ($editMode === 'edit') {
                /* Edit mode panel description. */
                $panelDescription = 'Select a piece of media from below and then click one of the admin buttons at the bottom of the page to edit the media you selected.';

                /* load edit handlers for this panel */
                require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formHandlers/selectDisplayPanel_editMedia.php');

            } elseif ($editMode === 'add') {
                $panelDescription = 'Use the admin buttons below to administer the new <span style="color:#66ff66">' . ucwords($nameOfDisplayBeingEdited) . '</span> display\'s media.';
                /* load add handlers for this panel */
                require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formHandlers/selectDisplayPanel_addDisplays.php');
            } elseif ($editMode === 'delete') {
                $panelDescription = 'Media was deleted successfully.';
                /* load delete handlers for this panel */
                require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formHandlers/mediaCrudPanel_deleteMedia.php');
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
    $sdmassembler->sdmAssemblerIncorporateAppOutput("<div id='SdmMediaDisplaysAdminPanel' class='SdmMediaDisplaysAdminPanel'><h2>$panelName</h2><p>$panelDescription</p><div class='adminPanelContentSpacer'></div>$completeFormHtml</div>", array('incpages' => array('SdmMediaDisplays'), 'roles' => array('root'), 'incmethod' => 'prepend'));
    //UNCOMMENT TO DEBUG PANEL PARAMETERS: //
    //var_dump(['display to edit' => $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit'), '$currentPanel' => $currentPanel, '$panelName' => $panelName, '$panelDescription' => $panelDescription, '$adminMode' => $adminMode, '$editMode' => $editMode]);
}
