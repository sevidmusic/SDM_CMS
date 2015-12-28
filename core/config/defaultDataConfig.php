<?php

/** Setup Configuration */
$config = array(/** This array defines the default configuration for a SDM CMS site | NOTE: Most of the array items will be converted to objects in order to allow them to be accsesed by object notation */
    'content' => array(
        'homepage' => array(
            'main_content' => 'Welcome To The SDM CMS',
        ),
        'Sdm Cms Documentation' => array(
            'main_content' => 'This page provides basic information about the Sdm Cms and it\'s components.<br /><br /><h3>Sdm Core :</h3> Responsible for determining core paths and provides methods for interacting with core. <br /><br /><h3>Sdm Assembler:</h3> Responsible for assembling a page. <br /><br /><h3>Sdm Nms:</h3> Responsible for handling navigation components, including building the HTML for menus and sets of menu items.<br /><br /><h3>Sdm Cms:</h3> Responsible for handling content CRUD including the enabling/disabling of core and user apps<br /><br /><h3>Sdm Form:</h3> Responsible for building forms and retrieving submitted form data.<br /><br /><h3>Sdm Gatekeeper</h3> Responsible for security.',
        ),
    ), // end content array
    'settings' => array(
        'theme' => 'sdmResponsive',
        'enabledapps' => array('contentManager' => 'contentManager', 'SdmErrorLog' => 'SdmErrorLog', 'navigationManager' => 'navigationManager', 'SdmCoreOverview' => 'SdmCoreOverview', 'SdmAuth' => 'SdmAuth', 'SdmDevMenu' => 'SdmDevMenu'),
    ), // end 'settings' array
    'menus' => $menus,
); // end $config array
$data = utf8_encode(trim(json_encode($config)));
echo (file_put_contents($sdmcore->sdmCoreGetDataDirectoryPath() . '/data.json', $data, LOCK_EX) != false ? '<h4 style="color:#00FF99">Site configuration reset to defaults successfully</h4><p><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage">Click Here</a> to view your new SDM CMS powered site</p>' : '<h2 style="color:red;">Could not configure site!Check config.php to determine the cause of the error.</h2>');