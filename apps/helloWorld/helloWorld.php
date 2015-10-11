<?php

/**
 * This app demonstrates a simple app.
 */
$output = '<h4>Hello World</h4><p>The helloWorld app demonstrates just how easy it is to create an app for the SDM CMS. Have a peek at it\'s source code for some examples.</p>'; // this string will be sent to the pages in the incpages array, or all pages if incpages is empty or not set
$options = array(
    'wrapper' => 'main_content',
    'incmethod' => 'overwrite',
    'incpages' => array('helloWorld'),
    'ignorepages' => array(),
); // options array determines how an apps output is incorporated into the page
$devmode = FALSE; // if set to TRUE then dev data about the app output will be displayed on the page as well
// we use the Sdm Assembler's sdmAssemblerIncorporateAppOutput() method to display our apps output on the page
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, $options, $devmode);