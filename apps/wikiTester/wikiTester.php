<?php

if ($sdmcore->sdmCoreDetermineRequestedPage() === 'wikiTester') {
// App info output : describes app.
    $output = '<p>This app performs tests to see what wikipedia returns for agiven movie title.</p>';
    require($sdmcore->sdmCoreGetUserAppDirectoryPath() . '/wikiTester/functions.php');
// movie titles to query, should be a piped string, i.e. 'movieOne|movie2|Movie%20three|...|movie408'
    $querytitles = 'goodfellas|family guy';
// build the movie data array
    $movieApiQueryArray = buildMovieApiQueryArray($querytitles);
// display the test url that was generated for the reuqest
    $output .= '<p>Tested url <a href="' . $movieApiQueryArray['mmhQueryUrl'] . '">' . $movieApiQueryArray['mmhQueryUrl'] . '</a></p>';
// display array as an HTML list in the app output
    $output .= wikiArrayToList($movieApiQueryArray);
// incorporate app output into page
    $sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, array('incpages' => array('wikiTester')), FALSE);
}