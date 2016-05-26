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

/* Default options */
$SdmMediaDisplaysOptions = array(
    'wrapper' => 'main_content',
    'incpages' => array($currentDisplay),
);

/* Determine which options array to use based on $currentDisplay. */
$option = $currentDisplay . 'Options';

/* Use appropriate options array. If an options array is not found for the current display
 * then the default $SdmMediaDisplaysOptions will be used.
 */
$options = (isset($$option) === true ? $$option : $SdmMediaDisplaysOptions);