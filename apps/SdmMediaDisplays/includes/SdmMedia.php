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
class SdmMedia implements JsonSerializable
{
    /** @var $sdmMediaDisplayName string
     * The name to use in displays for the Sdm Media object. Could be the name of the song the Sdm Media object
     * represents, or the title of a movie, etc.
     */
    protected $sdmMediaDisplayName;
    /** @var $sdmMediaType string The type of media the Sdm Media object represents.
     * Supported types are: audio, video, youtube, image, and canvas
     */
    protected $sdmMediaType;
    /** @var $sdmMediaId int A randomly generated unique id for the Sdm Media object. */
    protected $sdmMediaId;
    /** @var
     * A unique machine name that can be used as an alternative id to the $sdmMediaId.
     * Unlike the $sdmMediaId, the $sdmMediaMachineName can contain alpha-numeric characters,
     * not just integers.
     */
    protected $sdmMediaMachineName;
    /** @var $sdmMediaSourceName string
     * The name of the media source file. (NOTE: Do not include the file extension, just the
     * name of the file. */
    protected $sdmMediaSourceName;
    /** @var $sdmMediaSourceExtension string The file extension of the media source file. */
    protected $sdmMediaSourceExtension;
    /** @var $sdmMediaSourceUrl string
     * The url to the location of the media source file.
     * e.g., http://www.example.com/path/to/media/source/file
     * (NOTE: Do not include the media source file's name or extension,
     * the url should point to the location of the media source file,
     * not the media source file itself.)
     */
    protected $sdmMediaSourceUrl;
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
    protected $sdmMediaSourcePath;
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
    protected $sdmMediaSourceType;
    /** @var $sdmMediaProtected bool
     * If set to true, then any Sdm Media Display object this Sdm Media object is handled
     * by will attempt to protect the media this Sdm Media object represents from download.
     * If set to false, then any Sdm Media Display object this Sdm Media object is handled
     * by will not make an attempt to protect the media represents from download.
     */
    protected $sdmMediaProtected;
    /** @var $sdmMediaPublic bool
     * If set to true, then any Sdm Media Display object that handles this Sdm Media object
     * will only display the object in private Sdm Media Display views.
     *
     * If set to false, then any Sdm Media Display object that handles this Sdm Media object
     * will display this Sdm Media object in public and private Sdm Media Display views.
     */
    protected $sdmMediaPublic;
    /** @var $sdmMediaPlace int An integer that will be used to determine this SdmMedia objects place in
     *       the SdmMediaDisplay.
     */
    protected $sdmMediaPlace;
    /** @var  $sdmMediaCategory string The name of the category to sort this SdmMedia object by in the SdmMediaDisplay. */
    protected $sdmMediaCategory;

    /**
     * @param mixed $sdmMediaMachineName
     */
    public function sdmMediaSetMachineName($sdmMediaMachineName)
    {
        $this->sdmMediaMachineName = $sdmMediaMachineName;
    }

    /**
     * @param string $sdmMediaSourceName
     */
    public function sdmMediaSetSourceName($sdmMediaSourceName)
    {
        $this->sdmMediaSourceName = $sdmMediaSourceName;
    }

    /**
     * @param string $sdmMediaSourceUrl
     */
    public function sdmMediaSetSourceUrl($sdmMediaSourceUrl)
    {
        $this->sdmMediaSourceUrl = $sdmMediaSourceUrl;
    }

    /**
     * @param string $sdmMediaSourcePath
     */
    public function sdmMediaSetSourcePath($sdmMediaSourcePath)
    {
        $this->sdmMediaSourcePath = $sdmMediaSourcePath;
    }

    /**
     * @param string $sdmMediaSetId
     */
    public function sdmMediaSetId($sdmMediaId)
    {
        $this->sdmMediaId = $sdmMediaId;
    }

    /**
     * @param string $sdmMediaSourceExtension
     */
    public function sdmMediaSetSourceExtension($sdmMediaSourceExtension)
    {
        $this->sdmMediaSourceExtension = $sdmMediaSourceExtension;
    }

    /**
     * @return int
     */
    public function sdmMediaGetSdmMediaPlace()
    {
        return $this->sdmMediaPlace;
    }

    /**
     * @return string
     */
    public function sdmMediaGetSdmMediaCategory()
    {
        return $this->sdmMediaCategory;
    }

    /**
     * @return string
     */
    public function sdmMediaGetSdmMediaDisplayName()
    {
        return $this->sdmMediaDisplayName;
    }

    /**
     * @return string
     */
    public function sdmMediaGetSdmMediaType()
    {
        return $this->sdmMediaType;
    }

    /**
     * @return int
     */
    public function sdmMediaGetSdmMediaId()
    {
        return $this->sdmMediaId;
    }

    /**
     * @return mixed
     */
    public function sdmMediaGetSdmMediaMachineName()
    {
        return $this->sdmMediaMachineName;
    }

    /**
     * @return string
     */
    public function sdmMediaGetSdmMediaSourceName()
    {
        return $this->sdmMediaSourceName;
    }

    /**
     * @return string
     */
    public function sdmMediaGetSdmMediaSourceExtension()
    {
        return $this->sdmMediaSourceExtension;
    }

    /**
     * @return string
     */
    public function sdmMediaGetSdmMediaSourceUrl()
    {
        return $this->sdmMediaSourceUrl;
    }

    /**
     * @return string
     */
    public function sdmMediaGetSdmMediaSourcePath()
    {
        return $this->sdmMediaSourcePath;
    }

    /**
     * @return string
     */
    public function sdmMediaGetSdmMediaSourceType()
    {
        return $this->sdmMediaSourceType;
    }

    /**
     * @return boolean
     */
    public function sdmMediaIsProtected()
    {
        return $this->sdmMediaProtected;
    }

    /**
     * @return boolean
     */
    public function sdmMediaIsPublic()
    {
        return $this->sdmMediaPublic;
    }

    public function sdmMediaCreateMediaObject($properties)
    {
        $sdmMediaObject = new self();
        /* Media Display Id | . */
        $sdmMediaObject->sdmMediaId = (isset($properties['sdmMediaId']) === true ? $properties['sdmMediaId'] : rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999));
        /* Media Display Name | displayName */
        $sdmMediaObject->sdmMediaDisplayName = (isset($properties['sdmMediaDisplayName']) === true ? $properties['sdmMediaDisplayName'] : null);
        /* Media Type | mediaType */
        $sdmMediaObject->sdmMediaType = (isset($properties['sdmMediaType']) === true ? $properties['sdmMediaType'] : null);
        /* Media Machine Name | machineName */
        $sdmMediaObject->sdmMediaMachineName = (isset($properties['sdmMediaMachineName']) === true ? $properties['sdmMediaMachineName'] : null); // @todo: generate random alphanumeric id if not set
        /* Media Source Name | sourceName */
        $sdmMediaObject->sdmMediaSourceName = (isset($properties['sdmMediaSourceName']) === true ? $properties['sdmMediaSourceName'] : null);
        /* Media Source Extension |  */
        $sdmMediaObject->sdmMediaSourceExtension = (isset($properties['sdmMediaSourceExtension']) ? $properties['sdmMediaSourceExtension'] : null);
        /* Media Src Url | srcUrl */
        $sdmMediaObject->sdmMediaSourceUrl = (isset($properties['sdmMediaSourceUrl']) === true ? $properties['sdmMediaSourceUrl'] : null);
        /* Media Src Path | srcPath */
        $sdmMediaObject->sdmMediaSourcePath = (isset($properties['sdmMediaSourcePath']) === true ? $properties['sdmMediaSourcePath'] : null);
        /* Source Type | srcType */
        $sdmMediaObject->sdmMediaSourceType = (isset($properties['sdmMediaSourceType']) === true ? $properties['sdmMediaSourceType'] : null);
        /* Media Protected | protected */
        $sdmMediaObject->sdmMediaProtected = (isset($properties['sdmMediaProtected']) === true ? $properties['sdmMediaProtected'] : true);
        /* Media Public | public */
        $sdmMediaObject->sdmMediaPublic = (isset($properties['sdmMediaPublic']) === true ? $properties['sdmMediaPublic'] : false);

        /* Media Place | place */
        $sdmMediaObject->sdmMediaPlace = (isset($properties['sdmMediaPlace']) === true ? $properties['sdmMediaPlace'] : 0);

        /* Media Category | category */
        $sdmMediaObject->sdmMediaCategory = (isset($properties['sdmMediaCategory']) === true ? $properties['sdmMediaCategory'] : 'default');

        /* Return the SdmMedia object. */
        return $sdmMediaObject;
    }

    public function jsonSerialize()
    {
        return [
            'sdmMediaId' => $this->sdmMediaId,
            'sdmMediaMachineName' => $this->sdmMediaMachineName,
            'sdmMediaDisplayName' => $this->sdmMediaDisplayName,
            'sdmMediaCategory' => $this->sdmMediaCategory,
            'sdmMediaId' => $this->sdmMediaId,
            'sdmMediaPlace' => $this->sdmMediaPlace,
            'sdmMediaPublic' => $this->sdmMediaPublic,
            'sdmMediaProtected' => $this->sdmMediaProtected,
            'sdmMediaSourceType' => $this->sdmMediaSourceType,
            'sdmMediaSourceName' => $this->sdmMediaSourceName,
            'sdmMediaSourceExtension' => $this->sdmMediaSourceExtension,
            'sdmMediaSourcePath' => $this->sdmMediaSourcePath,
            'sdmMediaSourceUrl' => $this->sdmMediaSourceUrl,
            'sdmMediaType' => $this->sdmMediaType,
        ];
    }

    /**
     * Get an array of the names of the Sdm Media class's property names.
     * @return array Returns the names of the SdmMedia() class's properties.
     */
    public function sdmMediaListMediaPropertyNames() {
        $properties = get_object_vars($this);
        $propertyNames = array();
        foreach($properties as $propertyName => $void) {
            $propertyNames[] = $propertyName;
        }
        return $propertyNames;
    }
}