<?php

/**
 * jQuery user app: This app gives themes and apps access to the jQuery library.
 *
 * Note: Apps that require jQuery should be enabled after this app.
 *
 */

$output = '<div id="jQueryApp">
<h2>jQuery</h2></h2>
<p>Provides the jQuery library.</p>
<p id="jQueryStatus"><!-- jQueryStatus.js output will end up here --></p>
</div>';
$options = array(
    'incpages' => array('jQuery'),
);

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);