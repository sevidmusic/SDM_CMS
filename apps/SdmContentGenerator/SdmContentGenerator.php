<?php

$options = array('incpages' => array('SdmContentGenerator'));
$output = '<h2>Sdm Content Generator</h2><p>Generates dev content for the SDM CMS</p>';
$cm = new SdmCms();
$genPages = array();
$limit = 500;
for ($i = 0; $i < $limit; $i++) {
    $genPages['gen_page_' . $i . '_' . rand(10000, 99999)] = rand(100000000000, 999999999999);
}
$status = array();
if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmContentGenerator') {
    foreach ($genPages as $id => $html) {
        $status[] = $cm->sdmCmsUpdateContent($id, 'main_content', $html);
    }
}
$output .= '<p>';
$output .= (in_array(FALSE, $status, TRUE) ? 'Could not generate pages' : $limit . ' pages were generated.');
$output .= '</p>';
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, $options);
