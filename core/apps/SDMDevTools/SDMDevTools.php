<?php

/**
 * This app provides Dev tools in the form of add app pages
 * that can be used to inspect the SDM CMS, including core,
 * site errors, and more.
 *
 * This app also demonstrates many of the ways you can incorporate app
 * out put into a page.
 * It shows how you can use an $oupput var and then make a call to
 * incorporateAppOutput() at the end of the file, it shows how you can make seperate
 * calls to incorporateAppOutput() withing an app, and it shows how
 * passing different options arrays to the various calls to incorporateAppOutput()
 * give the developer a lot of control on how where and when an app is incorporated
 * into a page.
 *
 */
// add a dev menu to all pages for while still in dev
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, trim('
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=contentManager">Content Manager</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=navigationManager">Navigation Manager</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=core">Core</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=errors">Error Log</a></p>
    <p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=clearErrorLog">Clear Error Log</a></p>
    <div style="border:3px solid red; border-radius:25px;background: black; color: #CC0066; padding: 20px; margin: 3px 3px 3px 3px;"><p><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=reset">Reset Site</a></p><p><i><b>NOTE: THIS WILL ERASE ALL SITE DATA AND SITE SETTINGS RESTORIENG THE SDM CMS TO ITS DEFAULT CONFIGURATION!</b></i><p></div>
'), array('incmethod' => 'prepend', 'ignorepages' => array('homepage', 'contentManager'), 'incpages' => array()));

// options array for incroporation of app out stored in $output var at the end of the file
$options = array(
    'wrapper' => 'main_content',
    'incmethod' => 'prepend',
    'incpages' => array('core', 'errors', 'clearErrorLog', 'reset'),
    'ignorepages' => array('contentManager', 'navigationManager'),
);
// initialize $output var
$output = '';
switch ($sdmcore->determineRequestedPage()) {
    case 'core': // dispaly current core configuration
        $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<h1>SDM CMS CORE</h1><p>Below is a visual representation of the current state of CORE</p>' . $sdmcore->sdmCoreCurlGrabContent($sdmcore->getRootDirectoryUrl() . '/coreoverview.php'), array('incmethod' => 'append', 'ignorepages' => array(), 'incpages' => array('core')));
        break;
    case 'errors': // display recent errors
        $output .= trim('<h1>Site Errors</h1>' . str_replace('[', '<p style="font-size: .8em;overflow:auto;border: 2px solid #CC0066;border-radius: 3px;background: black;color: #CC0066; margin: 3px 3px 3px 3px; padding: 23px 23px 23px 23px;">[', str_replace('<br />', '</p>', nl2br(file_get_contents($sdmcore->getCoreDirectoryPath() . '/logs/sdm_core_errors.log')))));
        break;
    case 'clearErrorLog': // reset site to default configuration | This will erase all site data includeing content, error logs, and site settings.
        $output .= $sdmcore->sdmCoreCurlGrabContent($sdmcore->getRootDirectoryUrl() . '/clearErrorLog.php');
        break;
    case 'reset': // reset site to default configuration | This will erase all site data includeing content, error logs, and site settings.
        // make an independant cal to incorporateAppOutput() so we can overwrite the target wrapper
        $sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $sdmcore->sdmCoreCurlGrabContent($sdmcore->getRootDirectoryUrl() . '/reset.php'), array('incmethod' => 'overwrite', 'ignorepages' => array(), 'incpages' => array('reset')));
        break;
}

// Incorporate Output
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options);