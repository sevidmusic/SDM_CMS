<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/11/16
 * Time: 10:49 AM
 */

/* If there the display's directory has media in it load it and build a radio form element for each piece of media. */
if ($sdmMediaDisplay->sdmMediaDisplayHasMedia($nameOfDisplayBeingEdited) === true && $currentPanel === 'mediaCrudPanel') {
    /* create display for admin panel */
    $mediaListDisplay = new SdmMediaDisplay($nameOfDisplayBeingEdited, $SdmCore);

    /* Get Media Object properties for the diplay being edited. */
    $displayMediaObjectProperties = $mediaListDisplay->sdmMediaDisplayLoadMediaObjectProperties($nameOfDisplayBeingEdited);

    /* Set initial row color to grey. Color will alternate between black and grey on each loop cycle. */
    $trColor = 'grey';

    /* Initialize $mediaInfoTd array which will hold the td elements for each media object. */
    $mediaInfoTd = array();

    /* Construct form radio form element for each media object. */
    foreach ($displayMediaObjectProperties as $mediaObjectId => $mediaObjectProperties) {
        /* Convert array of $mediaObjectProperties to an object. */
        $mediaObject = json_decode(json_encode($mediaObjectProperties));

        /* Alternate row color. */
        $trColor = ($trColor === 'grey' ? 'black' : 'grey');

        /* Assign $trColor as background color for td. */
        $mediaInfoTdStyle = "background: $trColor;";

        /* build td elements for each media object's properties and store it in the $mediaInfoTd */
        foreach ($mediaObjectProperties as $mediaPropertyName => $mediaPropertyValue) {
            $mediaInfoTd['propertyNames'][] = "<td style='$mediaInfoTdStyle' class='mediaInfoTd'>$mediaPropertyName</td>";
            $mediaInfoTd['propertyValues'][] = "<td style='$mediaInfoTdStyle' class='mediaInfoTd'>$mediaPropertyValue</td>";
        }

        /* Build table of media data from td elements. */
        $mediaTable = "<table class='mediaInfoTable'><tr>" . implode('', $mediaInfoTd['propertyNames']) . "</tr><tr>" . implode('', $mediaInfoTd['propertyValues']) . "</tr></table>";

        /**/
        /* Create media */ // media id used in key to insure uniqueness in case to media items have same display name
        $mediaFormValues['<!-- mediaId: ' . $mediaObject->sdmMediaId . $mediaObject->sdmMediaDisplayName . ' -->' . $mediaTable] = $mediaObject->sdmMediaId;
    }

    /* Create radio from element to allow selection of a piece of media for editing or deletion. */
    array_push($sdmMediaDisplayAdminPanelFormElements['mediaCrudPanel'], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('selectMediaToEdit', 'radio', '<p>Select a piece of media to edit. Use the admin buttons to navigate to the appropriate admin panel after selecting the media you wish to edit.</p>', $mediaFormValues, 20, array('labelTag' => 'div')));
}
