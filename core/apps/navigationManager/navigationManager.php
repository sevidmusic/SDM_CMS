<?php

/**
 * The navigation manager app is responible for providing
 * forms and form handling for navigation management.
 *
 * It utilizes the SdmNms classes methods for CRUD operations
 * such as update, and delete...
 *
 * THIS APP IS STILL IN DEVELOPMENT | USE AT YOUR OWN RISK!
 */
// if we are on one f the navigationManager pages display the appropriate content for that page
$sdmnms = SdmNms::sdmInitializeNms();

switch ($sdmcore->determineRequestedPage()) {
    case 'navigationManager':
        // display navigation manager links
        $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content = '<p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=navigationManagerAddMenu">Add Menu</a></p>' . $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content;
        $menuItem = new SdmMenuItem();
        $menuItem->menuItemId = rand(100000000000, 999999999999);
        $menuItem->menuItemMachineName = str_replace('sdm', '', preg_replace("/[^A-Za-z0-9 ]/", '', $sdmcore->sdm_kind(strval($menuItem->menuItemId))));
        $menuItem->menuItemDisplayName = str_replace('sdm', '', preg_replace('/(\s)+/', ' ', preg_replace("/[^A-Za-z ]/", ' ', $sdmcore->sdm_kind(strval($menuItem->menuItemId)))));
        $menuItem->menuItemWrappingTagType = 'div';
        $menuItem->menuItemPosition = 0;
        $menuItem->menuItemCssId = $menuItem->menuItemMachineName;
        $menuItem->menuItemCssClasses = array('sdm-menu', $menuItem->menuItemMachineName);
        $menuItem->destinationType = 'internal';
        $menuItem->destination = 'homepage';
        $menuItem->arguments = array('linkedby' => $menuItem->menuItemMachineName);
        $menuItem->menuItemKeyholders = array('all');
        $sdmcore->sdm_read_array($menuItem);

        break;
    case 'navigationManagerAddMenu':
        // initialize NMS
        $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content = '<h1>Add Menu</h1>' . $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content;
        // Handlers | Split into files once out of dev
        break;
}
