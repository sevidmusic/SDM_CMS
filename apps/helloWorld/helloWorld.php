<?php

/**
 * This app showss the minimum amount of code needed for an app.
 */
$options = array(
    'wrapper' => 'topmenu',
    'incmethod' => 'prepend',
    'incpages' => array('homepage'),
    'ignorepages' => array('core'),
);
$output = '<p>Appended Hello World app output via the SDM_Assembler\'s new incorporateAppOutput() method</p>';
$devmode = FALSE;
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options, $devmode);
