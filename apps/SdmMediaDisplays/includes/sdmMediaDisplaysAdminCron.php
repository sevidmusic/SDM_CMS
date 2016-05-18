<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/18/16
 * Time: 5:24 PM
 */

/* CRON tasks. Run each time Sdm Media Displays admin panel is accessed. */

/* Cleanup ghost json data */
$dataDirectoryListing = $sdmassembler->sdmCoreGetDirectoryListing('SdmMediaDisplays/displays/data', 'apps');

/* Ghost file path */
foreach ($dataDirectoryListing as $dataDirectoryName) {
    /* Delete any ghost .json files. */
    $ghostJsonFilePath = $sdmMediaDisplaysDirectoryPath . '/displays/data/' . $dataDirectoryName . '/.json';
    if (file_exists($ghostJsonFilePath) === true) {
        /* Delete file. */
        unlink($ghostJsonFilePath);

        /* Log deleting of file in to aide in any debugging that may be needed. */
        error_log('Sdm Media Displays: Removed ghost json file from ' . $ghostJsonFilePath . '.');
    }

}
