<?php
/* Only run this code if on SdmCoreOverview page */
if ($sdmassembler->sdmCoreDetermineRequestedPage() === 'SdmCoreOverview') {
    /**
     * Converts bytes to human readable unit such as megabytes. Which unit is used is determined
     * internally based on how many bytes are being converted, smaller sizes will convert to
     * smaller units.
     *
     * @param integer $bytes Number of bytes to convert to human readable unit.
     *
     * @param integer $decimals Number of decimals to round to.
     *
     * @return mixed Bytes converted to human readable unit, or false on failure.
     */
    function convertBytes($bytes, $decimals = 2)
    {
        $unit = str_split('BKMGTP');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . formatUnitBySize($unit[$factor]);
    }

    /**
     * @param $unit
     * @return string
     */
    function formatUnitBySize($unit)
    {
        switch ($unit) {
            case 'B':
                unset($unit);
                $unit = ' bytes';
                break;
            case 'K':
                unset($unit);
                $unit = ' kilobytes';
                break;
            case 'M':
                unset($unit);
                $unit = ' megabytes';
                break;
            case 'G':
                unset($unit);
                $unit = ' megabytes';
                break;
            case 'T':
                unset($unit);
                $unit = ' gigabytes';
                break;
            /* Petabytes is optional, but illogical since most
               hard drives and servers do not have petabytes of storage available.
            case 'P':
                unset($unit);
                $unit = ' petabytes';
                break;
            */
        }
        return $unit;
    }

    /* Options. */
    $options = array('wrapper' => 'main_content', 'incmethod' => 'append', 'incpages' => array('SdmCoreOverview'));

    /* Determine size of data.json */
    $dataObjectSize = filesize($sdmassembler->sdmCoreGetDataDirectoryPath() . '/data.json');

    /* Output. */
    $output = '
    <h2>Overview of current state the CORE</h2>
    <p>The current size of SDM CORE is ' . convertBytes($dataObjectSize) . ' (' . $dataObjectSize . ' bytes)' . '</p>
    <p>The SDM Core Overview app displays the current state of the Core Data Object stored in data.json</p>
    ';

    /* Load the entire data object. */
    $dataObject = $sdmassembler->sdmCoreLoadDataObject(false);

    /* Use output buffering to insure output of core via the DataObject is incorporated into the page correctly.
       Since sdmCoreSdmReadArray() typically echos it's output internally, output buffering is required in order
       to capture the output of sdmCoreSdmReadArray() and assign it to the $output string. */
    ob_start();

    /* Read data object with sdmCoreSdmReadArray(). */
    $sdmassembler->sdmCoreSdmReadArray($dataObject);

    /* Assign captured output from sdmCoreSdmReadArray() to $output string. */
    $output .= ob_get_clean();

    /* Incorporate core overview into page. */
    $sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);
}

