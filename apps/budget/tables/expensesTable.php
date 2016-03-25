<?php

/* Expenses Table */
$expensesTable = '<table class="rounded">';
$expensesTable .= '<thead>All Expenses:</thead>';
$color = true;
foreach ($expenses as $expense => $amount) {
    $bgColor = ($color === true ? '#000000' : '#777777');
    $expensesTable .= '<tr style="background: ' . $bgColor . '"><td>' . $expense . '</td><td>$' . $amount . '</td></tr>';
    $color = ($color === true ? false : true);
}
/* Total Expenses*/
$totalExpenses = array_sum($expenses);
$expensesTable .= '<tr class="' . ($categoryExpenseTotal <= 0 ? 'positive' : 'negative') . '"><td>Total Expenses: ' . $totalExpenses . '</td></tr>';
$expensesTable .= '</table>';