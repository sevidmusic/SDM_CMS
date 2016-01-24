<?php

$output = '
<h2>jQuery Ui</h2></h2>
<p>Provides the jQuery Ui library.</p>
<p id="jQueryUiStatus"><!-- jQueryUiStatus.js output will end up here --></p>
';
$options = array(
    'incpages' => array('jQueryUi'),
);

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);