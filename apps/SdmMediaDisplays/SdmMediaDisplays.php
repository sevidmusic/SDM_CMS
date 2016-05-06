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
$audioProperties = array(
    'type' => 'audio',
    'displayName' => 'Pickin\' Up',
    'machineName' => 'pickin_up',
    'srcUrl' => 'http://localhost:8888/TestingMedia',
    'srcPath' => '/Applications/MAMP/htdocs/TestingMedia',
    'srcType' => 'local',
    'srcName' => '01 Pickin Up',
    'srcExt' => 'mp3',
    'protected' => false,
    'private' => false,
);

$youtubeVideoProperties = array(
    'type' => 'youtube',
    'displayName' => 'Radiohead - Paranoid Android',
    'machineName' => 'radiohead_paranoid_android',
    'srcUrl' => 'https://www.youtube.com/embed/sPLEbAVjiLA', // embed url is the only youtube url that works, share and watch urls fail. If embely is implemented this may change, but for now the embed url must be used.
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
    'machineName' => 'my_light',
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
    'displayName' => 'HTML 5 Canvas Gradient',
    'machineName' => 'html5_canvas_gradient',
    'srcUrl' => 'http://localhost:8888/TestingMedia',
    'srcPath' => '/Applications/MAMP/htdocs/TestingMedia',
    'srcType' => 'local',
    'srcName' => 'html5CanvasGradient',
    'srcExt' => 'js',
    'protected' => true,
    'private' => false,
);

/** Create the media objects **/

/* Create audio SdmMedia object. */
$audioObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($audioProperties);

/* Create video SdmMedia object. */
$youtubeVideoObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($youtubeVideoProperties);

/* Create image SdmMedia object. */
$imageObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($imageProperties);

/* Create canvas SdmMedia object. */
$canvasObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($canvasProperties);

/** Add media objects to display **/

/* Add audio object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($audioObject);

/* Add video object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($youtubeVideoObject);

/* Add image object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($imageObject);

/* Add canvas object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($canvasObject);


/** Build display **/
/* Get SdmMedia object html */
$sdmMediaDisplayObjects = $sdmMediaDisplay->sdmMediaGetSdmMediaDisplayMediaElementsHtml();

/* Create a string of SdmMedia object html. */
$sdmMediaDisplayObjectsHtml = implode('<br>', $sdmMediaDisplayObjects);

/* Output strng of SdmMedia object's html to page. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmMediaDisplayObjectsHtml, array('incpages' => array('SdmMediaDisplays')));




/* DEV OUTPUT */
$output = '<div style="padding:42px;font-size:.42em;width: 100%; height: 420px;overflow: auto; border: 3px solid #ffffff; border-radius: 9px;"><pre>'; // <pre> is used for correct handling of newlines

ob_start();

$definedVars = get_defined_vars();

var_dump($definedVars['sdmMediaDisplay']);

$out = ob_get_contents();

ob_end_clean();

$output .= htmlspecialchars($out, ENT_QUOTES); // Escape HTML special chars

$output .= '</div></pre>';

//$output .= implode('<br>', $test);

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, array('incpages' => array('SdmMediaDisplays')));
