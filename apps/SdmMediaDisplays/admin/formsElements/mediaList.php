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
        //array_push($sdmMediaDisplayAdminPanelFormElements[$panelFormElement], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('displayToEdit', 'hidden', '', $nameOfDisplayBeingEdited, 100));
        foreach ($mediaObjectProperties as $mediaPropertyName => $mediaPropertyValue) {
            $sdmassembler->sdmCoreSdmReadArray($mediaPropertyName . ': ' . $mediaPropertyValue);

        }

    }


}
