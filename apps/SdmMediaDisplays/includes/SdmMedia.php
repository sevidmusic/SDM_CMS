<?php

/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/4/16
 * Time: 8:40 AM
 */

/**
 * Class SdmMedia
 *
 * Defines the properties and methods for a SdmMedia object.
 **
 */
class SdmMedia
{
    /** @var $sdmMediaDisplayName string
     * The name to use in displays for the Sdm Media object. Could be the name of the song the Sdm Media object
     * represents, or the title of a movie, etc.
     */
    private $sdmMediaDisplayName;

    /** @var $sdmMediaType string The type of media the Sdm Media object represents.
     * Supported types are: sound, video, image, and canvas
     */
    private $sdmMediaType;

    /** @var $sdmMediaId int A randomly generated unique id for the Sdm Media object. */
    private $sdmMediaId;

    /** @var
     * A unique machine name that can be used as an alternative id to the $sdmMediaId.
     * Unlike the $sdmMediaId, the $sdmMediaMachineName can contain alpha-numeric characters,
     * not just integers.
     */
    private $sdmMediaMachineName;

    /** @var $sdmMediaSourceName string
     * The name of the media source file. (NOTE: Do not include the file extension, just the
     * name of the file. */
    private $sdmMediaSourceName;

    /** @var $sdmMediaSourceExtension string The file extension of the media source file. */
    private $sdmMediaSourceExtension;

    /** @var $sdmMediaSourceUrl string
     * The url to the location of the media source file.
     * e.g., http://www.example.com/path/to/media/source/file
     * (NOTE: Do not include the media source file's name or extension,
     * the url should point to the location of the media source file,
     * not the media source file itself.)
     */
    private $sdmMediaSourceUrl;

    /** @var  $sdmMediaSourcePath string
     * The path to the parent directory of the media source file. (only applies to local source types).
     * e.g., path/to/media/source/file
     *
     * NOTE: Do not include the media source file's name or extension, the path should point to the
     * parent directory of the media source file, not the media source file itself.
     *
     * NOTE: If source type is external then this property will be overwritten internally with the value
     * '_EXTERNAL_SDM_MEDIA_SOURCE_' which will indicate, to any SdmMediaDisplay() this SdmMedia object
     * is added to, that the $sdmMediaSourceUrl should always be used to load or reference the media for
     * this SdmMedia object.
     */
    private $sdmMediaSourcePath;

    /** @var $sdmMediaSourceType string
     * The type of source, either 'local' or 'external'. The 'local' type is for media sources that
     * exist locally on the site or the site's server. The 'external' type is for sources that exist
     * on other sites or servers.
     *
     * For instance:
     *
     * - If the source is a video that exists on your site www.YourSite.com/path/to/video and therefore
     *   also on the local server at a location like /SERVER/YourSite.com/path/to/video then type
     *   should be set to 'local'.
     *
     *
     * - If the source is a video link from another site, for instance a video on http://www.someOtherSite.com,
     *   then the 'external' type should be used.
     */
    private $sdmMediaSourceType;


    /** @var $sdmMediaProtected bool
     * If set to true, then any Sdm Media Display object this Sdm Media object is handled
     * by will attempt to protect the media this Sdm Media object represents from download.
     * If set to false, then any Sdm Media Display object this Sdm Media object is handled
     * by will not make an attempt to protect the media represents from download.
     */
    private $sdmMediaProtected;

    /** @var $sdmMediaPublic bool
     * If set to true, then any Sdm Media Display object that handles this Sdm Media object
     * will only display the object in private Sdm Media Display views.
     *
     * If set to false, then any Sdm Media Display object that handles this Sdm Media object
     * will display this Sdm Media object in public and private Sdm Media Display views.
     */
    private $sdmMediaPublic;


    public function sdmMediaCreateMediaObject($properties)
    {
        $sdmMediaObject = new self();
        /* Assign a random id to the Sdm Media object. */
        $sdmMediaObject->sdmMediaId = rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999);
        /* Media Display Name | displayName */
        $sdmMediaObject->sdmMediaDisplayName = (isset($properties['displayName']) === true ? $properties['displayName'] : null);
        /* Media Type | mediaType */
        $sdmMediaObject->sdmMediaType = (isset($properties['type']) === true ? $properties['type'] : null);
        /* Media Machine Name | machineName */
        $sdmMediaObject->sdmMediaMachineName = (isset($properties['machineName']) === true ? $properties['machineName'] : null); // @todo: generate random alphanumeric id if not set
        /* Media Source Name | sourceName */
        $sdmMediaObject->sdmMediaSourceName = (isset($properties['srcName']) === true ? $properties['srcName'] : null);
        /* Media Source Extension |  */
        $sdmMediaObject->sdmMediaSourceExtension = (isset($properties['srcExt']) ? $properties['srcExt'] : null);
        /* Media Src Url | srcUrl */
        $sdmMediaObject->sdmMediaSourceUrl = (isset($properties['srcUrl']) === true ? $properties['srcUrl'] : null);
        /* Media Src Path | srcPath */
        $sdmMediaObject->sdmMediaSourcePath = (isset($properties['srcPath']) === true ? $properties['srcPath'] : null);
        /* Source Type | srcType */
        $sdmMediaObject->sdmMediaSourceType = (isset($properties['srcType']) === true ? $properties['srcType'] : null);
        /* Media Protected | protected */
        $sdmMediaObject->sdmMediaProtected = (isset($properties['protected']) === true ? $properties['protected'] : true);
        /* Media Public | public */
        $sdmMediaObject->sdmMediaPublic = (isset($properties['public']) === true ? $properties['public'] : false);
        /* Return the SdmMedia object. */
        return $sdmMediaObject;
    }

    final public function sdmMediaObjectInfo()
    {
        return get_object_vars($this);
    }


}