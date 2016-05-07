<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 5:59 PM
 */

/* Create New Sdm Media Display | To use a custom template specify its name. */
$sdmMediaDisplay = new SdmMediaDisplay();

/* From JSON | Define media properties for the media objects to be created. */
$imageProperties = array(
    'type' => 'image',
    'displayName' => 'The First Sdm Media Object',
    'machineName' => 'my_light',
    'srcUrl' => 'http://localhost:8888/TestingMedia',
    'srcPath' => '/Applications/MAMP/htdocs/TestingMedia',
    'srcType' => 'local',
    'srcName' => 'MyLight',
    'srcExt' => 'jpg',
    'protected' => false,
    'private' => true,
    'category' => 'image',
    'place' => 1,
);

/* Create SdmMedia object. */
$imageObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($imageProperties);

var_dump(json_encode($imageObject));
/* Add SdmMedia object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($imageObject);

/* Build Display */
$sdmMediaDisplay->sdmMediaDisplayBuildMediaDisplay();

/* Get Display Html */
$sdmMediaDisplayHtml = $sdmMediaDisplay->sdmMediaDisplayGetSdmMediaDisplayHtml();

$options = array(
    'incpages' => array('SdmMediaDisplays'),
    'wrapper' => 'footer',
);
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmMediaDisplayHtml, $options);