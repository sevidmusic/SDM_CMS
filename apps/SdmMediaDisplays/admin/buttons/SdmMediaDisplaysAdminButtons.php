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
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editDisplays', 'panel', 'selectDisplayPanel', 'Edit Displays', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addDisplays', 'panel', 'selectDisplayPanel', 'Add Displays', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteDisplays', 'panel', 'deleteDisplayPanel', 'Delete Displays', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'deleteDisplayPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteDisplay', 'panel', 'displayCrudPanel', 'Delete Display', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteDisplay', 'panel', 'displayCrudPanel', 'Cancel', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'mediaCrudPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editMedia', 'panel', 'editMediaPanel', 'Edit Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addMedia', 'panel', 'editMediaPanel', 'Add Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteMedia', 'panel', 'deleteMediaPanel', 'Delete Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveContinue', 'panel', 'mediaCrudPanel', 'Save and Continue', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveContinue', 'panel', 'displayCrudPanel', 'Save and Finish', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'deleteMediaPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteMedia', 'panel', 'mediaCrudPanel', 'Delete Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteMedia', 'panel', 'mediaCrudPanel', 'Cancel', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'selectDisplayPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_selectDisplay', 'panel', 'mediaCrudPanel', 'Edit Media for Selected Display', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'editMediaPanel' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveMedia', 'panel', 'mediaCrudPanel', 'Save Changes to Media', array('form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
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

