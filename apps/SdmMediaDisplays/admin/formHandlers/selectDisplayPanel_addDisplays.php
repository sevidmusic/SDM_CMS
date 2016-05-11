<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/10/16
 * Time: 1:57 PM
 */

/* Create display directory if it does not already exist. */
$displayDirectoryPath = $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data/' . $nameOfDisplayBeingEdited;
if (is_dir($displayDirectoryPath) === false) {
    $status = mkdir($displayDirectoryPath, 0777);
}

/* If there the display's directory has media in it load it. */
if($sdmMediaDisplay->sdmMediaDisplayHasMedia($nameOfDisplayBeingEdited) === true) {
//var_dump('loading media for ' . $nameOfDisplayBeingEdited . '...');
}



