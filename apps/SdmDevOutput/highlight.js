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
    if (requestedPage === 'SdmDevOutput') {
        /* Get initial color value */
        var initialColor = $(".custom-form-elements-container").css("backgroundColor");
        var initialOpacity = $(".custom-form-elements-container").css("opacity");

        /* mouseover effect */
        $(property).on("mouseover", function () {
            $(this).css("backgroundColor", "#000000");
            //$(this).css("backgroundColor", "#ffffff");
            $(this).css("opacity", "1");
        });

        /* mouseout effect */
        $(property).on("mouseout", function () {
            $(this).css("backgroundColor", initialColor);
            $(this).css("opacity", initialOpacity);
        });
    }
});