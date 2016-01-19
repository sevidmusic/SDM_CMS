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
    function aniBg(target, colors, aniTime, index) {
        var limit = colors.length;
        var newIndex = ((index + 1) < limit) ? index + 1 : 0; // reset index if {index + 1} !< limit;
        console.log('limit: ' + limit + ' | index: ' + index + ' | newIndex:' + newIndex);
        $(target).animate({backgroundColor: colors[index]}, aniTime, function () {

            $(target).animate({backgroundColor: colors[newIndex]}, aniTime, function () {
                aniBg(target, colors, aniTime, newIndex);
            });

        });
    }
    /* Animate Background Color */
    aniBg("html body",['#ffffff', '#00FF77'], 3420, 0);
});