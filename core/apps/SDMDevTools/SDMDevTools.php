<?php

/**
 * This app provides Dev tools in the form of add app pages
 * that can be used to inspect the SDM CMS, including core,
 * site errors, and more.
 */
switch ($sdmcore->determineRequestedPage()) {
    case 'core': // dispaly current core configuration
        $sdmcore->sdm_read_array($sdmcore->sdmCoreLoadDataObject());
        break;
    case 'errors': // display recent errors
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= trim('<h1>Site Errors</h1>' . str_replace('[', '<p style="font-size: .8em;overflow:auto;border: 2px solid #CC0066;border-radius: 3px;background: black;color: #CC0066; margin: 3px 3px 3px 3px; padding: 23px 23px 23px 23px;">', str_replace('<br />', '</p>', nl2br(file_get_contents($sdmcore->getCoreDirectoryPath() . '/logs/sdm_core_errors.log')))));
        break;
    case 'clearErrorLog': // reset site to default configuration | This will erase all site data includeing content, error logs, and site settings.
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= $sdmcore->sdmCoreCurlGrabContent($sdmcore->getRootDirectoryUrl() . '/clearErrorLog.php');
        break;
    case 'reset': // reset site to default configuration | This will erase all site data includeing content, error logs, and site settings.
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= $sdmcore->sdmCoreCurlGrabContent($sdmcore->getRootDirectoryUrl() . '/reset.php');
        break;
}

// add a dev menu to all pages for while still in dev
$sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= trim('
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=contentManager">Content Manager</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=navigationManager">Navigation Manager</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=core">Core</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=errors">Error Log</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=clearErrorLog">Clear Error Log</a></p>
    <div style="border:3px solid red; border-radius:25px;background: black; color: #CC0066; padding: 20px; margin: 3px 3px 3px 3px;"><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=reset">Reset Site</a></p><p><i><b>NOTE: THIS WILL ERASE ALL SITE DATA AND SITE SETTINGS RESTORIENG THE SDM CMS TO ITS DEFAULT CONFIGURATION!</b></i><p></div>
        ');
