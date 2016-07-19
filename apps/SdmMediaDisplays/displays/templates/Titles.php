<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 7/11/16
 * Time: 9:41 AM
 */

/**
 * This template is very simple, showing the media's title and the media.
 * It also modifies the media's html so all media items are only 250px wide, and wraps
 * the output in a div that floats left.
 *
 * @param $media string  The html for the media. Provided by SdmMediaDisplay() class.
 * @param $mediaObject object The media object. Provided by the SdmMediaDisplay class.
 */
function TitleAndCategory($media, $mediaObject)
{
    $originalTags = array('<img ', '<video ', '<audio ', '<canvas ', '<iframe ');
    $modifiedTags = array('<img style="width: 420px;" ', '<video style="width: 420px;" ', '<audio style="width: 420px;" ', '<canvas style="width: 420px;" ', '<iframe style="width: 420px;" ');
    $mediaHtml = str_replace($originalTags, $modifiedTags, $media);
    $title = $mediaObject->sdmMediaGetSdmMediaDisplayName();
    echo "<div style='float: left;margin: 20px 25px 0px 25px;height: 500px; border: 5px solid #ffffff; padding: 20px;'><h3>$title</h3>$mediaHtml</div>";
}
