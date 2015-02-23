<?php

$options = array();
$output = '';
// form submitted successfully
if ($_POST['sdm_form']['content_manager_form_submitted'] === 'content_manager_form_submitted') {
    $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '
                    <!-- contentManager div -->
                    <div id"contentManager">
                        <p>Form has been submitted with the following values.
                            <ul>
                                <li>PAGE : ' . $_POST['sdm_form']['page'] . '</li>';
    // loop through and update wrappers
    foreach ($sdmcms->sdmCmsDetermineAvailableWrappers() as $dispalyValue => $machineValue) {
        $sdmcms->sdmCmsUpdateContent($_POST['sdm_form']['page'], $machineValue, nl2br($_POST['sdm_form'][$machineValue]));
        $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '
                                <li>Wrapper with ID : ' . $machineValue . '<br/>Wrapper Content : <xmp>' . $_POST['sdm_form'][$machineValue] . '</xmp></li>';
    }
    $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '
                            </ul></p>
                    </div>
                    <!-- close contentManager div -->';
}
// form submitted but error occured
else {
    $sdmassembler_dataObject->content->$sdmassembler_requestedpage->main_content .= '
                <div id="contentManager">
                    <p>And error occure and the form could not be submitted</p>
                </div>
                ';
}

$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options);