<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/13/16
 * Time: 11:23 AM
 */
if ($currentPanel === 'editMediaPanel') {
    switch ($adminMode) {
        case 'editMedia':
            /* Determine id of the media to be edited. */
            $mediaToEdit = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('selectMediaToEdit');

            /* Create display for admin panel */
            $editMediaPanelDisplay = new SdmMediaDisplay($nameOfDisplayBeingEdited, $SdmCore);

            /* Get Media Object properties for the display being edited, and set $addToCurrent parameter to true so they
               are added to the $editMediaPanelDisplay. */
            $editMediaPanelDisplayMediaObjectProperties = $editMediaPanelDisplay->sdmMediaDisplayLoadMediaObjectProperties($nameOfDisplayBeingEdited, true);

            /* Get array of display's media's html strings. */
            $editMediaDisplayElementsHtml = $editMediaPanelDisplay->sdmMediaGetSdmMediaDisplayMediaElementsHtml();

            /* Get the media to edit's properties.  */
            $mediaToEditProperties = $editMediaPanelDisplayMediaObjectProperties[$mediaToEdit];

            /* Get media to edit's html */
            $mediaToEditsHtml = $editMediaDisplayElementsHtml[$mediaToEditProperties['sdmMediaMachineName']];

            /* The following will be set on submission of changes | protected $sdmMediaMachineName; protected $sdmMediaSourceName; protected $sdmMediaSourceExtension; */

            /* Create editMediaPanel form elements. */
            $editPanelFormElements = array(
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaDisplayName', 'text', '<p>' . $mediaToEditsHtml . '</p><br/>Name or title for the media.', $mediaToEditProperties['sdmMediaDisplayName'], 1),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaCategory', 'text', 'Category name to organize media by. Media is ordered in display by media\'s category, place, and finally name.', $mediaToEditProperties['sdmMediaCategory'], 2),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaPlace', 'select', 'Media\'s palce. Represents media\'s position in display relative to other media in the same category.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(range(1, 1000), $mediaToEditProperties['sdmMediaPlace']), 3),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceType', 'select', '<p>Is the media source external or local?</p><p>External sources are sources from other sites, such as Youtube. Local sources are, as the name implies, stored locally.<br/><span style="font-size: .5em;">Use local if you are uploading the media.</br>Use external if media is from a url to a site such as youtube.</span></p>', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('External (Media resource from another site)' => 'external', 'Local (Media stored locally)' => 'local',), $mediaToEditProperties['sdmMediaSourceType']), 4),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaFile', 'file', 'Upload media file | Only used for local media sources.', null, 5),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceUrl', 'text', 'Url To Media | Only set for external media sources. (If youtube url it must be the embed url provided by youtube.)', ($mediaToEditProperties['sdmMediaSourceUrl'] ? $mediaToEditProperties['sdmMediaSourceUrl'] : 'local'), 6),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaType', 'select', 'Select the media\'s type.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('Image' => 'image', 'Audio' => 'audio', 'Video' => 'video', 'Youtube Video' => 'youtube', 'HTML5 Canvas Image/Animation (Javascript file for HTML5 canvas tag)' => 'canvas',), $mediaToEditProperties['sdmMediaType']), 7),
                // hidden elements
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaId', 'hidden', '', $mediaToEditProperties['sdmMediaId'], 420),
                // for now, enforce local path
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourcePath', 'hidden', '', $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/media', 421),
                //$sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourcePath', 'hidden', '', $mediaToEditProperties['sdmMediaSourcePath'], 421),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaProtected', 'hidden', '', $mediaToEditProperties['sdmMediaProtected'], 422),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaPublic', 'hidden', '', $mediaToEditProperties['sdmMediaPublic'], 423),
            );
            break;
        case 'addMedia':
            $editPanelFormElements = array(
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaDisplayName', 'text', '<p>' . $mediaToEditsHtml . '</p><br/>Name or title for the media.', '', 1),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaCategory', 'text', 'Category name to organize media by. Media is ordered in display by media\'s category, place, and finally name.', '', 2),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaPlace', 'select', 'Media\'s palce. Represents media\'s position in display relative to other media in the same category.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(range(1, 1000), 1), 3),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceType', 'select', '<p>Is the media source external or local?</p><p>External sources are sources from other sites, such as Youtube. Local sources are, as the name implies, stored locally.<br/><span style="font-size: .5em;">Use local if you are uploading the media.</br>Use external if media is from a url to a site such as youtube.</span></p>', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('External (Media resource from another site)' => 'external', 'Local (Media stored locally)' => 'local',), 'local'), 4),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaFile', 'file', 'Upload media file | Only used for local media sources.', null, 5),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourceUrl', 'text', 'Url To Media | Only set for external media sources. (If youtube url it must be the embed url provided by youtube.)', '', 6),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaType', 'select', 'Select the media\'s type.', $sdmMediaDisplaysAdminForm->sdmFormSetDefaultInputValues(array('Image' => 'image', 'Audio' => 'audio', 'Video' => 'video', 'Youtube Video' => 'youtube', 'HTML5 Canvas Image/Animation (Javascript file for HTML5 canvas tag)' => 'canvas',), 'image'), 7),
                // hidden elements
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaId', 'hidden', '', rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999) . rand(1000, 9999), 420),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaSourcePath', 'hidden', '', $sdmassembler->sdmCoreGetUserAppDirectoryPath() . '/SdmMediaDisplays/displays/media', 421),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaProtected', 'hidden', '', false, 422),
                $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('sdmMediaPublic', 'hidden', '', false, 423),
            );
            break;
        default:
            // do nothing, loggin error unnecessary and cluttered error log.
            //error_log('User app SdmMediaDisplays editMedia admin panel accessed with invalid admin mode "' . $adminMode . '"');
    }
    //$mediaToEditProperties->sdmMediaId

    foreach ($editPanelFormElements as $formElement) {
        $sdmMediaDisplayAdminPanelFormElements['editMediaPanel'][] = $formElement;
    }
}