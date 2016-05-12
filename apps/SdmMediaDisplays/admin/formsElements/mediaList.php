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
    $displayMediaObjectProperties = $mediaListDisplay->sdmMediaDisplayLoadMediaObjectProperties($nameOfDisplayBeingEdited);
    $trColor = 'grey';
    foreach ($displayMediaObjectProperties as $mediaObject => $mediaObjectProperties) {
        $mediaObject = json_decode(json_encode($mediaObjectProperties));
        $trColor = ($trColor === 'grey' ? 'black' : 'grey');
        $table = "
                <table style='overflow:scroll;width:78%;float:right;position:relative; display:block;padding:20px;margin-top:22px;margin-right: 177px;margin-bottom:-170px;background:#3498db;font-size: .42em;border: 3px solid #ffffff;-webkit-border-radius: 9px;-moz-border-radius: 9px;border-radius: 9px;'>
                    <tr>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaType</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaId</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaMachineName</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaDisplayName</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaCategory</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaPlace</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaProtected</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaPublic</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaType</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaSourceName</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaSourceExtension</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaPath</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>sdmMediaUrl</td>
                    </tr>
                    <tr>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaType</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaId</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaMachineName</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaDisplayName</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaCategory</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaPlace</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaProtected</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaPublic</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaType</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaSourceName</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaSourceExtension</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaPath</td>
                        <td style='padding:20px; border:3px solid #ffffff;border-radius:3px;background: $trColor;min-width: 420px;'>$mediaObject->sdmMediaUrl</td>
                    </tr>
                </table>
        ";
        $media['mediaInfoTable'] = $table;
        $media['formValues'][$mediaObject->sdmMediaDisplayName . $media['mediaInfoTable']] = $mediaObject->sdmMediaId;
        /*
        foreach ($mediaObjectProperties as $mediaPropertyName => $mediaPropertyValue) {
            /* Convert from camel case to words. *
            preg_match_all('/((?:^|[A-Z])[a-z]+)/', $mediaPropertyName, $propertyNameFormatted);
            /* Construct panel name string from camel case to words conversion result, use ucwords()
            so first letter of each word is capitalized. *
            $propertyDisplayName = ucwords(implode(' ', $propertyNameFormatted[0]));
            $media[$propertyDisplayName . ' Some Text'] = $mediaPropertyValue;
        }*/
    }
    array_push($sdmMediaDisplayAdminPanelFormElements['mediaCrudPanel'], $sdmMediaDisplaysAdminForm->sdmFormCreateFormElement('selectMediaToEdit', 'radio', '<p>Select a piece of media to edit. Use the admin buttons to navigate to the appropriate admin panel after selecting the media you wish to edit.</p>', $media['formValues'], 20, array('labelTag' => 'div')));
}
