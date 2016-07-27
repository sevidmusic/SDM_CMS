<?php

/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/28/16
 * Time: 3:22 PM
 */

/**
 * Class SdmMediaDisplaysAdmin Provides the admin functionality for the SdmMediaDisplays app. This class
 * sets up various admin panels used create and delete displays, and to manage a displays settings and it's media.
 * It also defines the methods that handle the backend CRUD functionality of each admin panel.
 */
class SdmMediaDisplaysAdmin extends SdmForm
{
    /** @var $adminPanel string|null The name of the current admin panel. Will be null upon initial arrival on SdmMediaDisplays page. */
    private $adminPanel;

    /** @var $displayBeingEdited string|null Name of display being edited. Will be null if no display has been selected for editing. */
    private $displayBeingEdited;

    /** @var $adminFormElements array Array of html for the current admin panel's form elements. */
    private $adminFormElements;

    /** @var $adminFormButtons array Array of html for the current admin panel's buttons */
    private $adminFormButtons;

    /** @var $initialSetup bool True if initial setup was performed. False if initial setup was not performed.
     *                          Initial setup is only performed if the data and media directories do not exist.
     */
    private $initialSetup;

    /** @var $output string Html output for the current admin panel. */
    private $output;

    /** @var $messages string Html for any messages that should be shown to the user. Messages are used to communicate
     *                        when things happen, for instance whether or not a display or a piece of media was saved
     *                        successfully.
     */
    private $messages;

    /** @var SdmCms object Local instance of the SdmCms() class. This object is injected via the first parameter of the __constructor(). */
    private $sdmCms;

    /** @var SdmMediaDisplay object|null Local instance of the SdmMediaDisplay() class. Only created if a display has been selected for editing.
     *                                   This object is not injected, but instantiated by the __constructor() if a display has been selected for
     *                                   editing. If a display has not been selected for editing, i.e., $displayBeingEdited is null, then this
     *                                   property will be null.
     */
    private $sdmMediaDisplay;

    /** @var $sdmMediaDisplaysDirectoryPath string Path to SdmMediaDisplays app directory. */
    private $sdmMediaDisplaysDirectoryPath;

    /** @var $sdmMediaDisplaysDirectoryUrl string Url to SdmMediaDisplays app directory. */
    private $sdmMediaDisplaysDirectoryUrl;

    /** @var $sdmMediaDisplaysDataDirectoryPath string Path to SdmMediaDisplays data directory. */
    private $sdmMediaDisplaysDataDirectoryPath;

    /** @var $sdmMediaDisplaysDataDirectoryUrl string Url to SdmMediaDisplays data directory. */
    private $sdmMediaDisplaysDataDirectoryUrl;

    /** @var $sdmMediaDisplaysDataFilePath string|null Path to $displayBeingEdited's data file. Will be null if $displayBeingEdited is null. */
    private $sdmMediaDisplaysDataFilePath;

    /** @var $sdmMediaDisplaysMediaDirectoryPath string Path to SdmMediaDisplays media directory. */
    private $sdmMediaDisplaysMediaDirectoryPath;

    /** @var $sdmMediaDisplaysMediaDirectoryUrl string Url to SdmMediaDisplays media directory. */
    private $sdmMediaDisplaysMediaDirectoryUrl;

    /** @var $sdmMediaDisplaysAdminPageUrl string Url to SdmMediaDisplays page. */
    private $sdmMediaDisplaysAdminPageUrl;

    /** @var $cronTasksPerformed bool True if cron tasks were performed, false otherwise. */
    private $cronTasksPerformed;

    /** @var $displaysExist bool True if at least one display exists, false otherwise. */
    private $displaysExist;

    /** @var $currentDisplayExists bool True if $displayBeingEdited exists, false otherwise. */
    private $currentDisplayExists;

    /** @var $pathToCurrentDisplay bool|string  Path to $displayBeingEdited's data directory. Will be false if no display has been
     *                                          selected for editing, or if the $displayBeingEdited does not exist.
     */
    private $pathToCurrentDisplay;

    /** @var $availableDisplays array Array of available displays. */
    private $availableDisplays;

    /** @var $displayBeingEditedId string $displayBeingEdited's id. @todo: should be null if $displayBeingEdited is null. */
    private $displayBeingEditedId;

    /**
     * SdmMediaDisplaysAdmin constructor. Initializes properties that require initialization.
     * Requires an instance of the SdmCms() class be injected via the first parameter.
     *
     * @param SdmCms $sdmCms Instance of the SdmCms class.
     *
     * @param $availableDisplays array Array of available Sdm Media Displays.
     */
    public function __construct(SdmCms $sdmCms, $availableDisplays)
    {
        /* Call SdmForm()'s __constructor() */
        parent::__construct();
        $this->adminPanel = ($this->sdmFormGetSubmittedFormValue('adminPanel') !== null ? $this->sdmFormGetSubmittedFormValue('adminPanel') : 'displayCrudPanel');
        $this->displayBeingEdited = $this->sdmFormGetSubmittedFormValue('displayName');
        $this->adminFormElements = array();
        $this->adminFormButtons = array();
        $this->sdmCms = $sdmCms;
        /** THE FOLLOWING PROPERTIES MUST BE INITIALIZED AFTER THE $sdmCms PROPERTY! **/
        $this->sdmMediaDisplaysDirectoryPath = $this->sdmCms->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays';
        $this->sdmMediaDisplaysDirectoryUrl = $this->sdmCms->sdmCoreGetUserAppDirectoryUrl() . '/SdmMediaDisplays';
        /** THE FOLLOWING PROPERTIES MUST BE INITIALIZED AFTER THE $sdmMediaDisplaysDirectoryPath AND $sdmMediaDisplaysDirectoryUrl PROPERTIES! **/
        $this->sdmMediaDisplaysDataDirectoryPath = $this->sdmMediaDisplaysDirectoryPath . '/displays/data';
        $this->sdmMediaDisplaysDataDirectoryUrl = $this->sdmMediaDisplaysDirectoryUrl . '/displays/data';
        $this->sdmMediaDisplaysDataFilePath = ($this->displayBeingEdited === null ? null : $this->sdmMediaDisplaysDataDirectoryPath . '/' . $this->displayBeingEdited . '/' . hash('sha256', $this->displayBeingEdited) . '.json');
        $this->sdmMediaDisplaysMediaDirectoryPath = $this->sdmMediaDisplaysDirectoryPath . '/displays/media';
        $this->sdmMediaDisplaysMediaDirectoryUrl = $this->sdmMediaDisplaysDirectoryUrl . '/displays/media';
        $this->sdmMediaDisplaysAdminPageUrl = $this->sdmCms->sdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmMediaDisplays';
        $this->initialSetup = (isset($this->initialSetup) === true ? $this->initialSetup : $this->performInitialSetup());
        $this->cronTasksPerformed = (isset($this->cronTasksPerformed) ? $this->cronTasksPerformed : $this->runCronTasks());
        $this->displaysExist = (isset($this->displaysExist) === true ? $this->displaysExist : $this->displaysExist());
        $this->pathToCurrentDisplay = ($this->displayBeingEdited === null ? false : $this->sdmMediaDisplaysDataDirectoryPath . '/' . $this->displayBeingEdited);
        $this->currentDisplayExists = ($this->displayBeingEdited === null ? false : is_dir($this->pathToCurrentDisplay));
        $this->configureAdminForm();
        $this->processSubmittedValues();
        if (file_exists($this->sdmMediaDisplaysDataFilePath)) {
            $this->sdmMediaDisplay = new SdmMediaDisplay($this->displayBeingEdited, $this->sdmCms);
        }
        $this->availableDisplays = $availableDisplays;
        $this->displayBeingEditedId = ($this->displayBeingEdited === null ? null : hash('sha256', $this->displayBeingEdited));
    }

    /**
     * Performs initial setup of the Sdm Media Displays app.
     * Specifically it checks that the data and media directories exist, if they don't
     * then this method will create them.
     * @return bool Returns true if initial setup was run, false otherwise.
     */
    private function performInitialSetup()
    {
        /** Initial Setup **/

        /* Set initialSetup to false, if setup is required it will be changed to true internally. */
        $this->initialSetup = false;

        /* Required directories for Sdm Media Display's and it's admin panels to work. */
        $requiredDirectories = array($this->sdmMediaDisplaysDataDirectoryPath, $this->sdmMediaDisplaysMediaDirectoryPath);

        /* Insure required directories exist. */
        foreach ($requiredDirectories as $requiredDirectory) {
            /* Create path to required directory. */
            $requiredDirectoryPath = $requiredDirectory;

            /* Check if directory exists. */
            $requiredDirectoryExists = is_dir($requiredDirectoryPath);

            /* If required directory does not exist create it. */
            if ($requiredDirectoryExists !== true) {
                mkdir($requiredDirectoryPath);
                /* Initial setup occurred, set $this->initialSetup to true to indicate that this is a new setup. */
                $this->initialSetup = true;
            }
        }

        /* Show initial setup message if $this->initialSetup is true. */
        if ($this->initialSetup === true) {
            $this->output .= "
                <h2>Sdm Media Displays</h2>
                <p>Looks like you just enabled this app.</p>
                <p>Welcome to the Sdm Media Displays app. With the Sdm Media
                Displays app you will be able to add media to your website's
                pages, including images, video, embeded video (such as youtube),
                and HTML 5 canvas scripts.</p>
                <p>Initial setup complete.
                <a href='{$this->sdmCms->sdmCoreGetRootDirectoryUrl()}/index.php?page=SdmMediaDisplays'>
                Click here</a> to start creating media displays.</p>
                ";
        }
        return $this->initialSetup;
    }

    /**
     * Runs cron tasks for the Sdm Media Displays app.
     * @return bool Returns true if cron tasks were run, false otherwise.
     */
    private function runCronTasks()
    {

        /* Report if cron task were performed. */
        $this->cronTasksPerformed = false;

        /* Get a directory listing of all displays from the data directory. */
        $dataDirectoryListing = $this->sdmCms->sdmCoreGetDirectoryListing('SdmMediaDisplays/displays/data', 'apps');

        /* Look in each display's data directory and cleanup any ghost .json files that are found. */
        foreach ($dataDirectoryListing as $dataDirectoryName) {
            /* Delete any ghost .json files. */
            $ghostJsonFilePath = $this->sdmMediaDisplaysDataDirectoryPath . '/' . $dataDirectoryName . '/.json';
            if (file_exists($ghostJsonFilePath) === true) {
                /* Report cron tasks were run. */
                $this->cronTasksPerformed = true;

                /* Delete file. */
                unlink($ghostJsonFilePath);

                /* Log deleting of file in to aide in any debugging that may be needed. */
                $this->messages .= 'Sdm Media Displays: Removed ghost json files from ' . $ghostJsonFilePath . '.' . PHP_EOL;
            }

        }
        return $this->cronTasksPerformed;
    }

    /**
     * Determines if any displays exist.
     *
     * @return bool Returns true if there are displays, false otherwise.
     */
    private function displaysExist()
    {
        /* Only show edit and delete display buttons if there are displays other then the default. */
        $expectedDirs = array('.', '..', '.DS_Store', 'SdmMediaDisplays');

        /* Scan data directory for displays. */
        $displays = scandir($this->sdmCms->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data');

        /* Check $displays against $expectedDirs, if a display is found that is not an expected directory then at least on display exists. */
        foreach ($displays as $display) {
            if (!in_array($display, $expectedDirs)) {
                /* Display found, set $displaysExist to true. */
                $this->displaysExist = true;
                /* A display was found, exit loop. */
                break;
            }
            /* No displays exist. */
            $this->displaysExist = false;
        }
        return $this->displaysExist;
    }

    /**
     * Configures the Sdm Media Displays admin form's settings.
     *
     * @return bool Returns true.
     */
    private function configureAdminForm()
    {
        $this->formHandler = 'SdmMediaDisplays';
        $this->method = 'post';
        $this->excludeSubmitLabel = true;
        /* Since the admin panels use a single SdmForm() object, do not preserve submitted values
        since no admin panel ever leads back to the same admin panel and preserving submitted values
        could prevent saved display and media data from correctly pre-populating the admin forms, or
        lead to values from an admin panel being set as defaults for another admin panel. */
        $this->preserveSubmittedValues = false;
        $this->formClasses = 'SdmMediaDisplaysAdminForm';
        return true;
    }

    /**
     * Process the submitted form values for the various admin panels. This method does not return
     * anything since it's purpose is contextual. i.e., The function of this method is different
     * depending on the current admin panel.
     */
    private function processSubmittedValues()
    {
        switch ($this->adminPanel) {
            case 'editMedia':
                /* If the current display's data directory does not exist create it. */
                if ($this->currentDisplayExists === false) {
                    mkdir($this->pathToCurrentDisplay);
                }

                /* Create display id from display name. */
                $displayId = hash('sha256', $this->displayBeingEdited);

                $displayDataArray = array();
                $displayDataArray['options'] = array(
                    'incpages' => $this->sdmFormGetSubmittedFormValue('incpages'),
                    'ignorepages' => $this->sdmFormGetSubmittedFormValue('ignorepages'),
                    'roles' => $this->sdmFormGetSubmittedFormValue('roles'),
                    'incmethod' => $this->sdmFormGetSubmittedFormValue('incmethod'),
                    'wrapper' => $this->sdmFormGetSubmittedFormValue('wrapper'),
                );
                $displayDataArray['id'] = $displayId;
                $displayDataArray['displayName'] = $this->displayBeingEdited;
                $displayDataArray['template'] = $this->sdmFormGetSubmittedFormValue('template');

                /* Encode display data */
                $displayData = json_encode($displayDataArray);

                /* Save display data. */
                $displayDataSaved = file_put_contents($this->sdmMediaDisplaysDataDirectoryPath . '/' . $this->displayBeingEdited . '/' . $displayId . '.json', $displayData);
                switch ($displayDataSaved) {
                    case false:
                        $this->messages .= 'An error occurred and the changes to the ' . $this->displayBeingEdited . ' Sdm Media Display could not be saved!';
                        break;
                    default:
                        $this->messages .= 'Saved changes to ' . $this->displayBeingEdited . ' Sdm Media Display.';
                        break;
                }
                break;
            case 'saveMedia':
                /* Attempt to upload media file. */
                $fileUploaded = $this->uploadMedia();
                /* If media was uploaded successfully save media data. */
                if ($fileUploaded !== false) {
                    $this->saveMediaData($fileUploaded);
                }
                break;
        }
    }

    /**
     * Handles media file uploads. All media files, regardless of their parent display, are saved in the media directory.
     *
     * @return mixed Returns the name the uploaded media file was saved as on success, or false on failure. If media
     *               source type is external this method will return true. If media source type is local and no file
     *               was selected to be uploaded this method will return null.
     */
    private function uploadMedia()
    {
        /* If media is external, just return true, no need to try and upload an external media source. */
        if ($this->sdmFormGetSubmittedFormValue('sdmMediaSourceType') === 'external') {
            return true;
        }

        /* Media file save path. */
        $savePath = $this->sdmMediaDisplaysMediaDirectoryPath;

        try {
            /* Define valid white list of media types | @todo: make it possible for types to specify multiple mime types
               for instance, javascript is often mis-interpreted as text/plain, or text/javascript even though the standard
               is application/javascript. So, for compatibility reasons, js should support all three...
            */
            $validTypes = array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'json' => 'application/json',
                'js' => 'text/plain', /* text/plain is what is often interpreted for js files, even though the standard is application/javascript */
                'mp3' => 'audio/mpeg',
                'aif' => 'audio/x-aiff',
                'aiff' => 'audio/x-aiff',
                'ogg' => 'audio/ogg',
                'oga' => 'audio/oga',
                'mov' => 'video/quicktime',
                'm4v' => 'video/mp4',
            );

            /* Report progress to media upload log. */
            $sdmMediaUploadLog = $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$validTypes' => $validTypes]) . PHP_EOL;

            /* Report progress to media upload log. */
            $sdmMediaUploadLog = $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$_FILE' => $_FILES]) . PHP_EOL;

            /* Check if $_FILES['SdmForm']['error']['sdmMediaFile'] is unset. */
            $errorsValueSet = isset($_FILES['SdmForm']['error']['sdmMediaFile']);

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$errorsValueSet' => $errorsValueSet]) . PHP_EOL;

            /* Check if $_FILES['SdmForm']['error']['sdmMediaFile'] is an array. */
            $errorsValueManipulated = is_array($_FILES['SdmForm']['error']['sdmMediaFile']);

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$errorsValueManipulated' => $errorsValueManipulated]) . PHP_EOL;

            /* If $_FILES['SdmForm']['error']['sdmMediaFile'] is unset or if it is an array this request is
               suspicious. HTTP headers may have have been compromised, do not process! */
            if ($errorsValueSet === false || $errorsValueManipulated === true) {
                /* Temporarily block user from using the page, and show a message warning them not to hack or pentest. */
                echo '<div style="padding:42px;color: red;font-size:3em;background: #000000; opacity: 1; width: 100%; height: 25000px; z-index:1000;position: absolute; top: 0px; left:0px;">NO HACKING!<br>NO PENTESTING WITHOUT PERMISSION!!!</div>';
                $possibleAttackMessage = '<p>SdmMediaDisplay Upload Error: Invalid or corrupted parameters. Possible security breach! It is suggested that you
                            review the SdmMediaDisplay file upload log to try and identify if a breach has in fact occured.</p>
                            <p>You should review the Sdm Media Display upload log, and then review the media files that were uploaded
                             and check that the files are in fact the type of media they claim to be. Whether the files are valid or
                             not depends on the whitelist of media types which currently defines the following valid types: ' . implode(', ', $validTypes) . '.</p>
                             <p>However, some attacks may get past the white list which is why you need to manually go through the media files and make sure they
                             are what they claim to be.</p>' . PHP_EOL;
                error_log($possibleAttackMessage);
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
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$maxSize' => $maxSize]) . PHP_EOL;


            $maxSizeMultiplier = 1000; // set to 1 GB so video to accommodate large audio and video.

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$maxSizeMultiplier' => $maxSizeMultiplier]) . PHP_EOL;


            $minSize = 1000; // 1000 === 1 kilobyte | 1000 = 0.001 megabytes

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$minSize' => $minSize]) . PHP_EOL;


            $minSizeMultiplier = 1;

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$minSizeMultiplier' => $minSizeMultiplier]) . PHP_EOL;


            /* Get uploaded file size | Kinda sucks to have to rely on http headers here, but
               so far I can't find another way to get the uploaded file size without actually
               allowing the upload to happen, checking file size after upload, and unlinking file
               if to large or small which would defeat the purpose of this check and open up
               a DDos security hole.
            */
            $uploadedFileSize = $_FILES['SdmForm']['size']['sdmMediaFile'];

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$uploadedFileSize' => $uploadedFileSize]) . PHP_EOL;


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
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$loadedFilesInfo' => $loadedFilesInfo]) . PHP_EOL;

            /* Determine uploaded files type. */
            $uploadedFilesType = $loadedFilesInfo->file($_FILES['SdmForm']['tmp_name']['sdmMediaFile']);

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$uploadedFilesType' => $uploadedFilesType]) . PHP_EOL;


            /* Check if file type/extension matches a valid file type, if it does use it, otherwise
               $validFileExt will be set to false. */
            $validFileExt = array_search($uploadedFilesType, $validTypes, true);

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$validFileExt' => $validFileExt]) . PHP_EOL;


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

            if ($this->sdmFormGetSubmittedFormValue('originalSdmMediaSourceName') !== null && $this->sdmFormGetSubmittedFormValue('originalSdmMediaSourceExtension') !== null) {
                $originalMediaFileName = $this->sdmFormGetSubmittedFormValue('originalSdmMediaSourceName') . '.' . $this->sdmFormGetSubmittedFormValue('originalSdmMediaSourceExtension');
            }

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$uniqueFileName' => $uniqueFileName]);

            /* Report progress to media upload log. */
            $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['$savePath' => $savePath]) . PHP_EOL;

            /* Attempt to upload and save the file, throw an error if upload fails. */
            if (move_uploaded_file($_FILES['SdmForm']['tmp_name']['sdmMediaFile'], $savePath . '/' . $uniqueFileName) !== false) {
                /* If $originalMediaFileName is set then user is replacing the media objects media file, so, unlink the original file and the original data file.
                   They will both be recreated based on the new uploaded file. */
                if (isset($originalMediaFileName)) {
                    unlink($savePath . '/' . $originalMediaFileName);
                }
                /* upload succeed. */
                $fileUploadSuccessMessage = 'Sdm Media Displays File Upload Status: File was uploaded successfully.' . PHP_EOL;
                /* Report to upload log */
                $sdmMediaUploadLog .= $this->sdmCms->sdmCoreSdmReadArrayBuffered(['Upload Status' => $fileUploadSuccessMessage]);
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

        /* Log upload. */
        file_put_contents($this->sdmCms->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/logs/sdmMediaDisplayLog_' . time() . '.html', $sdmMediaUploadLogMessage);

        return ($fileUploadStatus === true ? $uniqueFileName : $fileUploadStatus);

    }

    /**
     * Saves media data in a json file under the $displayBeingEdited's data directory.
     *
     * @param $uniqueFileName mixed The return value of uploadMedia(). For local files this will be the name
     *                              of the media file saved by uploadMedia(). For external sources this will
     *                              be the boolean true. If this value is a string it will be used to generate
     *                              the name of the json file this media data is saved in, as well as the value
     *                              saved as the sdmMediaId, sdmMediaSourceName, and sdmMediaSourceExtension
     *                              otherwise the name of the json file, the sdmMediaId, sdmMediaSourceName,
     *                              and sdmMediaSourceExtension will be generated internally by this method.
     *
     * NOTE: If $uniqueFileName is null then saveMediaData() will check to see if the originalSdmMediaSourceName and
     * originalSdmMediaSourceExtension form elements were submitted, if they were their values will be used to generate
     * the name of the json file the media data is saved in, as well as the value saved as the sdmMediaId, sdmMediaSourceName,
     * and sdmMediaSourceExtension.
     *
     * @return bool Returns true if the media data was successfully saved, returns false on failure.
     */
    private function saveMediaData($uniqueFileName)
    {
        /* Get submitted form values */
        $submittedEditMediaFormValues = array_keys($this->sdmFormGetSubmittedFormValue('all'));

        /** Unpack vars from file upload handler **/

        /* The unique file name generated on file upload. This includes the file extension. */
        $fileName = $uniqueFileName;

        /* Initialize array to hold new media property values. */
        $newMediaPropertyValues = array();

        /* Add each submitted form value to the $newMediaPropertyValues array. It's ok if values unrelated to the SdmMedia object
           are included because they will simply be ignored on creation of new SdmMedia() object. */
        foreach ($submittedEditMediaFormValues as $submittedEditMediaFormKey) {
            switch ($submittedEditMediaFormKey) {
                case 'sdmMediaSourceUrl':
                    /* If sdmMediaSourceType is local, enforce local url, otherwise use supplied. */
                    $newMediaPropertyValues[$submittedEditMediaFormKey] = ($this->sdmFormGetSubmittedFormValue('sdmMediaSourceType') === 'local' ? $this->sdmCms->sdmCoreGetRootDirectoryUrl() . '/apps/SdmMediaDisplays/displays/media' : $this->sdmFormGetSubmittedFormValue($submittedEditMediaFormKey));
                    break;
                case 'sdmMediaProtected':
                case 'sdmMediaPublic':
                    /* For now enforce public and protected using false since these properties
                       have not yet been implemented. */
                    $newMediaPropertyValues[$submittedEditMediaFormKey] = false;
                    break;
                default:
                    /* Use submitted value without special processing. */
                    $newMediaPropertyValues[$submittedEditMediaFormKey] = $this->sdmFormGetSubmittedFormValue($submittedEditMediaFormKey);
                    break;
            }
        }

        /* Create new SdmMedia() object instance. */
        $updateMediaObject = new SdmMedia();

        /* Create new media object from new media property values. */
        $newMediaObject = $updateMediaObject->sdmMediaCreateMediaObject($newMediaPropertyValues);

        /* If originalSdmMediaSourceName was submitted set the $originalDataFileName variable and remove original data file. */
        if ($this->sdmFormGetSubmittedFormValue('originalSdmMediaSourceName') !== null) {
            $originalDataFileName = $this->sdmFormGetSubmittedFormValue('originalSdmMediaSourceName') . '.json';
            /* Remove the original data file since it is always created/re-created upon adding or editing a piece of media. */
            unlink($this->pathToCurrentDisplay . '/' . $originalDataFileName);
        }

        /* Generate $safeFileName based on whether or not sdmMediaSourceType is 'external' or 'local'. Also, for external
           media properly encode the sdmMediaSourceUrl */
        switch ($newMediaPropertyValues['sdmMediaSourceType']) {
            case 'external':
                /* Set safe file name */
                $safeFileName = hash('sha256', $newMediaPropertyValues['sdmMediaSourceUrl']);

                /* Properly encode embedded urls for display and set source url to properly encoded external url value. */
                $newMediaObject->sdmMediaSetSourceUrl($this->sdmMediaEncodeExternalMediaUrl($newMediaPropertyValues['sdmMediaSourceUrl']));

                break;
            default:
                /* Generate a safe file name from either the $originalDataFileName or the $uniqueFileName to be used as the sdmMediaSourceName,
                   sdmMediaSourceId, and as the name of the data file for this media object. NOTE: $safeFileName does not include file extension.
                   Basically, if $uniqueFileName is null and the $originalDataFileName is set, then the user has updated media without
                   specifying a new file, so use the original data filename, otherwise use the new $uniqueFileName to generate the $safeFileName.
                 */
                $safeFileName = ($uniqueFileName === null && isset($originalDataFileName) === true ? substr($originalDataFileName, 0, strpos($originalDataFileName, '.')) : substr($uniqueFileName, 0, strpos($uniqueFileName, '.')));

                break;
        }

        /* Set media source name based on $safeFileName. */
        $newMediaObject->sdmMediaSetSourceName($safeFileName);

        /* Set media source id based on $safeFileName. */
        $newMediaObject->sdmMediaSetId($safeFileName);

        /* Set media source extension based on uploaded file name. NOTE: If $fileName is null, then see if the
           originalSdmMediaSourceExtension form value was submitted, if it was use it's value as the extension. */
        $fileExtension = ($fileName === null ? $this->sdmFormGetSubmittedFormValue('originalSdmMediaSourceExtension') : substr($fileName, strpos($fileName, ".") + 1));
        $newMediaObject->sdmMediaSetSourceExtension($fileExtension);

        /* Convert media display name to camel case and set as machine name */
        $camelCaseMediaName = str_replace(' ', '', ucfirst(preg_replace("/[^a-z]+/i", "", $this->sdmFormGetSubmittedFormValue('sdmMediaDisplayName'))));

        /* Set  media machine name */
        $newMediaObject->sdmMediaSetMachineName($camelCaseMediaName);

        /* Json encode new media object to prepare for storage. */
        $newMediaObjectJson = json_encode($newMediaObject);

        /* Save new media object  */
        $saveStatus = file_put_contents($this->sdmMediaDisplaysDataDirectoryPath . '/' . $this->displayBeingEdited . '/' . $newMediaObject->sdmMediaGetSdmMediaSourceName() . '.json', $newMediaObjectJson);

        /* Added confirmation message to panel description. */
        $this->messages .= 'Saved changes to media "' . $this->sdmFormGetSubmittedFormValue('sdmMediaDisplayName') . '"';

        return ($saveStatus !== false ? true : false);
    }

    /**
     * Attempts to encode an external media url so it complies to the embed format
     * of the provider. At the moment this function only supports Vimeo, and Youtube.
     * Other provider urls will be returned without modification.
     *
     * @param string $url The external media url to encode.
     *
     * @return string The encoded url.
     */
    private function sdmMediaEncodeExternalMediaUrl($url)
    {
        /* Determine provider. */
        $provider = parse_url($url);

        /* Encode url based on provider */
        switch ($provider['host']) {
            case 'youtube.com':
            case 'www.youtube.com':
                $embedUrl = str_replace('watch?v=', 'embed/', $url);
                break;
            case 'vimeo.com':
                $embedUrl = str_replace('vimeo.com/', 'player.vimeo.com/video/', $url);
                break;
            default:
                $embedUrl = $url;
                break;
        }
        return $embedUrl;
    }

    /**
     * Returns the html for the current admin panel.
     *
     * @return string The html for the current admin panel.
     */
    public function getCurrentAdminPanel()
    {
        /* If this is not the initial setup assemble admin panel. */
        if ($this->initialSetup === false) {
            /* Assemble form elements. */
            $this->assembleAdminFormElements();

            /* Assemble form buttons. */
            $this->assembleAdminFormButtons();

            /* Initialize $formHtml array. */
            $formHtml = array();

            /* Start building form. */
            $formHtml['openingFormTags'] = $this->sdmFormOpenForm($this->sdmCms->sdmCoreGetRootDirectoryUrl());
            $formHtml['formElementsHtml'] = implode(PHP_EOL, $this->adminFormElements);
            $formHtml['closingFormTags'] = $this->sdmFormCloseForm();

            /* Display admin buttons for the current panel */
            $this->output .= implode('', $formHtml) . implode('', $this->adminFormButtons);
        }

        /* Return current admin panel's messages and output. */
        return $this->messages . $this->output;
    }

    /**
     * Assembles the appropriate form elements for the current admin panel. This method adds these elements
     * to the parent SdmForm() classes form elements array.
     *
     * @return array Returns an array of the assembled form elements for the current admin panel.
     */
    private function assembleAdminFormElements()
    {
        /* Determine which form elements to define for the current adminPanel. */
        switch ($this->adminPanel) {
            case 'addDisplay':
            case 'editDisplay':
                if ($this->currentDisplayExists === true) {
                    /* Load display data */
                    $displayData = json_decode(file_get_contents($this->sdmMediaDisplaysDataFilePath));
                }
                /* Create "displayName" form element which determines the name of the display. */
                $this->sdmFormCreateFormElement('displayName', 'text', 'Enter a name for this display', (isset($displayData) ? $displayData->displayName : ''), 0);
                /* Create array holding the value 'all' to be used to indicate that all pages should include the display. */
                $allPages = array('all' => 'all');
                /* Create an array of available pages that the display can be assigned to. */
                $availablePages = $this->sdmCms->sdmCoreDetermineAvailablePages();
                /* Create array of available apps that the display can be assigned to. */
                $enabledApps = (array)$this->sdmCms->sdmCoreDetermineEnabledApps();
                /* Create array of assignable pages from the $allPages, $availablePages, and $enabledApps arrays. */
                $assignablePages = array_merge($allPages, $availablePages, $enabledApps);
                /* Create "incmethod" form element which determines how the display is included into the page. */
                $this->sdmFormCreateFormElement('incmethod', 'select', 'Select the method the display should be incorporated into the page, append will place it before other content, prepend will place it after, overwrite will force the display to overwrite other content.', $this->sdmFormSetDefaultInputValues(array('Append' => 'append', 'Prepend' => 'prepend', 'Overwrite' => 'overwrite'), (isset($displayData) ? $displayData->options->incmethod : 'prepend')), 1);
                /* Create "incpages" form element which detemrines which pages the display is included on. */
                $this->sdmFormCreateFormElement('incpages', 'checkbox', 'Select the pages the display should show up on. If the display should show on all pages check the "all" option', $this->sdmFormSetDefaultInputValues($assignablePages, (isset($displayData) ? $displayData->options->incpages : '')), 2);
                /* Create "ingorepages" form element which determines which pages the display will not be included on. */
                $this->sdmFormCreateFormElement('ignorepages', 'checkbox', 'Select the pages the display should NOT show up on. If the display should be hidden on all pages check the "all" option', $this->sdmFormSetDefaultInputValues($assignablePages, (isset($displayData) ? $displayData->options->ignorepages : 'SdmMediaDisplays')), 3);
                /* Create "wrapper" form element which determines which content wrapper the display is assigned to. */
                $this->sdmFormCreateFormElement('wrapper', 'select', 'Select the content wrapper the display should be assigned to.', $this->sdmFormSetDefaultInputValues($this->sdmCms->sdmCmsDetermineAvailableWrappers(), (isset($displayData) ? $displayData->options->wrapper : 'main_Content')), 4);
                /* Create "roles" form element which determines which user roles can view the display. */
                $this->sdmFormCreateFormElement('roles', 'checkbox', 'Select the user roles this display can be viewed by. For instance if only "root" users should see the display select the "root" role.', $this->sdmFormSetDefaultInputValues(array('Root' => 'root', 'Basic User' => 'basicUser', 'All Roles' => 'all'), (isset($displayData) ? $displayData->options->roles : 'root')), 5);
                /* Create list of available display templates. */
                $templateDirectoryListing = $this->sdmCms->sdmCoreGetDirectoryListing('SdmMediaDisplays/displays/templates', 'apps');
                $ignoredListings = array('.', '..', '.DS_Store');
                $availableTemplates = array();
                foreach ($templateDirectoryListing as $templateFile) {
                    if (in_array($templateFile, $ignoredListings) === false) {
                        $availableTemplates[str_replace('.php', '', $templateFile)] = $templateFile;
                    }
                }
                /* Create "template" form element which determines which template the display will use to format it's output. */
                $this->sdmFormCreateFormElement('template', 'select', 'Select the display template to use for this display. If the display does not require a custom template use the default Sdm Media Displays template.', $this->sdmFormSetDefaultInputValues($availableTemplates, (isset($displayData) ? $displayData->template : 'SdmMediaDisplays.php')), 6);
                break;
            case 'editDisplays':
                $this->sdmFormCreateFormElement('displayName', 'select', 'Select a display to edit.', $this->sdmFormSetDefaultInputValues($this->availableDisplays, ''), 1);
                break;
            case 'saveMedia':
            case 'cancelAddEditMedia':
            case 'editMedia':
                /* Create hidden element to store name of display being edited. */
                $this->sdmFormCreateFormElement('displayName', 'hidden', 'Current Display Being Edited', $this->displayBeingEdited, 420);

                /* If display being edited has media create display preview with checkboxes so user can select a piece of media to edit. */
                if ($this->sdmMediaDisplay->sdmMediaDisplayHasMedia($this->displayBeingEdited)) {

                    /* Load media object properties for the display being edited's media. */
                    $mediaProperties = $this->sdmMediaDisplay->sdmMediaDisplayLoadMediaObjectProperties($this->displayBeingEdited);

                    /* Create SdmMedia objects for display being edited. */
                    $mediaObjects = array();
                    foreach ($mediaProperties as $properties) {
                        $mediaObjects[] = $this->sdmMediaDisplay->sdmMediaCreateMediaObject($properties);
                    }

                    /* Initialize array of media ids used as the radio button values for the mediaToEdit form element. */
                    $mediaIds = array();
                    /* Initialize array of media names used as the radio button text for the mediaToEdit form element. */
                    $mediaNames = array();
                    /* Add SdmMedia objects to display being edited. */
                    foreach ($mediaObjects as $mediaObject) {
                        $this->sdmMediaDisplay->sdmMediaDisplayAddMediaObject($mediaObject);
                        /* Use json functions to gain access to $mediaObject's protected properties sdmMediaMachineName and sdmMediaId. */
                        $mediaData = json_decode(json_encode($mediaObject));
                        /* Add sdmMediaId to $mediaIds array and index by sdmMediaMachineName */
                        $mediaIds[$mediaData->sdmMediaMachineName] = $mediaData->sdmMediaId;
                        /* Add sdmMediaName to $mediaNames array and index by sdmMediaMachineName */
                        $mediaNames[$mediaData->sdmMediaMachineName] = $mediaData->sdmMediaDisplayName;
                    }

                    /* Create array of media html to be used to add media previews to radio buttons belonging to mediaToEdit form element. */
                    $media = array_flip($this->sdmMediaDisplay->sdmMediaGetSdmMediaDisplayMediaElementsHtml());

                    /* Initialize $availableMedia array which will hold the radio button html, media preview, and the media id to be used in construction of the mediaToEdit form element. */
                    $availableMedia = array();
                    foreach ($media as $mediaHtml => $mediaName) {
                        $availableMedia['<!-- Preview Container --><div style="padding: 40px;"><!-- Media HTML --><div>' . str_replace(array('<img ', '<iframe ', '<audio ', '<video ', '<canvas '), array('<img style="width:250px;" ', '<iframe style="width:250px;" ', '<audio style="width:250px;" ', '<video style="width:250px;" ', '<canvas style="width:250px;" '), $mediaHtml) . '</div><!-- End Media HTML --><!-- Media Name --><div>' . $mediaNames[$mediaName] . '</div><!-- End Media Name--></div><!-- End Preview Container -->'] = $mediaIds[$mediaName];
                    }

                    /* Create radio buttons for user to select media to edit from. */
                    $this->sdmFormCreateFormElement('mediaToEdit', 'radio', 'Select a piece of media to edit.', $this->sdmFormSetDefaultInputValues($availableMedia, ''), 2, array('style' => 'position:relative;float:left;margin:-172px 0px 0px 10px;'));
                }
                break;
            case 'addMedia':
            case 'editSelectedMedia':
                /* If on editSelectedMedia admin panel and mediaToEdit data file exists, load it so the data can be used to pre-populate the editSelectedMedia admin panel's form. */
                if ($this->adminPanel === 'editSelectedMedia' && file_exists($this->pathToCurrentDisplay . '/' . $this->sdmFormGetSubmittedFormValue('mediaToEdit') . '.json') === true) {
                    $mediaData = json_decode(file_get_contents($this->pathToCurrentDisplay . '/' . $this->sdmFormGetSubmittedFormValue('mediaToEdit') . '.json'));
                    /* When editing media, store the original sdmMediaSourceName and sdmMediaSourceExtension so it can be used to update the media data upon arriving at the saveMedia admin panel. */
                    $this->sdmFormCreateFormElement('originalSdmMediaSourceName', 'hidden', 'Original sdmMediaSourceName of the media being edited', $mediaData->sdmMediaSourceName, 517);
                    $this->sdmFormCreateFormElement('originalSdmMediaSourceExtension', 'hidden', 'Original sdmMediaSourceExtension of the media being edited', $mediaData->sdmMediaSourceExtension, 527);
                }
                /* Create hidden form element to story the name of the display being edited */
                $this->sdmFormCreateFormElement('displayName', 'hidden', 'Current Display Being Edited', $this->displayBeingEdited, 420);
                /* Media source path (local path to media file, only used for local media sources) */
                $this->sdmFormCreateFormElement('sdmMediaSourcePath', 'hidden', '', $this->sdmMediaDisplaysMediaDirectoryPath, 421);
                /* Name/title of the media */
                $this->sdmFormCreateFormElement('sdmMediaDisplayName', 'text', '<br/>Name or title for the media.', (isset($mediaData) === true ? $mediaData->sdmMediaDisplayName : ''), 1);
                /* Media source type (local or external) */
                $this->sdmFormCreateFormElement('sdmMediaSourceType', 'select', '<p>Is the media source external or local?</p><p>External sources are sources from other sites, such as Youtube. Local sources, as the name implies, are stored locally.<br/>Use external if media is from a url to a site such as Youtube or Vimeo.<br/>Use local if you are uploading the media.</p>', $this->sdmFormSetDefaultInputValues(array('External (Media resource from another site)' => 'external', 'Local (Media stored locally)' => 'local',), (isset($mediaData) === true ? $mediaData->sdmMediaSourceType : 'local')), 2);
                /* Media file (only used for local media sources). */
                $this->sdmFormCreateFormElement('sdmMediaFile', 'file', 'Upload media file | Only used for local media sources.', null, 3);
                /* Media source url (only used for external media sources). */
                $this->sdmFormCreateFormElement('sdmMediaSourceUrl', 'text', 'Url To Media | Only set for external media sources. (If youtube url it must be the embed url provided by youtube.)', (isset($mediaData) === true ? $mediaData->sdmMediaSourceUrl : ''), 4);
                /* Media type (i.e., image, audio, etc.) */
                $this->sdmFormCreateFormElement('sdmMediaType', 'select', 'Select the media\'s type.', $this->sdmFormSetDefaultInputValues(array('Image' => 'image', 'Audio' => 'audio', 'Video' => 'video', 'Embedded Media (Media from another site such as Youtube or Vimeo)' => 'youtube', 'HTML5 Canvas Image/Animation (Javascript file for HTML5 canvas tag)' => 'canvas',), (isset($mediaData) ? $mediaData->sdmMediaType : 'image')), 5);
                /* Media category */
                $this->sdmFormCreateFormElement('sdmMediaCategory', 'text', 'Category name to organize media by. Media is ordered in display by media\'s category, place, and finally name.', (isset($mediaData) === true ? $mediaData->sdmMediaCategory : ''), 6);
                /* Media place */
                $this->sdmFormCreateFormElement('sdmMediaPlace', 'select', 'Media\'s place. Represents media\'s position in display relative to other media in the same category.', $this->sdmFormSetDefaultInputValues(range(1, 1000), (isset($mediaData) === true ? $mediaData->sdmMediaPlace + 1 : 1)), 7);
                /* NOTE: Properties that do not have a form element defined will be set upon processing submitted form. */
                break;
        }

        /* Build form elements html */
        $this->sdmFormBuildFormElements();

        /* Push form element HTML for each of this admin panels form elements into the adminFormElements array. */
        foreach ($this->formElements as $formElement) {
            array_push($this->adminFormElements, $this->sdmFormGetFormElementHtml($formElement['id']));
        }

        /* Return adminFormElements for the current adminPanel. */
        return $this->adminFormElements;
    }

    /**
     * Assembles the appropriate form buttons for the current admin panel.
     *
     * @return array Returns an array of form buttons for the current admin panel.
     */
    private function assembleAdminFormButtons()
    {
        /* Determine which admin panel is in use. */
        switch ($this->adminPanel) {
            case 'displayCrudPanel':
                /* Create buttons for the displayCrudPanel */
                $buttons = array(
                    'addDisplay' => $this->createSdmMediaDisplayAdminButton('addDisplayButton', 'adminPanel', 'addDisplay', 'Create New Display', array('form' => $this->sdmFormGetFormId())),
                    'editDisplays' => $this->createSdmMediaDisplayAdminButton('editDisplaysButton', 'adminPanel', 'editDisplays', 'Edit Displays', array('form' => $this->sdmFormGetFormId())),
                    'deleteDisplays' => $this->createSdmMediaDisplayAdminButton('deleteDisplaysButton', 'adminPanel', 'deleteDisplays', 'Delete Displays', array('form' => $this->sdmFormGetFormId())),
                );
                /* If there are any displays, show all the displayCrudPanel buttons. */
                if ($this->displaysExist === true) {
                    $this->adminFormButtons = $buttons;
                    break;
                }
                /* If there aren't any displays, only show addDisplaysButton. */
                array_push($this->adminFormButtons, $buttons['addDisplay']);
                break;
            case 'addDisplay':
            case 'editDisplay':
                /* Create buttons for the addDisplay panel*/
                $buttons = array(
                    'editMedia' => $this->createSdmMediaDisplayAdminButton('editMediaButton', 'adminPanel', 'editMedia', ($this->currentDisplayExists === true ? 'Save Changes and Edit Media' : 'Add Media To New Display'), array('form' => $this->sdmFormGetFormId())),
                    'cancelAddDisplays' => $this->createSdmMediaDisplayAdminButton('cancelAddDisplaysButton', 'adminPanel', 'displayCrudPanel', 'Cancel', array('form' => $this->sdmFormGetFormId())),
                );
                /* Show edit media buttons. */
                $this->adminFormButtons = $buttons;
                break;
            case 'editDisplays':
                $buttons = array(
                    'editDisplay' => $this->createSdmMediaDisplayAdminButton('editDisplayButton', 'adminPanel', 'editDisplay', 'Edit Selected Display', array('form' => $this->sdmFormGetFormId())),
                    'cancelEditDisplays' => $this->createSdmMediaDisplayAdminButton('cancelEditDisplaysButton', 'adminPanel', 'displayCrudPanel', 'Cancel', array('form' => $this->sdmFormGetFormId())),
                );
                $this->adminFormButtons = $buttons;
                break;
            case 'saveMedia':
            case 'cancelAddEditMedia':
            case 'editMedia':
                $buttons = array(
                    'addMedia' => $this->createSdmMediaDisplayAdminButton('addMediaButton', 'adminPanel', 'addMedia', 'Add Media To Display', array('form' => $this->sdmFormGetFormId())),
                    'editSelectedMedia' => $this->createSdmMediaDisplayAdminButton('editSelectedMediaButton', 'adminPanel', 'editSelectedMedia', 'Edit Selected Media', array('form' => $this->sdmFormGetFormId())),
                    'deleteSelectedMedia' => $this->createSdmMediaDisplayAdminButton('deleteSelectedMediaButton', 'adminPanel', 'deleteSelectedMedia', 'Delete Selected Media', array('form' => $this->sdmFormGetFormId())),
                    'cancelEditMedia' => $this->createSdmMediaDisplayAdminButton('cancelEditMediaButton', 'adminPanel', 'displayCrudPanel', 'Return To Main Menu', array('form' => $this->sdmFormGetFormId())),
                );
                if ($this->sdmMediaDisplay->sdmMediaDisplayHasMedia($this->displayBeingEdited) === true) {
                    $this->adminFormButtons = $buttons;
                    break;
                }
                array_push($this->adminFormButtons, $buttons['addMedia']);
            array_push($this->adminFormButtons, $buttons['cancelEditMedia']);
                break;
            case 'addMedia':
            case 'editSelectedMedia':
                $this->adminFormButtons = array(
                    'saveMedia' => $this->createSdmMediaDisplayAdminButton('saveMediaButton', 'adminPanel', 'saveMedia', 'Save Media', array('form' => $this->sdmFormGetFormId())),
                    'cancel' => $this->createSdmMediaDisplayAdminButton('cancelButton', 'adminPanel', 'cancelAddEditMedia', 'Cancel', array('form' => $this->sdmFormGetFormId())),
                );
                break;
        }

        /* Return adminFormButtons. */
        return $this->adminFormButtons;
    }

    /**
     * Creates an html button.
     * @param $id string The button id.
     * @param $name string The key used to index the button value in the SdmForm array.
     * @param $value mixed The value to send when the button is clicked.
     * @param $label string The text to display on the button.
     * @param array $otherAttributes Associative array of additional attributes.
     * @return string Returns a string of html for the button.
     */
    private function createSdmMediaDisplayAdminButton($id, $name, $value, $label, $otherAttributes = array())
    {
        $attributes = array();
        foreach ($otherAttributes as $attributeName => $attributeValue) {
            $attributes[] = "$attributeName='$attributeValue'";
        }
        return "<button id='$id' name='SdmForm[$name]' type='submit' value='$value' " . implode(' ', $attributes) . ">$label</button>";
    }

}