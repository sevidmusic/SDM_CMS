$(document).ready(function () {
    /* Image url to background image we are inserting. */
    var imageUrl = "http://localhost:8888/sdm_cms/themes/sdmResponsiveFade/imgs/sdm-cms-logo.png";

    /* Insert background image */
    $("html body").css('background-image', 'url(' + imageUrl + ')');

    /* Create an array of n random colors. */
    var colorsArr = generateColors(3);

    /* Animate page bg using colorsArr. */
    aniBg("html body", colorsArr, 1420, 0);
});