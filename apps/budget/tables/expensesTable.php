<?php

/* Expenses Table */
$expensesTable = '<table class="budget-rounded">';
$expensesTable .= '<thead>All Expenses:</thead>';
$color = true;
foreach ($expenses as $expense => $amount) {
    $bgColor = ($color === true ? 'budget-expense-cell-bg-1' : 'budget-expense-cell-bg-2');
    $expensesTable .= '<tr class="budget-expense-cell ' . $bgColor . '"><td>' . $expense . '</td><td>$' . $amount . '</td></tr>';
    $color = ($color === true ? false : true);
}
/* Total Expenses*/
$totalExpenses = array_sum($expenses);
$expensesTable .= '<tr class="budget-expense-cell ' . ($categoryExpenseTotal <= 0 ? 'budget-positive' : 'budget-negative') . '"><td>Total Expenses: ' . $totalExpenses . '</td></tr>';
$expensesTable .= '</table>';