<?php

/**
 * jQuery user app: This app gives themes and apps access to the jQuery library.
 *
 * Note: Apps that require jQuery should be enabled after this app.
 *
 */

$output = '
<h2>jQuery</h2></h2>
<p>Provides the jQuery library.</p>
<p id="jQueryStatus"><!-- jQueryStatus.js output will end up here --></p>
';
$options = array(
    'incpages' => array('jQuery'),
);

$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);

$sdmassembler->sdmAssemblerIncorporateAppOutput('
<p>Note: The jQuery app must be enabled before any apps or themes dependent on
jQuery can be used. If an app or theme that depends on jQuery was enabled before
the jQuery app then you will have to disable the dependent app, make sure jQuery is enabled,
and then re-enable the dependent app</p>', ['incpages' => ['jQuery'], 'incmethod' => 'append']);