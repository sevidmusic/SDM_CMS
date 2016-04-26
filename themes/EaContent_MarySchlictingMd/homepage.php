<?php
/**
 * This file defines the basic html structure for the EaContent_MarySchlictingMd theme.
 */
?>

<!-- Row 1 (#locked_msmd-page-row-1)| Holds Content Wrappers: #locked_msmd-logo, #locked_msmd-main-menu  -->
<div id="locked_msmd-page-row-1" class="dev msmd-row msmd-row-width">

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
<!-- End row 1 (#locked_msmd-page-row-1)| Holds Content Wrappers: #locked_msmd-logo, #locked_msmd-main-menu  -->

<!-- Row 2 (#locked_msmd-page-row-2) | Holds Content Wrappers: #msmd-homepage-welcome-text, 1 Generic Wrapper with no id. -->
<div id="locked_msmd-page-row-2" class="dev msmd-row msmd-row-width">

    <!-- #msmd-homepage-welcome-text -->
    <div id="msmd-homepage-welcome-text"
         class="dev msmd-col-4 msmd-wrapper-padding msmd-myriad-pro-condensed">
        <h1 class="msmd-all-caps">Welcome</h1>
        <p><?php echo $sdmassembler->sdmAssemblerGetContentHtml('msmd-homepage-welcome-text'); ?></p>
        <p class="msmd-read-more-link">
            <a class="msmd-all-caps"
               href="<?php echo $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=welcome'; ?>">
                <span class="msmd-square-bullet"></span>Read More</a>
        </p>
    </div>
    <!-- End #msmd-homepage-welcome-text -->

    <!-- Generic wrapper, no id. -->
    <div class="dev msmd-col-8 msmd-wrapper-padding">
        <img id="msmd-homepage-kitty-img"
             src="<?php echo $sdmassembler->sdmCoreGetCurrentThemeDirectoryUrl() . '/designImages/kitten.jpg'; ?>">
    </div>
    <!-- End Generic wrapper, no id. -->

</div>
<!-- End row 2 (#locked_msmd-page-row-2) | Holds Content Wrappers: #msmd-homepage-welcome-text, 1 Generic Wrapper with no id. -->

<!-- Row 3 (#locked_msmd-page-row-3) | Holds Content Wrappers: #locked_msmd-shortcut-box-display, #msmd-shortcut-box-row-1, #msmd-shortcut-box-row-2, #locked_msmd-shortcut-box-1, #locked_msmd-shortcut-box-2, #locked_msmd-shortcut-box-3, #locked_msmd-shortcut-box-4, #locked_msmd-shortcut-box-5, #msmd-shortcut-box-1-content, #msmd-shortcut-box-2-content, #msmd-shortcut-box-3-content, #msmd-shortcut-box-4-content, #msmd-shortcut-box-5-content,   -->
<div id="locked_msmd-page-row-3" class="dev msmd-row msmd-row-width">
    <div id="locked_msmd-shortcut-box-display">
        <div id="msmd-shortcut-box-row-1" class="msmd-shortcut-box-row">
            <!-- #locked_shortcut-box-1 -->
            <div id="locked_msmd-shortcut-box-1" class="msmd-shortcut-box msmd-shortcut-box-col- msmd-shortcut-box-1">
                <!-- #msmd-shortcut-box-1-content -->
                <div id="msmd-shortcut-box-1-content" class="msmd-shortcut-box-content-container">
                    <h2>Services</h2>
                    <p><?php /* limit to 88 chars */
                        echo substr($sdmassembler->sdmAssemblerGetContentHtml('msmd-shortcut-box-1-content'), 0, 128) . '...'; ?>
                    </p>
                </div>
                <!-- End #msmd-shortcut-box-1-content -->

            </div>
            <!-- End #locked_shortcut-box-1 -->

            <!-- #locked_shortcut-box-2 -->
            <div id="locked_msmd-shortcut-box-2" class="msmd-shortcut-box msmd-shortcut-box-col- msmd-shortcut-box-2">
                <!-- #msmd-shortcut-box-2-content -->
                <div id="msmd-shortcut-box-2-content" class="msmd-shortcut-box-content-container">
                    <h2>Staff</h2>
                    <p><?php /* limit to 88 chars */
                        echo substr($sdmassembler->sdmAssemblerGetContentHtml('msmd-shortcut-box-2-content'), 0, 128) . '...'; ?>
                    </p>
                </div>
                <!-- End #msmd-shortcut-box-2-content -->

            </div>
            <!-- End #locked_shortcut-box-2 -->

            <!-- #locked_shortcut-box-3 -->
            <div id="locked_msmd-shortcut-box-3" class="msmd-shortcut-box msmd-shortcut-box-col- msmd-shortcut-box-3">
                <!-- #msmd-shortcut-box-3-content -->
                <div id="msmd-shortcut-box-3-content" class="msmd-shortcut-box-content-container">
                    <h2>Services</h2>
                    <p><?php /* limit to 88 chars */
                        echo substr($sdmassembler->sdmAssemblerGetContentHtml('msmd-shortcut-box-3-content'), 0, 128) . '...'; ?>
                    </p>
                </div>
                <!-- End #msmd-shortcut-box-3-content -->

            </div>
            <!-- End #locked_shortcut-box-3 -->

        </div>
        <div id="msmd-shortcut-box-row-2" class="msmd-shortcut-box-row">

            <!-- #locked_shortcut-box-4 -->
            <div id="locked_msmd-shortcut-box-4" class="msmd-shortcut-box msmd-shortcut-box-col- msmd-shortcut-box-4">
                <!-- #msmd-shortcut-box-4-content -->
                <div id="msmd-shortcut-box-4-content" class="msmd-shortcut-box-content-container">
                    <h2>Services</h2>
                    <p><?php /* limit to 88 chars */
                        echo substr($sdmassembler->sdmAssemblerGetContentHtml('msmd-shortcut-box-4-content'), 0, 128) . '...'; ?>
                    </p>
                </div>
                <!-- End #msmd-shortcut-box-4-content -->

            </div>
            <!-- End #locked_shortcut-box-4 -->

            <!-- #locked_shortcut-box-5 -->
            <div id="locked_msmd-shortcut-box-5" class="msmd-shortcut-box msmd-shortcut-box-col- msmd-shortcut-box-5">
                <!-- #msmd-shortcut-box-5-content -->
                <div id="msmd-shortcut-box-5-content" class="msmd-shortcut-box-content-container">
                    <h2>Services</h2>
                    <p><?php /* limit to 88 chars */
                        echo substr($sdmassembler->sdmAssemblerGetContentHtml('msmd-shortcut-box-5-content'), 0, 128) . '...'; ?>
                    </p>
                </div>
                <!-- End #msmd-shortcut-box-5-content -->

            </div>
            <!-- End #locked_shortcut-box-5 -->
        </div>
    </div>
</div>
<!-- End row 3 (#locked_msmd-page-row-3) | Holds Content Wrappers: #locked_msmd-shortcut-box-display, #msmd-shortcut-box-row-1, #msmd-shortcut-box-row-2, #locked_msmd-shortcut-box-1, #locked_msmd-shortcut-box-2, #locked_msmd-shortcut-box-3, #locked_msmd-shortcut-box-4, #locked_msmd-shortcut-box-5, #msmd-shortcut-box-1-content, #msmd-shortcut-box-2-content, #msmd-shortcut-box-3-content, #msmd-shortcut-box-4-content, #msmd-shortcut-box-5-content,   -->

<!-- Row 4 (#locked_msmd-page-row-4) | Holds Content Wrappers:  #locked_msmd-homepage-emergencies, #msmd-homepage-emergencies-text, #locked_msmd-homepage-small-animals, #msmd-homepage-small-animals-text -->
<div id="locked_msmd-page-row-4" class="dev msmd-row msmd-row-width">
    <!-- #locked_msmd-homepage-emergencies -->
    <div id="locked_msmd-homepage-emergencies" class="dev  msmd-col-5">

        <h2 class="dev msmd-all-caps">Emergencies</h2>
        <!-- #msmd-homepage-emergencies-text -->
        <div id="msmd-homepage-emergencies-text">
            <p>
                <?php
                echo $sdmassembler->sdmAssemblerGetContentHtml('msmd-homepage-emergencies-text');
                ?>
            </p>
        </div>
        <!-- End #msmd-homepage-emergencies-text -->

    </div>
    <!-- End #locked_msmd-homepage-emergencies -->

    <!-- #locked_msmd-homepage-small-animals -->
    <div id="locked_msmd-homepage-small-animals" class="dev  msmd-col-7 msmd-small-animals-wrapper">

        <h2 class="dev msmd-all-caps">Small Animals</h2>
        <!-- #msmd-homepage-small-animals-text -->
        <div id="msmd-homepage-small-animals-text">
            <p>
                <?php
                echo substr($sdmassembler->sdmAssemblerGetContentHtml('msmd-homepage-small-animals-text'), 0, 128) . '...';
                ?>
            </p>
        </div>
        <!-- End #msmd-homepage-small-animals-text -->

    </div>
    <!-- End #locked_msmd-homepage-small-animals -->

</div>
<!-- End row 4 (#locked_msmd-page-row-4) | Holds Content Wrappers:  #locked_msmd-homepage-emergencies, #msmd-homepage-emergencies-text, #locked_msmd-homepage-small-animals, #msmd-homepage-small-animals-text -->


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