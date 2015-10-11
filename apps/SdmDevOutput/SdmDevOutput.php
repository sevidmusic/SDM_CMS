<?php

/** Log of what is being tested */
$tests = array(
    'SdmGateKeeper()' . rand(1000, 9999) => 'Checking to see if sessions are working properly',
    'SdmGateKeeper()' . rand(100, 999) => 'Testing that sessionConfigInfo() method is working',
);

/** TESTS */
$sdmGatekeeper = new SdmGatekeeper();
$_SESSION['RequestedPage'] = $sdmcore->SdmCoreDetermineRequestedPage();
$sdmGatekeeper->sessionConfigInfo();


/** APP OUTPUT */
$devOutput = '<h2>Dev Output</h2><p>This app can be used to test, debug, and experiment with snippets of PHP code.</p>';
// Display current tests logged in $tests array
$devOutput .= '<p>Current Tests</p><table style="width:100%;color:aqua;border:3px solid white;">';
foreach ($tests as $key => $value) {
    $devOutput .= '<tr>
                <td style="padding:12px;border:1px solid white;">Testing ' . trim(str_replace(range(0, 9), '', $key)) . '</td>
                <td style="padding:12px;color:#CCCCCC;border:1px solid white;">' . $value . '</td>
            </tr>';
}
$devOutput .= '</table>';
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $devOutput, array('wrapper' => 'main_content', 'incmethod' => 'append', 'incpages' => array('SdmDevOutput')));

