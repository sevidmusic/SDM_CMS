/**
 * Animates the background color of a specified element.
 * @param target The target element.
 * @param colors An array of colors to animate through.
 * @param aniTime The time in mili-seconds it takes to animate between each color.
 */
function aniBg(target, colors, aniTime, index) {
    var limit = colors.length;
    var newIndex = ((index + 1) < limit) ? index + 1 : 0; // reset index if {index + 1} !< limit;
    // display current color | newIndex represents the current color
    fadeInText('#colors', '<p>Color Cycle: ' + (index + 1) + '</p><p>Current Background Color: <span style="color:' + colors[index] + '">' + colors[index] + '</span></p>');
    $(target).animate({backgroundColor: colors[index]}, aniTime, function () {
        $(target).animate({backgroundColor: colors[newIndex]}, aniTime - (aniTime / 2), function () {
            aniBg(target, colors, aniTime, newIndex);
        });
    });
}

/**
 * Fades text into an element.
 * @param string target The target element.
 * @param string text The text to fade into the target element.
 *                    Note: text will replace any existing content.
 */
function fadeInText(target, text) {
    $(target).fadeOut(function () {
        $(this).html(text);
    }).fadeIn();
}
/**
 * Returns a random value from an array.
 * @param arr The array to pick from.
 * @returns {*} A random value from the array.
 */
function arrayRand(arr) {
    return arr[Math.floor(Math.random() * arr.length)];
}

/**
 * Generates an array of random hex colors.
 * @param numColors The number of colors to generate. Defaults to 256.
 * @returns {Array} An array of hex colors. How many colors are in the array
 *                  depends on numColors.
 */
function generateColors(numColors) {
    if (typeof(numColors) === 'undefined') {
        numColors = 256;
    }
    var colorsArr = [];
    var alpha = '0 1 2 3 4 5 6 7 8 9 A B C D E F';
    var alphaArr = alpha.split(" ");
    for (var i = 0; i < numColors; i++) {
        var newColor = '#' + arrayRand(alphaArr) + arrayRand(alphaArr) + arrayRand(alphaArr) + arrayRand(alphaArr) + arrayRand(alphaArr) + arrayRand(alphaArr);
        colorsArr.push(newColor);
        //console.log('randCharOne: ' + randCharOne + ' | randCharTwo: ' + randCharTwo + ' | randCharThree: ' + randCharThree + ' | pushed color:' + newColor);
    }
    return colorsArr;
}