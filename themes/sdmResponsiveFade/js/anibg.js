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
        var lastIndex = index;
        var newIndex = (index > limit) ? 0 : index + 1;
        console.log('limit: ' + limit + ' | lastIndex: ' + lastIndex + ' | newIndex:' + newIndex);
        $(target).animate({backgroundColor: colors[0]}, aniTime, function () {

            $(target).animate({backgroundColor: colors[1]}, aniTime, function () {
                aniBg(target, colors, aniTime, newIndex);
            });

        });
    }
    /* Animate Background Color */
    aniBg("html body",['#ffffff', '#00FF77'], 3420, 0);
});