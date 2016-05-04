<?php

/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/3/16
 * Time: 6:22 PM
 */
class SdmMediaDisplay
{
    /**
     *
     * sdmMediaDisplayId: Unique numeric id for this object.
     *
     * sdmMediaDisplayName: A name to identify this object. Name should be formatted for display.
     *
     * sdmMediaDisplayMachineName: An alternative unique id to assign to this object. This id can be non-numeric unlike the sdmMediaDisplayId.
     *
     * sdmMediaDisplayStylesheets: An array of the names of the stylesheets to load for this display.
     *
     * sdmMediaDisplayScripts: An array of the names of the scripts to load for this display.
     */
    private $sdmMediaDisplayId;
    private $sdmMediaDisplayName;
    private $sdmMediaDisplayMachineName;
    private $sdmMediaDisplayStylesheets;
    private $sdmMediaDisplayScripts;

    final public function __construct()
    {
        $sdmMediaDisplayId = 4567829876356783;
        $sdmMediaDisplayName = 'Test Display';
        $sdmMediaDisplayMachineName = 'test_display';
        $sdmMediaDisplayStylesheets = array('test_display');
        $sdmMediaDisplayScripts = array('test_display');
    }
}