<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:26 PM
 */

/**
 * Create a button for that can be assigned to one of the admin panels.
 * @param $id string The buttons id.
 * @param $name string The buttons name.
 * @param $value string The buttons value.
 * @param $label string The text to use for the button.
 * @return string The html for the button.
 */
function createSdmMediaDisplayAdminButton($id, $name, $value, $label, $otherAttributes = array())
{
    $attributes = array();
    foreach ($otherAttributes as $attributeName => $attributeValue) {
        $attributes[] = "$attributeName='$attributeValue'";
    }
    return "<button id='$id' name='SdmForm[$name]' type='submit' data-referred-by-button='$id' value='$value' " . implode(' ', $attributes) . ">$label</button>";
}

/* Define buttons for each Sdm Media Displays admin panel. */
$sdmMediaDisplayAdminPanelButtons = array(
    'displayCrudPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addDisplays', 'panel', 'selectDisplayPanel_addDisplays', 'Add Display', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'deleteDisplayPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteSelectedDisplay', 'panel', 'confirmDeleteDisplayPanel_deleteSelectedDisplay', 'Delete Selected Display', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:88%;')),
    ),
    'confirmDeleteDisplayPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteDisplay', 'panel', 'displayCrudPanel_confirmDeleteDisplay', 'Delete Display', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:44%;')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteDisplay', 'panel', 'displayCrudPanel_cancelDeleteDisplay', 'Cancel', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:44%;')),
    ),
    'mediaCrudPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addMedia', 'panel', 'editMediaPanel_addMedia', 'Add Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveContinue', 'panel', 'mediaCrudPanel_saveContinue', 'Refresh Panel', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveFinish', 'panel', 'displayCrudPanel_saveFinish', 'Save and Finish', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'deleteMediaPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteMedia', 'panel', 'mediaCrudPanel_confirmDeleteMedia', 'Delete Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:44%;')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteMedia', 'panel', 'mediaCrudPanel_cancelDeleteMedia', 'Cancel', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:44%;')),
    ),
    'selectDisplayPanel' => array(/* defined below */),
    'editMediaPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveMedia', 'panel', 'mediaCrudPanel_saveMedia', 'Save Changes to Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:88%;')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelSaveMedia', 'panel', 'mediaCrudPanel_cancelSaveMedia', 'Cancel', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:88%;')),
    ),
);

/* Only show edit and delete display buttons if there are displays other then the default. */
$expectedDirs = array('.', '..', '.DS_Store', 'SdmMediaDisplays');

/* Scan data directory for displays. */
$displays = scandir($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data');

/* Check $displays against $expectedDirs, if a display is found that is not an expected directory then display exist. */
foreach ($displays as $display) {
    if (!in_array($display, $expectedDirs)) {
        /* Display found, set $displaysExist to true. */
        $displaysExist = true;
        /* A display was found, exit loop. */
        break;
    }
    /* No displays exist. */
    $displaysExist = false;
}

if ($displaysExist === true) {
    var_dump('Displays exist.');
    array_push($sdmMediaDisplayAdminPanelButtons['displayCrudPanel'], createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editDisplays', 'panel', 'selectDisplayPanel_editDisplays', 'Edit Displays', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())));
    array_push($sdmMediaDisplayAdminPanelButtons['displayCrudPanel'], createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteDisplays', 'panel', 'deleteDisplayPanel_deleteDisplays', 'Delete Displays', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())));

}
/* If the current display being edited has media create edit and delete media buttons for the mediaCrudPanel. */
if ($sdmMediaDisplay->sdmMediaDisplayHasMedia($nameOfDisplayBeingEdited) === true) {
    array_push($sdmMediaDisplayAdminPanelButtons['mediaCrudPanel'], createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editMedia', 'panel', 'editMediaPanel_editMedia', 'Edit Selected', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())));
    array_push($sdmMediaDisplayAdminPanelButtons['mediaCrudPanel'], createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteMedia', 'panel', 'deleteMediaPanel_deleteMedia', 'Delete Selected', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())));

}

/* Only show edit media items button on selectDisplayPanel for add and edit admin modes if there are
   pages that do not already have a display assigned to to them or if there are displays available to edit. */
if ((!empty($pagesAvailableToDisplays) && $adminMode === 'addDisplays') || (!empty($displaysAvailableToEdit) && $adminMode === 'editDisplays')) {
    array_push($sdmMediaDisplayAdminPanelButtons['selectDisplayPanel'], createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_selectDisplay', 'panel', 'mediaCrudPanel_selectDisplay', 'Edit Media for ' . ($adminMode === 'editDisplays' ? 'Selected' : 'New') . ' Display', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:88%;')));
}
/* Get current admin panels buttons. */
$currentPanelsButtons = array();
foreach ($sdmMediaDisplayAdminPanelButtons as $panel => $panelButtons) {
    if ($panel === $currentPanel) {
        foreach ($panelButtons as $panelButton) {
            $currentPanelsButtons[] = $panelButton;
        }
    }
}

