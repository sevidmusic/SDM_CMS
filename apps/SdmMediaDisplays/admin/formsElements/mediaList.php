<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/11/16
 * Time: 10:49 AM
 */

/* If there the display's directory has media in it load it and build a radio form element for each piece of media. */
if ($sdmMediaDisplay->sdmMediaDisplayHasMedia($nameOfDisplayBeingEdited) === true) {
    /* create display for admin panel */
    $mediaListDisplay = new SdmMediaDisplay($nameOfDisplayBeingEdited, $SdmCore);
    $displayMediaObjectProperties = $mediaListDisplay->sdmMediaDisplayLoadMediaObjectProperties($nameOfDisplayBeingEdited);
    foreach ($displayMediaObjectProperties as $mediaObject => $mediaObjectProperties) {
        $incrementer = 0;
        foreach ($mediaObjectProperties as $mediaPropertyName => $mediaPropertyValue) {
            if ($currentPanel === 'mediaCrudPanel') {
                array_push($sdmMediaDisplayAdminPanelFormElements['mediaCrudPanel'], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement($mediaPropertyName, 'hidden', '<!-- ' . $mediaPropertyName . ' -->', $mediaPropertyValue, $incrementer));
                $incrementer++;
            }
        }
    }
    // $sdmassembler->sdmCoreSdmReadArray($sdmMediaDisplayAdminPanelFormElements);

}
