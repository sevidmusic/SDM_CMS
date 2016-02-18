$(document).ready(function () {

    /* Code for retrieveing $_GET values from post on stackoverflow.
     @see http://stackoverflow.com/questions/439463/how-to-get-get-and-post-variables-with-jquery */
    var $_GET = {};

    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
        function decode(s) {
            return decodeURIComponent(s.split("+").join(" "));
        }

        $_GET[decode(arguments[1])] = decode(arguments[2]);
    });

    /* Determine requested page. */
    var requestedPage = $_GET['page'];

    /*Property to highlight */
    var property = ".highlight";
    if (requestedPage === 'contentManagerUpdateContentFormSubmission') {
        /* Get initial color value */
        var initialColor = $(".sdmResponsive").css("color");

        /* mouseover effect */
        $(property).on("mouseover", function () {
            $(this).css("color", "#00ff33");
        });

        /* mouseout effect */
        $(property).on("mouseout", function () {
            $(this).css("color", initialColor);
        });
    }
});