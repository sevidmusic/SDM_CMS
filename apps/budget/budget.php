<?php

/**
 * Hello World user app: This app demonstrates a simple Sdm Cms user app.
 */

/* Configure incorporation options. */
$options = array(
    'wrapper' => 'main_content',
    'incmethod' => 'overwrite',
    'incpages' => array('budget'),
    'ignorepages' => array(),
    'roles' => array('all'),
);

$availableCash = 0;
$availableDebit = 234.86;
$availableExpectedIncome = 33;
$availableBalance = $availableCash + $availableDebit + $availableExpectedIncome;
$expenses = array(
    'Car Insurance' => 170.23,
    'Php Storm' => 8.9,
    'Gas' => (80 - 16.25),
    'Tolls' => 7.5,
    'Cigarettes' => (41 - 40.99), // price of 1 pack times number of packs
    'Weed' => (50 - 50),
    'Laundry' => (7.5 - 7.5),
    'Twisted Tea' => (3.02 * 2),
);
/*
$expenses = array(
    'Car Insurance' => 170.23,
    'Php Storm' => 8.9,
    'Gas' => (80 - 16.25),
    'Tolls' => 7.5,
    'Cigarettes' => (41 - 40.99), // price of 1 pack times number of packs
    'Weed' => (50 - 50),
    'Laundry' => (7.5 - 7.5),

);*/
$totalExpenses = array_sum($expenses);
$availableAfterExpenses = $availableBalance - $totalExpenses;

/* Create some output. */
$output = '<div id="helloWorld"><h4 class="center">Budget ' . date('F d, Y') . '</h4>';

/* Funds Table */
$output .= '<table class="rounded">
            <thead>Summary of available balance and expenses:</thead>
              <tr class="positive">
                <td>Available Balance:</td>
                <td>$' . $availableBalance . '</td>
              </tr>
              <tr class="negative">
                <td>Total Expenses</td>
                <td>$' . $totalExpenses . '</td>
              </tr>
              <tr class="' . ($availableAfterExpenses > 0 ? 'positive' : 'negative') . '">
                <td>Available Balance After Expenses</td>
                <td>$' . $availableAfterExpenses . '</td>
              </tr>
            </table>';

/* Expenses Table */
$output .= '<table class="rounded">';
$output .= '<thead>Expenses:</thead>';
$color = true;
foreach ($expenses as $expense => $amount) {
    $bgColor = ($color === true ? '#000000' : '#777777');
    $output .= '<tr style="background: ' . $bgColor . '"><td>' . $expense . '</td><td>$' . $amount . '</td></tr>';
    $color = ($color === true ? false : true);
}
$output .= '</table></div>';

/* Incorporate output. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);