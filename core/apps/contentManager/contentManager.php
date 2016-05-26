<?php

/**
 * Content Manager core app: Provides basic content management tools to the Sdm Cms.
 */

// this otpions array will be passed to sdmAssemblerIncorporateAppOutput() wherever this app outputs data.
$options = array(
    'incpages' => array(
        'contentManagerAddContentForm',
        'contentManagerEditContentForm',
        'contentManagerSelectPageToEditForm',
        'contentManagerUpdateContentFormSubmission',
        'contentManagerSelectPageToDeleteForm',
        'contentManagerDeletePageSubmission',
        'contentManagerSelectThemeForm',
        'contentManagerSelectThemeFormSubmission',
        'contentManagerAdministerAppsForm',
        'contentManagerAdministerAppsFormSubmission',
    ),
    'roles' => array('root'),
);
//contentManagerSelectThemeForm.php
$sdmassembler = $sdmassembler; // see SdmAssembler.php and the app loading methods
if (substr($sdmassembler->sdmCoreDetermineRequestedPage(), 0, 14) === 'contentManager') {
    // CREATE A NEW CONTENT MANAGEMENT OBJECT
    $sdmcms = new SdmCms();
    // determine which section of the content manager was requested
    switch ($sdmassembler->sdmCoreDetermineRequestedPage()) {
        // edit content form
        case 'contentManagerAddContentForm':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/forms/contentManagerAddContentForm.php');
            break;
        // edit content form
        case 'contentManagerEditContentForm':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/forms/contentManagerEditContentForm.php');
            break;
        // edit content form
        case 'contentManagerSelectPageToEditForm':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/forms/contentManagerSelectPageToEditForm.php');
            break;
        // edit content form submitted
        case 'contentManagerUpdateContentFormSubmission':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/handlers/contentManagerUpdateContentFormSubmission.php');
            break;
        // delete page form
        case 'contentManagerSelectPageToDeleteForm':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/forms/contentManagerSelectPageToDeleteForm.php');
            break;
        // delete page handler
        case 'contentManagerDeletePageSubmission':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/handlers/contentManagerDeletePageSubmission.php');
            break;
        // change theme
        case 'contentManagerSelectThemeForm':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/forms/contentManagerSelectThemeForm.php');
            break;
        // Select Theme For Submission
        case 'contentManagerSelectThemeFormSubmission':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/handlers/contentManagerSelectThemeFormSubmission.php');
            break;
        // Administer Apps
        case 'contentManagerAdministerAppsForm':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/forms/contentManagerAdministerAppsForm.php');
            break;
        // Administer Apps Form Submission
        case 'contentManagerAdministerAppsFormSubmission':
            require_once($sdmassembler->sdmCoreGetCoreAppDirectoryPath() . '/contentManager/handlers/contentManagerAdministerAppsFormSubmission.php');
            break;
        default:
            // present content manager menu
            $sdmassembler->sdmAssemblerIncorporateAppOutput('
                <div id="contentManager">
                <p>Welcome to the Content Manager. Here you can create, edit, delete, and restore content</p>
                    <ul>
                        <li><a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=contentManagerAddContentForm">Add Content</a></li>
                        <li><a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=contentManagerSelectPageToEditForm">Edit Content</a></li>
                        <li><a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=contentManagerSelectThemeForm">Change Theme</a></li>
                        <li><a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=contentManagerSelectPageToDeleteForm">Delete Page</a></li>
                        <li><a href="' . $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=contentManagerAdministerAppsForm">Administer Apps</a></li>
                    </ul>
                </div>
                ', array('incpages' => array('contentManager'), 'roles' => array('root')));
            break;
    }
}