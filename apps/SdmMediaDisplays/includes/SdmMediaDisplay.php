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

    /**
     * SdmMediaDisplay constructor. Initializes the sdmMediaDisplayMedia array.
     */
    final public function __construct()
    {
        /* Initialize the sdmMediaDisplayMedia array which will hold the Sdm Media objects
           for this Sdm Media Display. */
        $this->sdmMediaDisplayMedia = array();
        /* Initialize the sdmMediaDisplayMediaElementsHtml array which will hold the Sdm Media Element's html
           for this Sdm Media Display. Media element html is indexed by the relative SdmMedia object's
           sdmMediaMachineName prperty.  */
        $this->sdmMediaDisplayMediaElementsHtml = array();

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
        switch($sourceType) {
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
                $mediaElementHtml = "<$type id='$machineName' controls><source src='$src'>Your browser does not support the HTML5 $type tag.</$type>";
                /*
                <{type}>
                <source src="{src}" type="{type}/{ext}">
                <source src="{src}" type="{type}/{ext}">
                Your browser does not support the HTML5 {type} tag. The {type} cannot be played.
                </{type}>
                */
                break;
            case 'image':
                $mediaElementHtml = "<img id='$machineName' src='$src'>";
                // <{type === image ? img} {src} {attributes}>
                break;
            case 'canvas':
                $mediaElementHtml = "<canvas id='$machineName'>Your browser does not support the HTML5 canvas tag.</canvas>";
                // <{type} {'attributes'}>Your browser does not support the HTML5 {type} tag.</{type}>
                break;
            case 'youtube':
                // @todo support this type, requires the use of an iframe whose src is the youtube media url
                break;
            default:
                error_log('SdmMedia object with id "' . $id . '" was assigned media type "' . $type . '
                " which is not supported by the SdmMedia class. Error occurred when SdmMedia object was
                passed to sdmMediaDisplayBuildMediaElementHtml(). Supported types are: audio, video, image,
                and canvas. The SdmMedia objects machine name is ' . $machineName);
                break;
        }

        /* Add media element html to the sdmMediaDisplayMediaElementsHtml */
        $this->sdmMediaDisplayMediaElementsHtml[$machineName] = $mediaElementHtml;
    }

}