<?php

/**
 * This file is used by reset.php to setup default content.
 */

/* Homepage */
$homepageMainContent = '
<p>Welcome to your new SDM CMS powered site. To start adding content check out the
<a href="' . $sdmGatekeeper->sdmCoreGetRootDirectoryUrl() . '/index.php?page=contentManager">content manager</a></p>
<p>The SDM CMS is a unique JSON driven CMS. Still in development, this CMS is designed to be easy to use, and easy to
develop with. The user interface provided by the core apps makes it easy to build a site without writing a single
line of code, and for the more hands on users, the SDM CMS can easily be customized and extended via the development
of custom themes and user apps.</p>
<p>The SDM CMS uses json to store site data, i.e., in place of a database a json file is used
to store site data. Json was chosen because it is highly portable, is highly used,
and because it is highly compatible with javascript, and other languages, and many
languages like PHP have built in functions and methods for interacting with json.</p>
<p>@todo: I am planning on developing a core app that will come packaged with the SDM CMS
that will allow admin to switch to a database for storage. The supported
databases at first will most likely be MySql and SQLite, also considering Mongo DB.</p>
<p>Im currently working on the documentation for the SDM CMS, and as soon as
I finish checking for typos and insuring clarity the documentation will
be available.</p>
<p>I built this CMS out of a love for coding, particularly in PHP. I had worked with other CMS\'s
like Drupal, and WordPress, and wanted a simpler more portable CMS that was easier to grasp
under the hood. I also wanted to get a better understanding of how a CMS works, and figured
why not dive in and learn from experience.</p>
<p>I am very passionate about this project, and will continue to develop and improve the SDM CMS.</p>
<p>Thank you for trying out the Sdm Cms.</p>
<p>Sevi Donnelly Foreman</p>
';

/* Sdm Cms Documentation */
$sdmCmsDocumentationMainContent = '
    <p>This page provides basic information about the Sdm Cms and it\'s components. At some point
    it will revised to be much more descriptive of the SDM CMS and it\'s components. For now
    this is a general overview.</p>
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
        'requiredApps' => new stdClass(),
        'iniSettings' => array(
            /*'error_reporting' => array(
                E_ALL,
                E_STRICT,
                E_NOTICE,
            ),*/
            'log_errors' => true,
            'error_log' => $sdmGatekeeper->sdmCoreGetCoreDirectoryPath() . '/logs/sdm_core_errors.log',
            'display_errors' => false,
            'auto_detect_line_endings' => true,
            'session.use_trans_sid' => false,
            'session.use_only_cookies' => true,
            'session.hash_function' => 'sha512',
            'session.hash_bits_per_character' => 6,
            'session.gc_maxlifetime' => array_product([20, 60]),
            'session.gc_probability' => 2,
            'session.gc_divisor' => 100,
            'date.timezone' => 'America/New_York',
        ),
    ),

    /* Menus | @see "core/config/defaultMenuConfig.php" for default menu configuration */
    'menus' => $menus,
);

/* Encode data as json and then encode the json strings as utf-8 to prepare core data for storage in data.json */
$data = utf8_encode(trim(json_encode($config)));

/* Store core data in data.json. */
$dataStored = file_put_contents($sdmGatekeeper->sdmCoreGetDataDirectoryPath() . '/data.json', $data, LOCK_EX);

/* Display message indicating weather or not core data was written to data.json successfully. */
$successMsg = '<h4 style="color:#00FF7F">Site configuration reset to defaults successfully</h4><p><a href="' . $sdmGatekeeper->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage">Click Here</a> to view your new SDM CMS powered site.</p>';
$failureMsg = '<h4 style="color:#FF0000">Error: Core data could not be written to data.json. Could not configure site!</h4>';
echo($dataStored !== false ? $successMsg : $failureMsg);
