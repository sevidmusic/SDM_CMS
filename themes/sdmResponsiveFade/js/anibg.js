$(document).ready(function () {
    /* Image url to background image we are inserting. */
    var imageUrl = "http://localhost:8888/sdm_cms/themes/sdmResponsiveFade/imgs/sdm-cms-logo.png";

    /* Insert background image */
    $("html body").css('background-image', 'url(' + imageUrl + ')');

    /* Create an array of n random colors. */
    var colorsArr = generateColors(256);

    /* Add div to page to display current background color */
    $('body').prepend('<div style="background: #000000; font-size: .5em; border-radius: 9px; text-align: center; width:27%; margin:5px auto; padding: 2px 0px 2px 0px; opacity:.75;" id="colors">Starting background color animation...</div>');

    /* Animate page bg using colorsArr. */
    aniBg("html body", colorsArr, 5420, 0);
});