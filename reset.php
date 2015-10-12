<?php

echo '<div style="background:#DDDDDD;width:75%;border:2px solid #CCCCCC;border-radius:7px;margin:0 auto;padding:20px;">';
echo '<h1>SDM CMS</h1>';
/** require SdmCore to allow reset.php to utilize core components */
require(__DIR__ . '/core/includes/SdmCore.php');
/** require SdmNms to allow reset.php to utilize menu CRUD components */
require(__DIR__ . '/core/includes/SdmNms.php');

$sdmcore = new SdmCore();
/**
 * Run this file to configure a new site.
 */
/** Delete any old session data */
$targets = scandir(session_save_path());
foreach ($targets as $sessfile) {
    unlink(session_save_path() . '/' . $sessfile);
}
/** Setup default menus for new site */
/** Main Menu */
$mainMenuItem1 = new SdmMenuItem();
$mainMenuItem1->arguments = array('defaultMenuItem=true', 'linkedBy=main_menu');
$mainMenuItem1->destination = 'homepage';
$mainMenuItem1->destinationType = 'internal';
$mainMenuItem1->menuItemCssClasses = array('homelink');
$mainMenuItem1->menuItemCssId = 'defaultMenuItem_homepage';
$mainMenuItem1->menuItemDisplayName = 'Homepage';
$mainMenuItem1->menuItemEnabled = '1';
$mainMenuItem1->menuItemId = rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999);
$mainMenuItem1->menuItemKeyholders = array('all');/** @todo change to admin once gatekeeper is developed */
$mainMenuItem1->menuItemMachineName = 'homepage';
$mainMenuItem1->menuItemPosition = 1;
$mainMenuItem1->menuItemWrappingTagType = 'li';

$mainMenuItem2 = new SdmMenuItem();
$mainMenuItem2->arguments = array('defaultMenuItem=true', 'linkedBy=main_menu');
$mainMenuItem2->destination = 'contentManager';
$mainMenuItem2->destinationType = 'internal';
$mainMenuItem2->menuItemCssClasses = array();
$mainMenuItem2->menuItemCssId = 'defaultMenuitem_manage_content';
$mainMenuItem2->menuItemDisplayName = 'Manage Content';
$mainMenuItem2->menuItemEnabled = '1';
$mainMenuItem2->menuItemId = rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999);
$mainMenuItem2->menuItemKeyholders = array('root');/** @todo change to admin once gatekeeper is developed */
$mainMenuItem2->menuItemMachineName = 'manage_content';
$mainMenuItem2->menuItemPosition = 2;
$mainMenuItem2->menuItemWrappingTagType = 'li';

$mainMenuItem3 = new SdmMenuItem();
$mainMenuItem3->arguments = array('defaultMenuItem=true', 'linkedBy=main_menu');
$mainMenuItem3->destination = 'navigationManager';
$mainMenuItem3->destinationType = 'internal';
$mainMenuItem3->menuItemCssClasses = array();
$mainMenuItem3->menuItemCssId = 'defaultMenuItem_edit_menus';
$mainMenuItem3->menuItemDisplayName = 'Edit Menus';
$mainMenuItem3->menuItemEnabled = '1';
$mainMenuItem3->menuItemId = rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999);
$mainMenuItem3->menuItemKeyholders = array('root');/** @todo change to admin once gatekeeper is developed */
$mainMenuItem3->menuItemMachineName = 'edit_menus';
$mainMenuItem3->menuItemPosition = 3;
$mainMenuItem3->menuItemWrappingTagType = 'li';

$mainMenuItem4 = new SdmMenuItem();
$mainMenuItem4->arguments = array('defaultMenuItem=true', 'linkedBy=main_menu');
$mainMenuItem4->destination = 'SdmErrorLog';
$mainMenuItem4->destinationType = 'internal';
$mainMenuItem4->menuItemCssClasses = array();
$mainMenuItem4->menuItemCssId = 'defaultMenuItem_review_site_errors';
$mainMenuItem4->menuItemDisplayName = 'Review Site Errors';
$mainMenuItem4->menuItemEnabled = '1';
$mainMenuItem4->menuItemId = rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999);
$mainMenuItem4->menuItemKeyholders = array('root');/** @todo change to admin once gatekeeper is developed */
$mainMenuItem4->menuItemMachineName = 'review_site_errors';
$mainMenuItem4->menuItemPosition = 4;
$mainMenuItem4->menuItemWrappingTagType = 'li';

$mainMenuItem5 = new SdmMenuItem();
$mainMenuItem5->arguments = array('defaultMenuItem=true', 'linkedBy=main_menu');
$mainMenuItem5->destination = 'SdmCoreOverview';
$mainMenuItem5->destinationType = 'internal';
$mainMenuItem5->menuItemCssClasses = array();
$mainMenuItem5->menuItemCssId = 'defaultMenuItem_review_core';
$mainMenuItem5->menuItemDisplayName = 'Review Core';
$mainMenuItem5->menuItemEnabled = '1';
$mainMenuItem5->menuItemId = rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999);
$mainMenuItem5->menuItemKeyholders = array('root');/** @todo change to admin once gatekeeper is developed */
$mainMenuItem5->menuItemMachineName = 'review_core';
$mainMenuItem5->menuItemPosition = 5;
$mainMenuItem5->menuItemWrappingTagType = 'li';

$mainMenuItem6 = new SdmMenuItem();
$mainMenuItem6->arguments = array('defaultMenuItem=true', 'linkedBy=main_menu');
$mainMenuItem6->destination = 'SdmAuth';
$mainMenuItem6->destinationType = 'internal';
$mainMenuItem6->menuItemCssClasses = array();
$mainMenuItem6->menuItemCssId = 'defaultMenuItem_login';
$mainMenuItem6->menuItemDisplayName = 'Login';
$mainMenuItem6->menuItemEnabled = '1';
$mainMenuItem6->menuItemId = rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999);
$mainMenuItem6->menuItemKeyholders = array('basic_user');/** @todo change to admin once gatekeeper is developed */
$mainMenuItem6->menuItemMachineName = 'login';
$mainMenuItem6->menuItemPosition = 50;
$mainMenuItem6->menuItemWrappingTagType = 'li';

$mainMenuItems = array($mainMenuItem1->menuItemId => $mainMenuItem1, $mainMenuItem2->menuItemId => $mainMenuItem2, $mainMenuItem3->menuItemId => $mainMenuItem3, $mainMenuItem4->menuItemId => $mainMenuItem4, $mainMenuItem5->menuItemId => $mainMenuItem5, $mainMenuItem6->menuItemId => $mainMenuItem6);

$mainMenu = new SdmMenu();
$mainMenu->displaypages = array('all');
$mainMenu->menuCssClasses = array('horizontal-menu');
$mainMenu->menuCssId = 'main-menu';
$mainMenu->menuDisplayName = 'Main Menu';
$mainMenu->menuId = rand(10000000, 99999999);
$mainMenu->menuItems = $mainMenuItems;
$mainMenu->menuKeyholders = array('all');
$mainMenu->menuMachineName = 'main_menu';
$mainMenu->menuPlacement = 'prepend';
$mainMenu->menuWrappingTagType = 'ul';
$mainMenu->wrapper = 'main_content';


/** Footer Menu */
$footerItem1 = new SdmMenuItem();
$footerItem1->arguments = array('defaultMenuItem=true', 'linkedBy=footer_menu');
$footerItem1->destination = 'Sdm Cms Documentation';
$footerItem1->destinationType = 'internal';
$footerItem1->menuItemCssClasses = array();
$footerItem1->menuItemCssId = 'defaultMenuItem_documentation';
$footerItem1->menuItemDisplayName = 'Documentation';
$footerItem1->menuItemEnabled = '1';
$footerItem1->menuItemId = rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999);
$footerItem1->menuItemKeyholders = array('all');/** @todo change to admin once gatekeeper is developed */
$footerItem1->menuItemMachineName = 'documentation';
$footerItem1->menuItemPosition = 1;
$footerItem1->menuItemWrappingTagType = 'p';

$footerItem2 = new SdmMenuItem();
$footerItem2->arguments = array('defaultMenuItem=true', 'linkedBy=footer_menu');
$footerItem2->destination = 'https://github.com/sevidmusic/SDM_CMS';
$footerItem2->destinationType = 'external';
$footerItem2->menuItemCssClasses = array();
$footerItem2->menuItemCssId = 'defaultMenuItem_view_sdm_cms_on_github';
$footerItem2->menuItemDisplayName = 'View SDM CMS on GitHub';
$footerItem2->menuItemEnabled = '1';
$footerItem2->menuItemId = rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999);
$footerItem2->menuItemKeyholders = array('all');/** @todo change to admin once gatekeeper is developed */
$footerItem2->menuItemMachineName = 'view_sdm_cms_on_github';
$footerItem2->menuItemPosition = 2;
$footerItem2->menuItemWrappingTagType = 'p';

$footerItem3 = new SdmMenuItem();
$footerItem3->arguments = array('defaultMenuItem=true', 'linkedBy=footer_menu');
$footerItem3->destination = 'homepage';
$footerItem3->destinationType = 'internal';
$footerItem3->menuItemCssClasses = array('homelink');
$footerItem3->menuItemCssId = 'gohome-link';
$footerItem3->menuItemDisplayName = 'Go Home';
$footerItem3->menuItemEnabled = '1';
$footerItem3->menuItemId = rand(100, 99999) . chr(rand(65, 90)) . rand(100, 99999) . chr(rand(65, 90)) . chr(rand(65, 90)) . rand(100, 99999);
$footerItem3->menuItemKeyholders = array('all');/** @todo change to admin once gatekeeper is developed */
$footerItem3->menuItemMachineName = 'go_home';
$footerItem3->menuItemPosition = 50;
$footerItem3->menuItemWrappingTagType = 'p';

$footerMenuItems = array($footerItem1->menuItemId => $footerItem1, $footerItem2->menuItemId => $footerItem2, $footerItem3->menuItemId => $footerItem3);

$footerMenu = new SdmMenu();
$footerMenu->displaypages = array('all');
$footerMenu->menuCssClasses = array('border rounded-edge');
$footerMenu->menuCssId = 'footer-menu';
$footerMenu->menuDisplayName = 'Footer Menu';
$footerMenu->menuId = rand(10000000, 99999999);
$footerMenu->menuItems = $footerMenuItems;
$footerMenu->menuKeyholders = array('all');
$footerMenu->menuMachineName = 'footer_menu';
$footerMenu->menuPlacement = 'append';
$footerMenu->menuWrappingTagType = 'div';
$footerMenu->wrapper = 'main_content';

/** Store Menus */
$menus = new stdClass();
$menus->{$mainMenu->menuId} = $mainMenu;
$menus->{$footerMenu->menuId} = $footerMenu;


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
        'theme' => 'sdm',
        'enabledapps' => array('contentManager' => 'contentManager', 'SdmErrorLog' => 'SdmErrorLog', 'navigationManager' => 'navigationManager', 'SdmCoreOverview' => 'SdmCoreOverview', 'SdmAuth' => 'SdmAuth'),
    ), // end 'settings' array
    'menus' => $menus,
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
// reset error logs
file_put_contents($sdmcore->sdmCoreGetCoreDirectoryPath() . '/logs/sdm_core_errors.log', '', LOCK_EX);
file_put_contents($sdmcore->sdmCoreGetCoreDirectoryPath() . '/logs/badRequestsLog.log', '', LOCK_EX);
echo 'An empty error log was created to track site errors. You can view the error log <a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmErrorLog">HERE</a>';
echo '</div>';
?>
