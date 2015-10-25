// Alert a welcome message one time to test that .as resources are being loaded
// i.e., this js will not run if .as resources are not being loaded.
var alerted = localStorage.getItem('alerted') || '';
if (alerted != 'yes') {
    if (typeof jQuery != "undefined") {
        alert("jQuery Loaded Sucessfully");
        // then check if jQuery UI has been loaded
        if (typeof jQuery.ui != "undefined") {
            alert("jQuery UI Loaded Sucessfully");
        }
        else { // if jQuery UI is missing, alert user
            alert("MISSING JS RESOURCE (no jQuery UI):\n\n jQuery UI failed to load properly so some site features may not be available.\n\nPlease report this to the site admin at:\n\nADMINEMAILADDRESS@EMAILSERVER.COM\n\nor at\n\nLINKTOADMINCONTACT.");
        }
    } else { // if jQuery is missing alert user
        alert("MISSING JS RESOURCE (no jQuery):\n\n jQuery failed to load properly so some site features may not be available.\n\nPlease report this to the site admin at:\n\nADMINEMAILADDRESS@EMAILSERVER.COM\n\nor at\n\nLINKTOADMINCONTACT.");
    }
    localStorage.setItem('alerted', 'yes');
}

