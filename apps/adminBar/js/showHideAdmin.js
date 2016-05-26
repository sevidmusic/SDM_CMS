/**
 * Created by sevidmusic on 4/24/16.
 */

// Create a cookie that adminBar.php can check to make sure to only display admin bar if javascript is enabled.
document.cookie = "adminBarDetectJavascriptEnabled=true";

$(document).ready(function () {

    var $_GET = {};

    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
        function decode(s) {
            return decodeURIComponent(s.split("+").join(" "));
        }

        $_GET[decode(arguments[1])] = decode(arguments[2]);
    });

    /* Determine requested page. */
    var requestedPage = $_GET['page'];

    /* Determine what pages to display admin bar and panel on immediately */
    var displayPages = [
        'admin',
        /*
        'contentManager',
        'contentManagerAddContentForm',
        'contentManagerEditContentForm',
        'contentManagerSelectPageToEditForm',
        'contentManagerUpdateContentFormSubmission',
        'contentManagerSelectPageToDeleteForm',
        'contentManagerDeletePageSubmission',
        'contentManagerSelectThemeForm',
        'contentManagerSelectThemeFormSubmission',
        'contentManagerAdministerAppsForm',
        'contentManagerAdministerAppsFormSubmission',
        'navigationManager',
        'navigationManagerAddMenuStage1', // select number of menu items
        'navigationManagerAddMenuStage2', // configure menu items
        'navigationManagerAddMenuStage3', // configure menu
        'navigationManagerAddMenuStage4', // add menu
        'navigationManagerDeleteMenuStage1', // select menu to delete
        'navigationManagerDeleteMenuStage2', // confirm selected menu should be deleted
        'navigationManagerDeleteMenuStage3', // delete menu
        'navigationManagerEditMenuStage1', // select menu to edit
        'navigationManagerEditMenuStage2', // edit menu settings or select menuItem to edit
        'navigationManagerEditMenuStage3_submitmenuchanges', // handle edit menu form submission
        'navigationManagerEditMenuStage3_editmenuitem', // edit menuItem settings
        'navigationManagerEditMenuStage3_submitmenuitemchanges', // handle edit menu item form submission
        'navigationManagerEditMenuStage3_confirmdeletemenuitem', // confirm menu item to be deleted
        'navigationManagerEditMenuStage3_deletemenuitem', // handles deletion of a menu item from a menu through submission of confrim delete menu item form
        'navigationManagerEditMenuStage3_addmenuitem', // add a menu item to a menu
        'navigationManagerEditMenuStage3_submitaddmenuitem', // submit added menu item
        'SdmAuth',
        'SdmAuthLogin',
        'SdmCoreOverview',
        'SdmErrorLog',
         */
    ];

    // Determine initial body properties
    var initialBodyProperties = {bodyPaddingTop: $("body").css("paddingTop")};

    var newBodyProperties = {bodyPaddingTop: "100px"};

    var animationTime = 900;
    // Hide admin Bar initially if requestedPage does not match one of the displayPages.
    if (jQuery.inArray(requestedPage, displayPages) === -1) {
        $("#adminBarDisplay").hide();
        // Reset created space for admin bar at top of page
        $("body").animate({"paddingTop": initialBodyProperties.bodyPaddingTop}, animationTime);

    } else {
        // otherwise increase #adminBarDisplay height to 100%
        $("#adminBarDisplay").css("min-height", "100%");
        // create space for admin bar at top of page
        $("body").animate({"paddingTop": newBodyProperties.bodyPaddingTop}, animationTime);
    }

    // Hide admin panel when hideAdminPanel button is clicked and decrease height of adminBar
    $("#hideAdminPanel").click(function () {
        $("#adminBarDisplay").css("min-height", "");
        $("#adminPanelDisplay").slideUp("slow");
    });

    // Show admin panel when showAdminPanel button is clicked and increase #adminBarDisplay height to 100%
    $("#showAdminPanel").click(function () {
        $("#adminBarDisplay").css("min-height", "100%");
        $("#adminPanelDisplay").slideDown("slow");
    });

    // Hide admin bar when hideAdminBar button is clicked
    $("#hideAdminBar").click(function () {
        /* Reset height */
        $("#adminBarDisplay").css("min-height", "");
        /* Slide admin panel up first to make animation smoother. */
        $("#adminPanelDisplay").slideUp("slow");

        /* Slide adminBarDisplay up. */
        $("#adminBarDisplay").slideUp("slow");
        // Reset created space for admin bar at top of page
        $("body").animate({"paddingTop": initialBodyProperties.bodyPaddingTop}, animationTime);
        // show showAdminBar button so user can re-enable adminBarDisplay
        $("#showAdminBar").show();
    });

    // Show admin bar when showAdminBar button is clicked
    $("#showAdminBar").click(function () {
        $("#adminBarDisplay").slideDown("slow");
        // create space for admin bar at top of page
        $("body").animate({"paddingTop": newBodyProperties.bodyPaddingTop}, animationTime);
        // hide self when clicked | i.e., hide showAdminBar button when admin bar is shown
        $("#showAdminBar").hide();
    });

    function adminButtonRollover(element, newCssPropertyValues) {
        $(element).css("background-color", newCssPropertyValues.backgroundColor);
        $(element).css("color", newCssPropertyValues.color);
        $(element).css("opacity", newCssPropertyValues.opacity);
        $(element).css("border-color", newCssPropertyValues.borderColor);
    }

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