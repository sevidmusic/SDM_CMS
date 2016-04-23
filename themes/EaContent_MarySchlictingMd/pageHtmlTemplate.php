<?php
/**
 * This file defines the basic html structure for the EaContent_MarySchlictingMd theme.
 */
?>

<?php

/* Use switch to accommodate custom page configurations. */
switch ($sdmassembler->sdmCoreDetermineRequestedPage()) {
    case '404':
        require_once($sdmassembler->sdmCoreGetCurrentThemeDirectoryPath() . '/404.html');
        break;
    default:
        ?>

        <!-- row 1 | Holds Content Wrappers: #locked_msmd-logo, #msmd-dvm-box, #locked_msmd-main-menu -->
        <div class="dev msmd-row msmd-row-width msmd-header-row">

            <!-- #locked_msmd-logo -->
            <div id="locked_msmd-logo" class="dev  msmd-col-5 msmd-header-wrapper-top-padding msmd-century-gothic">

                <h1 class="dev msmd-all-caps msmd-scaled-text">Mary Schlicting</h1>

                <p id="msmd-dvm-box" class="dev msmd-all-caps">D.V.M.</p>

            </div>
            <!-- End #locked_msmd-logo -->

            <!-- #locked_msmd-main-menu -->
            <div id="locked_msmd-main-menu" class="dev msmd-col-6 msmd-horizontal-menu msmd-myriad-pro-condensed">

                <!-- Main menu ul -->
                <ul>
                    <li id="msmd-main-menu-first-element">Main</li>
                    <li>About Us</li>
                    <li>Services</li>
                    <li>Links</li>
                    <li>Contacts</li>
                </ul>
                <!-- End Main menu ul -->

            </div>
            <!-- End #locked_msmd-main-menu -->

        </div>
        <!-- End row 1 | Holds Content Wrappers: #msmd-logo, #msmd-main-menu -->

        <!-- row 2 | Holds Content Wrappers: #msmd-homepage-welcome-text -->
        <div class="dev msmd-row msmd-row-width">

            <!-- #msmd-homepage-welcome-text -->
            <div id="msmd-homepage-welcome-text"
                 class="dev msmd-col-4 msmd-wrapper-padding msmd-myriad-pro-condensed msmd-all-caps">
                <h1>Welcome</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi non feugiat nisl, a ultricies risus.
                    Nam nec
                    porttitor velit, congue faucibus tortor.</p>
            </div>
            <!-- End #msmd-homepage-welcome-text -->

            <!-- Generic wrapper, no id. -->
            <div class="dev msmd-col-8 msmd-wrapper-padding">
                <img id="msmd-homepage-kitty-img"
                     src="<?php echo $sdmassembler->sdmCoreGetCurrentThemeDirectoryUrl() . '/designImages/kitten.jpg'; ?>">
            </div>
            <!-- End Generic wrapper, no id. -->

        </div>
        <!-- End row 2 | Holds Content Wrappers: #msmd-homepage-welcome-text -->

        <?php break; ?>
    <?php } // end switch ?>