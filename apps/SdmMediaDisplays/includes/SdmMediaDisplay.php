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
                $this->sdmMediaDisplayMedia = array();

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
         * Adds an SdmMedia object to the display.
         * @param SdmMedia $media
         * @return bool
         */
        final public function sdmMediaDisplayAddMediaObject($media)
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

                /* Return $status. */
                return $status;
        }

}