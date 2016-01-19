$(document).ready(function () {
    /* Image url to background image we are inserting */
    var imageUrl = "http://localhost:8888/sdm_cms/themes/sdmResponsiveFade/imgs/sdm-cms-logo.png";

    /* Insert background image */
    $("html body").css('background-image', 'url(' + imageUrl + ')');

    /**
     *
     * @param target
     * @param color
     * @param aniTime
     */
    function aniBg(target, colors, aniTime) {

        $(target).animate({backgroundColor: colors[0]}, aniTime, function () {

            $(target).animate({backgroundColor: colors[1]}, aniTime, function () {
                anibg(target, colors, aniTime);
            });

        });
    }
    /* Animate Background Color */
    aniBg("html body",['#ffffff', '#FF3377'], 5420);
});