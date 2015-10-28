<!-- row 1 -->
<div class="row">
    <div id="top-menu"class="col-12 border-1">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('top-menu', $sdmassembler_themeContentObject);
        ?>
    </div>
</div>
<!-- row 2 -->
<div class="row">
    <?php
    $sideBarExists = (SdmAssembler::sdmAssemblerGetContentHtml('side-menu', $sdmassembler_themeContentObject) === '<!-- side-menu placeholder -->' ? FALSE : TRUE);
    if ($sideBarExists === TRUE) {
        ?>
        <div id="side-menu" class="col-3 border-2 gradi-bg">
            <?php
            echo SdmAssembler::sdmAssemblerGetContentHtml('side-menu', $sdmassembler_themeContentObject);
            ?>    </div>
        <div id="locked-spacer" class="col-1 spacer"></div>
    <?php } ?>
    <div id="main_content"class="<?php echo ($sideBarExists === TRUE ? 'col-8' : 'col-12'); ?> border-2 gradi-bg">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('main_content', $sdmassembler_themeContentObject);
        ?>
    </div>
</div>
<!-- row 3 -->
<div class="row">
    <div id="footer"class="col-12">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('footer', $sdmassembler_themeContentObject);
        ?>
    </div>
</div>