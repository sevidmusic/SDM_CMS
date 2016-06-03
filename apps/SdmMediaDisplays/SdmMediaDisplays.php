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
$currentDisplay = 'Windham Wine And Liquors Backgrounds';

/* Load app output options. */
require_once(__DIR__ . '/SdmMediaDisplayOptions.php');

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

    /* Output display */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmMediaDisplayHtml, $currentDisplaysOptions);

}

/* If current page is the SdmMediaDisplays page show admin panel. */
if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmMediaDisplays') {

    $adminPanel = new SdmMediaDisplaysAdmin();

    $output = $adminPanel->getCurrentAdminPanel();

    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, $currentDisplaysOptions);


    /* Load admin panels */
    //require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/SdmMediaDisplaysAdminPanel.php');

}
