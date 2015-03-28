<?php

// THIS APP USES EMBEDLY AND OMDB TO DISCOVER DATA ABOUT A MOVIE TITLE OR MOVIE URL

require($sdmcore->sdmCoreGetUserAppDirectoryPath() . '/wikiTester/functions.php');
$queryArgs = array(
    'action' => 'query', // type of action for the api to take, most cases will be query
    'format' => 'json', // the format to return the data in, most cases will be json, but can also be xml and a variety of other formats. @see http://www.mediawiki.org/wiki/API
    //'indexpageids' => 'indexpageids', // will return an array of page ids for the returned pages
    'titles' => 'Goodfellas|Family_Guy|Pulp_Fiction|Snatch', // the titles to search for (should be a piped string : Title_One|Title_Two|Title_Three)
    'redirects' => 'redirects', // will cause API to try and resolve an naming convention issues witht he request titles or page ids that could lead to inaccurate results or no results at all
    'prop' => 'info', // the property categories we wish to get
    'inprop' => 'url|displaytitle', // the property types we wish to get
);
// query request
$queryRequest = $sdmcore->sdmCoreCurlGrabContent('http://en.wikipedia.org/w/api.php', $queryArgs);
// parse args
$parseArgs = array(
    'action' => 'parse',
    'format' => 'json',
    'title' => 'family',
    'redirects' => 'redirects',
    'prop' => '',
);
// parse request
$parseRequest = $sdmcore->sdmCoreCurlGrabContent('http://en.wikipedia.org/w/api.php', $parseArgs);
// App info output : descrives app.
$output = '<p>This app performs tests to see what wikipedia returns for agiven movie title.</p>';
// parse output
$output .= '<div style="height:420px;overflow:auto;"><h3>Wiki Tester : Parse Example</h3>' . wikiArrayToList(json_decode($parseRequest, TRUE)) . '</div>';
// query output
//$output .= '<div style="height:420px;overflow:auto;"><h3>Wiki Tester : Query Example</h3>' . wikiArrayToList(json_decode($queryRequest, TRUE)) . '</div>';
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, array('incpages' => array('wikiTester')), FALSE);

