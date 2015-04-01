<?php

// IN DEV
// THIS APP USES EMBEDLY AND OMDB TO DISCOVER DATA ABOUT A MOVIE TITLE OR MOVIE URL
// App info output : describes app.
$output = '<p>This app performs tests to see what wikipedia returns for agiven movie title.</p>';
require($sdmcore->sdmCoreGetUserAppDirectoryPath() . '/wikiTester/functions.php');
// args
$revisionPost = array(
    'action' => 'query',
    'prop' => 'revisions',
    'titles' => 'goodfellas|family guy',
    'rvprop' => 'content',
    'rvsection' => 0, // important | gets the infobox
//    'rvexpandtemplates' => 'rvexpandtemplates', // changes how the returned page items are fomratted. i.e., if this property does NOT exist then pure wikitext is returend, if it is present then wikitext formatted for readability is returned. THIS SHOULD NOT BE USED outside of development as it will make it impossible to parse the returned data efficiently.
    'redirects' => 'redirects', // important | reolves any redirects related to the titles
    'format' => 'json', // important | we need json
    'indexpageids' => 'indexpageids', // important | returns an array of all the page ids for the returned pages
//    'continue' => '||',
);
$post = $revisionPost;
//$post = $expandTemplatesPost;
//$post = $extractPost;
$i = 0;
$postArgsCount = count($post);
$get = '';
foreach ($post as $key => $value) {
    $get .= $key . '=' . str_replace(' ', '%20', $value) . ($i === ($postArgsCount - 1) ? '' : '&');
    $i++;
}
// build api request url
$apiRequestUrl = 'http://en.wikipedia.org/w/api.php?' . $get;
// parse request
$apiRequest = $sdmcore->sdmCoreCurlGrabContent($apiRequestUrl);
// parse output
switch ($_GET['wikiquerymode']) {
    case 'wikidata':
        $output .= '<div style="height:420px;overflow:auto;"><h3>Wiki Tester : Query Example</h3><p>Tested Url : ' . '<a href="' . $apiRequestUrl . '">' . $apiRequestUrl . '</a>' . '</p><p>Tested $_POST values : ' . wikiArrayToList($post, 'POST') . '</p>' . (is_array(json_decode($apiRequest, TRUE)) ? wikiArrayToList(json_decode($apiRequest, TRUE)) : '<h5 style="color:red;">Request did not return a json formatted string! Use format=json in your request url or post array to get a json result.</h5><xmp>' . $apiRequest) . '</xmp></div>';
        break;
    case 'mmhdata':
        $output .= '<div style="height:420px;overflow:auto;"><h3>Wiki Tester : Query Example</h3><p>Tested Url : ' . '<a href="' . $apiRequestUrl . '">' . $apiRequestUrl . '</a>' . '</p><p>Tested $_POST values : ' . wikiArrayToList($post, 'POST') . '</p>' . (is_array(json_decode($apiRequest, TRUE)) ? wikiArrayToList(json_decode($apiRequest, TRUE)) : '<h5 style="color:red;">Request did not return a json formatted string! Use format=json in your request url or post array to get a json result.</h5><xmp>' . $apiRequest) . '</xmp></div>';
        break;
    default:
        $output .= '<ul><li><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=wikiTester&wikiquerymode=wikidata">Wiki Data Query Test</a></li>';
        $output .= '<li><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=wikiTester&wikiquerymode=mmhdata">MMh Mined Wiki Data Query Reults</a></li></ul>';
        break;
}

$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, array('incpages' => array('wikiTester')), FALSE);

