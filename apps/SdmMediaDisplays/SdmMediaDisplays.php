<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 5:59 PM
 */

/** @var $currentDisplay string
 * The current display is determined by the currently requested page.
 * It is used to determine if there is display data for the current page,
 * and which app options array to use if there is.
 */
$currentDisplay = 'homepage';

/* Create an instance of SdmCore() for the SdmMediaDisplay(). */
$SdmCore = new SdmCore();

/* Only build a display if there is SdmMedia data for the currentDisplay (i.e., The current page). */
if (file_exists(__DIR__ . '/displays/data/' . $currentDisplay) === true) {
    /* Create New Sdm Media Display */
    $sdmMediaDisplay = new SdmMediaDisplay($currentDisplay, $SdmCore);

    /* Load media object properties for the media in this display */
    $mediaProperties = $sdmMediaDisplay->sdmMediaDisplayLoadMediaObjectProperties($currentDisplay);

    /* Create SdmMedia objects for this display. */
    $mediaObjects = array();
    foreach ($mediaProperties as $properties) {
        $mediaObjects[] = $sdmMediaDisplay->sdmMediaCreateMediaObject($properties);
    }

    /* Add SdmMedia objects to display. */
    foreach ($mediaObjects as $mediaObject) {
        $sdmMediaDisplay->sdmMediaDisplayAddMediaObject($mediaObject);
    }

    /* Build Display */
    $sdmMediaDisplay->sdmMediaDisplayBuildMediaDisplay();

    /* Get Display Html */
    $sdmMediaDisplayHtml = $sdmMediaDisplay->sdmMediaDisplayGetSdmMediaDisplayHtml();

    /* Load app output options. */
    require_once(__DIR__ . '/SdmMediaDisplayOptions.php');

    /* Output display */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmMediaDisplayHtml, $options);

}

/* If current page is the SdmMediaDisplays page show admin panel. */
if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmMediaDisplays') {
    /*** Build Admin Panel ***/

    /* Instantiate new form object. */
    $editDisplayForm = new SdmForm();

    /* Form handler */
    $editDisplayForm->formHandler = 'SdmMediaDisplays';

    /* Form method */
    $editDisplayForm->method = 'post';

    /* Determine whether form should preserve submitted values. */
    $editDisplayForm->preserveSubmittedValues = true;

    /** Form Elements **/
    $editDisplayForm->sdmFormCreateFormElement('page', 'select', 'Select a page to assign this display to:', $sdmassembler->sdmCoreDetermineAvailablePages(), 0);

    /* Submit button label. */
    $editDisplayForm->submitLabel = 'Save Changes to Display';

    /* Set form to preserve submitted values. */
    $editDisplayForm->preserveSubmittedValues = true;

    /* Build Form */
    $editDisplayForm->sdmFormBuildForm();

    /* Load admin panels */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/SdmMediaDisplaysAdminPanel.php');

}
