<?php
/**
 * This file defines the basic html structure for the sdmResponsive theme.
 */
?>

<!-- row 1 -->
<div class="row row-min-wid-fix">
    <div id="locked_top-menu" class="col-12 col-m-12 border-bottom">
        <?php
        echo $sdmassembler->sdmAssemblerGetContentHtml('top-menu');
        ?>
    </div>
</div>
<!-- row 2 -->
<div class="row row-min-wid-fix padded-row">
    <?php
    $sidebar = $sdmassembler->sdmAssemblerGetContentHtml('side-menu');
    $sidebarInvalidValues = array(null, '', '<!-- side-menu placeholder -->');
    $sideBarExists = (in_array($sidebar, $sidebarInvalidValues) === true ? false : true);
    if ($sideBarExists === true) {
        ?>
        <div id="locked_side-menu" class="col-3 col-m-3 rounded">
            <?php
            echo $sdmassembler->sdmAssemblerGetContentHtml('side-menu');
            ?>    </div>
        <div id="locked-spacer" class="col-1 col-m-1 spacer"></div>
    <?php } ?>
    <div id="main_content" data-wrapper-display-name="Main Content"
         class="<?php echo($sideBarExists === true ? 'col-8 col-m-8' : 'col-12 col-m-12'); ?> rounded">
        <?php
        echo $sdmassembler->sdmAssemblerGetContentHtml('main_content');
        ?>
    </div>
</div>
<!-- row 3 -->
<div class="row row-min-wid-fix">
    <div id="footer" class="col-12 col-m-12">
        <?php
        echo $sdmassembler->sdmAssemblerGetContentHtml('footer');
        ?>
    </div>
</div>

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