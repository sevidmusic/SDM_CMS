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
    /* Define valid white list of media types */
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

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$validTypes' => $validTypes]) . PHP_EOL;

    /* Report progress to media upload log. */
    $sdmMediaUploadLog = $sdmassembler->sdmCoreSdmReadArrayBuffered(['$_FILE' => $_FILES]) . PHP_EOL;

    /* Check if $_FILES['SdmForm']['error']['sdmMediaFile'] is unset. */
    $errorsValueSet = isset($_FILES['SdmForm']['error']['sdmMediaFile']);

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$errorsValueSet' => $errorsValueSet]) . PHP_EOL;

    /* Check if $_FILES['SdmForm']['error']['sdmMediaFile'] is an array. */
    $errorsValueManipulated = is_array($_FILES['SdmForm']['error']['sdmMediaFile']);

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$errorsValueManipulated' => $errorsValueManipulated]) . PHP_EOL;

    /* If $_FILES['SdmForm']['error']['sdmMediaFile'] is unset or if it is an array this request is
       suspicious. HTTP headers may have have been compromised, do not process! */
    if ($errorsValueSet === false || $errorsValueManipulated === true) {
        /* Temporarily block user from using the page, and show a message warning them not to hack or pentest. */
        echo '<div style="padding:42px;color: red;font-size:5em;background: #000000; opacity: 1; width: 100%; height: 25000px; z-index:1000;position: absolute; top: 0px; left:0px;">NO HACKING!<br>NO PENTESTING WITHOUT PERMISSION!!!</div>';
        $possibleAttackMessage = '<p>SdmMediaDisplay Upload Error: Invalid or corrupted parameters. Possible security breach! It is suggested that you
        review the SdmMediaDisplay file upload log to try and identify if a breach has in fact occured.</p>
        <p>You should review the Sdm Media Display upload log, and then review the media files that were uploaded
         and check that the files are in fact the type of media they claim to be. Whether the files are valid or
         not depends on the whitelist of media types which currently defines the following valid types: ' . implode(', ', $validTypes) . '.</p>
         <p>However, some attacks may get past the white list which is why you need to manually go through the media files and make sure they
         are what they claim to be.</p>' . PHP_EOL;
        throw new RuntimeException($possibleAttackMessage);
        /* Stop file upload script */
        exit;
    }

    /* Check for upload errors. @see http://php.net/manual/en/features.file-upload.errors.php for more information each error type. */
    switch ($_FILES['SdmForm']['error']['sdmMediaFile']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('SdmMediaDisplay Upload Error: No file sent.' . PHP_EOL);
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('SdmMediaDisplay Upload Error: Exceeded filesize limit.' . PHP_EOL);
        default:
            throw new RuntimeException('SdmMediaDisplay Upload Error: Unknown errors.' . PHP_EOL);
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

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$maxSize' => $maxSize]) . PHP_EOL;


    $maxSizeMultiplier = 32;

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$maxSizeMultiplier' => $maxSizeMultiplier]) . PHP_EOL;


    $minSize = 1000; // 1000 === 1 kilobyte | 1000 = 0.001 megabytes

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$minSize' => $minSize]) . PHP_EOL;


    $minSizeMultiplier = 1;

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$minSizeMultiplier' => $minSizeMultiplier]) . PHP_EOL;


    /* Get uploaded file size | Kinda sucks to have to rely on http headers here, but
       so far I can't find another way to get the uploaded file size without actually
       allowing the upload to happen, checking file size after upload, and unlinking file
       if to large or small which would defeat the purpose of this check and open up
       a DDos security hole.
    */
    $uploadedFileSize = $_FILES['SdmForm']['size']['sdmMediaFile'];

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$uploadedFileSize' => $uploadedFileSize]) . PHP_EOL;


    /* Make sure file is not to big or to small. */
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

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$loadedFilesInfo' => $loadedFilesInfo]) . PHP_EOL;

    /* Determine uploaded files type. */
    $uploadedFilesType = $loadedFilesInfo->file($_FILES['SdmForm']['tmp_name']['sdmMediaFile']);

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$uploadedFilesType' => $uploadedFilesType]) . PHP_EOL;


    /* Check if file type/extension matches a valid file type, if it does use it, otherwise
       $validFileExt will be set to false. */
    $validFileExt = array_search($uploadedFilesType, $validTypes, true);

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$validFileExt' => $validFileExt]) . PHP_EOL;


    /* If file type is not valid throw an error. */
    if ($validFileExt === false) {
        throw new RuntimeException('SdmMediaDisplay Upload Error: Invalid file format.' . PHP_EOL);
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


    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$uniqueFileName' => $uniqueFileName]);

    /* Media file save path. */
    $savePath = $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/media';

    /* Report progress to media upload log. */
    $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['$savePath' => $savePath]) . PHP_EOL;

    /* Attempt to upload and save the file, throw an error if upload fails. */
    if (move_uploaded_file($_FILES['SdmForm']['tmp_name']['sdmMediaFile'], $savePath . '/' . $uniqueFileName) !== false) {
        /* upload succeed. */
        $fileUploadSuccessMessage = 'Sdm Media Displays File Upload Status: File was uploaded successfully.' . PHP_EOL;
        /* Report to upload log */
        $sdmMediaUploadLog .= $sdmassembler->sdmCoreSdmReadArrayBuffered(['Upload Status' => $fileUploadSuccessMessage]);
        $fileUploadStatus = true;
    } else {
        $fileUploadStatus = false;
        throw new RuntimeException('SdmMediaDisplay Upload Error: Failed to move uploaded file.');
    }

} catch (RuntimeException $e) {
    /* Catch any error messages, log error message to core error log, and assign to $errorMessages. */
    $errorMessages = $e->getMessage();
    error_log($errorMessages);
}

$sdmMediaUploadLogMessage = (isset($errorMessages) ? $errorMessages . PHP_EOL : 'File Uploaded without any errors.') . $sdmMediaUploadLog;

/* Create message to user based on failure or success*/
$fileUploadStatusUserDisplayMessage = (isset($errorMessages) ? $errorMessages : $fileUploadSuccessMessage);

/* Log upload. */
file_put_contents($sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/logs/sdmMediaDisplayLog_' . time() . '.html', $sdmMediaUploadLogMessage);

