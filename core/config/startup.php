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
function __autoload($class)
{
    /* Class file's name. */
    $classFile = $class . '.php';

    /* First see if the the class one of the core classes in the core/includes directory. */
    switch (file_exists(__SDM_INCTDIR__ . '/' . $classFile) === true) {
        case true:
            require_once(__SDM_INCTDIR__ . '/' . $classFile);
            break;
        default:
            /* Core apps */
            $coreAppsDirectory = __SDM_ROOTDIR__ . '/core/apps';
            $coreApps = scandir($coreAppsDirectory);

            /* User apps */
            $userAppsDirectory = __SDM_ROOTDIR__ . '/apps';
            $userApps = scandir($userAppsDirectory);

            /* See if the class is provided by a core or user app. */
            foreach ($coreApps as $coreApp) {
                $coreAppIncludesDirectoryPath = $coreAppsDirectory . '/' . $coreApp . '/includes';
                if (file_exists($coreAppIncludesDirectoryPath . '/' . $classFile) === true) {
                    require_once($coreAppIncludesDirectoryPath . '/' . $classFile);
                    break 2;
                }
            }
            /* If class not provided by core app, look in user apps */
            foreach ($userApps as $userApp) {
                $userAppIncludesDirectoryPath = $userAppsDirectory . '/' . $userApp . '/includes';
                if (file_exists($userAppIncludesDirectoryPath . '/' . $classFile) === true) {
                    require_once($userAppIncludesDirectoryPath . '/' . $classFile);
                    break 2;
                }
            }

            /* Arriving here indicates that the class is not provided by either a core
               or user app, so log an error */
            error_log('__autoload() Error: Class file ' . $classFile . ' is not provided by CORE or by an app. Make sure ' . $classFile . ' exists either in core/includes or in an APP/includes directory.');
            error_log('could not load ' . $class . '()');
            break;
    }
    return;

}

/* Initialize the SdmAssembler(). */
$sdmassembler = new SdmAssembler;

/* Configure core. */
$sdmassembler->sdmCoreConfigureCore();

/* Start or resume session. */
$sdmassembler->sessionStart();

/* Load and assemble the requested page's content. */
$sdmassembler->sdmAssemblerLoadAndAssembleContent();
