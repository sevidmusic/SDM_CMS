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
    ),
    'menus' => array(
        array(
            /* MENU SETTINGS */
            'menuId' => rand(10000000, 99999999), // should be a randomly generated 8 digit whole number
            'menuMachineName' => 'main_menu',
            'menuDisplayName' => 'Main Menu',
            'wrapper' => 'main_content',
            'menuWrappingTagType' => '', // applied to menu | i.e. <ul>THE MENU</ul>
            'menuPlacement' => 'prepend', // can be prepend or append. At the moment you can not place menu in the middle of the wrapper becuse it is to complex to calculate such placement. If a menu needs to exist in the middle of a wrapper then it should be hardcoded in the theme for the site, or generated with a custom app...
            'menuCssId' => 'main-menu',
            'menuCssClasses' => array(
                'sdm-menu',
                'main-menu',
            ),
            'displaypages' => array(// if all is in the array, the menu will be displayed on all pages, if all is NOT present, then the array should contain the names of the pages that this menu should appear on.
                'all',
            ),
            'menuKeyholders' => array(
                'unrestricted' => 'unrestricted', // unrestrited is a speical role that basically says anyone can use this regardless of role
            ), // end menuKeyholders
            /* MENU ITEMS */
            'menuItems' => array(
                /* menu item 1 */
                array(
                    'menuItemId' => rand(10000000, 99999999),
                    'menuItemMachineName' => 'homepage',
                    'menuItemDisplayName' => 'Homepage',
                    'menuItemWrappingTagType' => '', // applied to menu item | i.e. <li>THE MENU</li>
                    'menuItemPosition' => 0,
                    'menuItemCssId' => 'menu-item-homepage',
                    'menuItemCssClasses' => array(
                        'sdm-menu-item',
                        'internal-link',
                    ),
                    'destinationType' => 'internal', // can also be intental | if external then checks will be performed agaisnt a blacklist to insure sites deemed unsafe are not linked to from the core site. Also it shouldbe possible for external links to be disabled from the UI so admin can quickly assess if site issues are related to malicious links on the site.
                    'destination' => 'homepage', // external links should contain a completete url (i.e., http://www.example.com) DO NOT INCLUDE ENDING /, IF YOU DO THE CMS WILL REMOVE IT AND IT MAY BREAK YOUR SITE LINKS, internal should indicate the page that this menu item points to
                    'arguments' => array(// will translate into ?argument1=argumentvalue&argument2=argument%20two%20value being appended to the url this menu points to
                        'linkedby' => 'main menu',
                    ), // end 'arguments' array
                    'menuItemKeyholders' => array(// array of roles allowed to interact with this menu item. Done on an item to item basis as well as a global basis in case menus with more then one role contain links that some roles may not be allowed to interact with even though they can use the menu
                        'unrestricted' => 'unrestricted', // unrestrited is a speical role that basically says anyone can use this regardless of role
                    ), // end 'menuItemKeyholders' array
                ), // end 'menuItem1' array
                /* menu item 2 */
                array(
                    'menuItemId' => rand(10000000, 99999999),
                    'menuItemMachineName' => 'contentManager',
                    'menuItemDisplayName' => 'Content Manager',
                    'menuItemWrappingTagType' => '', // applied to menu item | i.e. <li>THE MENU</li>
                    'menuItemPosition' => 0,
                    'menuItemCssId' => 'menu-item-contentManager',
                    'menuItemCssClasses' => array(
                        'sdm-menu-item',
                        'internal-link',
                    ),
                    'destinationType' => 'internal', // can also be intental | if external then checks will be performed agaisnt a blacklist to insure sites deemed unsafe are not linked to from the core site. Also it shouldbe possible for external links to be disabled from the UI so admin can quickly assess if site issues are related to malicious links on the site.
                    'destination' => 'contentManager', // external links should contain a completete url (i.e., http://www.example.com) DO NOT INCLUDE ENDING /, IF YOU DO THE CMS WILL REMOVE IT AND IT MAY BREAK YOUR SITE LINKS, internal should indicate the page that this menu item points to
                    'arguments' => array(// will translate into ?argument1=argumentvalue&argument2=argument%20two%20value being appended to the url this menu points to
                        'linkedby' => 'main menu',
                    ), // end 'arguments' array
                    'menuItemKeyholders' => array(// array of roles allowed to interact with this menu item. Done on an item to item basis as well as a global basis in case menus with more then one role contain links that some roles may not be allowed to interact with even though they can use the menu
                        'unrestricted' => 'unrestricted', // unrestrited is a speical role that basically says anyone can use this regardless of role
                    ), // end 'menuItemKeyholders' array
                ), // end 'menuItem2' array
                /* menu item 3 */
                array(
                    'menuItemId' => rand(10000000, 99999999),
                    'menuItemMachineName' => 'navigationManager',
                    'menuItemDisplayName' => 'Navigation Manager',
                    'menuItemWrappingTagType' => '', // applied to menu item | i.e. <li>THE MENU</li>
                    'menuItemPosition' => 0,
                    'menuItemCssId' => 'menu-item-contentManager',
                    'menuItemCssClasses' => array(
                        'sdm-menu-item',
                        'internal-link',
                    ),
                    'destinationType' => 'internal', // can also be intental | if external then checks will be performed agaisnt a blacklist to insure sites deemed unsafe are not linked to from the core site. Also it shouldbe possible for external links to be disabled from the UI so admin can quickly assess if site issues are related to malicious links on the site.
                    'destination' => 'navigationManager', // external links should contain a completete url (i.e., http://www.example.com) DO NOT INCLUDE ENDING /, IF YOU DO THE CMS WILL REMOVE IT AND IT MAY BREAK YOUR SITE LINKS, internal should indicate the page that this menu item points to
                    'arguments' => array(// will translate into ?argument1=argumentvalue&argument2=argument%20two%20value being appended to the url this menu points to
                        'linkedby' => 'main menu',
                    ), // end 'arguments' array
                    'menuItemKeyholders' => array(// array of roles allowed to interact with this menu item. Done on an item to item basis as well as a global basis in case menus with more then one role contain links that some roles may not be allowed to interact with even though they can use the menu
                        'unrestricted' => 'unrestricted', // unrestrited is a speical role that basically says anyone can use this regardless of role
                    ), // end 'menuItemKeyholders' array
                ), // end 'menuItem3' array
            ), // end 'menuItems' array
        ), // end menu array,
    ), // end 'menus' array
    'settings' => array(
        'theme' => 'sdmDemoTheme1',
        'enabledapps' => array('contentManager' => 'contentManager', 'navigationManager' => 'navigationManager'),
    ), // end 'settings' array
); // end $config array


$data = utf8_encode(trim(json_encode($config)));
echo (file_put_contents($sdmcore->getDataDirectoryPath() . '/data.json', $data, LOCK_EX) != FALSE ? '<h4 style="color:#33CC33">Site configuration reset to defaults succsessfully</h4><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=homepage">Click Here</a> to view your new SDM CMS powered site</p>' : '<h2 style="color:red;">Could not configure site!Check config.php to determine the cause of the error.</h2>');
echo '<h3>Site Configuration:</h3><p>The following data was written to: <b style="color:#999999"><i>' . $sdmcore->getDataDirectoryPath() . '/data.json</i></b></p>';
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
echo '</div>';
?>
