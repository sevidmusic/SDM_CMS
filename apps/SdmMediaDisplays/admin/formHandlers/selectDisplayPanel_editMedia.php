<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/13/16
 * Time: 7:35 PM
 */

if ($adminMode === 'saveMedia') {

    /* Load file upload handler if a file was submitted. */
    if ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaFile') !== null) {
        require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formHandlers/fileUploadHandler.php');

        /** Unpack vars from file upload handler **/

        /* Upload status */
        $uploadStatus = $fileUploadStatus;

        /* Path file was uploaded to/ */
        $fileSavedToPath = $savePath;

        /* The unique file name generated on file upload. */
        $fileName = $uniqueFileName;

        /* Generate a save file name to be used as the sdmMediaSourceName and as the name of the json and media
           files that are created for this media object. */
        $safeFileName = substr($uniqueFileName, 0, strpos($uniqueFileName, '.'));
        var_dump('File uploaded: ' . $uploadStatus, 'File uploaded to path: ' . $fileSavedToPath, 'File uploaded using name: ' . $fileName);

    }

    /* Get submitted form values */
    $submittedEditMediaFormValues = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('all');

    /* Initialize array to hold new media property values. */
    $newMediaPropertyValues = array();

    /* Add each submitted form value to the $newMediaPropertyValues array. It's ok if values unrelated to the SdmMedia object
       are included because they will simply be ignored on creation of new SdmMedia() object. */
    foreach ($submittedEditMediaFormValues as $submittedEditMediaFormKey => $submittedEditMediaFormValue) {
        switch ($submittedEditMediaFormKey) {
            case 'sdmMediaSourceUrl':
                /* If sdmMediaSourceType is local, enforce local url, otherwise use supplied. */
                $newMediaPropertyValues[$submittedEditMediaFormKey] = ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaSourceType') === 'local' ? $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/apps/SdmMediaDisplays/displays/media' : $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue($submittedEditMediaFormKey));
                break;
            case 'sdmMediaId':
                /**
                 * Generate new random 20 character numeric id for media objects every time they are edited.
                 * This is ok because the json file and the relative media file are updated each time media
                 * is edited.
                 */
                $newMediaPropertyValues[$submittedEditMediaFormKey] = rand(1000, 9999) . rand(100, 999) . rand(1, 9999) . rand(1000, 9999) . rand(10000, 99999);
                break;
            case 'sdmMediaProtected':
            case 'sdmMediaPublic':
                /* For now enforce public and protected using false since these properties
                   have not yet been implemented. */
                $newMediaPropertyValues[$submittedEditMediaFormKey] = false;
                break;
            default:
                /* Use submitted value without special processing. */
                $newMediaPropertyValues[$submittedEditMediaFormKey] = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue($submittedEditMediaFormKey);
                break;
        }
    }

    /* Create new SdmMedia() object instance. */
    $updateMediaObject = new SdmMedia();

    /* Create new media object from new media property values. */
    $newMediaObject = $updateMediaObject->sdmMediaCreateMediaObject($newMediaPropertyValues);

    /** Set Properties that rely on file upload handler. **/

    /* Set media source name based on uploaded file name */
    $newMediaObject->sdmMediaSetSourceName($safeFileName);

    /* Set media source id based on uploaded file name */
    $newMediaObject->sdmMediaSetId($safeFileName);

    /* Set media source extension based on uploaded file name */
    $fileExtension = substr($fileName, -3);
    $newMediaObject->sdmMediaSetSourceExtension($fileExtension);

    /* Convert media display name to camel case and set as machine name */
    $camelCaseFileName = str_replace(' ', '', ucfirst(preg_replace("/[^a-z]+/i", "", $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaDisplayName'))));

    /* Set  media machine name */
    $newMediaObject->sdmMediaSetMachineName($camelCaseFileName);

    /* Json encode new media object to prepare for storage. */
    $newMediaObjectJson = json_encode($newMediaObject);

    /* Save new media object  */
    var_dump($newMediaObject, $newMediaObjectJson);
    file_put_contents($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data/' . $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit') . '/' . $safeFileName . '.json', $newMediaObjectJson);

    /* Added confirmation message to panel description. */
    $panelDescription .= '<p>Saved changes to media "' . $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaDisplayName') . '".</p>';

}