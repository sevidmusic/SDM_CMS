// first check if jQuery has been loaded
if (typeof jQuery != 'undefined') {
    // then check if jQuery UI has been loaded
    if (typeof jQuery.ui != 'undefined') {
        $("document").ready(function() {
            /* VARIABLE INIT */
            // the theme to use for this dragNDropGatekeeper | correlates to the name of the css file that defines the desired theme styles
            var theme = 'blueSkies';
            // siteRootUrl set in dragNDropGatekeeper.php when used with SDM CMS so app can use sdm core to determine correct paths | var siteRootUrl = 'http://localhost:8888/MMh/Dev/SiteApps/dragNDropGatekeeper';
            // load in our dragNDropGatekeeper stylesheet and append to <head>
            $('head').append('<link rel="stylesheet" type="text/css" href="' + siteRootUrl + '/js/dragNDropGatekeeper-0.0.0/themes/' + theme + '.css">');
            // parent div | should be the id of the div that is to be the parent of the dragNDropGatekeeper div | note: a parent div must exist or the dragNDropGatekeeper will not know where to put the dragNDropGatekeeper.
            var parentDiv = '#main_content';
            // determine wheater dragNDropGateKeeper is prepended or appended to the parentDiv, or if it should overwrite the parentDiv
            var writeMethod = 'prepend';
            // the id to use for the "drag and drop gatekeeper" div | this div wraps all dragNDropGatekeeper elements
            var dragNDropGatekeeperElementId = '#dragNDropGatekeeperWrapper';
            // the id to use for the "drag from" div
            var dragFrom = '#dragFromElement';
            // the id to use for the "drag to" div
            var dragTo = '#dragToElement';
            // string of html for dragNDropGatekeeper div | this div will become a child of the parentDiv and will wrap all other dragNDropGatekeeper elements
            var dragNDropGatekeeper = $('<div id="' + dragNDropGatekeeperElementId.replace('#', '') + '">');
            // html for dragFrom elements wrapper | this is where users will drag dragFrom elements from | note: we replace '#' with '' because '#' is not appropriate for use in an element id
            var dragNDropGatekeeper_dragFromElement = '<div id="dragNDropGatekeeperLeft"><div class="dragNDropGatekeeper dragFromElement" id="' + dragFrom.replace('#', '') + '"><p>DRAG ME</p></div><!-- End ' + dragFrom + ' element --></div><!-- End #dragNDropGatekeeperLeft -->';
            // html for dragTo element | this is where users will drop dragFrom elements to | note: we replace '#' with '' because '#' is not appropriate for use in an element id
            var dragNDropGatekeeper_dropDiv = '<div id="dragNDropGatekeeperRight"><div class="dragNDropGatekeeper dragToElement" id="' + dragTo.replace('#', '') + '"><p>DROP HERE</p></div><!-- End ' + dragTo + ' element --></div><!-- End #dragNDropGatekeeperLeft -->';
            /* DRAG AND DROP DIV CREATION */
            // append dragNDropGatekeeper_dragFromElement to the dragNDropGatekeeper
            dragNDropGatekeeper.append(dragNDropGatekeeper_dragFromElement);
            // append dragNDropGatekeeper_dropDiv to the dragNDropGatekeeper
            dragNDropGatekeeper.append(dragNDropGatekeeper_dropDiv);
            // add our dragNDropGatekeeper to the parentDiv
            (writeMethod === 'overwrite' ? $(parentDiv).html(dragNDropGatekeeper) : (writeMethod === 'prepend' ? $(parentDiv).prepend(dragNDropGatekeeper) : $(parentDiv).append(dragNDropGatekeeper)));
            /* END DRAG AND DROP DIV CREATION */
            /* ADD DRAG AND DROP FUNCTIONALITY */
            // Make our dragFrom element draggable
            $(dragFrom).draggable({
                /* Options */
                revert: 'invalid', // causes the dragFrom element to animate back to its orginial position if the dragTo element does not accept it, or if the dragFrom element is not dropped in the appropriate dragTo element | @see http://api.jqueryui.com/draggable/#option-revert
            });
            // make our dragTo element "droppable" | @see http://api.jqueryui.com/droppable/
            $(dragTo).droppable({
                /* Options */
                // Tell our dragTo element which dragFrom element to accept
                accept: dragFrom,
                // determine "tolerance" | @see http://api.jqueryui.com/droppable/#option-tolerance
                tolerance: "fit", // other options : "intersect" "point" "touch"
                /* Events */
                // determine what happens when a dragFrom element starts to be dragged
                activate: function(event, ui) {
                    // modify dragTo elements styles while dragFrom element is being dragged to help user identify the dragTo element.
                    $(this).css({
                        background: 'lightgreen',
                    });
                },
                // determine what happens when a dragFrom element stops being dragged
                deactivate: function(event, ui) {
                    // change dragTo element's styles if user stops dragging dropFrom element
                    $(this).css({
                        background: '',
                    });
                },
                // determine what happens when a dragFrom element is dragged over the dragTo element
                over: function(event, ui) {
                    // modify dragTo element's styles to indicate when dragFrom element is over the dragTo element
                    $(this).css({
                        background: 'red',
                    });
                },
                // determine what happens when a dragFrom element that was dragged into the dragTo element is dragged back out of the dragTo element but is still being dragged
                out: function(event, ui) {
                    // modify dragTo element's styles if dragFrom element is dragged back out of the dragTo element
                    $(this).css({
                        background: '',
                    });
                },
                // detremine what happens when dragFrom element is dropped into the dragTo element
                drop: function(event, ui) {
                    // make sure dragFrom element is secure and safe to accepet
                    if ($(dragFrom).attr('id') === dragFrom.replace('#', '')) {
                        // remove draggable functionality from the sucessfully dropped dragFrom element
                        $(dragFrom).draggable("destroy");
                        // overwrite css to keep dropped dropFrom element inside of drapTo element
                        $(dragFrom).css({
                            position: 'absolute', // positioning of dragged element must absolute to prevent dragged item from ending up on random parts of the screen
                            top: $(dragTo).position().top + 'px',
                            left: $(dragTo).position().left + 'px',
                        });
                    }
                },
            });
            /* END ADD DRAG N DROP FUNCTIONALITY */
        }); // end of $("document").ready(function()
    }
    else { // if jQuery UI is missing, alert user
        alert("MISSING JS RESOURCE:\n\n jQuery UI failed to load properly so some site features may not be available.\n\nPlease report this to the site admin at:\n\nADMINEMAILADDRESS@EMAILSERVER.COM\n\nor at\n\nLINKTOADMINCONTACT.");
    }

} else { // if jQuery is missing alert user
    alert("MISSING JS RESOURCE:\n\n jQuery failed to load properly so some site features may not be available.\n\nPlease report this to the site admin at:\n\nADMINEMAILADDRESS@EMAILSERVER.COM\n\nor at\n\nLINKTOADMINCONTACT.");
}