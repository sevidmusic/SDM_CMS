<div id="lockedwrapper">
    <div id="topmenu">
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('topmenu', $sdmassembler_themeContentObject);
        ?>
    </div>
    <div id='main_content'>
        <?php
        echo SdmAssembler::sdmAssemblerGetContentHtml('main_content', $sdmassembler_themeContentObject);
        ?>
    </div>
</div>