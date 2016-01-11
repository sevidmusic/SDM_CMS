<?php

/* Initialize default content */

/* Homepage */
$homepageMainContent = '<p>Welcome to your new SDM CMS powered site. To start adding content check out the <a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=contentManager">content manager</a></p>';

/* Sdm Cms Documentation */
$sdmCmsDocumentationMainContent = '
    <p>This page provides basic information about the Sdm Cms and it\'s components.</p>
    <h3>Sdm Core :</h3>
    <p>Responsible for determining core paths and provides methods for interacting with core.</p>
    <h3>Sdm Assembler:</h3>
    <p>Responsible for assembling a page.</p>
    <h3>Sdm Nms:</h3>
    <p>Responsible for handling navigation components, including building the HTML for menus and sets of menu items.</p>
    <h3>Sdm Cms:</h3><p>Responsible for handling content CRUD including the enabling/disabling of core and user apps</p>
    <h3>Sdm Form:</h3><p>Responsible for building forms and retrieving submitted form data.</p>
    <h3>Sdm Gatekeeper</h3><p>Responsible for security.</p>
    ';

/* Define default configuration. */
$config = array(
    /* Content */
    'content' => array(
        'homepage' => array(
            'main_content' => $homepageMainContent,
        ),
        'Sdm Cms Documentation' => array(
            'main_content' => $sdmCmsDocumentationMainContent,
        ),
    ),
    /* Settings */
    'settings' => array(
        'theme' => 'sdmResponsive',
        'enabledapps' => array(
            'contentManager' => 'contentManager',
            'navigationManager' => 'navigationManager',
            'SdmAuth' => 'SdmAuth',
            'SdmCoreOverview' => 'SdmCoreOverview',
            'SdmDevMenu' => 'SdmDevMenu',
            'SdmErrorLog' => 'SdmErrorLog',
        ),
    ),
    /* Menus | @see "core/config/defaultMenuConfig.php" for default menu configuration */
    'menus' => $menus,
);

/* Encode data as json and then encode the json strings as utf-8 to prepare core data for storage in data.json */
$data = utf8_encode(trim(json_encode($config)));

/* Store core data in data.json. */
$dataStored = file_put_contents($sdmcore->sdmCoreGetDataDirectoryPath() . '/data.json', $data, LOCK_EX);

/* Display message indicating weather or not core data was written to data.json successfully. */
$successMsg = '<h4 style="color:#00FF7F">Site configuration reset to defaults successfully</h4><p><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage">Click Here</a> to view your new SDM CMS powered site.</p>';
$failureMsg = '<h4 style="color:#FF0000">Error: Core data could not be written to data.json. Could not configure site!</h4>';
echo($dataStored !== false ? $successMsg : $failureMsg);
