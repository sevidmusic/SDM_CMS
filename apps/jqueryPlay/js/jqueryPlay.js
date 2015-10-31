var alerted = localStorage.getItem('alerted') || '';
if (alerted != 'yes') {
// first check if jQuery has been loaded
    if (typeof jQuery != 'undefined') {
        // then check if jQuery UI has been loaded
        if (typeof jQuery.ui != 'undefined') {
            $("document").ready(function() {
                /* VARIABLE INIT */
                alert('jQuery version "' + $.fn.jquery + '" and jQuery UI version "' + $.ui.version + '" are working on this page.');
            }); // end of $("document").ready(function()
        }
        else { // if jQuery UI is missing, alert user
            alert("MISSING JS RESOURCE:\n\n jQuery UI failed to load properly so some site features may not be available.\n\nPlease report this to the site admin at:\n\nADMINEMAILADDRESS@EMAILSERVER.COM\n\nor at\n\nLINKTOADMINCONTACT.");
        }

    } else { // if jQuery is missing alert user
        alert("MISSING JS RESOURCE:\n\n jQuery failed to load properly so some site features may not be available.\n\nPlease report this to the site admin at:\n\nADMINEMAILADDRESS@EMAILSERVER.COM\n\nor at\n\nLINKTOADMINCONTACT.");
    }
    localStorage.setItem('alerted', 'yes');
}