<!-- row 1 -->
<div class="row row-min-wid-fix">
    <div id="top-menu"class="col-12 col-m-12 border-bottom">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('top-menu', $sdmassembler_themeContentObject);
        ?>
    </div>
</div>
<!-- row 2 -->
<div class="row row-min-wid-fix padded-row">
    <?php
    $sidebar = SdmAssembler::sdmAssemblerGetContentHtml('side-menu', $sdmassembler_themeContentObject);
    $sidebarInvalidValues = array(null, '', '<!-- side-menu placeholder -->');
    $sideBarExists = (in_array($sidebar, $sidebarInvalidValues) === true ? false : true);
    if ($sideBarExists === true) {
        ?>
        <div id="side-menu" class="col-3 col-m-3 rounded">
            <?php
            echo SdmAssembler::sdmAssemblerGetContentHtml('side-menu', $sdmassembler_themeContentObject);
            ?>    </div>
        <div id="locked-spacer" class="col-1 col-m-1 spacer"></div>
    <?php } ?>
    <div id="main_content"class="<?php echo ($sideBarExists === true ? 'col-8 col-m-8' : 'col-12 col-m-12'); ?> rounded">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('main_content', $sdmassembler_themeContentObject);
        ?>
    </div>
</div>
<!-- row 3 -->
<div class="row row-min-wid-fix">
    <div id="footer"class="col-12 col-m-12">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('footer', $sdmassembler_themeContentObject);
        ?>
    </div>
</div>
