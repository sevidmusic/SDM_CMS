<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/15/16
 * Time: 12:30 PM
 */

/* Delete media file*/
unlink($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('pathToMediaBeingDeleted'));

/* Delete json file */
unlink($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('pathToMediaBeingDeletedJson'));
