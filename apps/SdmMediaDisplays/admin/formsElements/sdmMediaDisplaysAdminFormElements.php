<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:50 PM
 */

/* Determine pages available to displays. */
$pagesAvailableToDisplays = $sdmassembler->sdmCoreDetermineAvailablePages();

/* Determine available displays. | Based on directories in /displays/data.  */
$displaysAvailableToEdit = array_diff($sdmassembler->sdmCoreGetDirectoryListing('sdmMediaDisplays/displays/data', 'apps'), array('.', '..', '.DS_Store', 'SdmMediaDisplays'));

/* Structure an array of available displays for use as form values. */
$displaysAvailableToEditFormValueArray = array_combine($displaysAvailableToEdit, $displaysAvailableToEdit);

/* Structure an array of available pages for use as form value removing any page names that match the
   name of an available display. */
foreach ($pagesAvailableToDisplays as $key => $value) {
    // if page already has a display remove it from the $pagesAvailableToDisplays array
    if (in_array($value, $displaysAvailableToEdit) === true) {
        unset($pagesAvailableToDisplays[$key]);
    }
}

/** Build appropriate form element based on $adminMode **/

/* Check $adminMode to determine whether to show "select display to edit" or or "select page to show display on" form element on the selectDisplayPaenl. */
if ($adminMode === 'editDisplays' || $adminMode === 'deleteDisplays') {
    if (!empty($displaysAvailableToEditFormValueArray)) {
        $selectDisplayFormElement = $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayToEdit', 'select', 'Select Display', $displaysAvailableToEditFormValueArray, 0);
    }
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

/* Load additional edit media panel form elements. */
require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/editMediaPanelFormElements.php');

/* If $nameOfDisplayBeingEdited is set, assign it to all forms as a hidden form element */
if (isset($nameOfDisplayBeingEdited) === true) {
    foreach ($sdmMediaDisplayAdminPanelFormElements as $panelFormElement => $panelFormElementArray) {
        if ($panelFormElement !== 'selectDisplayPanel') {
            array_push($sdmMediaDisplayAdminPanelFormElements[$panelFormElement], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayToEdit', 'hidden', '', $nameOfDisplayBeingEdited, 100));
            array_push($sdmMediaDisplayAdminPanelFormElements[$panelFormElement], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayPageName', 'hidden', '', $nameOfDisplayBeingEdited, 101));
        }
    }
}

/* Create media object instance to be used by edit media form components.. */
$sdmMediaObject = new  SdmMedia;

/* Load mediaList form elements for mediaCrudPanel. */
require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/mediaList.php');

/* Load delete media admin panel form elements for mediaCrudPanel. */
require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formsElements/deleteMediaFormElements.php');

/* Incorporate form elements into the form. */
$sdmMediaDisplaysAdminForm->sdmFormBuildFormElements();

/* Get current admin panels form elements */
$currentPanelsFormElements = array();

foreach ($sdmMediaDisplayAdminPanelFormElements as $panel => $panelFormElements) {
    if ($panel === $currentPanel) {
        foreach ($panelFormElements as $formElement) {
            if ($formElement['type'] !== 'hidden') {
                if ($sdmMediaDisplaysAdminForm->sdmFormGetFormElementHtml($formElement['id']) !== null) {
                    $currentPanelsFormElements[] = '<div style="border: 3px solid white; border-radius: 9px;margin-bottom: 20px; padding: 20px; width: 88%;">' . $sdmMediaDisplaysAdminForm->sdmFormGetFormElementHtml($formElement['id']) . '</div>';
                }
            } else {
                $currentPanelsFormElements[] = $sdmMediaDisplaysAdminForm->sdmFormGetFormElementHtml($formElement['id']);
            }
        }
    }
}