<?php

$output = '';
// form submitted successfully
if ($_POST['SdmForm']['content_manager_form_submitted'] === 'content_manager_form_submitted') {
    $sdmcms->sdmCmsDeletePage($_POST['SdmForm']['page_to_delete']);


    $output .= '
                    <!-- contentManager div -->
                    <div id"contentManager">
                        <p>Form has been submitted with the following values.
                            <ul>
                                <li>PAGE : ' . $_POST['SdmForm']['page_to_delete'] . '</li>
                            </ul></p>
                    </div>
                    <!-- close contentManager div -->';
}
// form submitted but error occured
else {
    $output .= '
                <div id="contentManager">
                    <p>And error occured and the form could not be submitted</p>
                </div>
                ';
}

$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, $options);