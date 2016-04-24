<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 4/23/16
 * Time: 11:07 PM
 */

/** Create Show Hide Admin Buttons */

/* Define button inline styles */
$buttonStylesArray = array(
    'background-color: #000000;',
    'border: 2px solid #ffffff;',
    'border-radius:9px;',
    'opacity: .5;',
    'color: white;',
    'padding: 15px 32px;',
    'text-align: center;',
    'text-decoration: none;',
    'display: inline-block;',
    'font-size: 16px;'
);

$buttonStyles = implode(' ', $buttonStylesArray);

/* Create button html */
$hideAdminPanelButton = '<button id="hideAdminPanel" style="' . $buttonStyles . '" class="adminShowHideButton">Hide Admin Panel</button>';
$showAdminPanelButton = '<button id="showAdminPanel" style="' . $buttonStyles . '" class="adminShowHideButton">Show Admin Panel</button>';
$hideAdminBarButton = '<button id="hideAdminBar" style="' . $buttonStyles . '" class="adminShowHideButton">Hide Admin Bar</button>';
$showAdminBarButton = '<button id="showAdminBar" style="' . $buttonStyles . ' position: fixed; top: 0; left: 0;" class="adminShowHideButton">Show Admin Bar</button>';

$adminBarButtons = array($hideAdminBarButton, $hideAdminPanelButton, $showAdminPanelButton);

echo $showAdminBarButton;
?>
<!-- Temp admin panel while in development. -->
<div id="adminBarDisplay"
     style="overflow:auto;border-bottom: 12px solid #3399ff;position: fixed;top: 0;width: 100%; font-size: 2em; background: #000000;opacity:.8;color: #ffffff;">
    <?php
    echo implode(PHP_EOL, $adminBarButtons);
    ?>

    <script>
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
            }, function () {
                $(this).css("background-color", "#000000;");
                $(this).css("color", "#ffffff;");
                $(this).css("opacity", ".5");
            });

        });
    </script>
    <div id="adminPanelDisplay">
        <?php
        echo $sdmassembler->sdmAssemblerGetContentHtml('top-menu');
        echo $sdmassembler->sdmAssemblerGetContentHtml('main_content');
        ?>
    </div>
</div>
<!-- End temp admin panel while in development. -->
