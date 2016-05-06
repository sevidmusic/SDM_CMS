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
    'category' => 'audioVideo',
    'place' => 420,
);

$audio2Properties = array(
    'type' => 'audio',
    'displayName' => 'I\'ll Be Here - Full Arrangement',
    'machineName' => 'IllBeHere_FullArrangement',
    'srcUrl' => 'http://localhost:8888/TestingMedia',
    'srcPath' => '/Applications/MAMP/htdocs/TestingMedia',
    'srcType' => 'local',
    'srcName' => 'IllBeHere_FullArrangement',
    'srcExt' => 'aiff',
    'protected' => false,
    'private' => false,
    'category' => 'audioVideo',
    'place' => 420,
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
    'category' => 'audioVideo',
    'place' => 0,
);

$videoProperties = array(
    'type' => 'video',
    'displayName' => 'Sevi D & The Wilds live at King\'s Lounge',
    'machineName' => 'sevi_d_the_wilds_live_at_kings_Lounge',
    'srcUrl' => 'http://localhost:8888/TestingMedia',
    'srcPath' => '/Applications/MAMP/htdocs/TestingMedia',
    'srcType' => 'local',
    'srcName' => 'Sevi D & The Wilds live at King\'s Lounge',
    'srcExt' => 'm4v',
    'protected' => true,
    'private' => true,
    'category' => 'audioVideo',
    'place' => 0,
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
    'category' => 'image',
    'place' => 1,
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
    'category' => 'canvas',
    'place' => 28,
);

/** Create the media objects **/

/* Create audio SdmMedia object. */
$audioObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($audioProperties);

/* Create audio SdmMedia object. */
$audioObject2 = $sdmMediaDisplay->sdmMediaCreateMediaObject($audio2Properties);

/* Create youtube video SdmMedia object. */
$youtubeVideoObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($youtubeVideoProperties);

/* Create video SdmMedia object. */
$videoObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($videoProperties);

/* Create image SdmMedia object. */
$imageObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($imageProperties);

/* Create canvas SdmMedia object. */
$canvasObject = $sdmMediaDisplay->sdmMediaCreateMediaObject($canvasProperties);

/** Add media objects to display **/

/* Add audio object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($audioObject);

/* Add second audio object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($audioObject2);

/* Add youtube video object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($youtubeVideoObject);

/* Add video object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($videoObject);

/* Add image object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($imageObject);

/* Add canvas object to display. */
$sdmMediaDisplay->sdmMediaDisplayAddMediaObject($canvasObject);

/* Build Display */
$sdmMediaDisplay->sdmMediaDisplayBuildMediaDisplay();

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
