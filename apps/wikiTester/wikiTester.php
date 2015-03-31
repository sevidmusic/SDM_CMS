<?php

// IN DEV
// THIS APP USES EMBEDLY AND OMDB TO DISCOVER DATA ABOUT A MOVIE TITLE OR MOVIE URL
// App info output : describes app.
$output = '<p>This app performs tests to see what wikipedia returns for agiven movie title.</p>';
require($sdmcore->sdmCoreGetUserAppDirectoryPath() . '/wikiTester/functions.php');
// args
$post = array(
    'action' => 'query',
    'format' => 'json',
    'prop' => 'revisions',
    'titles' => 'goodfellas',
    'rvprop' => 'content',
);
$i = 0;
$postArgsCount = count($post);
$get = '';
foreach ($post as $key => $value) {
    $get .= $key . '=' . $value . ($i === ($postArgsCount - 1) ? '' : '&');
    $i++;
}
// build api request url
$apiRequestUrl = 'http://en.wikipedia.org/w/api.php?' . $get;
// parse request
$apiRequest = $sdmcore->sdmCoreCurlGrabContent($apiRequestUrl);
// parse output
$output .= '<div style="height:420px;overflow:auto;"><h3>Wiki Tester : Expand Templates Example</h3><p>Tested Url : ' . $apiRequestUrl . '</p><p>Tested $_POST values : ' . wikiArrayToList($post) . '</p>' . wikiArrayToList(json_decode($apiRequest, TRUE)) . '</div>';
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, array('incpages' => array('wikiTester')), FALSE);

