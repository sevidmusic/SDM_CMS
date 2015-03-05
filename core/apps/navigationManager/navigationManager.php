<?php

// this otpions array will be passed to incorporateAppOutput() wherever this app outputs data.
$options = array(
    'incpages' => array(
        'navigationManagerAddMenu',
        'navigationManagerSection2',
    ),
);
//navigationManagerSelectThemeForm.php
$sdmcore = $sdmcore; // see SdmAssembler.php and the app loading methods
if (substr($sdmcore->determineRequestedPage(), 0, 17) === 'navigationManager') {
    // CREATE A NEW CONTENT MANAGEMENT OBJECT
    $sdmcms = SdmCms::sdmInitializeCms();
    // determine which section of the content manager was requested
    switch ($sdmcore->determineRequestedPage()) {
        // edit content form
        case 'navigationManagerAddMenu':
            // create new naviagation management object
            $sdmnms = new SdmNms();
            // create a few menu items
            $newMenuItem = new SdmMenuItem();
            $newMenuItem->arguments = array('birthplace' => 'naviagtionManager_testMenuItem1');
            $newMenuItem->destination = 'contentManager';
            $newMenuItem->destinationType = 'internal';
            $newMenuItem->menuItemCssClasses = array('navigationManager', 'testMenuItem');
            $newMenuItem->menuItemCssId = 'menuItem1';
            $newMenuItem->menuItemDisplayName = 'Content Manager';
            $newMenuItem->menuItemEnabled;
            //$newMenuItem->menuItemId; set internaly
            $newMenuItem->menuItemKeyholders = array('root');
            $newMenuItem->menuItemMachineName = 'menuItem1';
            $newMenuItem->menuItemPosition = 0;
            $newMenuItem->menuItemWrappingTagType = 'p';
            // create menu
            $menu = new SdmMenu();
            $menu->displaypages = array('homepage');
            $menu->menuCssClasses = array('navigationManager', 'testMenu');
            $menu->menuCssId = 'testMenu';
            $menu->menuDisplayName = 'Test Menu';
            //$menu->menuId; // set internally
            $menu->menuItems = array($newMenuItem);
            $menu->menuKeyholders = array('root');
            $menu->menuMachineName = 'testMenu';
            $menu->menuPlacement = 'prepend';
            $menu->menuWrappingTagType = 'div';
            $menu->wrapper = 'main_content';
            $sdmnms->sdmNmsAddMenu($menu);

            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, 'Nav Section 1', array('incpages' => array('navigationManagerAddMenu')));
            break;

        case 'navigationManagerSection2':
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, 'Nav Section 2', array('incpages' => array('navigationManagerSection2')));
            break;

        default:
            // present content manager menu
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '
                <div id="navigationManager">
                <p>Welcome to the Navigation Manager. Here you can create, edit, delete, and restore content</p>
                    <ul>
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=navigationManagerAddMenu">navigationManagerAddMenu</a></li>
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=navigationManagerSection2">navigationManagerSection2</a></li>
                    </ul>
                </div>
                ', array('incpages' => array('navigationManager')));
            break;
    }
}