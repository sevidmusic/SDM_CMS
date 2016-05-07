<?php
/* Display function for the default display. */

/**
 * Wraps a $media objects html in a <li> tag.
 *
 * @param $media string The $media object's html to wrap in a <li> tag.
 */
function defaultSdmMediaDisplayDiv($media)
{
    $styles = array(
        'text-align:center',
        'margin-bottom:52px',
        'width: 1142px',
        'height: 1142px',
        'padding: 42px',
        'border: 3px solid #ffffff',
    );
    echo '<div style="' . implode('; ', $styles) . '">' . $media . '</div>';
}

echo $sdmMediaDisplay->sdmMediaDisplayGenerateMediaDisplay('defaultSdmMediaDisplayDiv');
?>
