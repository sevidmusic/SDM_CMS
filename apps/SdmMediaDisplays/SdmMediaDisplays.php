<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 5:59 PM
 */

/* Create New Sdm Media Display | To use a custom template specify its name. */
$sdmMediaDisplay = new SdmMediaDisplay();

$currentDisplay = ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmMediaDisplays' ? 'default' : $sdmassembler->sdmCoreDetermineRequestedPage());

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


    /* Options arrays for different displays */
    $defaultOptions = array(
        'incpages' => array('SdmMediaDisplays'),
        'wrapper' => 'main_content',
        'roles' => array('root'),
        'incmethod' => 'overwrite',
    );

    $homepageOptions = array(
        'incpages' => array('homepage'),
        'wrapper' => 'footer',
        'roles' => array('root'),
        'incmethod' => 'overwrite',
    );

    /* Determine which options array to use based on $currentDisplay. */
    $option = $currentDisplay . 'Options';

    /* Use appropriate options array. */
    $options = (isset($$option) === true ? $$option : array('incpages' => array('none')));

    /* Output display */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmMediaDisplayHtml, $options);
}