<?php

/* Categorized Expenses */
$categorizedExpenses = array(
    'Vices' => array(
        'Smokes' => (9 * 10.25),
        'Booze' => 40,
    ),
    'Groceries' => array(
        'Misc. Food' => (7.9),
        'Dog Food' => 30,
        'Cat Food (dry)' => 30,
        'Cat Litter' => 10,
    ),
    'Laundry' => array(
        'Four Loads' => 30,
    ),
    'Transportation' => array(
        'Gas' => 80,
        'Tolls' => 7.5,
    ),
);

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