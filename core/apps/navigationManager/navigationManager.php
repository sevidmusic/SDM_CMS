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
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content = '<p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=navigationManagerAddMenu">Add Menu</a></p>' . $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content;


        break;
    case 'navigationManagerAddMenu':
        // initialize NMS
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content = '<h1>Add Menu</h1>' . $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content;
        // Handlers | Split into files once out of dev
        // create a menu object
        $menu = new SdmMenu();
        // create some menuItem objects

        $sdmnms->sdmNmsAddMenu($menu);
        break;
}
