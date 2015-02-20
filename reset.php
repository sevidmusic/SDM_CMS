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
    /* Working on makeing it unnecessary to require pages for apps.
     * If you cant find a solution, then continue to make setting up
     * core app pages part of the reset.php file.
     *
     *
      'contentManager' => array(
      'main_content' => 'Manage Content',
      ),
      'contentManagerAddContentForm' => array(
      'main_content' => 'Add Content',
      ),
      'contentManagerSelectPageToEditForm' => array(
      'main_content' => 'Select Page To Edit',
      ),
      'contentManagerEditContentForm' => array(
      'main_content' => 'Edit Content',
      ),
      'contentManagerSelectThemeForm' => array(
      'main_content' => 'Select Site Theme',
      ),
      'contentManagerSelectThemeFormSubmission' => array(
      'main_content' => 'Theme Selected',
      ),
      'contentManagerSelectPageToDeleteForm' => array(
      'main_content' => 'Delete Page',
      ),
      'contentManagerDeletePageSubmission' => array(
      'main_content' => 'Page Deleted',
      ),
      'contentManagerUpdateContentFormSubmission' => array(
      'main_content' => 'Content Updated',
      ),
      'contentManagerAdministerAppsForm' => array(
      'main_content' => 'Administer Apps',
      ),
      'contentManagerAdministerAppsFormSubmission' => array(
      'main_content' => 'Administer Apps Form Submittied',
      ),
      'contentManagerAdministerAppsFormSubmission' => array(
      'main_content' => 'Administer Apps Form Submittied',
      ),
      'navigationManager' => array(
      'main_content' => 'Navigation Manager',
      ),
      'navigationManagerAddMenu' => array(
      'main_content' => 'Add Menu. Note: At the moment internally generated dev menus are only possible. The menu system will soon be complete, and you will be able to add and congifure menus custom to your needs.',
      ),
      'core' => array(
      'main_content' => 'Overview of SDM Core.',
      ),
      'errors' => array(
      'main_content' => 'Overview of recent site errors.',
      ), */
    ),
    'menus' => array(), // end 'menus' array
    'settings' => array(
        'theme' => 'sdmDemoTheme1',
        'enabledapps' => array('contentManager' => 'contentManager', 'navigationManager' => 'navigationManager', 'SDMDevTools' => 'SDMDevTools'),
    ), // end 'settings' array
); // end $config array


$data = utf8_encode(trim(json_encode($config)));
echo (file_put_contents($sdmcore->getDataDirectoryPath() . '/data.json', $data, LOCK_EX) != FALSE ? '<h4 style="color:#33CC33">Site configuration reset to defaults succsessfully</h4><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=homepage">Click Here</a> to view your new SDM CMS powered site</p>' : '<h2 style="color:red;">Could not configure site!Check config.php to determine the cause of the error.</h2>');
echo '<h3>Site Configuration:</h3><p>The following data was written to: <b style="color:#999999"><i>' . $sdmcore->getDataDirectoryPath() . '/data.json</i></b></p>';
echo '<p>The site\'s root URL is : ' . '<b style="color:#999999"><i>' . $sdmcore->getRootDirectoryUrl() . '</i></b>';
$sdmcore->sdm_read_array($config);
echo '<h3>SDM Core Configuration</h3>';
$sdmcore->sdm_read_array($sdmcore);
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
$sdmcore->sdm_read_array($apps);
// reset error log
file_put_contents($sdmcore->getCoreDirectoryPath() . '/logs/sdm_core_errors.log', '', LOCK_EX);
echo 'An empty error log was created to track site errors. You can view the error log <a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=errors">HERE</a>';
echo '</div>';
?>
