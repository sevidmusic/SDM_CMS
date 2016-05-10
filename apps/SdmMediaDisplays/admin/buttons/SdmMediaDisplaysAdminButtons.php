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
var_dump($sdmMediaDisplay->sdmMediaDisplayHasMedia('homepage'), $nameOfDisplayBeingEdited);
/* Define buttons for each Sdm Media Displays admin panel. */
$sdmMediaDisplayAdminPanelButtons = array(
    'displayCrudPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editDisplays', 'panel', 'selectDisplayPanel_editDisplays', 'Edit Displays', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addDisplays', 'panel', 'selectDisplayPanel_addDisplays', 'Add Displays', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteDisplays', 'panel', 'deleteDisplayPanel_deleteDisplays', 'Delete Displays', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'deleteDisplayPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteDisplay', 'panel', 'displayCrudPanel_confirmDeleteDisplay', 'Delete Display', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:44%;')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteDisplay', 'panel', 'displayCrudPanel_cancelDeleteDisplay', 'Cancel', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:44%;')),
    ),
    'mediaCrudPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editMedia', 'panel', 'editMediaPanel_editMedia', 'Edit Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addMedia', 'panel', 'editMediaPanel_addMedia', 'Add Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteMedia', 'panel', 'deleteMediaPanel_deleteMedia', 'Delete Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveContinue', 'panel', 'mediaCrudPanel_saveContinue', 'Save and Continue', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveFinish', 'panel', 'displayCrudPanel_saveFinish', 'Save and Finish', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'deleteMediaPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteMedia', 'panel', 'mediaCrudPanel_confirmDeleteMedia', 'Delete Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:44%;')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteMedia', 'panel', 'mediaCrudPanel_cancelDeleteMedia', 'Cancel', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:44%;')),
    ),
    'selectDisplayPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_selectDisplay', 'panel', 'mediaCrudPanel_selectDisplay', 'Edit Media for ' . ($adminMode === 'editDisplays' ? 'Selected' : 'New') . ' Display', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:88%;')),
    ),
    'editMediaPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveMedia', 'panel', 'mediaCrudPanel_saveMedia', 'Save Changes to Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:88%;')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelSaveMedia', 'panel', 'mediaCrudPanel_cancelSaveMedia', 'Cancel', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId(), 'style' => 'margin-left:0px;min-width:88%;')),
    ),
);

/* Get current admin panels buttons. */
$currentPanelsButtons = array();
foreach ($sdmMediaDisplayAdminPanelButtons as $panel => $panelButtons) {
    if ($panel === $currentPanel) {
        foreach ($panelButtons as $panelButton) {
            $currentPanelsButtons[] = $panelButton;
        }
    }
}

