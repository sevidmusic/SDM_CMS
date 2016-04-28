<?php
/**
 * This file defines the structure of the homepage for the EaContent_PaulsMotors theme.
 *
 * It was created for Ea Content by Sevi Donnelly Foreman.
 *
 * @author: Sevi Donnelly Foreman
 *
 * @created: April 27, 2016 at 2:53 p.m.
 *
 */
?>

<!-- #locked_eac_pm-header-row | .eac_pm-row -->
<div id="locked_eac_pm-header-row" class="dev eac_pm-row eac_pm-row-dimensions">

    <!-- #locked_eac_pm-header-logo | .eac_pm-col -->
    <div id="locked_eac_pm-header-logo" class="dev eac_pm-col-4">

        <!-- #locked_eac_pm-header-logo-text -->
        <div id="locked_eac_pm-header-logo-text">
            <h1>Paul's Motors</h1>
        </div>
        <!-- End #locked_eac_pm-header-logo-text -->

    </div>
    <!-- End #locked_eac_pm-header-logo | .eac_pm-col -->

    <!-- #locked_eac_pm-header-address | .eac_pm-col -->
    <div id="locked_eac_pm-header-address" class="dev eac_pm-col-4">

        <!-- #locked_eac_pm-header-address-text -->
        <div id="locked_eac_pm-header-address-text">6 Fairview Ave Poughkeepsie Ny</div>
        <!-- End #locked_eac_pm-header-address-text -->

    </div>
    <!-- End #locked_eac_pm-header-address | .eac_pm-col -->

    <!-- #locked_eac_pm-header-phone-number | .eac_pm-col -->
    <div id="locked_eac_pm-header-phone-number" class="dev eac_pm-col-4">

        <!-- #locked_eac_pm-header-phone-number-text -->
        <div id="locked_eac_pm-header-phone-number-text">
            <h3>845-471-4240</h3>
        </div>
        <!-- End #locked_eac_pm-header-phone-number-text -->

    </div>
    <!-- End #locked_eac_pm-header-phone-number | .eac_pm-col -->

</div>
<!-- End #locked_eac_pm-header-row | .eac_pm-row -->

<!-- #locked_eac_pm-homepage-row2 | .eac_pm-row -->
<div id="locked_eac_pm-homepage-row2" class="dev eac_pm-row eac_pm-row-dimensions">

    <!-- #locked_eac_pm-hours -->
    <div id="locked_eac_pm-hours" class="eac_pm-col-6">M-F: 8AM - 5:15PM SAT-SUN 8AM - NOON</div>
    <!-- End #locked_eac_pm-hours -->

    <!-- #locked_eac_pm-main-menu -->
    <div id="locked_eac_pm-main-menu" class="eac_pm-col-6 eac_pm-horizontal-menu">
        <ul>
            <li>Home</li>
            <li>Service</li>
            <li>Cars</li>
            <li>Contact</li>
        </ul>
    </div>
    <!-- End #locked_eac_pm-main-menu -->

</div>
<!-- End #locked_eac_pm-homepage-row2 | .eac_pm-row -->


<!-- #locked_eac_pm-homepage-row3 | .eac_pm-row -->
<div id="locked_eac_pm-homepage-row3" class="dev eac_pm-row eac_pm-row-dimensions">
    <!-- spacer element -->
    <div class="eac_pm-col-6"></div>
    <!-- End spacer element -->

    <!-- #eac_pm-homepage-box1 -->
    <div id="eac_pm-homepage-box1" class="eac_pm-col-6 eac_pm-homepage-box">
        <?php echo substr($sdmassembler->sdmAssemblerGetContentHtml('eac_pm-homepage-box1'), 0, 128); ?>
    </div>
    <!-- End #eac_pm-homepage-box1 -->

</div>
<!-- End #locked_eac_pm-homepage-row3 | .eac_pm-row -->


<!-- #locked_eac_pm-homepage-row4 | .eac_pm-row -->
<div id="locked_eac_pm-homepage-row4" class="dev eac_pm-row eac_pm-row-dimensions">

    <!-- #eac_pm-homepage-box2 -->
    <div id="eac_pm-homepage-box2" class="eac_pm-col-6 eac_pm-homepage-box">
        <?php echo substr($sdmassembler->sdmAssemblerGetContentHtml('eac_pm-homepage-box2'), 0, 128); ?>
    </div>
    <!-- End #eac_pm-homepage-box2 -->

    <!-- spacer element -->
    <div class="eac_pm-col-6"></div>
    <!-- End spacer element -->

</div>
<!-- End #locked_eac_pm-homepage-row4 | .eac_pm-row -->


<!-- #locked_eac_pm-homepage-row5 | .eac_pm-row -->
<div id="locked_eac_pm-homepage-row5" class="dev eac_pm-row eac_pm-row-dimensions">

    <!-- spacer element -->
    <div class="eac_pm-col-6"></div>
    <!-- End spacer element -->


    <!-- #eac_pm-homepage-box3 -->
    <div id="eac_pm-homepage-box3" class="eac_pm-col-6 eac_pm-homepage-box">
        <?php echo substr($sdmassembler->sdmAssemblerGetContentHtml('eac_pm-homepage-box3'), 0, 128); ?>
    </div>
    <!-- End #eac_pm-homepage-box3 -->

</div>
<!-- End #locked_eac_pm-homepage-row5 | .eac_pm-row -->


<!-- #locked_eac_pm-footer-row | .eac_pm-row -->
<div id="locked_eac_pm-footer-row" class="dev eac_pm-row eac_pm-row-dimensions">

    <!-- #locked_eac_pm-footer-text -->
    <div id="locked_eac_pm-footer-text" class="eac_pm-col-12">Copyright &copy; Paul's Motors 2016. All Rights Reserved.
        Website Design by EA CONTENT
    </div>
    <!-- End #locked_eac_pm-footer-text -->

</div>
<!-- End #locked_eac_pm-footer-row | .eac_pm-row -->

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