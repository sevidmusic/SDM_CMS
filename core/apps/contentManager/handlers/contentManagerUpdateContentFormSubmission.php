<?php

$options = array(
    'incpages' => array('contentManagerUpdateContentFormSubmission'),
);
$output = '';
// form submitted successfully
if ($_POST['SdmForm']['content_manager_form_submitted'] === 'content_manager_form_submitted') {
    $output .= '
                    <!-- contentManager div -->
                    <div id"contentManager">
                        <p>Form has been submitted with the following values.
                            <ul>
                                <li>PAGE : ' . $_POST['SdmForm']['page'] . '</li>';
    // loop through and update wrappers
    foreach ($sdmcms->sdmCmsDetermineAvailableWrappers() as $dispalyValue => $machineValue) {
        $sdmcms->sdmCmsUpdateContent($_POST['SdmForm']['page'], $machineValue, nl2br($_POST['SdmForm'][$machineValue]));
        $output .= '
                                <li>Wrapper with ID : ' . $machineValue . '<br/>Wrapper Content : <xmp>' . $_POST['SdmForm'][$machineValue] . '</xmp></li>';
    }
    $output .= '
                            </ul></p>
                    </div>
                    <!-- close contentManager div -->';
}
// form submitted but error occured
else {
    $output .= '
                <div id="contentManager">
                    <p>And error occure and the form could not be submitted</p>
                </div>
                ';
}

$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, $options);