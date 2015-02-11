<?php

// form submitted successfully
if ($_POST['sdm_form']['content_manager_form_submitted'] === 'content_manager_form_submitted') {
    $sdmcms->sdmCmsDeletePage($_POST['sdm_form']['page_to_delete']);


    $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '
                    <!-- contentManager div -->
                    <div id"contentManager">
                        <p>Form has been submitted with the following values.
                            <ul>
                                <li>PAGE : ' . $_POST['sdm_form']['page_to_delete'] . '</li>
                            </ul></p>
                    </div>
                    <!-- close contentManager div -->';
}
// form submitted but error occured
else {
    $sdmassembler_contentObject->content->$sdmassembler_requestedpage->main_content .= '
                <div id="contentManager">
                    <p>And error occured and the form could not be submitted</p>
                </div>
                ';
}