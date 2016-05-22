<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/19/16
 * Time: 3:09 PM
 */

function ourSelectionsDisplays($media, $mediaObject)
{
    $mediaHtml = str_replace('<img', '<img width="320"', $media);
    $title = $mediaObject->sdmMediaGetSdmMediaDisplayName();
    $category = $mediaObject->sdmMediaGetSdmMediaCategory();
    if ($category !== 'Logos') {
        echo "
        <div class='ourSelectionsMediaContainer'>
            <div class='ourSelectionsMediaWrapper'>
                <div class='ourSelectionsMediaTitle'>
                    <h3>$title</h3>
                </div>
                <div class='ourSelectionsMedia'>$mediaHtml</div>
            </div>
        </div>
        ";
    } else {
        echo $media;
    }
}

echo $sdmMediaDisplay->sdmMediaDisplayGenerateMediaDisplay('ourSelectionsDisplays');
/* clear display floats */
echo "<div style='clear: both'></div>";
