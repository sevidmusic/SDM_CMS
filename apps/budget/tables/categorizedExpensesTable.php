<?php

$budgetLoaded = isset($savedBudget);

switch ($budgetLoaded) {
    case true:
        /* Categorized Expenses | Must be converted to an array */
        $categorizedExpenses = json_decode(json_encode($savedBudget->categorizedExpenses), true);
        break;
    default:
        /* Categorized Expenses | Hardcoded | @todo make these settings into a form. */
        $categorizedExpenses = (
        $selectSavedBudgetForm->sdmFormGetSubmittedFormValue('categorizedExpenses') !== null
            ? json_decode($selectSavedBudgetForm->sdmFormGetSubmittedFormValue('categorizedExpenses'), true)
            : array(
            'Misc' => array(
                'Cigarettes' => 10.25 * 7,
                'Golf' => 40,
                'Drinks' => 0,
            ),
            'Transportation' => array(
                'Tolls' => 13.50,
                'Gas' => 73,
            ),
            'Food' => array(
                'For us' => 56.50,
            ),
            'Bills' => array(
                'Geico' => 50.60,
                'PhpStorm' => 8.90,
            ),
            /*'Paycheck' => array(
              'If check' => 4 * (7 * (15 - (15 * .25))),
              'If cash' => 4 * (7 * 15),
            ),*/
        )
        );
        break;
}

/* Categorized Expenses Table */
$categorizedExpensesTable = '<table class="rounded">';
$categorizedExpensesTable .= '<thead>Categorized Expenses:</thead>';
$color = true;
foreach ($categorizedExpenses as $category => $categoryExpenses) {
    $categorizedExpensesTable .= '<tr><th style="text-align: left;">' . $category . '</th></tr>';
    foreach ($categoryExpenses as $expense => $amount) {
        $bgColor = ($color === true ? '#000000' : '#777777');
        $categorizedExpensesTable .= '<tr style="background: ' . $bgColor . ';"><td>' . $expense . '</td><td>$' . $amount . '</td></tr>';
        $color = ($color === true ? false : true);
        // add expenses to the $expenses array so the total expenses for all categories combined can be calculated
        $expenses[$category . ': ' . $expense] = $amount;
    }
    $categoryExpenseTotal = array_sum($categoryExpenses);
    $categorizedExpensesTable .= '<tr class="' . ($categoryExpenseTotal <= 0 ? 'positive' : 'negative') . '"><td style="text-align: left;">' . 'Total Category Expense: ' . $categoryExpenseTotal . '</td></tr>';
}
$categorizedExpensesTable .= '</table>';