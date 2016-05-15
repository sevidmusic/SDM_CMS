<?php
/* Display function for the default display. */

/**
 * Wraps a $media objects html in a <li> tag.
 *
 * @param $media string The $media object's html to wrap in a <li> tag.
 */
function assembleHomepageDisplay($media, $mediaObject)
{
    echo '<div id="sdmMediaHomepageDisplay"><div class="sdmMediaHomepageDisplayMedia"><h2>' . $mediaObject->sdmMediaGetSdmMediaDisplayName() . '</h2>' . $media . '</div></div>';
}

echo $sdmMediaDisplay->sdmMediaDisplayGenerateMediaDisplay('assembleHomepageDisplay');

