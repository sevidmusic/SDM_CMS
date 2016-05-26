<?php

/**
 * Sdm Kind user app: Demonstrates the Sdm Cms's internal encryption and decryption algorithms.
 */

/* Options array. */
$options = array(
    'wrapper' => 'main_content',
    'incmethod' => 'overwrite',
    'incpages' => array('sdmKind'),
    'ignorepages' => array(),
    'roles' => array('root'),
);

/* Initial output with html formatting for display. */
$output = '<h1>Sdm kind Demo:O</h1><p>The sdmKind() and sdmNice() methods are responsible for handling internal encryption and decryption for the SDM CMS.</p>';

/* Text to be encrypted. */
$text = 'This text will be encrypted differently each time it is passed to sdmKind() and no matter how the text is encrypted sdmNice() will be able to decrypt it.';

/* Encrypt text. */
$enc = $sdmassembler->sdmKind($text);

/* Decrypt text. */
$dec = $sdmassembler->sdmNice($enc);

/* Add original text to output with html formatting for display. */
$output .= '<p>Text: <span style="color: #00BB00;">' . $text . '</span></p>';

/* Add encrypted text to output with html formatting for display. */
$output .= '<p>Encrypted Text: <span style="color: #3399ff">' . $enc . '</span></p>';

/* Add decrypted text to output with html formatting for display. */
$output .= '<p>Decrypted Text: <span style="color: #77BB77;">' . $dec . '</span></p>';

/* Incorporate apps output into the page. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);
