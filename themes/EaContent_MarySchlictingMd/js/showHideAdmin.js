/**
 * Created by sevidmusic on 4/24/16.
 */

$(document).ready(function () {
    // Hide admin panel initially
    $("#adminPanelDisplay").hide();

    // Hide showAdminBar button initially
    $("#showAdminBar").hide();

    // Hide admin panel when hideAdminPanel button is clicked
    $("#hideAdminPanel").click(function () {
        $("#adminPanelDisplay").hide();
    });

    // Show admin panel when showAdminPanel button is clicked
    $("#showAdminPanel").click(function () {
        $("#adminPanelDisplay").show();
    });

    // Hide admin bar when hideAdminBar button is clicked
    $("#hideAdminBar").click(function () {
        $("#adminBarDisplay").hide();
        // show showAdminBar button so user can re-enable adminBarDisplay
        $("#showAdminBar").show();
    });

    // Show admin bar when showAdminBar button is clicked
    $("#showAdminBar").click(function () {
        $("#adminBarDisplay").show();
        // hide self when clicked
        $("#showAdminBar").hide();
    });

    function adminButtonRollover(element, newCssPropertyValues) {
        $(element).css("background-color", newCssPropertyValues.backgroundColor);
        $(element).css("color", newCssPropertyValues.color);
        $(element).css("opacity", newCssPropertyValues.opacity);
        $(element).css("border-color", newCssPropertyValues.borderColor);
    }

    // enforce initial styles
    //$(".adminShowHideButton").css("background-color", "#000000;");
    //$(".adminShowHideButton").css("color", "#ffffff;");
    //$(".adminShowHideButton").css("opacity", ".5");
    //$(".adminShowHideButton").css("border-color", "#CCCCCC");

    // Unpack original values of css properties that will be modified on rollover
    var initialCssPropertyValues = {
        backgroundColor: $(".adminShowHideButton").css('background-color'),
        color: $(".adminShowHideButton").css('color'),
        opacity: $(".adminShowHideButton").css('opacity'),
        borderColor: $(".adminShowHideButton").css('border-color'),
    };
    // Defined jquery rollovers for buttons with class .adminShowHideButton
    $(".adminShowHideButton").hover(function () {
        var newCssPropertyValues = {
            backgroundColor: "#ffffff;",
            color: "#000000;",
            opacity: "1",
            borderColor: "#888888",
        };
        adminButtonRollover(this, newCssPropertyValues);
    }, function () {
        adminButtonRollover(this, initialCssPropertyValues);
    });

});