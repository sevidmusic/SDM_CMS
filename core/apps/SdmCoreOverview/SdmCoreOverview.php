<?php

// app description page | if you visit YOURSITE.com/index.php?page=SdmDevMenu this output will be dsiplayed
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<h2>Overview of current state the CORE</h2><p>The SDM Core Overview app displays the current state of the SDM CORE.</p>', array('wrapper' => 'main_content', 'incmethod' => 'append', 'incpages' => array('SdmCoreOverview')));
// load the core overview file
$output = $sdmcore->sdmCoreCurlGrabContent($sdmcore->getCoreAppDirectoryUrl() . '/SdmCoreOverview/co.php');
// incorporate core overview
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, array('wrapper' => 'main_content', 'incpages' => array('SdmCoreOverview')));