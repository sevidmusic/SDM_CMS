<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/13/16
 * Time: 7:35 PM
 */

if ($adminMode === 'saveMedia') {

    /* Get submitted form values */
    $submittedEditMediaFormValues = array_keys($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('all'));

    /* Load file upload handler if a file was submitted. */
    require_once($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/admin/formHandlers/fileUploadHandler.php');

    /** Unpack vars from file upload handler **/

    /* Path file was uploaded to/ */
    $fileSavedToPath = $savePath;

    /* The unique file name generated on file upload. This includes the file extension. */
    $fileName = $uniqueFileName;

    /* Generate a save file name to be used as the sdmMediaSourceName and as the name of the json and media
       files that are created for this media object. Does not include file extension. */
    $safeFileName = substr($uniqueFileName, 0, strpos($uniqueFileName, '.'));

    /* Initialize array to hold new media property values. */
    $newMediaPropertyValues = array();

    /* Add each submitted form value to the $newMediaPropertyValues array. It's ok if values unrelated to the SdmMedia object
       are included because they will simply be ignored on creation of new SdmMedia() object. */
    foreach ($submittedEditMediaFormValues as $submittedEditMediaFormKey) {
        switch ($submittedEditMediaFormKey) {
            case 'sdmMediaSourceUrl':
                /* If sdmMediaSourceType is local, enforce local url, otherwise use supplied. */
                $newMediaPropertyValues[$submittedEditMediaFormKey] = ($sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaSourceType') === 'local' ? $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/apps/SdmMediaDisplays/displays/media' : $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue($submittedEditMediaFormKey));
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

    switch ($newMediaPropertyValues['sdmMediaSourceType']) {
        case 'external':
            /* Set safe file name */
            $safeFileName = hash('sha256', $newMediaPropertyValues['sdmMediaSourceUrl']);

            /* Set media source name based on $safeFileName */
            $newMediaObject->sdmMediaSetSourceName($safeFileName);

            /* Set source path to source url for external media */
            $newMediaObject->sdmMediaSetSourcePath($newMediaPropertyValues['sdmMediaSourceUrl']);

            /* Set media source id based on uploaded file name */
            $newMediaObject->sdmMediaSetId($safeFileName);
            break;
        default:

            /* Set media source name based on uploaded file name */
            $newMediaObject->sdmMediaSetSourceName($safeFileName);

            /* Set media source id based on uploaded file name */
            $newMediaObject->sdmMediaSetId($safeFileName);
            break;
    }

    /* Set media source extension based on uploaded file name */
    $fileExtension = substr($fileName, -3);
    $newMediaObject->sdmMediaSetSourceExtension($fileExtension);

    /* Convert media display name to camel case and set as machine name */
    $camelCaseMediaName = str_replace(' ', '', ucfirst(preg_replace("/[^a-z]+/i", "", $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaDisplayName'))));

    /* Set  media machine name */
    $newMediaObject->sdmMediaSetMachineName($camelCaseMediaName);

    /* Json encode new media object to prepare for storage. */
    $newMediaObjectJson = json_encode($newMediaObject);

    /* Save new media object  */
    file_put_contents($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data/' . $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('displayToEdit') . '/' . $newMediaObject->sdmMediaGetSdmMediaSourceName() . '.json', $newMediaObjectJson);

    /* Added confirmation message to panel description. */
    $panelDescription .= '<p>Saved changes to media "' . $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaDisplayName') . '".</p>';

}