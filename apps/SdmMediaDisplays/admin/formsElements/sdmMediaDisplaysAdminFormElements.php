<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:50 PM
 */

/* Determine pages available to displays. */
$pagesAvailableToDisplays = $sdmassembler->sdmCoreDetermineAvailablePages();

/* Determine available displays | based on directories in /displays/data  */
$displaysAvailableToEdit = array_diff($sdmassembler->sdmCoreGetDirectoryListing('sdmMediaDisplays/displays/data', 'apps'), array('.', '..', '.DS_Store', 'SdmMediaDisplays'));

/* Structure an array of available displays for use as form value */
$displaysAvailableToEditFormValueArray = array_combine($displaysAvailableToEdit, $displaysAvailableToEdit);

/* Structure an array of available pages for use as form value removing any page names that match the
   name of an available display. */
foreach ($pagesAvailableToDisplays as $key => $value) {
    // if page already has a display remove it from the $pagesAvailableToDisplays array
    if (in_array($value, $displaysAvailableToEdit) === true) {
        unset($pagesAvailableToDisplays[$key]);
    }
}

/** Handle form element assignments that are based on $adminMode **/

/* Check $adminMode to determine whether to show "select display to edit" or or "select page to show display on" form element on the selectDisplayPaenl. */
if ($adminMode === 'editDisplays' || $adminMode === 'deleteDisplays') {
    $selectDisplayFormElement = $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayToEdit', 'select', 'Select Display', $displaysAvailableToEditFormValueArray, 0);
} else {
    if (!empty($pagesAvailableToDisplays)) {
        $selectDisplayFormElement = $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayPageName', 'select', 'Select A Page To Show Display On', $pagesAvailableToDisplays, 0);
    }
}

if (!isset($selectDisplayFormElement)) {
    $selectDisplayFormElement = array();
}
/* Define form elements for each Sdm Media Displays admin panel. */
$sdmMediaDisplayAdminPanelFormElements = array(
    'mediaCrudPanel' => array(),
    'deleteMediaPanel' => array(),
    'confirmDeleteDisplayPanel' => array(),
    'deleteDisplayPanel' => array(
        $selectDisplayFormElement,
    ),
    'selectDisplayPanel' => array(
        $selectDisplayFormElement,
    ),
    'editMediaPanel' => array(),
);
require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/editMediaPanelFormElements.php');


// if $nameOfDisplayBeingEdited is set, assign it to all forms as a hidden form element
if (isset($nameOfDisplayBeingEdited) === true) {
    foreach ($sdmMediaDisplayAdminPanelFormElements as $panelFormElement => $panelFormElementArray) {
        if ($panelFormElement !== 'selectDisplayPanel') {
            array_push($sdmMediaDisplayAdminPanelFormElements[$panelFormElement], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayToEdit', 'hidden', '', $nameOfDisplayBeingEdited, 100));
            array_push($sdmMediaDisplayAdminPanelFormElements[$panelFormElement], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayPageName', 'hidden', '', $nameOfDisplayBeingEdited, 101));
        }
    }
}
$sdmMediaObject = new  SdmMedia;

/* Load mediaList form elements for mediaCrudPanel. */
require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/mediaList.php');

/* Incorporate form elements into the form. */
$sdmMediaDisplaysAdminForm->sdmFormBuildFormElements();

/* Get current admin panels form elements */
$currentPanelsFormElements = array();

foreach ($sdmMediaDisplayAdminPanelFormElements as $panel => $panelFormElements) {
    if ($panel === $currentPanel) {
        foreach ($panelFormElements as $formElement) {
            $currentPanelsFormElements[] = $sdmMediaDisplaysAdminForm->sdmFormGetFormElementHtml($formElement['id']);
        }
    }
}