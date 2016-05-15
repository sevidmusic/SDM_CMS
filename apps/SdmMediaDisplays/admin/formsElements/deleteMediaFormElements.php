<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/15/16
 * Time: 4:42 AM
 */

/* Get media id. */
$mediaToDeltesId = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('selectMediaToEdit');

/* Determine media path */
$mediaPath = $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays';

/* Determine media json path */
$mediaJsonPath = $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data/' . $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit');

/* DEV */
var_dump($mediaToDeltesId, $mediaPath, $mediaJsonPath);