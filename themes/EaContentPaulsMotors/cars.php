<?php
/**
 * This file defines the structure of the Cars page for the EaContent_PaulsMotors theme.
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
            <h1>
                <a href="<?php echo $sdmassembler->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage&linkedBy=locked_eac_pm-header-logo-text'; ?>">Paul's
                    Motors</a></h1>
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

<!-- #locked_eac_pm-row2 | .eac_pm-row -->
<div id="locked_eac_pm-row2" class="dev eac_pm-row eac_pm-row-dimensions">

    <!-- #locked_eac_pm-hours -->
    <div id="locked_eac_pm-hours" class="eac_pm-col-6">M-F: 8AM - 5:15PM SAT-SUN 8AM - NOON</div>
    <!-- End #locked_eac_pm-hours -->

    <!-- #locked_eac_pm-main-menu -->
    <div id="eac_pm-main-menu" class="eac_pm-col-6 eac_pm-horizontal-menu">
        <?php echo $sdmassembler->sdmAssemblerGetContentHtml('eac_pm-main-menu'); ?>
    </div>
    <!-- End #locked_eac_pm-main-menu -->

</div>
<!-- End #locked_eac_pm-row2 | .eac_pm-row -->

<!-- #locked_eac_pm-cars-for-sale-row -->
<div id="locked_eac_pm-cars-for-sale-row" class="eac_pm-row eac_pm-row-dimensions">

    <!-- #locked_eac_pm-cars-for-sale-box -->
    <div id="locked_eac_pm-cars-for-sale-box" class="eac_pm-col-12">

        <!-- #eac_pm-cars-for-sale-box-text -->
        <div id="eac_pm-cars-for-sale-box-text">
            <?php echo $sdmassembler->sdmAssemblerGetContentHtml('eac_pm-cars-for-sale-box'); ?>
        </div>
        <!-- End #eac_pm-cars-for-sale-box-text -->

    </div>
    <!-- End #locked_eac_pm-cars-for-sale-box -->

</div>
<!-- #locked_eac_pm-cars-for-sale-row -->

<!-- #locked_eac_pm-cars-main-content-row | .eac_pm-row -->
<div id="locked_eac_pm-cars-main-content-row" class="dev eac_pm-row eac_pm-row-dimensions">

    <!-- #main_content -->
    <div id="main_content" class="eac_pm-col-12">
        <?php echo $sdmassembler->sdmAssemblerGetContentHtml('main_content'); ?>
    </div>
    <!-- End #main_content -->

</div>
<!-- End #locked_eac_pm-cars-main-content-row | .eac_pm-row -->

<!-- #locked_eac_pm-mini-about-boxes-row | .eac_pm-row -->
<div id="locked_eac_pm-mini-about-boxes-row" class="dev eac_pm-row eac_pm-row-dimensions">

    <!-- #eac_pm-mini-about-box1 -->
    <div id="locked_eac_pm-mini-about-box1" class="eac_pm-col-4">
        <h2 class="eac_pm-all-caps">Communities We Serve</h2>
        <ul>
            <li>City of Poughkeepsie</li>
            <li>Town of Poughkeepsie</li>
            <li>Hyde Park</li>
            <li>Lagrangeville</li>
            <li>Pleasant Valley</li>
            <li>Arlington</li>
            <li>Spackenkill</li>
            <li>Highland</li>
            <li>Central Dutchess County</li>
        </ul>
    </div>
    <!-- End #eac_pm-mini-about-box1 -->

    <!-- #eac_pm-mini-about-box2 -->
    <div id="eac_pm-mini-about-box2" class="eac_pm-col-4">
        <h2 class="eac_pm-all-caps">Certifications</h2>
        <p><?php echo $sdmassembler->sdmAssemblerGetContentHtml('eac_pm-mini-about-box2'); ?>
    </div>
    <!-- End #eac_pm-mini-about-box2 -->

    <!-- #eac_pm-mini-about-box3 -->
    <div id="eac_pm-mini-about-box3" class="eac_pm-col-4">
        <h2 class="eac_pm-all-caps">55+ Years Of Quality</h2>
        <p><?php echo $sdmassembler->sdmAssemblerGetContentHtml('eac_pm-mini-about-box3'); ?></p>
    </div>
    <!-- End #eac_pm-mini-about-box3 -->

</div>
<!-- End #locked_eac_pm-mini-about-boxes-row | .eac_pm-row -->

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