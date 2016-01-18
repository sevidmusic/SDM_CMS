if (typeof jQuery == 'undefined') {
    alert("jQuery could not be loaded:\n\n jQuery failed to load properly so some site features may not be available.");
} else {
    $(document).ready(function () {
        $("#jQueryStatus").replaceWith('jQuery version "' + $.fn.jquery + '" is installed on this site.');
    });
}