<?php

echo '<div style="background:#DDDDDD;width:75%;border:2px solid #CCCCCC;border-radius:7px;margin:0 auto;padding:20px;">';
echo '<h1>SDM CMS</h1>';
require(__DIR__ . '/core/config/startup.php');
/**
 * Run this file to configure a new site.
 */
$config = array(
    'content' => array(
        'homepage' => array(
            'main_content' => 'Welcome To The SDM CMS',
        ),
    ),
    'menus' => array(), // end 'menus' array
    'settings' => array(
        'theme' => 'sdmDemoTheme1',
        'enabledapps' => array('contentManager' => 'contentManager', 'SdmDevMenu' => 'SdmDevMenu'),
    ), // end 'settings' array
); // end $config array
$data = utf8_encode(trim(json_encode($config)));
echo (file_put_contents($sdmcore->sdmCoreGetDataDirectoryPath() . '/data.json', $data, LOCK_EX) != FALSE ? '<h4 style="color:#33CC33">Site configuration reset to defaults succsessfully</h4><p><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage">Click Here</a> to view your new SDM CMS powered site</p>' : '<h2 style="color:red;">Could not configure site!Check config.php to determine the cause of the error.</h2>');
echo '<h3>Site Configuration:</h3><p>The following data was written to: <b style="color:#999999"><i>' . $sdmcore->sdmCoreGetDataDirectoryPath() . '/data.json</i></b></p>';
echo '<p>The site\'s root URL is : ' . '<b style="color:#999999"><i>' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '</i></b>';
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
// reset error log
file_put_contents($sdmcore->sdmCoreGetCoreDirectoryPath() . '/logs/sdm_core_errors.log', '', LOCK_EX);
echo 'An empty error log was created to track site errors. You can view the error log <a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=errors">HERE</a>';
echo '</div>';
?>
