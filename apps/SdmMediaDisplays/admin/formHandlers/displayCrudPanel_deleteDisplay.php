<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/15/16
 * Time: 3:21 PM
 */

function sdmMediaDisplayDeleteDisplay($path)
{
    if (is_dir($path) === true) {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file) {
            sdmMediaDisplayDeleteDisplay(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    } else if (is_file($path) === true) {
        return unlink($path);
    }

    return false;
}

if ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit') !== null) {
    $displayToBeDeleted = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit');
    $pathToDisplayDataDirectory = $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data';
    $displayDeleted = sdmMediaDisplayDeleteDisplay($pathToDisplayDataDirectory . '/' . $displayToBeDeleted);
    /* Only remove data directory as media directory may have media that is shared by multiple displays. */

}
