<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/18/16
 * Time: 5:19 PM
 */


/** Initial Setup **/

/* Set initialSetup to false, if setup is required it will be changed to true internally. */
$initialSetup = false;

/* Required directories for Sdm Media Display's and it's admin panels to work. */
$requiredDirectories = array('displays/data', 'displays/data/SdmMediaDisplays', 'displays/media');

/* Insure required directories exist. */
foreach ($requiredDirectories as $requiredDirectory) {
    /* Create path to required directory. */
    $requiredDirectoryPath = $sdmMediaDisplaysDirectoryPath . '/' . $requiredDirectory;

    /* Check if directory exists. */
    $requiredDirectoryExists = is_dir($requiredDirectoryPath);

    /* If required directory does not exist create it. */
    if ($requiredDirectoryExists !== true) {
        mkdir($requiredDirectoryPath);
        /* Initial setup occurred, set $initialSetup to true to indicate that this is a new setup. */
        $initialSetup = true;
    }
}

/* Show initial setup message if $initialSetup is true. */
if ($initialSetup === true) {
    $initialSetupMessage = "
        <h2>Sdm Media Displays</h2>
        <p>Looks like you just enabled this app.</p>
        <p>Welcome to the Sdm Media Displays app. With the Sdm Media
        Displays app you will be able to add media to your website's
        pages, including images, video, embeded video (such as youtube),
        and HTML 5 canvas scripts.</p>
        <p>Initial setup complete.
        <a href='{$sdmassembler->sdmCoreGetRootDirectoryUrl()}/index.php?page=SdmMediaDisplays'>
        Click here</a> to start creating media displays.</p>";
    $sdmassembler->sdmAssemblerIncorporateAppOutput($initialSetupMessage, array('incpages' => array('SdmMediaDisplays'), 'incmethod' => 'overwrite'));
}

