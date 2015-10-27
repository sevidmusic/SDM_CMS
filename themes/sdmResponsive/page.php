<div class="row">
    <div id="top-menu"class="col-12">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('top-menu', $sdmassembler_themeContentObject);
        ?>
    </div>
</div>
<div class="row">
    <div id="side-menu" class="col-3">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('side-menu', $sdmassembler_themeContentObject);
        ?>    </div>
    <div id="locked-spacer" class="col-1 spacer"></div>
    <div id="main_content"class="col-8">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('main_content', $sdmassembler_themeContentObject);
        ?>
    </div>
    <div class="row">
        <div id="footer"class="col-12">
            <?php
            echo SdmAssembler::sdmAssemblerGetContentHtml('footer', $sdmassembler_themeContentObject);
            ?>
        </div>
    </div>