<?php

// THIS APP USES EMBEDLY AND OMDB TO DISCOVER DATA ABOUT A MOVIE TITLE OR MOVIE URL

require($sdmcore->sdmCoreGetUserAppDirectoryPath() . '/omdbTester/functions.php');
/** APP SETTINGS CODE * */
// app output options
$options = array(
    'wrapper' => 'main_content',
    'incmethod' => 'append',
    'incpages' => array('omdbTester'),
        //'ignorepages' => array('contentManager'),
); // options array determines how an apps output is incorporated into the page
$devmode = FALSE; // if set to TRUE then dev data about the app output will be displayed on the page as well
//  Add our initial app output to the $output var, this also initializes the $output var
$output = '<h2>OMDB Tester</h2><p>This app generates an html table that displays the data that is returned from OMDB for a given movie title based. It works by first looking up a movie url on embedly, then it grabs the title returned for that url and passes the title as search parameter to OMDB. Then the data returned from OMDB is organized into an HTML table and displayed on the page. The urls tested can be seen in the source code in the $movieUrls array. To see the OMDB data table click here: <br/><br/><a href="' . $sdmcore->sdmCoreGetRootDirectoryUrl() . '/index.php?page=omdbTester&mode=test">Generate OMDB Data Table</a></p><p>You can also test an individual movie url by entering a movie url from a site like YouTube into the form below.</p>';
// Add our oembed test form. This form allows the user to submit the movie title or url via an html form instead of testing all the urls in returned by getTestMovieUrls()
$output .= getOmdbTestForm();
// determine if we should display the omdb data table, and weather or not to build the table based on a user submitted url or to test all providers by uilding a $movieUrls array with test urls to videos on the different providers sites
if (isset($_POST['SdmForm'])) {
    // if movie url submitted via getOembedTestForm, then use the submitter url as our only test movie url.
    $movieUrls = array(
        htmlentities(SdmForm::sdmFormGetSubmittedFormValue('movieUrl')),
    );
    // add our embedly data table to our app output
    $output .= buildOmdbDataTable($movieUrls);
}
// otherwise see if we are in test mode
elseif (isset($_GET['mode']) && $_GET['mode'] === 'test') {
    // if in test mode, build table based on the test urls returned by getTestMovieUrls()
    $movieUrls = getTestMovieUrls();
    // add our embedly data table to our app output
    $output .= buildOmdbDataTable($movieUrls);
}
// incorporate our app output into the page
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, $options, $devmode);

