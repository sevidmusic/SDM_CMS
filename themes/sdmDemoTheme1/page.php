<div id="lockedwrapper">
    <div id="topmenu">
        <?php
        $nms = new SdmNms();
        echo (isset($sdmassembler_themeContentObject->topmenu) ? $sdmassembler_themeContentObject->topmenu : '<a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=homepage">Homepage</a>');
        echo $nms->sdmNmsGetWrapperMenusHtml();
        ?>
    </div>
    <div id='main_content'>
        <?php
        echo $nms->sdmNmsGetWrapperMenusHtml();
        //$nms->sdmNmsdeleteMenu(81667828860);
        echo (isset($sdmassembler_themeContentObject->main_content) ? $sdmassembler_themeContentObject->main_content : '<!-- No Content -->');
        ?>
    </div>
</div>