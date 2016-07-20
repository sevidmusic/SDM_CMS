<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 7/11/16
 * Time: 9:41 AM
 */

/**
 * This template is very simple, and is meant to demo how a template works. It's MEDIA function
 * shows the media's title and the media. It also modifies the media's html before outputting it
 * so all media items are only 420px wide, and wraps the MEDIA output in a div that floats left.
 *
 * This template also implements the "Before" and "After" template functions which allow
 * a template to create output before and after the display's media items. In this template
 * the "Before" function simply adds a title for the display wrapped in an <h2> tag, and the
 * "After" functions adds a div that clears the floats applied to the media items so the rest
 * of the page is not effected by the left float of the media objects.
 *
 * This is a great demo template for illustrating how to setup a SdmMediaDisplay template.
 *
 * NOTE: This template uses a return statement for the output of it's functions, the SdmMediaDisplay() class
 * also tolerates directly using print and echo to generate output. For example the functions could be re-written
 * as follows and would still work:
 *
 * function TitlesBefore() {
 * echo '<h2>Floating Media Display with Titles:</h2>';
 * }
 *
 * function Titles($media, $mediaObject) {
 * $originalTags = array('<img ', '<video ', '<audio ', '<canvas ', '<iframe ');
 * $modifiedTags = array('<img style="width: 420px;" ', '<video style="width: 420px;" ', '<audio style="width: 420px;" ', '<canvas style="width: 420px;" ', '<iframe style="width: 420px;" ');
 * $mediaHtml = str_replace($originalTags, $modifiedTags, $media);
 * $title = $mediaObject->sdmMediaGetSdmMediaDisplayName();
 * echo "<div style='float: left;margin: 20px 25px 0px 25px;height: 500px; border: 5px solid #ffffff; padding: 20px;'><h3>$title</h3>$mediaHtml</div>";
 * }
 *
 * function TitlesAfter() {
 * echo '<div style="clear:both;"></div>';
 * }
 *
 * The only catch is the template must be consistent in using echo/print, or return, as mixing them could break the "Before", MEDIA, "After"
 * output logic. For instance, if the MEDIA function uses echo, and the "Before" function uses return, then the output of the "Before" function
 * would actually show up after the output of the MEDIA function since echo will force the output onto the page immediately.
 *
 */

/**
 * "Before" function.
 */
function TitlesBefore()
{
    return '<h2>Floating Media Display with Titles:</h2>';
}

/**
 * Media Function
 * @param $media string The media html.
 * @param $mediaObject object The media object.
 */
function Titles($media, $mediaObject)
{
    $originalTags = array('<img ', '<video ', '<audio ', '<canvas ', '<iframe ');
    $modifiedTags = array('<img style="width: 420px;" ', '<video style="width: 420px;" ', '<audio style="width: 420px;" ', '<canvas style="width: 420px;" ', '<iframe style="width: 420px;" ');
    $mediaHtml = str_replace($originalTags, $modifiedTags, $media);
    $title = $mediaObject->sdmMediaGetSdmMediaDisplayName();
    return "<div style='float: left;margin: 20px 25px 0px 25px;height: 500px; border: 5px solid #ffffff; padding: 20px;'><h3>$title</h3>$mediaHtml</div>";
}

/**
 * "After" function.
 */
function TitlesAfter()
{
    return '<div style="clear:both;"></div>';
}
