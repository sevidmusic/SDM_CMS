<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 5:59 PM
 */

/* Create New Sdm Media Display | To use a custom template specify its name. */
$sdmMediaDisplay = new SdmMediaDisplay();

/** @var $currentDisplay string
 * The current display is determined by the currently requested page.
 * It is used to determine if there is display data for the current page,
 * and which app options array to use if there is.
 */
$currentDisplay = $sdmassembler->sdmCoreDetermineRequestedPage();

/* Only build a display if there is SdmMedia data for the currentDisplay (i.e., The current page). */
if (file_exists(__DIR__ . '/displays/data/' . $currentDisplay) === true) {

    /* Get directory listing of saved media for the current display. */
    $savedMedia = $sdmassembler->sdmCoreGetDirectoryListing("SdmMediaDisplays/displays/data/$currentDisplay", 'apps');

    /* Load media objects */
    $mediaJson = array();
    foreach ($savedMedia as $mediaJsonFilename) {
        $badFileNames = array('.', '..');
        if (in_array($mediaJsonFilename, $badFileNames) === false) {
            /* Load media from current displays data directory. */
            $mediaJson[] = file_get_contents(__DIR__ . '/displays/data/' . $currentDisplay . '/' . $mediaJsonFilename);
        }
    }

    /* Unpack media properties. */
    $mediaProperties = array();
    foreach ($mediaJson as $json) {
        /* Decode media. */
        $mediaProperties[] = json_decode($json, true);
    }


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