<?php
/* Display function for the default display. */

/**
 * Wraps a $media objects html in a <li> tag.
 *
 * @param $media string The $media object's html to wrap in a <li> tag.
 */
function defaultSdmMediaDisplayList($media)
{
    $styles = array(
        'text-align:center',
        'margin-bottom:52px',
        'width: 1142px',
        'padding: 42px',
        'border: 3px solid red',
    );
    echo '<li style="' . implode('; ', $styles) . '">' . $media . '</li>';
}

?>

<?
/*
<h1>Default Generated Display</h1>
<ul>
    <?php
    echo $sdmMediaDisplay->sdmMediaDisplayGenerateMediaDisplay();
    ?>
</ul>*/
?>
<?php
/*
?>
<h1>Custom Generated Display</h1>
<ul>
    <?php
    echo $sdmMediaDisplay->sdmMediaDisplayGenerateMediaDisplay('defaultSdmMediaDisplayList');
    ?>
</ul>
*/