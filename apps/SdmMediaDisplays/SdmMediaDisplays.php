<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 5:59 PM
 */

/* Create New Sdm Media Display */
$sdmMediaDisplay = new SdmMediaDisplay();

/* Define media properties for the media objects to be created. */
$soundProperties = array(
    'type' => 'sound',
    'displayName' => 'Picking Up',
    'machineName' => '8sdfjkhd83iid99d7y3j9ugd',
    'srcUrl' => 'http://localhost:8888/TestingMedia',
    'srcPath' => '/Applications/MAMP/htdocs/TestingMedia',
    'srcType' => 'local',
    'srcName' => '01 Pickin Up',
    'srcExt' => 'mp3',
    'protected' => false,
    'private' => false,
);

$videoProperties = array(
    'type' => 'video',
    'displayName' => 'Radiohead - Paranoid Android',
    'machineName' => 'radiohead_paranoid_android',
    'srcUrl' => 'https://www.youtube.com/watch?v=fHiGbolFFGw',
    'srcPath' => null,
    'srcType' => 'external',
    'srcName' => 'MyLight',
    'srcExt' => null,
    'protected' => true,
    'private' => true,
);

$imageProperties = array(
    'type' => 'image',
    'displayName' => 'The First Sdm Media Object',
    'machineName' => 'sdm_media_object_0',
    'srcUrl' => 'http://localhost:8888/TestingMedia',
    'srcPath' => '/Applications/MAMP/htdocs/TestingMedia',
    'srcType' => 'local',
    'srcName' => 'MyLight',
    'srcExt' => 'jpg',
    'protected' => false,
    'private' => true,
);

$canvasProperties = array(
    'type' => 'canvas',
    'displayName' => 'HTML 5 Animation Demo',
    'machineName' => 'html_ani_dem',
    'srcUrl' => 'http://localhost:8888/TestingMedia',
    'srcPath' => '/Applications/MAMP/htdocs/TestingMedia',
    'srcType' => 'external',
    'srcName' => 'HTML5DemoAnimation',
    'srcExt' => 'js',
    'protected' => true,
    'private' => false,
);

/* Create the media objects */

$soundObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($soundProperties);
$videoObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($videoProperties);
$imageObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($imageProperties);
$canvasObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($canvasProperties);

/* Add sound object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($soundObject);

/* Add video object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($videoObject);

/* Add image object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($imageObject);

/* Add canvas object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($canvasObject);

/* DEV OUTPUT */
$sdmMediaDisplayObjects = $sdmMediaDisplay->sdmMediaDisplayGetMediaObjects();

/** Dev $output */
$output = '<div style="padding:42px;font-size:.42em;width: 100%; height: 420px;overflow: auto; border: 3px solid #ffffff; border-radius: 9px;"><pre>'; // <pre> is used for correct handling of newlines
ob_start();
$definedVars = get_defined_vars();
var_dump($definedVars['sdmMediaDisplay']);
$out = ob_get_contents();
ob_end_clean();
$output .= htmlspecialchars($out, ENT_QUOTES); // Escape HTML special chars
$output .= '</div></pre>';
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, array('incpages' => array('SdmMediaDisplays')));
