<?php

$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=homepage">Homepage</a></p>', array('wrapper' => 'topmenu', 'incmethod' => 'prepend'));
// add a dev menu to all pages related to this app | options array is used to determine which pages this app will output to
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, trim('
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=core">Core</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=errors">Error Log</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=clearErrorLog">Clear Error Log</a></p>
    <div style="border:3px solid red; border-radius:25px;background: black; color: #CC0066; padding: 20px; margin: 3px 3px 3px 3px;"><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=reset">Reset Site</a></p><p><i><b>NOTE: THIS WILL ERASE ALL SITE DATA AND SITE SETTINGS RESTORIENG THE SDM CMS TO ITS DEFAULT CONFIGURATION!</b></i><p></div>
'), array('incmethod' => 'prepend', 'ignorepages' => array('homepage', 'contentManager', 'navigationManager'), 'incpages' => array('core', 'errors', 'clearErrorLog', 'reset')));


// initialize $output var
$output = '';
switch ($sdmcore->determineRequestedPage()) {
    case 'core': // dispaly current core configuration
        $output = '<h1>SDM CMS CORE</h1><p>Below is a visual representation of the current state of CORE</p>' . $sdmcore->sdmCoreCurlGrabContent($sdmcore->getRootDirectoryUrl() . '/coreoverview.php');
        $options = array('incmethod' => 'append', 'ignorepages' => array(), 'incpages' => array('core'));
        $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options);
        break;
    case 'errors': // display recent errors
        $output = trim('<h1>Site Errors</h1>' . str_replace('[', '<p style="font-size: .8em;overflow:auto;border: 2px solid #CC0066;border-radius: 3px;background: black;color: #CC0066; margin: 3px 3px 3px 3px; padding: 23px 23px 23px 23px;">[', str_replace('<br />', '</p>', nl2br(file_get_contents($sdmcore->getCoreDirectoryPath() . '/logs/sdm_core_errors.log')))));
        $options = array('incmethod' => 'append', 'ignorepages' => array(), 'incpages' => array('errors'));
        $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options);
        break;
    case 'clearErrorLog': // reset site to default configuration | This will erase all site data includeing content, error logs, and site settings.
        $output = $sdmcore->sdmCoreCurlGrabContent($sdmcore->getRootDirectoryUrl() . '/clearErrorLog.php');
        $options = array('incmethod' => 'append', 'ignorepages' => array(), 'incpages' => array('clearErrorLog'));
        $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options);
        break;
    case 'reset': // reset site to default configuration | This will erase all site data includeing content, error logs, and site settings.
        // make an independant cal to incorporateAppOutput() so we can overwrite the target wrapper
        $output = $sdmcore->sdmCoreCurlGrabContent($sdmcore->getRootDirectoryUrl() . '/reset.php');
        $options = array('incmethod' => 'append', 'ignorepages' => array(), 'incpages' => array('reset'));
        $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options);
        break;
}