<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/7/16
 * Time: 12:50 PM
 */

/* Define form elements for each Sdm Media Displays admin panel. */
$pagesAvailableToDisplays = $sdmassembler->sdmCoreDetermineAvailablePages();
$sdmMediaDisplayAdminPanelFormElements = array(
    'selectDisplayPanel' => array(
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('target_display', 'select', 'Select A Display', $pagesAvailableToDisplays, 0),
    ),
    'editMedia' => array(
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaMachineName', 'text', 'Machine Safe Name For Media', '', 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaPlace', 'select', 'Position in display relative to other media.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(range(1, 1000), 1), 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaProtected', 'radio', 'Protect media from download. (still in development)', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('Yes' => true, 'No' => false,), false), 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaPublic', 'radio', 'Display media in public views.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('Yes' => true, 'No' => false,), true), 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceExtension', 'select', 'Select media\'s file extension.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('mp3 Audio File' => 'mp3', 'm4v Video File' => 'm4v', 'jpg Image File' => 'jpg', 'png Image File' => 'png'), 'mp3'), 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceName', 'text', 'Name of the media file.', '', 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourcePath', 'text', 'Path to media files parent directory.', '', 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceType', 'select', 'Is the media source external or local? External sources are sources from other sites, such as Youtube. Local sources are stored on the site\'s server.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('External (Media resource from another site)' => 'external', 'Local (Media stored locally)' => 'local',), 'audio'), 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceUrl', 'text', 'Url To Media (If youtube url it must be the embed url provided by youtube.)', '', 4),
        $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaType', 'select', 'Select the media\s type.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('Audio' => 'audio', 'Video' => 'video', 'Youtube Video' => 'youtube', 'HTML5 Canvas Image/Animation (Javascript file for HTML5 canvas tag)' => 'canvas',), 'audio'), 4),
    ),
);

/* Incorporate form elements into the form. */
$sdmMediaDisplaysAdminForm->sdmFormBuildFormElements();

/* Get current admin panels form elements */
$currentPanelsFormElements = array();

foreach ($sdmMediaDisplayAdminPanelFormElements as $panel => $panelFormElements) {
    if ($panel === $currentPanel) {
        foreach ($panelFormElements as $formElement) {
            $currentPanelsFormElements[] = $sdmMediaDisplaysAdminForm->sdmFormGetFormElementHtml($formElement['id']);
        }
    }
}