<?php

/**
 * This app showss the minimum amount of code needed for an app.
 */
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<h4>Appended Hello World app output via the SDM_Assembler\'s new incorporateAppOutput() method</h4>');
$options = array('incmethod' => 'prepend');
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, '<h4>Hello World app output via the SDM_Assembler\'s new incorporateAppOutput() method with options array set</h4>', $options);