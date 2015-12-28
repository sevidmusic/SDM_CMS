<!-- Run this file to configure a new SDM CMS site or to reset a site to it's default state. -->
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
<!-- row 1 -->
<div class="row row-min-wid-fix padded-row">
    <div id="main_content" class="col-12 col-m-12 rounded">
        <h1>SDM CMS</h1>
        <?php
        /** require SdmCore to allow reset.php to utilize core components */
        require(__DIR__ . '/core/includes/SdmCore.php');
        /** require SdmNms to allow reset.php to utilize menu CRUD components */
        require(__DIR__ . '/core/includes/SdmNms.php');
        /** initialize a SdmCore() object for reset.php to use */
        $sdmcore = new SdmCore();
        /** Check that the sdm and logs directories exists | on a new installation they may or may not exists so we need to create them if they do not */
        if (!file_exists(__DIR__ . '/core/sdm')) {
            mkdir(__DIR__ . '/core/sdm');
        }
        if (!file_exists(__DIR__ . '/core/logs')) {
            mkdir(__DIR__ . '/core/logs');
        }
        /** Cleanup any old logs and session data that may exist */
        require(__DIR__ . '/core/config/configCleanup.php');
        /** Setup default menus | this must be done BEFORE setting up default data or default menus would be excluded fromt he default data */
        require(__DIR__ . '/core/config/defaultMenuConfig.php');
        /** Setup default data */
        require(__DIR__ . '/core/config/defaultDataConfig.php');
        /** Display Configuration Status */
        echo '<p>Below is an overview of the sites current configuration:</p>';
        /** Display Core Paths and Urls */
        $paths = array(
            'Root Directory' => $sdmcore->sdmCoreGetRootDirectoryPath(),
            'Root Url' => $sdmcore->sdmCoreGetRootDirectoryUrl(),
            'Core Directory' => $sdmcore->sdmCoreGetCoreDirectoryPath(),
            'Core Url' => $sdmcore->sdmCoreGetCoreDirectoryUrl(),
            'Configuration Directory' => $sdmcore->sdmCoreGetConfiguratonDirectoryPath(),
            'Configuration Url' => 'NO METHOD FOR PATH RETRIVAL',
            'Includes Directory' => $sdmcore->sdmCoreGetIncludesDirectoryPath(),
            'Themes Directory' => $sdmcore->sdmCoreGetThemesDirectoryPath(),
            'Themes Url' => $sdmcore->sdmCoreGetThemesDirectoryUrl(),
            'Current Theme Directory' => $sdmcore->sdmCoreGetCurrentThemeDirectoryPath(),
            'Current Theme Url' => $sdmcore->sdmCoreGetCurrentThemeDirectoryUrl(),
            'User Apps Directory' => $sdmcore->sdmCoreGetUserAppDirectoryPath(),
            'User Apps Url' => $sdmcore->sdmCoreGetUserAppDirectoryUrl(),
            'Core Apps Directory' => $sdmcore->sdmCoreGetCoreAppDirectoryPath(),
            'Core App Url' => $sdmcore->sdmCoreGetCoreAppDirectoryUrl(),
            'Data Directory Path' => $sdmcore->sdmCoreGetDataDirectoryPath(),
            'Data Directory Url' => $sdmcore->sdmCoreGetDataDirectoryUrl(),
        );
        echo '<h3>Site Paths</h3>';
        $sdmcore->sdmCoreSdmReadArray($paths);
        /** Display Current Theme */
        echo '<h3>Site Default Theme:</h3>';
        $sdmcore->sdmCoreSdmReadArray(array('Current Theme' => $sdmcore->sdmCoreDetermineCurrentTheme()));
        /** Display List Of Available Apps */
        echo '<h3>Available Apps:</h3><p>(these apps are <b>not</b> necessarily enabled, you can enable/disable apps by logging in and using the content manager app which is enabled by default)</p>';
        $coreapps = $sdmcore->sdmCoreGetDirectoryListing('', 'coreapps');
        $userapps = $sdmcore->sdmCoreGetDirectoryListing('', 'userapps');
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
        $sdmcore->sdmCoreSdmReadArray($apps);
        /** Display Enabled Apps */
        echo '<h3>Enabled Apps:</h3>';
        $sdmcore->sdmCoreSdmReadArray($sdmcore->sdmCoreDetermineEnabledApps());
        ?>
    </div>
</div>
</body>
</html>