<?php

$output = '
<h2>jQuery</h2></h2>
<p>Provides the jQuery library.</p>
<p id="jQueryStatus"><!-- jQueryStatus.js output will end up here --></p>
';
$options = array(
    'incpages' => array('jQuery'),
);

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);