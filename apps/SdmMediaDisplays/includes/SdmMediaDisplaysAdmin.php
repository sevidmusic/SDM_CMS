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
    private $adminFormElements; // stores the html for each form element for the current admin panel
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
        /* Call SdmForm()'s __constructor() */
        parent::__construct();
        /* Current admin panel. */
        $this->adminPanel = (($this->sdmFormGetSubmittedFormValue('adminPanel') !== null) === true ? $this->sdmFormGetSubmittedFormValue('adminPanel') : 'displayCrudPanel');
        /* Current admin mode. */
        $this->adminMode = (($this->sdmFormGetSubmittedFormValue('adminMode') !== null) === true ? $this->sdmFormGetSubmittedFormValue('adminMode') : null);
        /* Current edit mode. */
        $this->editMode = (($this->sdmFormGetSubmittedFormValue('editMode') !== null) === true ? $this->sdmFormGetSubmittedFormValue('editMode') : null);
        /* Current display being edited. */
        $this->displayBeingEdited = $this->sdmFormGetSubmittedFormValue('displayName');
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
        /* Configure From */
        $this->configureAdminForm();
        /* Process any submitted form values from the last submitted admin panel. */
        $this->processSubmittedValues();
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

    private function configureAdminForm()
    {
        $this->formHandler = 'SdmMediaDisplays';
        $this->method = 'post';
        $this->excludeSubmitLabel = true;
        $this->preserveSubmittedValues = true;
        $this->formClasses = 'SdmMediaDisplaysAdminForm';
    }

    private function processSubmittedValues()
    {
        switch ($this->adminPanel) {
            case 'editMedia':
                $pathToCurrentDisplay = $this->sdmMediaDisplaysDataDirectoryPath . '/' . $this->displayBeingEdited;
                $displayExists = is_dir($pathToCurrentDisplay);
                if ($displayExists === false) {
                    mkdir($pathToCurrentDisplay);
                }
                break;
        }
    }

    public function getCurrentAdminPanel()
    {
        /* If this is not the initial setup assemble admin panel. */
        if ($this->initialSetup === false) {
            /* Assemble form elements. */
            $this->assembleAdminFormElements();

            /* Assemble form buttons. */
            $this->assembleAdminFormButtons();

            /* Initialize $formHtml array. */
            $formHtml = array();

            /* Start building form. */
            $formHtml['openingFormTags'] = $this->sdmFormOpenForm($this->sdmCore->sdmCoreGetRootDirectoryUrl());
            $formHtml['formElementsHtml'] = implode(PHP_EOL, $this->adminFormElements);
            $formHtml['closingFormTags'] = $this->sdmFormCloseForm();

            /* Display admin buttons for the current panel */
            $this->output = implode('', $formHtml) . implode('', $this->adminFormButtons);
        }

        /* Display dev output. */
        //$this->devOutput();

        /* Return current admin panel's output */
        return $this->output;
    }

    private function assembleAdminFormElements()
    {
        /* Determine which form elements to define for the current adminPanel. */
        switch ($this->adminPanel) {
            case 'addDisplay':
                $this->sdmFormCreateFormElement('displayName', 'text', 'Enter a name for this display', '', 1);
                $allPages = array('all' => 'all');
                $availablePages = $this->sdmCore->sdmCoreDetermineAvailablePages();
                $enabledApps = (array)$this->sdmCore->sdmCoreDetermineEnabledApps();
                $assignablePages = array_merge($allPages, $availablePages, $enabledApps);
                $this->sdmFormCreateFormElement('assignedPages', 'checkbox', 'Select the pages the display should show up on. If the display should show on all pages check the "all" option', $assignablePages, 2);
                break;
            case 'addMedia':
            case 'editSelectedMedia':
                /* Hidden form elements */

                /* Create hidden form element to story the name of the display being edited */
                $this->sdmFormCreateFormElement('displayName', 'hidden', 'Current Display Being Edited', $this->displayBeingEdited, 420);
                /* Media source path (local path to media file, only used for local media sources) */
                $this->sdmFormCreateFormElement('sdmMediaSourcePath', 'hidden', '', $this->sdmMediaDisplaysMediaDirectoryPath, 421);

                /* Editable form elements */

                /* Name/title of the media */
                $this->sdmFormCreateFormElement('sdmMediaDisplayName', 'text', '<br/>Name or title for the media.', '', 1);
                /* Media source type (local or external) */
                $this->sdmFormCreateFormElement('sdmMediaSourceType', 'select', '<p>Is the media source external or local?</p><p><span style="font-size: .5em;">External sources are sources from other sites, such as Youtube. Local sources, as the name implies, are stored locally.<br/>Use external if media is from a url to a site such as Youtube or Vimeo.<br/>Use local if you are uploading the media.</span></p>', $this->sdmFormSetDefaultInputValues(array('External (Media resource from another site)' => 'external', 'Local (Media stored locally)' => 'local',), 'local'), 2);
                /* Media file (only used for local media sources). */
                $this->sdmFormCreateFormElement('sdmMediaFile', 'file', 'Upload media file | Only used for local media sources.', null, 3);
                /* Media source url (only used for external media sources). */
                $this->sdmFormCreateFormElement('sdmMediaSourceUrl', 'text', 'Url To Media | Only set for external media sources. (If youtube url it must be the embed url provided by youtube.)', '', 4);
                /* Media type (i.e., image, audio, etc.) */
                $this->sdmFormCreateFormElement('sdmMediaType', 'select', 'Select the media\'s type.', $this->sdmFormSetDefaultInputValues(array('Image' => 'image', 'Audio' => 'audio', 'Video' => 'video', 'Embedded Media (Media from another site such as Youtube or Vimeo)' => 'youtube', 'HTML5 Canvas Image/Animation (Javascript file for HTML5 canvas tag)' => 'canvas',), 'image'), 5);
                /* Media category */
                $this->sdmFormCreateFormElement('sdmMediaCategory', 'text', 'Category name to organize media by. Media is ordered in display by media\'s category, place, and finally name.', '', 6);
                /* Media place */
                $this->sdmFormCreateFormElement('sdmMediaPlace', 'select', 'Media\'s palce. Represents media\'s position in display relative to other media in the same category.', $this->sdmFormSetDefaultInputValues(range(1, 1000), 1), 7);
                /* NOTE: Properties that do not have a form element defined will be set upon processing submitted form. */
                break;
        }

        /* Build form elements html */
        $this->sdmFormBuildFormElements();

        foreach ($this->formElements as $formElement) {
            array_push($this->adminFormElements, $this->sdmFormGetFormElementHtml($formElement['id']));
        }

        /* Return adminFormElements for the current adminPanel. */
        return $this->adminFormElements;
    }

    private function assembleAdminFormButtons()
    {
        /* Determine which admin panel is in use. */
        switch ($this->adminPanel) {
            case 'displayCrudPanel':
                /* Create buttons for the displayCrudPanel */
                $buttons = array(
                    'addDisplay' => $this->createSdmMediaDisplayAdminButton('addDisplayButton', 'adminPanel', 'addDisplay', 'Create New Display', array('form' => $this->sdmFormGetFormId())),
                    'editDisplays' => $this->createSdmMediaDisplayAdminButton('editDisplaysButton', 'adminPanel', 'editDisplays', 'Edit Displays', array('form' => $this->sdmFormGetFormId())),
                    'deleteDisplays' => $this->createSdmMediaDisplayAdminButton('deleteDisplaysButton', 'adminPanel', 'deleteDisplays', 'Delete Displays', array('form' => $this->sdmFormGetFormId())),
                );
                /* If there are any displays, show all the displayCrudPanel buttons. */
                if ($this->displaysExist === true) {
                    $this->adminFormButtons = $buttons;
                    break;
                }
                /* If there aren't any displays, only show addDisplaysButton. */
                array_push($this->adminFormButtons, $buttons['addDisplay']);
                break;
            case 'addDisplay':
                /* Create buttons for the addDisplay panel*/
                $buttons = array(
                    'editMedia' => $this->createSdmMediaDisplayAdminButton('editMediaButton', 'adminPanel', 'editMedia', 'Add Media To New Display', array('form' => $this->sdmFormGetFormId())),
                );
                /* Show edit media button. */
                array_push($this->adminFormButtons, $buttons['editMedia']);
                break;
            case 'editMedia':
                $buttons = array(
                    'addMedia' => $this->createSdmMediaDisplayAdminButton('addMediaButton', 'adminPanel', 'addMedia', 'Add Media To Display', array('form' => $this->sdmFormGetFormId())),
                    'editSelectedMedia' => $this->createSdmMediaDisplayAdminButton('editSelectedMediaButton', 'adminPanel', 'editSelectedMedia', 'Edit Selected Media', array('form' => $this->sdmFormGetFormId())),
                    'deleteSelectedMedia' => $this->createSdmMediaDisplayAdminButton('deleteSelectedMediaButton', 'adminPanel', 'deleteSelectedMedia', 'Delete Selected Media', array('form' => $this->sdmFormGetFormId())),
                );
                if ($this->sdmMediaDisplay->sdmMediaDisplayHasMedia($this->displayBeingEdited) === true) {
                    $this->adminFormButtons = $buttons;
                }
                array_push($this->adminFormButtons, $buttons['addMedia']);
                break;
        }
        /* Return adminFormButtons. */
        return $this->adminFormButtons;
    }

    /**
     * Creates an html button.
     * @param $id string The button id.
     * @param $name string The key used to index the button value in the SdmForm array.
     * @param $value mixed The value to send when the button is clicked.
     * @param $label string The text to display on the button.
     * @param array $otherAttributes Associative array of additional attributes.
     * @return string Returns a string of html for the button.
     */
    private function createSdmMediaDisplayAdminButton($id, $name, $value, $label, $otherAttributes = array())
    {
        $attributes = array();
        foreach ($otherAttributes as $attributeName => $attributeValue) {
            $attributes[] = "$attributeName='$attributeValue'";
        }
        return "<button id='$id' name='SdmForm[$name]' type='submit' value='$value' " . implode(' ', $attributes) . ">$label</button>";
    }

    private function devOutput()
    {
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['output' => $this->output]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['adminPanel' => $this->adminPanel]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['adminMode' => $this->adminMode]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['editMode' => $this->editMode]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['displayBeingEdited' => $this->displayBeingEdited]);
        $this->output .= $this->sdmCore->sdmCoreSdmReadArrayBuffered(['formElements' => $this->formElements]);
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

}