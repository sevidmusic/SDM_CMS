if (typeof jQuery == 'undefined') {
    alert('jQuery is required for jQuery Ui to work.');
} else {
    if (typeof jQuery.ui == 'undefined') {
        alert("jQuery Ui could not be loaded:\n\n jQuery Ui failed to load properly so some site features may not be available.");
    } else {
        $(document).ready(function () {
            $("#jQueryUiStatus").replaceWith('jQuery Ui version "' + $.ui.version + '" is installed on this site.');
        });
    }
}