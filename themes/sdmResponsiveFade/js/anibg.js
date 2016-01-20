$(document).ready(function () {
    /* Image url to background image we are inserting */
    var imageUrl = "http://localhost:8888/sdm_cms/themes/sdmResponsiveFade/imgs/sdm-cms-logo.png";

    /* Insert background image */
    //$("html body").css('background-image', 'url(' + imageUrl + ')');

    /**
     * Animates the background color of a specified element.
     * @param target The target element.
     * @param colors An array of colors to animate through.
     * @param aniTime The time in mili-seconds it takes to animate between each color.
     */
    function aniBg(target, colors, aniTime, index) {
        var limit = colors.length;
        var newIndex = ((index + 1) < limit) ? index + 1 : 0; // reset index if {index + 1} !< limit;
        // console.log('limit: ' + limit + ' | index: ' + index + ' | newIndex:' + newIndex);
        $(target).animate({backgroundColor: colors[index]}, aniTime, function () {
            // console.log('color: ' + colors[index]);
            $(target).animate({backgroundColor: colors[newIndex]}, aniTime - (aniTime / 2), function () {
                // console.log(colors[newIndex]);
                aniBg(target, colors, aniTime, newIndex);
            });

        });
    }

    colorsArr = [];
    var alpha = '0 1 2 3 4 5 6 7 8 9 A B C D E F';
    var alphaArr = alpha.split(" ");
    /* Create Random Array of Random Colors */
    for(var i = 0; i < 100; i++) {
        var randCharOne = alphaArr[Math.floor(Math.random() * alphaArr.length)];
        var randCharTwo = alphaArr[Math.floor(Math.random() * alphaArr.length)];
        var randCharThree = alphaArr[Math.floor(Math.random() * alphaArr.length)];
        var newColor = '#' + randCharOne + randCharOne + randCharTwo + randCharTwo + randCharThree + randCharThree;
        colorsArr.push(newColor);
        // console.log('randCharOne: ' + randCharOne + ' | randCharTwo: ' + randCharTwo + ' | randCharThree: ' + randCharThree + ' | pushed color:' + newColor);
    }
    aniBg("html body", colorsArr, 1420, 0);
});