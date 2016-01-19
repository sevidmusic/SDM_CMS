$(document).ready(function () {
    /* Animate background color */
    //$("html body").animate({backgroundColor: "#ffffff"}, 1420);
    /* Image url to background image we are inserting */
    var imageUrl = "http://localhost:8888/sdm_cms/themes/sdmResponsiveFade/imgs/sdm-cms-logo.png";
    /* Insert background image */
    $("html body").css('background-image', 'url(' + imageUrl + ')');
    /* Pulsate */
    var ar = $('html body');

    function pulsate() {
        ar.animate({backgroundColor: "#EEEEEE"}, 1420, function () {
            ar.animate({backgroundColor: "#000000"}, 1420, pulsate);
        });
    }

    pulsate();
});