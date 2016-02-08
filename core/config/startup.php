<?php

/**
 * This file starts up the SDM CMS.
 */

/** Require our config.php file which defines the core constants. */
require_once('config.php');

/**
 * Load Sdm Cms core classes.
 * @param $classes string The class to load.
 */
function __autoload($classes)
{
    $filename = $classes . '.php';
    include_once(__SDM_INCTDIR__ . '/' . $filename);
}

/* Initialize the SdmAssembler(). */
$sdmassembler = new SdmAssembler;

/* Configure core. */
$sdmassembler->sdmCoreConfigureCore();

/* Start or resume session. */
$sdmassembler->sessionStart();

/* Load and assemble the requested page's content. */
$sdmassembler->sdmAssemblerLoadAndAssembleContent();
