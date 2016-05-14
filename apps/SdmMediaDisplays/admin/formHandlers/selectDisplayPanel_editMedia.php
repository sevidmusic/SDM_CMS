<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/13/16
 * Time: 7:35 PM
 */

if ($adminMode === 'saveMedia') {

    /* Get submitted form values */
    $submittedEditMediaFormValues = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('all');

    /* Initialize array to hold new media property values. */
    $newMediaPropertyValues = array();

    /* Add each submitted form value to the $newMediaPropertyValues array. It's ok if values unrelated to the SdmMedia object
       are included because they will simply be ignored on creation of new SdmMedia() object. */
    foreach ($submittedEditMediaFormValues as $submittedEditMediaFormKey => $submittedEditMediaFormValue) {
        $newMediaPropertyValues[$submittedEditMediaFormKey] = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue($submittedEditMediaFormKey);
    }

    /* Load file upload handler. | WARNING: $newMediaPropertyValues must be defined before loading form handler!!! */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formHandlers/fileUploadHandler.php');

    /** Unpack vars from file upload handler **/

    /* Upload status */
    $uploadStatus = $fileUploadStatus;

    /* Path file was uploaded to/ */
    $fileSavedToPath = $savePath;

    /* The unique file name generated on file upload. */
    $fileName = $uniqueFileName;
    $safeFileName = substr($uniqueFileName, 0, strpos($uniqueFileName, '.'));
    var_dump('File uploaded: ' . $uploadStatus, 'File uploaded to path: ' . $fileSavedToPath, 'File uploaded using name: ' . $fileName);

    /* Create new SdmMedia() object instance. */
    $updateMediaObject = new SdmMedia();

    /* Create new media object from new media property values. */
    $newMediaObject = $updateMediaObject->sdmMediaCreateMediaObject($newMediaPropertyValues);

    /* Set media source name */
    $newMediaObject->sdmMediaSetSourceName($safeFileName);

    /* Set media source extension */
    $fileExtension = substr($fileName, -3);
    $newMediaObject->sdmMediaSetSourceExtension($fileExtension);

    /* Convert display name to camel case and set as machine name */
    $camelCaseFileName = str_replace(' ', '', ucfirst(preg_replace("/[^a-z]+/i", "", $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaDisplayName'))));

    /* Set  media machine name */
    $newMediaObject->sdmMediaSetMachineName($camelCaseFileName);

    var_dump($newMediaObject);

    /* Json encode new media object to prepare for storage. */
    $newMediaObjectJson = json_encode($newMediaObject);

    /* Save new media object  */
    // var_dump($newMediaObject, $newMediaObjectJson);

    /* Added confirmation message to panel description. */
    $panelDescription .= '<p>Saved changes to media "' . $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaDisplayName') . '".</p>';

}