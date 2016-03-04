<?php

/**
 * Sdm Dev Output user app: This app is intended for use by developers. With it php
 * snippets can be tested. Just add the code to test to this file, and incorporate
 * the output via sdmAssemblerIncorporateAppOutput().
 */

$description = '<h1>Sdm Dev Output App</h1><p>This app is intended for use by developers.
                    With it php snippets can be tested. Just add the code to test to the
                    SdmDevOutput.php file.</p>';
$output = '<!-- Sdm Dev Output App Placeholder -->' . $description;
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, ['incpages' => ['SdmDevOutput']]);

/* Uncomment to have app show an overview of the DataObject loaded for each page. */
$sdmassembler->sdmCoreSdmReadArray($sdmassembler->info());