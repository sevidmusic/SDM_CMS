<?php

// app description page | if you visit YOURSITE.com/index.php?page=SdmDevMenu this output will be dsiplayed
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, '<h2>Overview of current state the CORE</h2><p>The SDM Core Overview app displays the current state of the SDM CORE.</p>', array('wrapper' => 'main_content', 'incmethod' => 'append', 'incpages' => array('SdmCoreOverview')));
// load the core overview file
$coredata = $sdmassembler->sdmCoreLoadDataObject();
$output = $sdmassembler->sdmCoreCurlGrabContent($sdmassembler->sdmCoreGetCoreAppDirectoryUrl() . '/SdmCoreOverview/co.php', array('coredata' => $coredata));
// incorporate core overview
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, array('wrapper' => 'main_content', 'incpages' => array('SdmCoreOverview')));
