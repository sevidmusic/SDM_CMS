<?php

/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 6:22 PM
 */

/**
 * BUGS:
 * @todo: Fix problem with canvas media. If a canvas media
 * element is displayed in a template more then once only the first instance
 * will display because the script is being included redundently. Need to find
 * a way to add scripts needed by canvas tag to the html head just once.
 */
class SdmMediaDisplay extends SdmMedia
{
    /* Sdm Core Instance */
    private $SdmCore;
    /** @var  $sdmMediaDisplayMedia array Array of Sdm Media objects for this display. */
    private $sdmMediaDisplayMedia;

    /** @var  $sdmMediaDisplayMediaElementsHtml array Array of media element html indexed by sdmMediaMachineName */
    private $sdmMediaDisplayMediaElementsHtml;

    /** @var  $sdmMediaDisplayTemplate string
     * The name of the template file for this display excluding the file extension.
     */
    private $sdmMediaDisplayTemplate;

    /** @var  $sdmMediaDisplayHtml string Html for the display. */
    private $sdmMediaDisplayHtml;

    /** @var  $sdmMediaDisplayCategorizedMediaObjects array
     * Array of this displays SdmMedia object's html organized by SdmMediaCategory,
     * then SdmMediaPlace, and finally SdmMediaDisplayName
     */
    private $sdmMediaDisplayCategorizedMediaObjects;

    /* Stores the output options for the display. */
    private $outputOptions;

    /**
     * SdmMediaDisplay constructor. Initializes the sdmMediaDisplayMedia array.
     */
    final public function __construct($SdmMediaDisplayTemplate = 'SdmMediaDisplayDefaultTemplate', SdmCore $SdmCoreObject)
    {
        /** @var  SdmCore object Injected instance of the SdmCore() class. Used to create correct display paths and urls. */
        $this->SdmCore = $SdmCoreObject;

        /* Initialize the sdmMediaDisplayMedia array which will hold the Sdm Media objects
           for this Sdm Media Display. */
        $this->sdmMediaDisplayMedia = array();
        /* Initialize the sdmMediaDisplayMediaElementsHtml array which will hold the Sdm Media Element's html
           for this Sdm Media Display. Media element html is indexed by the relative SdmMedia object's
           sdmMediaMachineName property.  */
        $this->sdmMediaDisplayMediaElementsHtml = array();

        /* Assign Sdm Media Display template, default template will be used. */
        $this->sdmMediaDisplayTemplate = $SdmMediaDisplayTemplate;

        /* Initialize sdmMediaDisplayHtml string. This string will hold the html for the display built
           from the SdmMedia objects that belong to this display. */
        $this->sdmMediaDisplayHtml = '';

        /* By default, protect display from being viewed by anyone! This is done for security so displays that do not have
         * their output options configured correctly do not get viewed by the wrong eyes. If a display does not appear, it
         * may be that the display's data file is corrupted or the options were not probably configured.
         */
        $this->outputOptions = array('ignorepages' => array('all'), 'roles' => array('root'));

    }

    /**
     * Gets displays html.
     * @return string Returns the html for the display.
     */
    public function sdmMediaDisplayGetSdmMediaDisplayHtml()
    {
        return $this->sdmMediaDisplayHtml;
    }

    /**
     * This method will order the media objects, load the template,
     * and assigns the resulting html string to the sdmMediaDisplayHtml property.
     */
    public function sdmMediaDisplayBuildMediaDisplay()
    {
        /* Create an ordered array of this display's SdmMedia object's html. Array is ordered
           by category, place, and finally display name. */
        $this->sdmMediaDisplayBuildOrderedMediaObjectArray();

        /* Load the display's template*/
        $this->sdmMediaDisplayLoadDisplayTemplate();

        return true; //@todo : return something more useful
    }

    /**
     * Builds the ordered array of SdmMedia objects for the display.
     *
     * @return array Array of SdmMedia objects ordered by category, place, and display name respectively.
     */
    private function sdmMediaDisplayBuildOrderedMediaObjectArray()
    {
        /* Initialize $orderedMedia array. Organize Sdm Media Elements Html
           categorically, by place, and finally by display name. */
        $orderedMedia = array();
        $mediaElementsHtml = $this->sdmMediaDisplayMediaElementsHtml;
        $mediaObjects = $this->sdmMediaDisplayGetMediaObjects();
        foreach ($mediaObjects as $mediaObject) {
            /* Unpack media object properties */
            $mediaProperties = get_object_vars($mediaObject);
            /* Determine media category. */
            $mediaCategory = $mediaProperties['sdmMediaCategory'];
            /* Determine Media Place */
            $mediaPlace = $mediaProperties['sdmMediaPlace'];
            /* Filter SdmMediaDisplay name so only it's alphanumeric characters are used. */
            $mediaDisplayName = preg_replace("/[^a-zA-Z0-9]+/", " ", $mediaProperties['sdmMediaDisplayName']);
            /* Determine machine name. */
            $mediaMachineName = $mediaProperties['sdmMediaMachineName'];
            /* Assign SdmMedia object to the $orderedMedia array. Index by SdmMediaCategory,
               SdmMediaPlace, and finally SdmMediaDisplayName */
            $mediaObject->sdmMediaDynamicallyGeneratedHtml = $mediaElementsHtml[$mediaMachineName];
            //var_dump($mediaObject);
            $orderedMedia[$mediaCategory][$mediaPlace][$mediaDisplayName] = $mediaObject;
        }
        /* Sort each level of the $orderedMedia array. */
        $this->sdmMediaDisplaySortCategorizedMediaElements($orderedMedia);

        /* Assign sorted $orderedMedia array to the $sdmMediaDisplayCategorizedMediaObjects property. */
        $this->sdmMediaDisplayCategorizedMediaObjects = $orderedMedia;

        return true; // @todo: return something more useful
    }

    /**
     * Returns an array of the Sdm Media objects that belong to this display.
     * @return array Array of Sdm Media objects that belong to this display.
     */
    public function sdmMediaDisplayGetMediaObjects()
    {
        return $this->sdmMediaDisplayMedia;
    }

    /**
     * Sorts the $orderedMedia array created by sdmMediaDisplayBuildMediaDisplay()
     * recursively.
     *
     * @param $orderedMedia array The $orderedMedia array created by sdmMediaDisplayBuildMediaDisplay().
     *
     * @return bool True if sort succeeded, otherwise false.
     */
    private function sdmMediaDisplaySortCategorizedMediaElements(&$orderedMedia)
    {
        foreach ($orderedMedia as &$arrayLevel) {
            if (is_array($arrayLevel)) {
                $this->sdmMediaDisplaySortCategorizedMediaElements($arrayLevel);
            }
        }
        return ksort($orderedMedia);
    }

    /**/

    /**
     * Loads the display's template file.
     */
    private function sdmMediaDisplayLoadDisplayTemplate()
    {
        /* Build display based on template using an output buffer to capture the output of require_once() */
        ob_start();
        /* Store $this in local var so it can be accessed by template. */
        $sdmMediaDisplay = $this;
        $templateDirPath = str_replace('/includes', '', __DIR__) . '/displays/templates';
        switch (file_exists($templateDirPath . '/' . $this->sdmMediaDisplayTemplate . '.php')) {
            case true:
                require_once($templateDirPath . '/' . $this->sdmMediaDisplayTemplate . '.php');
                break;
            default:
                require_once($templateDirPath . '/SdmMediaDisplays.php');
                break;
        }


        $this->sdmMediaDisplayHtml = ob_get_contents();
        ob_end_clean();
    }

    /**
     * Generates a media display for a template based on the current display's media objects.
     * This method is meant to be called from within a Sdm Media Display template file.
     * @param $function string|null If set, should be the name of a user defined function that will be called
     *                              on each media element in the sdmMediaDisplayCategorizedMediaObjects array.
     * @return string
     */
    public function sdmMediaDisplayGenerateMediaDisplay($function = null)
    {
        /* Initialize $display array. */
        $display = array();
        /* Get categorized media objects for this display. */
        $categorizedMediaObjects = $this->sdmMediaDisplayCategorizedMediaObjects;
        switch (isset($function) && function_exists($function)) {
            case true:
                /* Unpack categorized media objects. */
                foreach ($categorizedMediaObjects as $category) {
                    foreach ($category as $place) {
                        foreach ($place as $mediaObject) {
                            /* Call user defined display assembly function, and add returned data to the $display array. */
                            $display[] = call_user_func_array($function, array($mediaObject->sdmMediaDynamicallyGeneratedHtml, $mediaObject));
                        }
                    }
                }
                break;
            default:
                /* Unpack categorized media objects. */
                foreach ($categorizedMediaObjects as $category) {
                    foreach ($category as $place) {
                        foreach ($place as $mediaObject) {
                            /* Add media's html to $display array. */
                            $display[] = PHP_EOL . $mediaObject->sdmMediaDynamicallyGeneratedHtml . PHP_EOL;
                        }
                    }
                }
                break;
        }

        return implode('', $display);
    }

    /**
     * Check if a directory is empty (a directory with just '.svn' or '.git' is empty)
     *
     * Used PHP's RecursiveDirectoryIterator
     *
     * Code adopted from last answer on Stackoverflow page @see http://stackoverflow.com/questions/7497733/how-can-use-php-to-check-if-a-directory-is-empty
     *
     * NOTE: Will return null if $display is not a directory.
     *
     * @param string $display The directory to check.
     * @return bool True if directory is empty, false if it is not empty. Will return null if $display is not a directory.
     */
    public function sdmMediaDisplayHasMedia($display)
    {
        /* Create full path to display's data directory */
        $displayDirectory = str_replace('/includes', '', __DIR__) . '/displays/data/' . $display;
        /* Make sure $displayDirectory is in fact a displayDirectory. */
        if (is_dir($displayDirectory) === true) {
            if (file_exists($displayDirectory . '/.DS_Store')) {
                $adjustForExpectedNonMediaFiles = -2; // -2 to account for .DS_Store and display data file
            } else {
                $adjustForExpectedNonMediaFiles = -1; // -1 to account for display's data file
            }
            /* Create new RecursiveDirectoryIterator, will be used to iterate through displayDirectory to see
               whether or not it is empty. */
            $directoryIterator = new RecursiveDirectoryIterator($displayDirectory, FilesystemIterator::SKIP_DOTS);
            /* Count iterator, this number will reflect the number of items in the displayDirectory. */
            $directoryCount = iterator_count($directoryIterator);
            /* If */
            $directoryIsEmpty = (($directoryCount + $adjustForExpectedNonMediaFiles) === 0 ? false : true);
            return $directoryIsEmpty;
        }
        return null;
    }

    /**
     * Returns an array of stored media properties for the media belonging to a specified display.
     *
     * @param $display string Name of the display whose media's properties are to be returned.
     *
     * @return array An array of each media's properties indexed by media's id.
     */
    public function sdmMediaDisplayLoadMediaObjectProperties($display, $addToCurrent = false)
    {
        /* Get directory listing of saved media for the current display. */
        $savedMedia = $this->SdmCore->sdmCoreGetDirectoryListing("SdmMediaDisplays/displays/data/$display", 'apps');

        /* Load media objects */
        $mediaJson = array();

        /* Load each media's json file and add stored json to $mediaJson array. */
        foreach ($savedMedia as $mediaJsonFilename) {
            /** @var  $badFileNames array  Array of files to ignore. @todo: could check file type instead or as well to insure only json files are loaded??? */
            $badFileNames = array('.', '..', '.DS_Store');

            /* Load media json, ignore $badFileNames*/
            if (in_array($mediaJsonFilename, $badFileNames) === false) {

                /* Load media from current displays data directory. */
                $mediaJson[] = file_get_contents($this->SdmCore->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data/' . $display . '/' . $mediaJsonFilename);
            }
        }

        /* If $addToCurrent is set to true add the media to the current SdmMediaDisplay object. */
        if ($addToCurrent === true) {
            foreach ($mediaJson as $mediaObjectJson) {
                /* Create new SdmMedia instance. */
                $mediaObject = new parent;

                /* Decode $mediaObject json. */
                $mediaObjectProperties = json_decode($mediaObjectJson);

                /* Set $mediaObject's properties based on properties defined in decoded $mediaObjectJson. */
                foreach ($mediaObjectProperties as $mediaPropertyName => $mediaPropertyValue) {
                    /* Set $mediaObject property. */
                    $mediaObject->{$mediaPropertyName} = $mediaPropertyValue;
                }

                /* Add $mediaObject to current display */
                $this->sdmMediaDisplayAddMediaObject($mediaObject);
            }

        }
        /* Unpack media properties. */
        $mediaProperties = array();

        /* Decode each media's json to an array and add the resulting array to $mediaProperties array */
        foreach ($mediaJson as $encodedMediaProperties) {
            /* Decode media. */
            $decodedMediaProperties = json_decode($encodedMediaProperties, true);

            /* Check $decodedMediaProperties to make sure the json file loaded is for media, not displays. */
            $decodedPropertyKeys = array_keys($decodedMediaProperties);

            /* If data is not for display, but for media add media properties.*/
            if (!in_array('displayName', $decodedPropertyKeys)) {
                /* Add media properties array to $mediaProperties array. */
                $mediaProperties[$decodedMediaProperties['sdmMediaId']] = $decodedMediaProperties;
            }

        }

        /* Return media properties array. */
        return $mediaProperties;
    }

    /**
     * Adds an SdmMedia() object to the display.
     *
     * @param SdmMedia $media The SdmMedia() object to add to the display.
     *
     * @return bool True if element was added successfully, false otherwise.
     */
    final public function sdmMediaDisplayAddMediaObject(SdmMedia $media)
    {
        /* Count the number of media objects already assigned to the display. */
        $elements = count($this->sdmMediaDisplayMedia);

        /* Attempt to add media object to display. */
        array_push($this->sdmMediaDisplayMedia, $media);

        /* Count number of media objects after add. */
        $newElements = count($this->sdmMediaDisplayMedia);

        /* If number of media objects not greater then the original
           number of media objects attempt to add media object failed. */
        $status = ($newElements > $elements ? true : false);

        /* If $media object was added successfully, create an html element for it. */
        if ($status === true) {
            $this->sdmMediaDisplayBuildMediaElementHtml($media);
        }
        /* Return $status. */
        return $status;
    }

    private function sdmMediaDisplayBuildMediaElementHtml(SdmMedia $media)
    {
        /* Unpack SdmMediaId */
        $id = $media->sdmMediaGetSdmMediaId();

        /* Unpack SdmMediaMachineName */
        $machineName = $media->sdmMediaGetSdmMediaMachineName();

        /* Unpack SdmMediaType */
        $type = $media->sdmMediaGetSdmMediaType();

        /* Unpack SdmMediaUrl */
        $sourceUrl = $media->sdmMediaGetSdmMediaSourceUrl();

        /* Unpack SdmMediaSourceName */
        $sourceName = $media->sdmMediaGetSdmMediaSourceName();

        /* Unpack SdmMediaSourceExtension */
        $sourceExtension = $media->sdmMediaGetSdmMediaSourceExtension();

        /* Unpack SdmMediaSourceType */
        $sourceType = $media->sdmMediaGetSdmMediaSourceType();

        /* Assemble $src from media source url, source name, and source extension based on source type. */
        switch ($sourceType) {
            case 'local':
                $src = $sourceUrl . '/' . rawurlencode($sourceName . '.' . $sourceExtension);
                break;
            case 'external':
                $src = $sourceUrl;
                break;
            default:
                break;
        }
        /* Assemble element html for the appropriate type. */
        switch ($type) {
            case 'audio':
            case 'video':
                /* Build audio/video element html. */
                $mediaElementHtml = "<$type id='$machineName' controls><source src='$src'>Your browser does not support the HTML5 $type tag.</$type>";
                break;
            case 'image':
                /* Build image element html */
                $mediaElementHtml = "<img id='$machineName' src='$src'>";
                break;
            case 'canvas':
                /* Build canvas element html. */
                $mediaElementHtml = "<canvas id='$machineName'>Your browser does not support the HTML5 canvas tag.</canvas>";

                /* Canvas scripts must be loaded after canvas tag is declared. */
                $mediaElementHtml .= "<script src='$src'></script>";
                break;
            case 'youtube':
                /* Build youtube element html */
                $mediaElementHtml = "<iframe src='$src'></iframe>";
                break;
            default:
                error_log('SdmMedia object with id "' . $id . '" was assigned media type "' . $type . '
                " which is not supported by the SdmMedia class. Error occurred when SdmMedia object was
                passed to sdmMediaDisplayBuildMediaElementHtml(). Supported types are: audio, video, image,
                and canvas. The SdmMedia objects machine name is ' . $machineName);
                break;
        }

        /* Count initial number of sdmMediaGetSdmMediaDisplayMediaElementsHtml items */
        $initialElements = count($this->sdmMediaGetSdmMediaDisplayMediaElementsHtml());

        /* Add media element html to the sdmMediaDisplayMediaElementsHtml */
        $this->sdmMediaDisplayMediaElementsHtml[$machineName] = $mediaElementHtml;

        /* Count new number of sdmMediaGetSdmMediaDisplayMediaElementsHtml items */
        $newElements = count($this->sdmMediaGetSdmMediaDisplayMediaElementsHtml());

        /* If number of new elements is greater then the initial number of elements in the
           sdmMediaGetSdmMediaDisplayMediaElementsHtml array then SdmMedia object html was
           constructed successfully, otherwise something went wrong.
        */
        $status = ($newElements > $initialElements ? true : false);

        return $status;

    }

    /**
     * Returns the SdmMediaDisplayMediaElementsHtml array.
     * @return array The SdmMediaDisplayMediaElementsHtml array which holds
     *               the html for the SdmMedia objects.
     */
    public function sdmMediaGetSdmMediaDisplayMediaElementsHtml()
    {
        return $this->sdmMediaDisplayMediaElementsHtml;
    }

    /**
     *
     * Loads a specified display's output options.
     *
     * @param $display string The name of the display to load output options for.
     *
     * @return array An array representing the output options for the display.
     *
     */
    public function loadDisplayOutputOptions($display)
    {
        /* Path to $display's data directory. */
        $pathToDisplaysData = $this->SdmCore->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data/' . $display . '/' . hash('sha256', $display) . '.json';

        /* Load and decode the displays json file. This file's name will be the hash of the display's name and will end with the .json extension. */
        $displayData = json_decode(file_get_contents($pathToDisplaysData));

        /* Load the output options for the display, cast to array so structure conforms to the structure expected by the SdmAssembler(). */
        $this->outputOptions = (array)$displayData->options;

        /* Return the displays output options. */
        return $this->outputOptions;
    }
}