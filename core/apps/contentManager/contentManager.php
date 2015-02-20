<?php

//contentManagerSelectThemeForm.php
$sdmcore = $sdmcore; // see SdmAssembler.php and the app loading methods
if (substr($sdmcore->determineRequestedPage(), 0, 14) === 'contentManager') {
    // CREATE A NEW CONTENT MANAGEMENT OBJECT
    $sdmcms = SdmCms::sdmInitializeCms();
    // determine which section of the content manager was requested
    switch ($sdmcore->determineRequestedPage()) {
        // edit content form
        case 'contentManagerAddContentForm':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/forms/contentManagerAddContentForm.php');
            break;
        // edit content form
        case 'contentManagerEditContentForm':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/forms/contentManagerEditContentForm.php');
            break;
        // edit content form
        case 'contentManagerSelectPageToEditForm':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/forms/contentManagerSelectPageToEditForm.php');
            break;
        // edit content form submitted
        case 'contentManagerUpdateContentFormSubmission':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/handlers/contentManagerUpdateContentFormSubmission.php');
            break;
        // delete page form
        case 'contentManagerSelectPageToDeleteForm':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/forms/contentManagerSelectPageToDeleteForm.php');
            break;
        // delete page handler
        case 'contentManagerDeletePageSubmission':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/handlers/contentManagerDeletePageSubmission.php');
            break;
        // change theme
        case 'contentManagerSelectThemeForm':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/forms/contentManagerSelectThemeForm.php');
            break;
        // Select Theme For Submission
        case 'contentManagerSelectThemeFormSubmission':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/handlers/contentManagerSelectThemeFormSubmission.php');
            break;
        // Administer Apps
        case 'contentManagerAdministerAppsForm':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/forms/contentManagerAdministerAppsForm.php');
            break;
        // Administer Apps Form Submission
        case 'contentManagerAdministerAppsFormSubmission':
            require_once($sdmcore->getCoreAppDirectoryPath() . '/contentManager/handlers/contentManagerAdministerAppsFormSubmission.php');
            break;
        default:
            // present content manager menu
            $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '
                <div id="contentManager">
                <p>Welcome to the Content Manager. Here you can create, edit, delete, and restore content</p>
                    <ul>
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=contentManagerAddContentForm">Add Content</a></li>
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=contentManagerSelectPageToEditForm">Edit Content</a></li>
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=contentManagerSelectThemeForm">Change Theme</a></li>
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=contentManagerSelectPageToDeleteForm">Delete Page</a></li>
                        <li><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=contentManagerAdministerAppsForm">Administer Apps</a></li>
                    </ul>
                </div>
                ';
            break;
    }
}