<?php
/**
 * This file defines the basic html structure for the EaContent_MarySchlictingMd theme.
 */
?>

    <!-- row 1 | Holds Content Wrappers: #locked_msmd-logo, #msmd-dvm-box, #locked_msmd-main-menu -->
    <div class="dev msmd-row msmd-row-width msmd-header-row">

        <!-- #locked_msmd-logo -->
        <div id="locked_msmd-logo" class="dev  msmd-col-5 msmd-header-wrapper-top-padding msmd-century-gothic">

            <h1 class="dev msmd-all-caps msmd-scaled-text"><a
                    href="<?php echo $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage'; ?>">Mary
                    Schlicting</a></h1>

            <p id="msmd-dvm-box" class="dev msmd-all-caps"><a
                    href="<?php echo $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage'; ?>">D.V.M.</a>
            </p>

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
             class="dev msmd-col-4 msmd-wrapper-padding msmd-myriad-pro-condensed">
            <h1 class="msmd-all-caps">Welcome</h1>
            <p><?php echo $sdmassembler->sdmAssemblerGetContentHtml('msmd-homepage-welcome-text'); ?></p>
            <p><a class="msmd-all-caps"
                  href="<?php echo $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=welcome'; ?>"><span
                        class="msmd-square-bullet"></span>Read More</a></p>
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
    <!--
      Special wrapper provided by Sdm Cms Core to hold Sdm Cms Core Output
      This wrapper is meant to hold core and user app output
      that is not part of the page. For instance, if an app needs to generate
      a message to the user this core wrapper is a good place to output the message.
    -->
    <div id="Sdm_Cms_Core_Output">
        <?php
        echo $sdmassembler->sdmAssemblerGetContentHtml('Sdm_Cms_Core_Output');
        ?>
    </div>

<?php
//require_once($sdmassembler->sdmCoreGetCurrentThemeDirectoryPath() . '/adminPanelDisplay.php');
?>