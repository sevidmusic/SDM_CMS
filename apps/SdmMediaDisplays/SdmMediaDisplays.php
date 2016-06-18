<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 5:59 PM
 */

/* If the data directory exists, attempt to load any displays that exist. */
if (is_dir(__DIR__ . '/displays/data') === true) {
    /* Get a directory listing of available displays. */
    $displaysDirectoryListing = $sdmassembler->sdmCoreGetDirectoryListing('SdmMediaDisplays/displays/data', 'apps');
    $availableDisplays = array();
    $ignoredListings = array('.', '..', '.DS_Store');
    foreach ($displaysDirectoryListing as $availableDisplay) {
        if (in_array($availableDisplay, $ignoredListings) === false) {
            $availableDisplays[$availableDisplay] = $availableDisplay;
        }
    }

    foreach ($availableDisplays as $currentDisplay) {

        /* Create an instance of SdmCore() for the SdmMediaDisplay(). */
        $SdmCore = new SdmCore();

        /* Only build a display if there is SdmMedia data for the currentDisplay. | A display may exist without any media. | @todo check should actually check if dir is empty */
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
            $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmMediaDisplayHtml, $sdmMediaDisplay->loadDisplayOutputOptions());

        }

    }
}

/* If current page is the SdmMediaDisplays page show admin panel. */
if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmMediaDisplays') {

    $adminPanel = new SdmMediaDisplaysAdmin(new SdmCms());

    $output = $adminPanel->getCurrentAdminPanel();

    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, array('incpages' => array('SdmMediaDisplays'), 'roles' => array('root')));

}
