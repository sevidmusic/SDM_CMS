<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/15/16
 * Time: 4:42 AM
 */

if ($adminMode === 'deleteMedia' && $currentPanel === 'deleteMediaPanel') {
    /* create display for deleteMedia panel */
    $deleteMediaDisplay = new SdmMediaDisplay($nameOfDisplayBeingEdited, $SdmCore);

    /* Get Media Object properties for the display being edited, and set $addToCurrent parameter to true so they
       are added to the $deleteMediaDisplay. */
    $deleteMediaDisplayObjectProperties = $deleteMediaDisplay->sdmMediaDisplayLoadMediaObjectProperties($nameOfDisplayBeingEdited, true);

    /* Get media id. */
    $mediaToDeletesId = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('selectMediaToEdit');

    /* Determine media path */
    $mediaPath = $deleteMediaDisplayObjectProperties[$mediaToDeletesId]['sdmMediaSourcePath'];

    /* Determine media json path */
    $mediaJsonPath = str_replace('media', 'data/' . $nameOfDisplayBeingEdited, $mediaPath);

    /* Determinre meda source extension */
    $mediaToDeletesSourceExtension = $deleteMediaDisplayObjectProperties[$mediaToDeletesId]['sdmMediaSourceExtension'];

    /* Determine path to media being deleted. */
    $pathToMediaBeingDeleted = $mediaPath . '/' . $mediaToDeletesId . '.' . $mediaToDeletesSourceExtension;
    $pathToMediaBeingDeletedJson = $mediaJsonPath . '/' . $mediaToDeletesId . '.json';

    /* */
    array_push($sdmMediaDisplayAdminPanelFormElements['deleteMediaPanel'], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaId', 'hidden', '', $mediaToDeletesId, 422));
    array_push($sdmMediaDisplayAdminPanelFormElements['deleteMediaPanel'], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('pathToMediaBeingDeleted', 'hidden', '', $pathToMediaBeingDeleted, 422));
    array_push($sdmMediaDisplayAdminPanelFormElements['deleteMediaPanel'], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('pathToMediaBeingDeletedJson', 'hidden', '', $pathToMediaBeingDeletedJson, 422));
}
