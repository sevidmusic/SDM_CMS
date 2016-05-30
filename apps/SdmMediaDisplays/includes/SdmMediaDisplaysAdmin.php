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
    private $displayBeingEdited; // name of display being edited
    private $adminFormElements; // alias for SdmForm()'s built in formElements array.
    private $adminFormButtons;
    private $initialSetup;
    private $output;
    private $messages;
    private $sdmCore; // local instance of SdmCore
    private $sdmMediaDisplay; // instance of display being edited created upon instantiation of a SdmMediaDisplaysAdmin() object.
    private $sdmMediaDisplaysDirectoryPath;
    private $sdmMediaDisplaysDirectoryUrl;
    private $sdmMediaDisplaysDataDirectoryPath;
    private $sdmMediaDisplaysDataDirectoryUrl;
    private $sdmMediaDisplaysMediaDirectoryPath;
    private $sdmMediaDisplaysMediaDirectoryUrl;
    private $sdmMediaDisplaysAdminPageUrl;
    private $sdmMediaDisplaysPageUrl;
    private $cronTasksPerformed;
    private $displaysExist;

    public function __construct()
    {
        parent::__construct();
        /* Current admin panel. */
        $this->adminPanel = (($this->sdmFormGetSubmittedFormValue('adminPanel') !== null) === true ? $this->sdmFormGetSubmittedFormValue('adminPanel') : 'displayCrudPanel');
        /* Current admin mode. */
        $this->adminMode = (($this->sdmFormGetSubmittedFormValue('adminMode') !== null) === true ? $this->sdmFormGetSubmittedFormValue('adminMode') : null);
        /* Current edit mode. */
        $this->editMode = (($this->sdmFormGetSubmittedFormValue('editMode') !== null) === true ? $this->sdmFormGetSubmittedFormValue('editMode') : null);
        /* Current display being edited. */
        $this->displayBeingEdited = (($this->sdmFormGetSubmittedFormValue('displayBeingEdited') !== null) === true ? $this->sdmFormGetSubmittedFormValue('displayBeingEdited') : null);
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
        /* Create local instance of an SdmMediaDisplay() object for the display being edited. */
        $this->sdmMediaDisplay = new SdmMediaDisplay($this->displayBeingEdited, $this->sdmCore);
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
        /* Initial setup performed. */
        $this->initialSetup = (isset($this->initialSetup) === true ? $this->initialSetup : $this->performInitialSetup());
        /* Cron tasks performed. */
        $this->cronTasksPerformed = (isset($this->cronTasksPerformed) ? $this->cronTasksPerformed : $this->runCronTasks());
        /* Displays exist. */
        $this->displaysExist = (isset($this->displaysExist) === true ? $this->displaysExist : $this->displaysExist());
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

        /* Report if cron task were performed. */
        $this->cronTasksPerformed = false;

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

    private function displaysExist()
    {
        /* Only show edit and delete display buttons if there are displays other then the default. */
        $expectedDirs = array('.', '..', '.DS_Store', 'SdmMediaDisplays');

        /* Scan data directory for displays. */
        $displays = scandir($this->sdmCore->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/data');

        /* Check $displays against $expectedDirs, if a display is found that is not an expected directory then at least on display exists. */
        foreach ($displays as $display) {
            if (!in_array($display, $expectedDirs)) {
                /* Display found, set $displaysExist to true. */
                $this->displaysExist = true;
                /* A display was found, exit loop. */
                break;
            }
            /* No displays exist. */
            $this->displaysExist = false;
        }
        return $this->displaysExist;
    }

    public function getCurrentAdminPanel()
    {
        /* If this is not the initial setup assemble admin panel. */
        if ($this->initialSetup === false) {
            /* Assemble form elements. */
            $this->assembleAdminFormElements();

            /* Assemble form buttons. */
            $this->assembleAdminFormButtons();

        }

        /* Display dev output. */
        $this->devOutput();

        /* Return current admin panel's output */
        return $this->output;
    }

    private function assembleAdminFormElements()
    {
        return $this->adminFormElements;
    }

    private function assembleAdminFormButtons()
    {
        return $this->adminFormButtons;
    }

    private function devOutput()
    {
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['output' => $this->output]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['adminPanel' => $this->adminPanel]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['adminMode' => $this->adminMode]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['editMode' => $this->editMode]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['displayBeingEdited' => $this->displayBeingEdited]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['adminFormElements' => $this->adminFormElements]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['adminFormButtons' => $this->adminFormButtons]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['initialSetup' => $this->initialSetup]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['messages' => $this->messages]);
        //$this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmCore' => $this->sdmCore]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmMediaDisplay' => $this->sdmMediaDisplay]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmMediaDisplaysDirectoryPath' => $this->sdmMediaDisplaysDirectoryPath]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmMediaDisplaysDirectoryUrl' => $this->sdmMediaDisplaysDirectoryUrl]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmMediaDisplaysDataDirectoryPath' => $this->sdmMediaDisplaysDataDirectoryPath]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmMediaDisplaysDataDirectoryUrl' => $this->sdmMediaDisplaysDataDirectoryUrl]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmMediaDisplaysMediaDirectoryPath' => $this->sdmMediaDisplaysMediaDirectoryPath]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmMediaDisplaysMediaDirectoryUrl' => $this->sdmMediaDisplaysMediaDirectoryUrl]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmMediaDisplaysAdminPageUrl' => $this->sdmMediaDisplaysAdminPageUrl]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['sdmMediaDisplaysPageUrl' => $this->sdmMediaDisplaysPageUrl]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['cronTasksPerformed' => $this->cronTasksPerformed]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['displaysExist' => $this->displaysExist]);
    }

    private function createSdmMediaDisplayAdminButton($id, $name, $value, $label, $otherAttributes = array())
    {
        $attributes = array();
        foreach ($otherAttributes as $attributeName => $attributeValue) {
            $attributes[] = "$attributeName='$attributeValue'";
        }
        return "<button id='$id' name='SdmForm[$name]' type='submit' data-referred-by-button='$id' value='$value' " . implode(' ', $attributes) . ">$label</button>";
    }

}