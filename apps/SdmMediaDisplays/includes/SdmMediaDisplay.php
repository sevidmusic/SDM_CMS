<?php

/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 6:22 PM
 */
class SdmMediaDisplay extends SdmMedia
{
    /** @var  $sdmMediaDisplayMedia array Array of Sdm Media objects for this display. */
    private $sdmMediaDisplayMedia;

    /** @var  $sdmMediaDisplayMediaElementsHtml array Array of media element html indexed by sdmMediaMachineName */
    private $sdmMediaDisplayMediaElementsHtml;

    /** @var  $sdmMediaDisplayTemplate string
     * The name of the template file for this display excluding the file extension.
     */
    private $sdmMediaDisplayTemplate;

    /**
     * SdmMediaDisplay constructor. Initializes the sdmMediaDisplayMedia array.
     */
    final public function __construct($SdmMediaDisplayTemplate = 'SdmMediaDisplayDefaultTemplate')
    {
        /* Initialize the sdmMediaDisplayMedia array which will hold the Sdm Media objects
           for this Sdm Media Display. */
        $this->sdmMediaDisplayMedia = array();
        /* Initialize the sdmMediaDisplayMediaElementsHtml array which will hold the Sdm Media Element's html
           for this Sdm Media Display. Media element html is indexed by the relative SdmMedia object's
           sdmMediaMachineName property.  */
        $this->sdmMediaDisplayMediaElementsHtml = array();

        /* Assign Sdm Media Display template, default template will be used. */
        $this->sdmMediaDisplayTemplate = $SdmMediaDisplayTemplate;

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
     */
    public function sdmMediaDisplayBuildMediaDisplay()
    {
        $orderedMedia = array();
        $mediaElementsHtml = $this->sdmMediaDisplayMediaElementsHtml;
        /* Order SdmMedia objects by category and place */
        foreach ($this->sdmMediaDisplayMedia as $index => $mediaObject) {
            $orderedMedia[$mediaObject->sdmMediaCategory][$mediaObject->sdmMediaPlace][$mediaObject->sdmMediaDisplayName] = $mediaElementsHtml[$mediaObject->sdmMediaMachineName];
        }
        return $orderedMedia;
    }


}