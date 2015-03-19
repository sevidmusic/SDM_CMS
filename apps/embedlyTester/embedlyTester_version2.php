<?php

/**
 * This app showss the minimum amount of code needed for an app.
 */
$options = array(
    'wrapper' => 'main_content',
    'incmethod' => 'prepend',
    'incpages' => array('embedlyTester'),
        //'ignorepages' => array('contentManager'),
); // options array determines how an apps output is incorporated into the page
$output = '<h2>Embedly Tester</h2><p>See what embedly returns for any movie url and save it to the embedlyDataLog file found in the embedlyTester app folder. You can view the log <a>here</a>:</p>';
$devmode = FALSE; // if set to TRUE then dev data about the app output will be displayed on the page as well
$embedlyTesterForm = new SDM_Form();
$embedlyTesterForm->form_handler = 'embedlyTester';
$embedlyTesterForm->method = 'post';
$embedlyTesterForm->submitLabel = 'See What Embedly Returns For This Movie';
$embedlyTesterForm->form_elements = array(
    array(
        'id' => 'movieUrl',
        'type' => 'text',
        'element' => 'Movie Url',
        'value' => '',
        'place' => '0',
    ),
);
$embedlyTesterForm->__build_form($sdmcore->getRootDirectoryUrl());
$output .= $embedlyTesterForm->__get_form();

function assemlbeExtractTableElements($extractData, $rowcolor) {
    $styles_extractDataTableRow = 'background: ' . $rowcolor . '; color: ' . ($rowcolor === 'grey' ? 'black' : 'white') . ';'; // @todo alternate bg color every row
    $styles_td = 'padding: 10px;border:2px solid #777777;border-radius: 3px;'; // dont set background color here, it will be set depending on what value is returned
    $styles_headerTd = 'padding 10px; border: 2px solid #999999; border-radius: 3px;';
    $decodedData = json_decode($extractData, TRUE);
    $output = '<tr style="' . $styles_extractDataTableRow . '"><td style="' . $styles_headerTd . '">provider_url</td><td style="' . $styles_headerTd . '">description</td><td style="' . $styles_headerTd . '">embeds</td><td style="' . $styles_headerTd . '">safe</td><td style="' . $styles_headerTd . '">provider_display</td><td style="' . $styles_headerTd . '">related</td><td style="' . $styles_headerTd . '">favicon_url</td><td style="' . $styles_headerTd . '">authors</td><td style="' . $styles_headerTd . '">images</td><td style="' . $styles_headerTd . '">cache_age</td><td style="' . $styles_headerTd . '">language</td><td style="' . $styles_headerTd . '">app_links</td><td style="' . $styles_headerTd . '">original_url</td><td style="' . $styles_headerTd . '">url</td><td style="' . $styles_headerTd . '">media</td><td style="' . $styles_headerTd . '">title</td><td style="' . $styles_headerTd . '">offset</td><td style="' . $styles_headerTd . '">lead</td><td style="' . $styles_headerTd . '">content</td><td style="' . $styles_headerTd . '">entities</td><td style="' . $styles_headerTd . '">favicon_colors</td><td style="' . $styles_headerTd . '">keywords</td><td style="' . $styles_headerTd . '">published</td><td style="' . $styles_headerTd . '">provider_name</td><td style="' . $styles_headerTd . '">type</td></tr>';
    $output .= '<tr style="' . $styles_extractDataTableRow . '">';
    // add extract data to table
    foreach ($decodedData as $value) {
        switch (is_array($value)) {
            case TRUE:
                $output .= '<td style="' . $styles_td . '">' . (is_null($value) || $value === '' ? '<i style="color:aqua">null</i>' : 'ARRAY') . '</td>';
                break;
            default:
                $output .= '<td style="' . $styles_td . (is_null($value) || $value === '' ? 'background: red;' : '') . '">' . (is_null($value) || $value === '' ? '<i style="color:aqua">null</i>' : (is_bool($value) === TRUE ? ($value === TRUE ? '<b style="color:green">TRUE</b>' : '<b style="color:red">FALSE</b>') : $value)) . '</td>';
                break;
        }
    }
    $output .= '</tr>';

    return $output;
}

if (isset($_POST['sdm_form'])) {
    $movieUrls = array();
    $embedlyUrl = 'http://api.embed.ly/1/extract?key=a975a6e3ebc1424d85338917fcaca978&url=';
    $movieUrls = array(htmlentities(SDM_Form::get_submitted_form_value('movieUrl')), 'https://www.youtube.com/watch?v=rG_7xur1iRc');
    $styles_embedlyTester_json = 'color: #000000; border: 2px solid #000000; border-radius: 3px; overflow: auto; width: 95%; padding: 20px; margin: 15px 0px 15px 0px;';
    $styles_extractData = 'background: #000000; color: #DDDDDD; border: 2px solid #000000; border-radius: 3px; overflow: auto; width: 95%; padding: 20px; margin: 15px 0px 15px 0px;';
    $styles_embedlyTester_requestFailed = 'color:red';
    $output .= '<table id="extractDataTable" style="' . $styles_extractDataTable . '">';
    $rowcolor = 'grey'; // initial row color
    foreach ($movieUrls as $movieUrl) {
        $embedlyRequestUrl = $embedlyUrl . $movieUrl;
        $extractData = $sdmcore->sdmCoreCurlGrabContent($embedlyRequestUrl, array());
        $output .= assemlbeExtractTableElements($extractData, $rowcolor);
        $rowcolor = ($rowcolor === 'grey' ? 'black' : 'grey'); // alternate row colors
    }

    $output .= '</table>';
}
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options, $devmode);

