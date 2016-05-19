<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/19/16
 * Time: 3:09 PM
 */

function coolDefconVideoDisplays($media, $mediaObject)
{
    $title = $mediaObject->sdmMediaGetSdmMediaDisplayName();
    $category = $mediaObject->sdmMediaGetSdmMediaCategory();
    $mediaUrl = $mediaObject->sdmMediaGetSdmMediaSourceUrl();
    $provider = parse_url($mediaUrl);
    echo "
        <div class='coolDefconVideoMediaContainer'>
            <div class='coolDefconVideoMediaWrapper'>
                <div class='coolDefconVideoMediaTitle'>
                    <h3>$title</h3>
                    <h5>$category</h5>
                </div>
                <div class='coolDefconVideoMedia'>$media</div>
                <div class='coolDefconVideoMediaOriginalLinkData'><p><a href='$mediaUrl'>Click Here</a> to view on <a href='$mediaUrl'>{$provider['host']}</a></p></div>
            </div>
        </div>
        ";
}

echo $sdmMediaDisplay->sdmMediaDisplayGenerateMediaDisplay('coolDefconVideoDisplays');
/* clear display floats */
echo "<div style='clear: both'></div>";
