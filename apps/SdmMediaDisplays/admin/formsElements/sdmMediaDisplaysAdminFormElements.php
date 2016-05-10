<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:50 PM
 */

/* Determine pages available to displays. */
$pagesAvailableToDisplays = $sdmassembler->sdmCoreDetermineAvailablePages();

/* Determine available displays */
$displaysAvailableToEdit = array_diff($sdmassembler->sdmCoreGetDirectoryListing('sdmMediaDisplays/displays/data', 'apps'), array('.', '..', 'SdmMediaDisplays'));

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
    $selectDisplayFormElement = $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayPageName', 'select', 'Select A Page To Show Display On', $pagesAvailableToDisplays, 0);
}
/* Define form elements for each Sdm Media Displays admin panel. */
$sdmMediaDisplayAdminPanelFormElements = array(
    'mediaCrudPanel' => array(),
    'deleteDisplayPanel' => array(
        $selectDisplayFormElement,
    ),
    'selectDisplayPanel' => array(
        $selectDisplayFormElement,
    ),
    'editMediaPanel' => array(
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaCategory', 'text', 'Category name to organize media by. Media is ordered in display by media\'s category, place, and finally name.', '', 1),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaPlace', 'select', 'Position in display relative to other media.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(range(1, 1000), 1), 2),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaFile', 'file', 'Upload media file | Only used for local medaia sources.', null, 2),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceType', 'select', 'Is the media source external or local? External sources are sources from other sites, such as Youtube. Local sources are stored on the site\'s server.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('External (Media resource from another site)' => 'external', 'Local (Media stored locally)' => 'local',), 'local'), 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaType', 'select', 'Select the media\'s type.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('Image' => 'image', 'Audio' => 'audio', 'Video' => 'video', 'Youtube Video' => 'youtube', 'HTML5 Canvas Image/Animation (Javascript file for HTML5 canvas tag)' => 'canvas',), 'audio'), 5),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceUrl', 'text', 'Url To Media | Only set for external media sources. (If youtube url it must be the embed url provided by youtube.)', '', 6),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourcePath', 'hidden', $sdmassembler->sdmCoreGetUserAppDirectoryUrl() . '/SdmMediaDisplays/displays/media', '', 4), // devnote: should be stored as /SITEROOT/apps/SdmMediaDisplays/displays/media/DISPLAY/MEDIA when displayed
        // The commented out form elements below are not needed as the values will be set internally.
        // $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaMachineName', 'text', 'Machine Safe Name For Media', '', 4), // set by file input
        //$sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaProtected', 'radio', 'Protect media from download. (still in development)', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('Yes' => true, 'No' => false,), false), 4), // still in dev
        //$sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaPublic', 'radio', 'Display media in public views.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('Yes' => true, 'No' => false,), true), 4), // still in dev
        //$sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceExtension', 'select', 'Select media\'s file extension.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('mp3 Audio File' => 'mp3', 'm4v Video File' => 'm4v', 'jpg Image File' => 'jpg', 'png Image File' => 'png'), 'mp3'), 4), // set by file input
        //$sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceName', 'text', 'Name of the media file.', '', 4), // set by file input
    ),
);

// if $nameOfDisplayBeingEdited is set, assign it to all forms as a hidden form element
if (isset($nameOfDisplayBeingEdited) === true) {
    foreach ($sdmMediaDisplayAdminPanelFormElements as $panelFormElement => $panelFormElementArray) {
        if ($panelFormElement !== 'selectDisplayPanel')
            array_push($sdmMediaDisplayAdminPanelFormElements[$panelFormElement], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayToEdit', 'hidden', '', $nameOfDisplayBeingEdited, 100));
        array_push($sdmMediaDisplayAdminPanelFormElements[$panelFormElement], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayPageName', 'hidden', '', $nameOfDisplayBeingEdited, 101));
    }
}
$sdmMediaObject = new  SdmMedia;

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