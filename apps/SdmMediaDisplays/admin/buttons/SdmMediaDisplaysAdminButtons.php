<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:26 PM
 */

/* Define buttons for each admin panel. */
/**
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
    return "<button id='$id' name='$name' value='$value' " . implode(' ', $attributes) . ">$label</button>";
}

/* Create Sdm Media Display's Admin Panel buttons. */
$sdmMediaDisplayAdminPanelButtons = array(
    'displayCrud' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editDisplays', 'panel', 'selectDisplayPanel', 'Edit Displays', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_editDisplays')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addDisplays', 'panel', 'selectDisplayPanel', 'Add Displays', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_addDisplays')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteDisplays', 'panel', 'deleteDisplayPanel', 'Delete Displays', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_deleteDisplays')),
    ),
    'deleteDisplay' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteDisplay', 'panel', 'displayCrud', 'Delete Display', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_confirmDeleteDisplay')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteDisplay', 'panel', 'displayCrud', 'Cancel', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_cancelDeleteDisplay')),
    ),
    'mediaCrud' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_editMedia', 'panel', 'editMedia', 'Edit Media', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_editMedia')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_addMedia', 'panel', 'editMedia', 'Add Media', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_editMedia')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_deleteMedia', 'panel', 'deleteMedia', 'Delete Media', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_editMedia')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveContinue', 'panel', 'editMedia', 'Save and Continue', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_saveContinue')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_saveContinue', 'panel', 'displayCrud', 'Save and Finish', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_saveFinish')),
    ),
    'deleteMedia' => array(
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_confirmDeleteMedia', 'panel', 'editMedia', 'Delete Media', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_confirmDeleteMedia')),
        createSdmMediaDisplayAdminButton('sdmMediaDisplayAdminButton_cancelDeleteMedia', 'panel', 'editMedia', 'Cancel', array('data-referred-by-button' => 'sdmMediaDisplayAdminButton_cancelDeleteMedia')),
    ),
);

/* Determine which admin panel is currently in use. */
//$currentPanel = 'displayCrud'; // dev value placeholder for submitted form value 'panel'
//$currentPanel = 'deleteDisplay'; // dev value placeholder for submitted form value 'panel'
$currentPanel = 'mediaCrud'; // dev value placeholder for submitted form value 'panel'
//$currentPanel = 'deleteMedia'; // dev value placeholder for submitted form value 'panel'

/* Get current admin panels buttons. */
$currentPanelsButtons = array();
foreach ($sdmMediaDisplayAdminPanelButtons as $panel => $panelButtons) {
    if ($panel === $currentPanel) {
        foreach ($panelButtons as $panelButton) {
            $currentPanelsButtons[] = $panelButton;
        }
    }
}

foreach ($currentPanelsButtons as $button) {
    $sdmassembler->sdmAssemblerIncorporateAppOutput($button);
}

//$sdmassembler->sdmAssemblerAssembleHtmlElement('', array('elementType' => 'button', 'name' => 'panel', 'value' => 'editDisplay', 'type' => 'submit'))


