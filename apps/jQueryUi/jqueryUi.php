<?php

/**
 * jQuery Ui user app: This app gives themes and apps access to the jQuery Ui library.
 *
 * Note: Apps that require jQuery Ui should be enabled after this app.
 *
 */

$output = '<div id="jQueryUiApp">
<h2>jQuery Ui</h2></h2>
<p>Provides the jQuery Ui library.</p>
<p id="jQueryUiStatus"><!-- jQueryUiStatus.js output will end up here --></p>
</div>';
$options = array(
    'incpages' => array('jQueryUi'),
);

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);