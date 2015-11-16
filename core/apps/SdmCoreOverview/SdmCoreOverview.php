<?php

// options
$options = array('wrapper' => 'main_content', 'incmethod' => 'append', 'incpages' => array('SdmCoreOverview'));
// output
$output = '<h2>Overview of current state the CORE</h2><p>The SDM Core Overview app displays the current state of the Core Data Object stored in data.json</p>';
// load the core overview file
$dataObject = $sdmassembler->sdmCoreLoadDataObject(false);
if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmCoreOverview') {
    ob_start();
    $sdmassembler->sdmCoreSdmReadArray($dataObject);
    $output .= ob_get_clean();
}
// incorporate core overview
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);
