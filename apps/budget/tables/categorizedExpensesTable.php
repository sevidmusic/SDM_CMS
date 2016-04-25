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
                'Cigarettes' => 10.25 * 1,
            ),
            'Transportation' => array(
                'Tolls' => 1.5 * 2,
                'Gas' => 10,
            ),
            'Bills' => array(
                'Geico' => 50.60,
                'PhpStorm' => 8.90,
            ),
        )
        );
        break;
}

/* Categorized Expenses Table */
$categorizedExpensesTable = '<table class="budget-rounded">';
$categorizedExpensesTable .= '<thead>Categorized Expenses:</thead>';
$color = true;
foreach ($categorizedExpenses as $category => $categoryExpenses) {
    $categorizedExpensesTable .= '<tr><th>' . $category . '</th></tr>';
    foreach ($categoryExpenses as $expense => $amount) {
        $bgColor = ($color === true ? 'budget-expense-cell-bg-1' : 'budget-expense-cell-bg-2');
        $categorizedExpensesTable .= '<tr class="budget-expense-cell ' . $bgColor . '"><td id="budget-rounded-left">' . $expense . '</td><td class="budget-text-center" id="budget-rounded-right">$' . $amount . '</td></tr>';
        $color = ($color === true ? false : true);
        // add expenses to the $expenses array so the total expenses for all categories combined can be calculated
        $expenses[$category . ': ' . $expense] = $amount;
    }
    $categoryExpenseTotal = array_sum($categoryExpenses);
    $categorizedExpensesTable .= '<tr class="budget-expense-cell ' . ($categoryExpenseTotal <= 0 ? 'budget-positive' : 'budget-negative') . '"><td class="budget-text-left">' . 'Total Category Expense: <b>$' . $categoryExpenseTotal . '</b></td></tr>';
}
$categorizedExpensesTable .= '</table>';