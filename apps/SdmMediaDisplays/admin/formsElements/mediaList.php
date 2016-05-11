<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/11/16
 * Time: 10:49 AM
 */

/* If there the display's directory has media in it load it and build a radio form element for each piece of media. */
if ($sdmMediaDisplay->sdmMediaDisplayHasMedia($nameOfDisplayBeingEdited) === true) {
    var_dump('loaded media list');
    /* create display for admin panel */
    $mediaListDisplay = new SdmMediaDisplay($nameOfDisplayBeingEdited, $SdmCore);
    $sdmassembler->sdmCoreSdmReadArray($mediaListDisplay);


}
