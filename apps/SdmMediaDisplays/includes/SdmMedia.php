<?php

/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/4/16
 * Time: 8:40 AM
 */
class SdmMedia
{
    public $sdmMediaDisplayName;
    private $sdmMediaType;
    private $sdmMediaId;
    private $sdmMediaMachineName;
    private $sdmMediaSrcUrl;
    private $sdmMediaSrcPath;
    private $sdmMediaProtected;
    private $sdmMediaPublic;

    final public function __construct()
    {
        $sdmMediaDisplayName = 'Test Media';
        $sdmMediaType = 'test';
        $sdmMediaId = 2934892384293423498234239;
        $sdmMediaMachineName = 'test_media';
        $sdmMediaSrcUrl = 'http://localhost:8888/SdmCms/some/piece/of/media';
        $sdmMediaSrcPath = 'some/piece/of/media';
        $sdmMediaProtected = true;
        $sdmMediaPublic = false;
    }


}