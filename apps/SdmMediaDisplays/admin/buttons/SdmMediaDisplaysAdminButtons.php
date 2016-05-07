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
    return "<button id='$id' name='SdmForm[$name]' type='submit' value='$value' " . implode(' ', $attributes) . ">$label</button>";
}

/* Define buttons for each Sdm Media Displays admin panel. */
$sdmMediaDisplayAdminPanelButtons = array(
    'displayCrud' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editDisplays', 'panel', 'selectDisplayPanel', 'Edit Displays', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_editDisplays', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addDisplays', 'panel', 'selectDisplayPanel', 'Add Displays', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_addDisplays', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteDisplays', 'panel', 'deleteDisplayPanel', 'Delete Displays', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_deleteDisplays', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'deleteDisplay' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteDisplay', 'panel', 'displayCrud', 'Delete Display', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_confirmDeleteDisplay', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteDisplay', 'panel', 'displayCrud', 'Cancel', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_cancelDeleteDisplay', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'mediaCrud' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editMedia', 'panel', 'editMedia', 'Edit Media', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_editMedia', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addMedia', 'panel', 'editMedia', 'Add Media', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_editMedia', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteMedia', 'panel', 'deleteMedia', 'Delete Media', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_editMedia', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveContinue', 'panel', 'editMedia', 'Save and Continue', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_saveContinue', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveContinue', 'panel', 'displayCrud', 'Save and Finish', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_saveFinish', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
    'deleteMedia' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteMedia', 'panel', 'editMedia', 'Delete Media', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_confirmDeleteMedia', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteMedia', 'panel', 'editMedia', 'Cancel', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_cancelDeleteMedia', 'form' => $sdmMediaDisplaysAdminForm->sdmFormGetFormId())),
    ),
);

/* Determine which admin panel is currently in use. */
$defaultPanel = 'displayCrud'; // dev value placeholder for submitted form value 'panel'
$requestedPanel = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('panel');
$currentPanel = ($requestedPanel === null ? $defaultPanel : $requestedPanel); // dev value placeholder for submitted form value 'panel'

/* Get current admin panels buttons. */
$currentPanelsButtons = array();
foreach ($sdmMediaDisplayAdminPanelButtons as $panel => $panelButtons) {
    if ($panel === $currentPanel) {
        foreach ($panelButtons as $panelButton) {
            $currentPanelsButtons[] = $panelButton;
        }
    }
}

// to get the panels buttons use $sdmassembler->sdmAssemblerIncorporateAppOutput(implode('', $currentPanelsButtons));

//$sdmassembler->sdmAssemblerAssembleHtmlElement('', array('elementType' => 'button', 'name' => 'panel', 'value' => 'editDisplay', 'type' => 'submit'))


