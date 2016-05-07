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
    /* Load media from current displays data directory. */
    $mediaData = file_get_contents(__DIR__ . '/displays/data/' . $currentDisplay . '/533720691427307256093454.json');

    /* Decode media. */
    $imageProperties = json_decode($mediaData, true);

    /* Create SdmMedia object. */
    $imageObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($imageProperties);

    /* Add SdmMedia object to display. */
    $sdmMediaDisplay->sdmMediaDisplayAddMediaObject($imageObject);

    /* Build Display */
    $sdmMediaDisplay->sdmMediaDisplayBuildMediaDisplay();

    /* Get Display Html */
    $sdmMediaDisplayHtml = $sdmMediaDisplay->sdmMediaDisplayGetSdmMediaDisplayHtml();

    /* Load app output options. */
    require_once(__DIR__ . '/SdmMediaDisplayOptions.php');

    /* Output display */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmMediaDisplayHtml, $options);
}