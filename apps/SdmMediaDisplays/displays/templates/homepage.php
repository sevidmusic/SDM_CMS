<?php
/* Display function for the default display. */

/**
 * Wraps a $media objects html in a <li> tag.
 *
 * @param $media string The $media object's html to wrap in a <li> tag.
 */
function defaultSdmMediaDisplayDiv($media)
{
    echo '<div id="sdmMediaHomepageDisplay"><div class="sdmMediaHomepageDisplayMedia">' . $media . '</div></div>';
}

echo $sdmMediaDisplay->sdmMediaDisplayGenerateMediaDisplay('defaultSdmMediaDisplayDiv');
?>
