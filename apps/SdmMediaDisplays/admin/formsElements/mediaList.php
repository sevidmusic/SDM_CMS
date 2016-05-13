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

    /* Get Media Object properties for the display being edited, and set $addToCurrent parameter to true so they
       are added to the $mediaListDisplay. */
    $displayMediaObjectProperties = $mediaListDisplay->sdmMediaDisplayLoadMediaObjectProperties($nameOfDisplayBeingEdited, true);

    /* Get array of display's media's html strings. */
    $mediaListDisplayElementsHtml = $mediaListDisplay->sdmMediaGetSdmMediaDisplayMediaElementsHtml();

    /* Set initial row color to grey. Color will alternate between black and grey on each loop cycle. */
    $trColor = 'grey';

    /* Construct form radio form element for each media object. */
    foreach ($displayMediaObjectProperties as $mediaObjectId => $mediaObjectProperties) {
        /* Convert array of $mediaObjectProperties to an object. */
        $mediaObject = json_decode(json_encode($mediaObjectProperties));

        /* Alternate row color. */
        $trColor = ($trColor === 'grey' ? 'black' : 'grey');

        /* Assign $trColor as background color for td. */
        $mediaInfoTdStyle = "background: $trColor;";

        /* Initialize $mediaInfoTd array which will hold the td elements for each media object. */
        $mediaInfoTd = array();
        /* build td elements for each media object's properties and store it in the $mediaInfoTd */
        foreach ($mediaObjectProperties as $mediaPropertyName => $mediaPropertyValue) {
            $mediaInfoTd['propertyNames'][] = "<th style='$mediaInfoTdStyle' class='mediaInfoTd'>$mediaPropertyName</th>";
            $mediaInfoTd['propertyValues'][] = "<td style='$mediaInfoTdStyle' class='mediaInfoTd'>$mediaPropertyValue</td>";
        }

        /* Build table of media data from td elements. */
        $mediaTable = "
            <table class='mediaInfoTable'>
                <caption id='mediaListTableCaption'>$mediaObject->sdmMediaDisplayName</caption>
                    <tr><td colspan='2'>" . $mediaListDisplayElementsHtml[$mediaObject->sdmMediaMachineName] . "</td></tr>
                    <tr>" . implode('', $mediaInfoTd['propertyNames']) . "</tr>
                    <tr>" . implode('', $mediaInfoTd['propertyValues']) . "</tr>
            </table>
            ";

        /* Create media */
        $mediaFormElementDescription = "
            <!-- mediaId: $mediaObject->sdmMediaId | mediaDisplayName: $mediaObject->sdmMediaDisplayName -->
            <div id='$mediaObject->sdmMediaId' class='sdmMediaDisplayAdminMediaList'>
                $mediaTable
            </div><div style='clear: both;'></div>
            <div id='mediaTableRadioText'>Select Media \"$mediaObject->sdmMediaDisplayName\"</div>
            <!-- End mediaId: $mediaObject->sdmMediaId | mediaDisplayName: $mediaObject->sdmMediaDisplayName -->
            ";
        $mediaFormValues[$mediaFormElementDescription] = $mediaObject->sdmMediaId;
    }

    /* Create radio from element to allow selection of a piece of media for editing or deletion. */
    array_push($sdmMediaDisplayAdminPanelFormElements['mediaCrudPanel'], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('selectMediaToEdit', 'radio', '<p>Select a piece of media to edit. Use the admin buttons to navigate to the appropriate admin panel after selecting the media you wish to edit.</p>', $mediaFormValues, 20, array('labelTag' => 'div', 'style' => 'width:14px;')));
}
