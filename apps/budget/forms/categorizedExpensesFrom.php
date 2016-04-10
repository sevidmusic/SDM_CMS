<?php

switch (isset($savedBudget)) {
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
                'Cigarettes' => 10.25 * 8,
                'Golf' => 100,
                'Drinks' => 25,
            ),
        ));
        break;
}

/* Categorized Expenses Table */
$categorizedExpensesTable = '<table class="budget-rounded">';
$categorizedExpensesTable .= '<thead>Categorized Expenses:</thead>';
$color = true;
foreach ($categorizedExpenses as $category => $categoryExpenses) {
    $categorizedExpensesTable .= '<tr><th class="budget-text-left">' . $category . '</th></tr>';
    foreach ($categoryExpenses as $expense => $amount) {
        $bgColor = ($color === true ? 'budget-expense-cell-bg-1' : 'budget-expense-cell-bg-2');
        $categorizedExpensesTable .= '<tr class="budget-expense-cell ' . $bgColor . '"><td>' . $expense . '</td><td>$' . $amount . '</td></tr>';
        $color = ($color === true ? false : true);
        // add expenses to the $expenses array so the total expenses for all categories combined can be calculated
        $expenses[$category . ': ' . $expense] = $amount;
    }
    $categoryExpenseTotal = array_sum($categoryExpenses);
    $categorizedExpensesTable .= '<tr class="budget-expense-cell ' . ($categoryExpenseTotal <= 0 ? 'budget-positive' : 'budget-negative') . '"><td class="budget-text-left">' . 'Total Category Expense: ' . $categoryExpenseTotal . '</td></tr>';
}
$categorizedExpensesTable .= '</table>';