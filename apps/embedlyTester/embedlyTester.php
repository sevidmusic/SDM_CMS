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

function formatEmbedDataForDisplay($data, $output = '', $issub = FALSE) {
    $decodedData = json_decode($data, TRUE);
    foreach ($decodedData as $key => $value) {
        switch (is_array($value)) {
            case TRUE:
                $output .= formatEmbedDataForDisplay(json_encode($value), $output, TRUE);
                break;
            default:
                $output .= '<br /><br />' . ($issub === TRUE ? '-->' : '') . $key . ' : ' . (is_null($value) || $value === '' ? 'null' : $value);
                break;
        }
    }
    return $output;
}

if (isset($_POST['sdm_form'])) {
    $embedlyUrl = 'http://api.embed.ly/1/extract?key=a975a6e3ebc1424d85338917fcaca978&url=';
    $movieUrl = htmlentities(SDM_Form::get_submitted_form_value('movieUrl'));
    //$post = array('url' => $movieUrl, 'key' => 'a975a6e3ebc1424d85338917fcaca978');
    $embedlyRequestUrl = $embedlyUrl . $movieUrl;
    $data = $sdmcore->sdmCoreCurlGrabContent($embedlyRequestUrl, array()); // , $post);
    $output .= '<h4>Checking url ' . $embedlyRequestUrl . '</h4>' . ($data === FALSE ? '<b style="color:red">Request Failed</b>' : '<div style="background: #000000; color: #CCCCCC;border: 2px solid #C0C0C0; border-radius: 3px; overflow: auto; width: 95%; padding: 20px; margin: 15px 0px 15px 0px;">JSON :<xmp>' . $data . '</xmp></div>'); //  . formatEmbedDataForDisplay($data);
    //$output .= '<h4>Checking url ' . $embedlyRequestUrl . '</h4>';
    //$sdmcore->sdm_read_array(json_decode($data));
}
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options, $devmode);

