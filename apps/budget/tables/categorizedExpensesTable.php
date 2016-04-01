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
                'Cigarettes' => 10.25 * 9,
                'Golf' => 100,
                'Drinks' => 25,
            ),
            'Transportation' => array(
                'Tolls' => 7.50,
                'Gas' => 60,
            ),
            'Food' => array(
                'Misc' => 60,
                'Dog food' => 35,
                'Cat food (dry)' => 25,
            )
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