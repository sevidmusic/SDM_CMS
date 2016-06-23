<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 5:59 PM
 */

/**
 * Returns an array of available displays.
 *
 * @param sdmAssembler $sdmAssembler Instance of the sdmAssembler() class.
 *
 * @return array Returns an array of available displays. If there are no available displays this function will return an empty array.
 */
function determineAvailableDisplays(sdmAssembler $sdmAssembler)
{
    /* Get a directory listing of available displays. */
    $displaysDirectoryListing = $sdmAssembler->sdmCoreGetDirectoryListing('SdmMediaDisplays/displays/data', 'apps');

    /* Initialize $availableDisplays array. */
    $availableDisplays = array();

    /* Create an array of directories that should be ignored, i.e. array of directories that are known to not represent a display. */
    $ignoredListings = array('.', '..', '.DS_Store');

    /* Add listings not in the $ignoredListings array to the available displays array. */
    foreach ($displaysDirectoryListing as $availableDisplay) {
        if (in_array($availableDisplay, $ignoredListings) === false) {
            $availableDisplays[$availableDisplay] = $availableDisplay;
        }
    }

    /* Return array of available displays */
    return $availableDisplays;
}

/* If the data directory exists, attempt to load any displays that exist. */
if (is_dir(__DIR__ . '/displays/data') === true) {

    /* Determine available displays */
    $availableDisplays = determineAvailableDisplays($sdmassembler);

    /* Process each available display. */
    foreach ($availableDisplays as $currentDisplay) {

        /* Create an instance of SdmCore() for the SdmMediaDisplay(). */
        $SdmCore = new SdmCore();

        /* Create New Sdm Media Display */
        $sdmMediaDisplay = new SdmMediaDisplay($currentDisplay, $SdmCore);

        /* Only build a display if there is SdmMedia data for the currentDisplay. | A display may exist without any media, in which case it should not be assembled in order to preserve memory and prevent errors. */
        if ($sdmMediaDisplay->sdmMediaDisplayHasMedia($currentDisplay)) {

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
