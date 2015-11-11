<?php

$options = array('incpages' => array('SdmContentGenerator'));
$output = '<h2>Sdm Content Generator</h2><p>Generates dev content for the SDM CMS</p>';
$cm = new SdmCms();

/**
 *
 * @param string $mode <p>One of the following strings.
 *                   <ul>
 *                     <li>alphaNum : This mode generates an random aplha-numerical string.</li>
 *                     <li>alpha : This mode generates an random aplhabetical string.</li>
 *                     <li>alphaNum : This mode generates an random numerical string.</li>
 *                   </ul>
 *                   </p>
 * @param int $limit <p>The maximum number of chars to generate.</p>
 *                   <p><b>Note</b>: <i>The resulting string may have a length
 *                      that is less than the $limit</i></p>
 * @return string <p>Rand string of either alpha-numerical, alphabetical, or numerical
 *                   chars depeding on $mode</p>
 */
function randString($mode, $limit) {
    $strLen = rand(1, $limit);
    $string = '';
    switch ($mode) {
        case 'alphaNum':
            $charArr = str_split('abcdefgabcdefghijklmnopqrstuvwxyz0123456789');
            for ($i = 0; $i <= $strLen; $i++) {
                $string .= $charArr[array_rand($charArr)];
            }
            break;
        case 'alpha':
            $charArr = str_split('abcdefgabcdefghijklmnopqrstuvwxyz');
            for ($i = 0; $i <= $strLen; $i++) {
                $string .= $charArr[array_rand($charArr)];
            }
            break;
        case 'num':
            $charArr = str_split('0123456789');
            for ($i = 0; $i <= $strLen; $i++) {
                $string .= $charArr[array_rand($charArr)];
            }
            break;
    }
    return $string;
}

/**
 * <p>Generates specified number of html paragraphs of random strings.</p>
 * @param int $numPara <p>The number of paragrapghs to generate.</p>
 * @param int $wordPerPara <p>Number of words per paragraph</p>
 * @param int $wordLen <p>Maximum number of chars per word.</p>
 *                      <p><b>Note</b>: <i>The resulting string may have a
 *                          length that is less than the $limit</i></p>
 * @param string $mode <p>One of the following strings.
 *                       <ul>
 *                         <li>alphaNum : This mode generates an random aplha-numerical string.</li>
 *                         <li>alpha : This mode generates an random aplhabetical string.</li>
 *                         <li>alphaNum : This mode generates an random numerical string.</li>
 *                       </ul>
 *                     </p>
 * @return string <p>Specified number of html paragraphs of random strings.</p>
 */
function randPara($numPara, $wordPerPara = 12, $wordLen = 12, $mode = null) {
    $para = '';
    $modes = array('alphaNum', 'alpha', 'num');
    // limit by $numPara
    for ($cycle = 0; $cycle <= $numPara; $cycle++) {
        $para .= '<p>';
        // limit by $wordPerPara
        for ($i = 0; $i <= $wordPerPara; $i++) {
            // create line
            $para .= randString(($mode != null ? $mode : $modes[array_rand($modes)]), $wordLen) . ' ';
        }
        // end line
        $para .= '</p>';
    }
    return $para;
}

if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmContentGenerator') {
    ini_set('max_execution_time', 3000);
    $pageLimit = 50; // form element
    $wrappers = $cm->sdmCmsDetermineAvailableWrappers(); // form element |
    $genPagenames = array();
    for ($index = 0; $index < $pageLimit; $index++) {
        $genPagename = randString('alpha', 8);
        $genPagenames[] = $genPagename;
        foreach ($wrappers as $wrapperId) {
            $para = randPara(rand(1, 12), rand(10, 900), rand(1, 12), 'alpha');
            $cm->sdmCmsUpdateContent($genPagename, $wrapperId, $para);
        }
    }
    $output .= '<p>' . $pageLimit . ' pages were generated.</p>';
    $output .= '<ul>';
    foreach ($genPagenames as $pg) {
        $output .= '<li><a href="' . $cm->sdmCoreGetRootDirectoryUrl() . '/index.php?page=' . $pg . '&linkedBy=SdmContentGenerator:userApp">' . $pg . '</a></li>';
    }
    $output .= '</ul>';
}
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmAssemblerDataObject, $output, $options);
