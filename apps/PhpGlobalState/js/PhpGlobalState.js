/**
 * Created by sevidmusic on 5/1/16.
 */

$(document).ready(function () {

    // Hide Php Global State initially
    $("#phpGlobalStateDisplay").hide();

    // Hide Php Global State when hidePhpGlobalState button is clicked and decrease height of PhpGlobalState
    $("#showPhpGlobalStateShowHideButton").click(function () {
        $("#phpGlobalStateDisplay").slideToggle("slow");
    });

});