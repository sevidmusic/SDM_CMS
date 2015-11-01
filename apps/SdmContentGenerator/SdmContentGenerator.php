<?php

$options = array('incpages' => array('SdmContentGenerator'));
$output = '<h2>Sdm Content Generator</h2><p>Generates dev content for the SDM CMS</p>';
$cm = new SdmCms();

function randString($mode, $limit) {
    $strLen = rand(1, $limit);
    $string = '';
    switch ($mode) {
        case 'alphaNum':
            $charArr = str_split('abcdefgabcdefghijklmnopqrstuvwxyz0123456789');
            for ($i = 0; $i < $strLen; $i++) {
                $string .= $charArr[array_rand($charArr)];
            }
            break;
        case 'alpha':
            $charArr = str_split('abcdefgabcdefghijklmnopqrstuvwxyz');
            for ($i = 0; $i < $strLen; $i++) {
                $string .= $charArr[array_rand($charArr)];
            }
            break;
        case 'num':
            $charArr = str_split('0123456789');
            for ($i = 0; $i < $strLen; $i++) {
                $string .= $charArr[array_rand($charArr)];
            }
            break;
    }
    return $string;
}

function randPara($lineLimit, $wordPerLine = 12, $wordLen = 12, $mode = NULL) {
    $para = '';
    $modes = array('alphaNum', 'alpha', 'num');
    // limit by $lineLimit
    for ($cycle = 0; $cycle < $lineLimit; $cycle++) {
        $para .= '<p>';
        // limit by $wordPerLine
        for ($i = 0; $i < $wordPerLine; $i++) {
            // create line
            $para .= randString(($mode != NULL ? $mode : $modes[array_rand($modes)]), $wordLen) . ' ';
        }
        // end line
        $para .= '</p>';
    }
    return $para;
}

if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmContentGenerator') {
    $pageLimit = 5;
    for ($index = 0; $index < $pageLimit; $index++) {
        $cm->sdmCmsUpdateContent(randString('alpha', 8), 'main_content', randPara(12, 100, 9, 'alpha'));
    }
}
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, $options);
