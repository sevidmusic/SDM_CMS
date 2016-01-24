<?php
/**
 * Run this file to configure a new SDM CMS site or to reset a site to the SDM CMS default state.
 * WARNING:
 * DO NOT INCLUDE THIS FILE ON YOUR PRODUCTION SITE, IT IS HIGHLY DANGEROUS AND IT IS
 * BEST TO LEAVE IT OUT TO INSURE NO ONE GETS UNAUTHORIZED ACCESS TO IT. THE REASON
 * THIS FILE IS DANGEROUS IS BECAUSE IT CAN DELETE YOUR ENTIRE SITE. IT IS ONLY MEANT
 * TO BE RUN AFTER INITIAL INSTALL OF THE SDM CMS, OR BY ADMIN IF A CORRUPTED SITE NEEDS
 * TO BE RESET IN WHICH CASE THE ADMIN MUST BE SURE THEY HAVE A BACKUP OF THE VERSION
 * OF data.json, OR THE DATABASE THAT THE DESIRED SITE DATA IS STORED IN.
 *
 * REALLY THE BEST SOLUTION IS TO DISCARD THIS FILE AND WRITE YOUR OWN VERSION CONTROL APP
 * FOR THE SDM CMS, SOMETHING LIKE GIT BUT MUCH MORE LIGHT WEIGHT. THE SDM CMS IS MEANT
 * TO BE EXTENSIBLE SO WHY NOT EXTEND IT WITH A USER APP THAT FITS YOUR CUSTOM NEEDS.
 * AT SOME POINT SUCH AN APP MAY EVEN BE INCLUDED IN CORE.
 */

/**
 * Load Sdm Cms core classes.
 * @param $classes string The class to load.
 */

function __autoload($classes)
{
    $filename = $classes . '.php';
    include_once(__DIR__ . '/core/includes/' . $filename);
}

/* Instantiate an SdmGatekeeper() object for reset.php to use. We use the SdmGatekeeper()
 because we need to access some of the methods provided by the SdmGatekeeper(). Since
 the SdmGatekeeper() is a child of SdmCore() we will still have access to the core
 methods. */
$sdmGatekeeper = new SdmGatekeeper();

/* Attempt to login user. */
$sessStarted = $sdmGatekeeper->sessionStart();
$_SESSION['sdmauth'] = 'auth';
$_SESSION['userRole'] = 'root';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Sdm Cms</title>
    <!-- Use default themes stylesheets to style reset.php -->
    <link rel="stylesheet" href="themes/sdmResponsive/css/layout.css">
    <link rel="stylesheet" href="themes/sdmResponsive/css/wrappers.css">
    <link rel="stylesheet" href="themes/sdmResponsive/css/style-classes.css">
    <link rel="stylesheet" href="themes/sdmResponsive/css/menus.css">
    <link rel="stylesheet" href="themes/sdmResponsive/css/forms.css">
</head>
<body class="sdmResponsive">
<div class="row row-min-wid-fix padded-row">
    <div id="main_content" class="col-12 col-m-12 rounded">
        <h1>WELCOME TO THE SDM CMS!</h1>
        <?php

        /* Check that the sdm and logs directories exist. On new installations they may or may not exists
         and they need to be created if they do not exist. */
        if (!file_exists(__DIR__ . '/core/sdm')) {
            mkdir(__DIR__ . '/core/sdm');
        }

        if (!file_exists(__DIR__ . '/core/logs')) {
            mkdir(__DIR__ . '/core/logs');
        }

        /* Require configCleanup.php which cleans up any old logs and session data that may exist. */
        require(__DIR__ . '/core/config/configCleanup.php');

        /* Require defaultMenuConfig.php which configures default menus. Note: Default menus must be
        configured BEFORE setting up default data or else the default menus will be excluded from
        the default data. */
        require(__DIR__ . '/core/config/defaultMenuConfig.php');

        /* Configure default data. */
        require(__DIR__ . '/core/config/defaultDataConfig.php');

        /* Display overview of the default core configuration. */
        echo '<p>Below is an overview of the sites current configuration:</p>';
        $paths = array(
            'Root Directory' => $sdmGatekeeper->sdmCoreGetRootDirectoryPath(),
            'Root Url' => $sdmGatekeeper->sdmCoreGetRootDirectoryUrl(),
            'Core Directory' => $sdmGatekeeper->sdmCoreGetCoreDirectoryPath(),
            'Core Url' => $sdmGatekeeper->sdmCoreGetCoreDirectoryUrl(),
            'Configuration Directory' => $sdmGatekeeper->sdmCoreGetConfiguratonDirectoryPath(),
            'Configuration Url' => $sdmGatekeeper->sdmCoreGetConfiguratonDirectoryUrl(),
            'Includes Directory' => $sdmGatekeeper->sdmCoreGetIncludesDirectoryPath(),
            'Themes Directory' => $sdmGatekeeper->sdmCoreGetThemesDirectoryPath(),
            'Themes Url' => $sdmGatekeeper->sdmCoreGetThemesDirectoryUrl(),
            'Current Theme Directory' => $sdmGatekeeper->sdmCoreGetCurrentThemeDirectoryPath(),
            'Current Theme Url' => $sdmGatekeeper->sdmCoreGetCurrentThemeDirectoryUrl(),
            'User Apps Directory' => $sdmGatekeeper->sdmCoreGetUserAppDirectoryPath(),
            'User Apps Url' => $sdmGatekeeper->sdmCoreGetUserAppDirectoryUrl(),
            'Core Apps Directory' => $sdmGatekeeper->sdmCoreGetCoreAppDirectoryPath(),
            'Core App Url' => $sdmGatekeeper->sdmCoreGetCoreAppDirectoryUrl(),
            'Data Directory Path' => $sdmGatekeeper->sdmCoreGetDataDirectoryPath(),
            'Data Directory Url' => $sdmGatekeeper->sdmCoreGetDataDirectoryUrl(),
        );

        /* Display core paths and urls. */
        echo '<h3>Site Paths</h3>';
        $sdmGatekeeper->sdmCoreSdmReadArray($paths);

        /* Display current theme. */
        echo '<h3>Site Default Theme:</h3>';
        $sdmGatekeeper->sdmCoreSdmReadArray(array('Current Theme' => $sdmGatekeeper->sdmCoreDetermineCurrentTheme()));

        /* Display list of available apps. */
        echo '<h3>Available Apps:</h3><p>(these apps are <b>not</b> necessarily enabled, you can enable/disable apps by logging in and using the content manager app which is enabled by default)</p>';
        $coreapps = $sdmGatekeeper->sdmCoreGetDirectoryListing('', 'coreapps');
        $userapps = $sdmGatekeeper->sdmCoreGetDirectoryListing('', 'userapps');
        $apps = array();
        foreach ($coreapps as $value) {
            if ($value != '.' && $value != '..' && $value != '.DS_Store') {
                $apps[] = $value;
            }
        }
        foreach ($userapps as $value) {
            if ($value != '.' && $value != '..' && $value != '.DS_Store') {
                $apps[] = $value;
            }
        }
        $sdmGatekeeper->sdmCoreSdmReadArray($apps);

        /* Display list of currently enabled apps. */
        echo '<h3>Enabled Apps:</h3>';
        $sdmGatekeeper->sdmCoreSdmReadArray($sdmGatekeeper->sdmCoreDetermineEnabledApps());

        ?>
    </div>
</div>
</body>
</html>