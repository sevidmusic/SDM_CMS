<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/13/16
 * Time: 8:58 PM
 *
 * This handles file uploads for the Sdm Media Display app and provides some basic security. It
 * is based on code found in the manual pages at @see http://php.net/manual/en/features.file-upload.php.
 *
 * This script security is designed with the understanding that by the time this script is run
 * the file has already been uploaded, so rather then prevention, it checks the uploaded file
 * and removes it if security issues are found.
 *
 * For example:
 * The mime type is usually checked in $FILE, which is not secure since it's values can be tampered with,
 * so this script checks the uploaded files type with PHP's loadedFilesInfo() class.
 */

try {
    /* If file error is unset or if it is an array this request is suspicious. HTTP headers may have
       have been compromised, do not process! */

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['Error Number (0 is ok):' => $_FILES['SdmForm']['error']['sdmMediaFile']]);
    // end dev

    $errorsValueSet = isset($_FILES['SdmForm']['error']['sdmMediaFile']);

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$errorsValueSet' => $errorsValueSet]);
    // end dev

    $errorsValueManipulated = is_array($_FILES['SdmForm']['error']['sdmMediaFile']);

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$errorsValueManipulated' => $errorsValueManipulated]);
    // end dev

    if ($errorsValueSet === false || $errorsValueManipulated === true) {
        echo '<div style="padding:42px;color: red;font-size:5em;background: #000000; opacity: 1; width: 100%; height: 25000px; z-index:1000;position: absolute; top: 0px; left:0px;">NO HACKING!<br>NO PENTESTING WITHOUT PERMISSION!!!</div>';
        throw new RuntimeException('SdmMediaDisplay Upload Error: Invalid or corrupted parameters.');
        /* Stop file upload script */
        exit;
    }

    /* Check for upload errors. @see http://php.net/manual/en/features.file-upload.errors.php for more information each error type. */
    switch ($_FILES['SdmForm']['error']['sdmMediaFile']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('SdmMediaDisplay Upload Error: No file sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('SdmMediaDisplay Upload Error: Exceeded filesize limit.');
        default:
            throw new RuntimeException('SdmMediaDisplay Upload Error: Unknown errors.');
    }

    /*  Insure file size is not to large and file size not to small.
     *  This protects against Denial of Service Attacks.
     *  From https://www.owasp.org/index.php/Unrestricted_File_Upload:
     *  "Limit the file size to a maximum value in order to prevent denial
     *  of service attacks (on file space or other web application’s
     *  functions such as the image re-sizer)."
     *
     *  "Restrict small size files as they can lead to denial of service attacks.
     *  So, the minimum size of files should be considered."
     *
     *  More info
     *  @see https://www.owasp.org/index.php/Unrestricted_File_Upload
     */
    $maxSize = 1000000; // 1000000 === 1000 kilobytes | 1000000 === 1 megabytes

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$maxSize' => $maxSize]);
    // end dev


    $maxSizeMultiplier = 32;

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$maxSizeMultiplier' => $maxSizeMultiplier]);
    // end dev


    $minSize = 1000; // 1000 === 1 kilobyte | 1000 = 0.001 megabytes

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$minSize' => $minSize]);
    // end dev


    $minSizeMultiplier = 1;

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$minSizeMultiplier' => $minSizeMultiplier]);
    // end dev


    /* Get uploaded file size | Kinda sucks to have to rely on http headers here, but
       so far I can't find another way to get the uploaded file size without actually
       allowing the upload to happen, checking file size after upload, and unlinking file
       if to large or small which would defeat the purpose of this check and open up
       a DDos security hole.
    */
    $uploadedFileSize = $_FILES['SdmForm']['size']['sdmMediaFile'];

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$uploadedFileSize' => $uploadedFileSize]);
    // end dev


    if ($uploadedFileSize > ($maxSize * $maxSizeMultiplier) && $uploadedFileSize < ($minSize * $minSizeMultiplier)) {
        throw new RuntimeException('SdmMediaDisplay Upload Error: Exceeded filesize limit.');
    }

    /**
     * Check mime type.
     *
     * Do not trust $_FILES['SdmForm']['mime']['sdmMediaFile'] value! It can be manipulated
     * client side and therefore is not reliable for security.
     * Check MIME with PHP's loadedFilesInfo() instead.
     */
    $loadedFilesInfo = new finfo(FILEINFO_MIME_TYPE);

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$loadedFilesInfo' => $loadedFilesInfo]);
    // end dev


    $validTypes = array(
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'json' => 'application/json',
        'js' => 'application/js',
        'mp3' => 'audio/mp3',
        'aiff' => 'audio/aiff',
        'ogg' => 'audio/ogg',
        'oga' => 'audio/oga',
        'mov' => 'video/quicktime',
        'mp4' => 'video/mp4',
    );


    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$validTypes' => $validTypes]);
    // end dev


    /* Determine uploaded files type. */
    $uploadedFilesType = $loadedFilesInfo->file($_FILES['SdmForm']['tmp_name']['sdmMediaFile']);

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$uploadedFilesType' => $uploadedFilesType]);
    // end dev


    /* Check if file type/extension matches a valid file type, if it does use it, otherwise
       $validFileExt will be set to false. */
    $validFileExt = array_search($uploadedFilesType, $validTypes, true);

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$validFileExt' => $validFileExt]);
    // end dev


    /* If file type is not valid throw an error. */
    if ($validFileExt === false) {
        throw new RuntimeException('SdmMediaDisplay Upload Error: Invalid file format.');
    }

    /**
     *  Give file unique name. This will prevent an attacker who successfully
     *  uploads a malicious file from easily accessing that file since they will not
     *  know the name of the file they upload after it is saved.
     *
     * e.g., Attacker uploads malicious evil.php.jpg and successfully tricks the file type check
     * into accepting the file as a jpg image.The attacker, if they knew where the file was stored,
     * could then go to http://oursite.com/path/to/evil.php.jpg and execute their evil script. By
     * renaming the file to something hard to guess, like 29348f8fufnf884jjf899dk99dd9.jpg (notice,
     * also removed bad extension!), it becomes much harder for an attacker to find the file, and
     * even more difficult for them to execute.
     *
     */
    $uniqueFileName = sprintf('%s.%s', sha1_file($_FILES['SdmForm']['tmp_name']['sdmMediaFile']), $validFileExt);


    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$uniqueFileName' => $uniqueFileName]);
    // end dev


    $savePath = $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/media';

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$savePath' => $savePath]);
    // end dev


    /* Attempt to upload and save the file, throw an error if upload fails. */
    if (!move_uploaded_file($_FILES['SdmForm']['tmp_name']['sdmMediaFile'], $savePath . '/' . $uniqueFileName)) {
        throw new RuntimeException('SdmMediaDisplay Upload Error: Failed to move uploaded file.');
    }
    /* upload succeed. */
    $fileUploadOutput .= 'File is uploaded successfully.';

    // dev
    $sdmassembler->sdmCoreSdmReadArray(['$fileUploadOutput' => $fileUploadOutput]);
    // end dev


} catch (RuntimeException $e) {

    /* Catch and log any error messages and assign to $fileUploadOutput. */
    error_log($e->getMessage());

}