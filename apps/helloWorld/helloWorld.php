<?php

/**
 * This app demonstrates a simple app.
 */

/* Configure incorporation options. */
$options = array(
    'wrapper' => 'main_content',
    'incmethod' => 'overwrite',
    'incpages' => array('helloWorld'),
    'ignorepages' => array(),
    'roles' => array('all'),
);

/* Create some output. */
$output = '<div id="helloWorld">
            <h4>Hello World</h4>
            <p>The helloWorld app demonstrates just how easy it is to create an app for the SDM CMS.
            Have a peek at it\'s source code for some examples.</p>
            </div>';

/* Incorporate output. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);