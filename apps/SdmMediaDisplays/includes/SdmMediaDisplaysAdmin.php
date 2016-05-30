<?php

/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/28/16
 * Time: 3:22 PM
 */
class SdmMediaDisplaysAdmin extends SdmForm
{
    private $adminPanel;
    private $adminMode;
    private $editMode;
    private $displayBeingEdited;
    private $adminFormElements;
    private $adminFormButtons;
    private $initialSetup;
    private $output;
    private $messages;
    private $sdmCore;
    private $sdmMediaDisplaysDirectoryPath;
    private $sdmMediaDisplaysDirectoryUrl;
    private $sdmMediaDisplaysDataDirectoryPath;
    private $sdmMediaDisplaysDataDirectoryUrl;
    private $sdmMediaDisplaysMediaDirectoryPath;
    private $sdmMediaDisplaysMediaDirectoryUrl;
    private $sdmMediaDisplaysAdminPageUrl;
    private $sdmMediaDisplaysPageUrl;
    private $cronTasksPerformed;

    public function __construct()
    {
        parent::__construct();
        /* Current admin panel. */
        $this->adminPanel = (isset($this->adminPanel) === true ? $this->adminPanel : 'default');
        /* Current admin mode. */
        $this->adminMode = (isset($this->adminMode) === true ? $this->adminMode : 'default');
        /* Current edit mode. */
        $this->editMode = (isset($this->editMode) === true ? $this->editMode : 'default');
        /* Current display being edited. */
        $this->displayBeingEdited = (isset($this->displayBeingEdited) === true ? $this->displayBeingEdited : 'default');
        /* Admin form elements. */
        $this->adminFormElements = (isset($this->adminFormElements) === true ? $this->adminFormElements : array());
        /* Admin form buttons. */
        $this->adminFormButtons = (isset($this->adminFormButtons) === true ? $this->adminFormButtons : array());
        /* Current admin panel's output. */
        $this->output = (isset($this->output) === true ? $this->output : 'An error occurred and the Sdm Media Displays admin panel is currently unavailable.');
        /* Admin panel messages. */
        $this->messages = (isset($this->messages) === true ? $this->messages : null);
        /* Local instance of SdmCore(). */
        $this->sdmCore = new SdmCore();
        /* Sdm media display's directory path. */
        $this->sdmMediaDisplaysDirectoryPath = $this->sdmCore->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays';
        /* Sdm media display's directory url. */
        $this->sdmMediaDisplaysDirectoryUrl = $this->sdmCore->sdmCoreGetUserAppDirectoryUrl() . '/SdmMediaDisplays';
        /* Sdm media display data directory path. */
        $this->sdmMediaDisplaysDataDirectoryPath = $this->sdmMediaDisplaysDirectoryPath . '/displays/data';
        /* Sdm media display data directory url. */
        $this->sdmMediaDisplaysDataDirectoryUrl = $this->sdmMediaDisplaysDirectoryUrl . '/displays/data';
        /* Sdm media display media directory path. */
        $this->sdmMediaDisplaysMediaDirectoryPath = $this->sdmMediaDisplaysDirectoryPath . '/displays/media';
        /* Sdm media display media directory url. */
        $this->sdmMediaDisplaysMediaDirectoryUrl = $this->sdmMediaDisplaysDirectoryUrl . '/displays/media';
        /* Sdm media display's admin page url. */
        $this->sdmMediaDisplaysAdminPageUrl = $this->sdmCore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=SdmMediaDisplays';
        /* Current display's page url. */
        $this->sdmMediaDisplaysPageUrl = $this->sdmCore->sdmCoreGetUserAppDirectoryUrl() . '/index.php?page=' . $this->displayBeingEdited;
        /* Cron tasks performed. */
        $this->cronTasksPerformed = (isset($this->cronTasksPerformed) ? $this->cronTasksPerformed : false);
    }

    public function getCurrentAdminPanel()
    {
        /* Perform any initial setup required. */
        $this->performInitialSetup();

        /* Run cron tasks. */
        $this->runCronTasks();

        /* Return current admin panel's output */
        return $this->output;
    }

    private function performInitialSetup()
    {
        /** Initial Setup **/

        /* Set initialSetup to false, if setup is required it will be changed to true internally. */
        $this->initialSetup = false;

        /* Required directories for Sdm Media Display's and it's admin panels to work. */
        $requiredDirectories = array($this->sdmMediaDisplaysDataDirectoryPath, $this->sdmMediaDisplaysMediaDirectoryPath, $this->sdmMediaDisplaysDataDirectoryPath . '/SdmMediaDisplays');

        /* Insure required directories exist. */
        foreach ($requiredDirectories as $requiredDirectory) {
            /* Create path to required directory. */
            $requiredDirectoryPath = $requiredDirectory;

            /* Check if directory exists. */
            $requiredDirectoryExists = is_dir($requiredDirectoryPath);

            /* If required directory does not exist create it. */
            if ($requiredDirectoryExists !== true) {
                mkdir($requiredDirectoryPath);
                /* Initial setup occurred, set $this->initialSetup to true to indicate that this is a new setup. */
                $this->initialSetup = true;
            }
        }

        /* Show initial setup message if $this->initialSetup is true. */
        if ($this->initialSetup === true) {
            $this->output = "
                <h2>Sdm Media Displays</h2>
                <p>Looks like you just enabled this app.</p>
                <p>Welcome to the Sdm Media Displays app. With the Sdm Media
                Displays app you will be able to add media to your website's
                pages, including images, video, embeded video (such as youtube),
                and HTML 5 canvas scripts.</p>
                <p>Initial setup complete.
                <a href='{$this->sdmCore->sdmCoreGetRootDirectoryUrl()}/index.php?page=SdmMediaDisplays'>
                Click here</a> to start creating media displays.</p>
                ";
        }
        return $this->initialSetup;
    }

    private function runCronTasks()
    {

        /* CRON tasks. Run each time Sdm Media Displays admin panel is accessed. */

        /* Get a directory listing of all displays from the data directory. */
        $dataDirectoryListing = $this->sdmCore->sdmCoreGetDirectoryListing('SdmMediaDisplays/displays/data', 'apps');

        /* Look in each display's data directory and cleanup any ghost .json files that are found. */
        foreach ($dataDirectoryListing as $dataDirectoryName) {
            /* Delete any ghost .json files. */
            $ghostJsonFilePath = $this->sdmMediaDisplaysDataDirectoryPath . '/' . $dataDirectoryName . '/.json';
            if (file_exists($ghostJsonFilePath) === true) {
                /* Report cron tasks were run. */
                $this->cronTasksPerformed = true;

                /* Delete file. */
                unlink($ghostJsonFilePath);

                /* Log deleting of file in to aide in any debugging that may be needed. */
                $this->messages .= 'Sdm Media Displays: Removed ghost json files from ' . $ghostJsonFilePath . '.' . PHP_EOL;
            }

        }
        return $this->cronTasksPerformed;
    }

}