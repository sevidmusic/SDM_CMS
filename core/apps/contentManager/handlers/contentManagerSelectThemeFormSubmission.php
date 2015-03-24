<?php

$output = '';
// form submitted successfully
if ($_POST['SdmForm']['content_manager_form_submitted'] === 'content_manager_form_submitted') {
    $output .= '
                    <!-- contentManager div -->
                    <div id="contentManager" style="background:#DDDDDD;width:75%;border:2px solid #CCCCCC;border-radius:7px;margin:0 auto;padding:20px;">
                        <p>Form has been submitted with the following values.
                            <ul>
                                <li>THEME: ' . $_POST['SdmForm']['theme'] . '</li>';
    $output .= '
                            </ul></p>
                    </div>
                    <!-- close contentManager div -->';
    // change the theme
    $sdmcms->sdmCmsChangeTheme($_POST['SdmForm']['theme']);
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