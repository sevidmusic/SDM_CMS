<!-- Run this file to configure a new SDM CMS site or to reset a site to it's default state. -->
<!DOCTYPE html>
<html>
    <head>
        <title>Reset Sdm Cms</title>
        <link rel="stylesheet" href="themes/sdmResponsive/css/layout.css">
        <link rel="stylesheet" href="themes/sdmResponsive/css/wrappers.css">
        <link rel="stylesheet" href="themes/sdmResponsive/css/style-classes.css">
        <link rel="stylesheet" href="themes/sdmResponsive/css/menus.css">
        <link rel="stylesheet" href="themes/sdmResponsive/css/forms.css">
    </head>
    <body class="sdmResponsive">
    <!-- row 1 -->
    <div class="row row-min-wid-fix padded-row">
        <div id="main_content"class="<?php echo ($sideBarExists === true ? 'col-8 col-m-8' : 'col-12 col-m-12'); ?> rounded">
            <h1>SDM CMS</h1>
            <?php
            /** require SdmCore to allow reset.php to utilize core components */
            require(__DIR__ . '/core/includes/SdmCore.php');
            /** require SdmNms to allow reset.php to utilize menu CRUD components */
            require(__DIR__ . '/core/includes/SdmNms.php');
            /** initialize a SdmCore() object for reset.php to use */
            $sdmcore = new SdmCore();
            /** Check that the sdm and logs directories exists | on a new installation they may or may not exists so we need to create them if they do not */
            if(!file_exists(__DIR__ . '/core/sdm')) {
                mkdir(__DIR__ . '/core/sdm');
            }
            if(!file_exists(__DIR__ . '/core/logs')) {
                mkdir(__DIR__ . '/core/logs');
            }
            /** Cleanup any old logs and session data that may exist */
            require(__DIR__ . '/core/config/configCleanup.php');
            /** Setup default menus | this must be done BEFORE setting up default data or default menus would be excluded fromt he default data */
            require(__DIR__ . '/core/config/defaultMenuConfig.php');
            /** Setup default data */
            require(__DIR__ . '/core/config/defaultDataConfig.php');
            /** Display Configuration Status */
            echo '<h3>Site Configuration:</h3><p>The following data was written to: <b style="color:#00FF99"><i>' . $sdmcore->sdmCoreGetDataDirectoryPath() . '/data.json</i></b></p>';
            echo '<p>The site\'s root URL is : ' . '<b style="color:#00FF99"><i>' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '</i></b>';
            $sdmcore->sdmCoreSdmReadArray($config);
            echo '<h3>SDM Core Configuration</h3>';
            $sdmcore->sdmCoreSdmReadArray($sdmcore);
            echo '<h3>Available Apps</h3><p>(these apps are <b>not</b> necessarily enabled)</p>';
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
            ?>        </div>
    </div>
    </body>
</html>