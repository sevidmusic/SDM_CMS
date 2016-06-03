<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:38 AM
 */
/* NOTE: Displays cannot be set to display on all pages. Pages must be specified.
 *       Also, it is not necessary to set the 'incpages' options as it will have
 *       no effect since displays are only loaded if a corresponding displays/data/DISPLAY
 *       directory exists.
 */


/* Options arrays for different displays | variable name format should follow $DISPLAYNAMEOptions*/

/* Default output options. */
$SdmMediaDisplaysDefaultOptions = array(
    'wrapper' => 'main_content',
    'incpages' => array($currentDisplay),
);

/* Sdm Media Displays Admin Panel output options. */
$SdmMediaDisplaysOptions = array(
    'wrapper' => 'main_content',
    'incpages' => array('SdmMediaDisplays'),
    'roles' => array('root'),
);
/* Determine which options array to use based on $currentDisplay. */
$option = $currentDisplay . 'Options';

/* Use appropriate options array. If an options array is not found for the current display
 * then the default $SdmMediaDisplaysDefaultOptions will be used.
 */

//$currentDisplaysOptions = (isset($$option) === true ? $$option : $SdmMediaDisplaysDefaultOptions); use once out of dev
$currentDisplaysOptions = array('incpages' => array('all'), 'wrapper' => 'main_content');
