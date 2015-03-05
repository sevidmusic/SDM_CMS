<?php

// this otpions array will be passed to incorporateAppOutput() wherever this app outputs data.
$options = array(
    'incpages' => array(
        'navigationManagerSection1',
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
        case 'navigationManagerSection1':
            $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, 'Nav Section 1', array('incpages' => array('navigationManagerSection1')));
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
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=navigationManagerSection1">navigationManagerSection1</a></li>
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=navigationManagerSection2">navigationManagerSection2</a></li>
                    </ul>
                </div>
                ', array('incpages' => array('navigationManager')));
            break;
    }
}