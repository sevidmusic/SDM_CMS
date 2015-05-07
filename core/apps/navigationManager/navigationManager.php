<?php

/**
 * Returns an array of integers within the range $s to $e.
 * i.e., rangeArray(4,7) would return the array : array(4 => 4, 5 => 5, 6 => 6, 7 => 7)
 * @param type $s Starting integer
 * @param type $e Ending integer
 * @return array Array of integers within range of $s to $e
 * @todo Incorporate into one of the core classes: SDM NMS, or SDM Core.
 */
function rangeArray($s, $e) {
    $array = array();
    while ($s <= $e) {
        $array[$s] = $s;
        $s++;
    }
    return $array;
}

// create new naviagation management object | this varialbe will be accsessible by the various stages forms and handlers
$sdmnms = new SdmNms();
// this otpions array will be passed to sdmAssemblerIncorporateAppOutput() wherever this app outputs data.
$options = array(
    'incpages' => array(
        'navigationManagerAddMenuStage1', // select number of menu items
        'navigationManagerAddMenuStage2', // configure menu items
        'navigationManagerAddMenuStage3', // configure menu
        'navigationManagerAddMenuStage4', // add menu
    ),
);
$sdmcore = $sdmcore; // see SdmAssembler.php
if (substr($sdmcore->sdmCoreDetermineRequestedPage(), 0, 17) === 'navigationManager') {
    // CREATE A NEW CONTENT MANAGEMENT OBJECT
    $sdmcms = SdmCms::sdmCmsInitializeCms();
    // determine which section of the content manager was requested
    switch ($sdmcore->sdmCoreDetermineRequestedPage()) {
        // Add Menu Stages
        case 'navigationManagerAddMenuStage1': // determine how many menu items this menu will have
            require($sdmcore->sdmCoreGetCoreAppDirectoryPath() . '/navigationManager/stages/addmenustage1.php');
            break;
        case 'navigationManagerAddMenuStage2': // configure the menu items
            require($sdmcore->sdmCoreGetCoreAppDirectoryPath() . '/navigationManager/stages/addmenustage2.php');
            break;
        case 'navigationManagerAddMenuStage3':
            require($sdmcore->sdmCoreGetCoreAppDirectoryPath() . '/navigationManager/stages/addmenustage3.php');
            break;
        case 'navigationManagerAddMenuStage4':
            require($sdmcore->sdmCoreGetCoreAppDirectoryPath() . '/navigationManager/stages/addmenustage4.php');
            break;
        // DELETE MENU STAGES
        case 'navigationManagerDeleteMenuStage1':
            require($sdmcore->sdmCoreGetCoreAppDirectoryPath() . '/navigationManager/stages/deletemenustage1.php');
            break;
        default:
            // present content manager menu
            $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '
                <div id="navigationManager">
                <p>Welcome to the Navigation Manager. Here you can create, edit, delete, and restore menus</p>
                    <ul>
                        <li><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=navigationManagerAddMenuStage1">Add Menu</a></li>
                        <li><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=navigationManagerDeleteMenuStage1">Delete Menu</a></li>
                    </ul>
                </div>
                ', array('incpages' => array('navigationManager')));
            break;
    }
}