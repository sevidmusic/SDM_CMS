<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 7/11/16
 * Time: 9:41 AM
 */

function YoutubeVideos($media, $mediaObject)
{
    $mediaHtml = str_replace('<iframe', '<iframe width="100%" height="850px"', $media);
    $title = $mediaObject->sdmMediaGetSdmMediaDisplayName();
    $category = $mediaObject->sdmMediaGetSdmMediaCategory();
    echo "
        <div class='youtubeVideoContainer'>
            <div class='youtubeVideoWrapper'>
                <div class='youtubeVideoMeta'>
                    <h2>$category</h2>
                    <h3>$title</h3>
                </div>
                <div class='youtubeVideoMedia'>$mediaHtml</div>
            </div>
        </div>
        ";
}
