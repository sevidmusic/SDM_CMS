<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/10/16
 * Time: 1:57 PM
 */

$sdmassembler->sdmCoreSdmReadArray('Add displays form handler loaded successfully.');
/* Create display directory if it does not already exist. */
$displayDirectoryPath = $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data/' . $nameOfDisplayBeingEdited;
if (is_dir($displayDirectoryPath) === false) {
    $status = mkdir($displayDirectoryPath, 0777);
}
