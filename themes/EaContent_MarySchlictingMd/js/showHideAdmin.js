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

    // Defined jquery rollovers for buttons with class .adminShowHideButton
    $(".adminShowHideButton").hover(function () {
        $(this).css("background-color", "#ffffff;");
        $(this).css("color", "#000000;");
        $(this).css("opacity", "1");
        $(this).css("border-color", "#888888");
    }, function () {
        $(this).css("background-color", "#000000;");
        $(this).css("color", "#ffffff;");
        $(this).css("opacity", ".5");
    });

});