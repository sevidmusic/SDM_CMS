<?php

/**
 * The budget app provides a from for creating a simple budget
 * based on a users available cash, available debit, available
 * credit, and expected expenses.
 */

/* Configure incorporation options. */
$options = array(
    'wrapper' => 'main_content',
    'incmethod' => 'overwrite',
    'incpages' => array('budget'),
    'ignorepages' => array(),
    'roles' => array('all'),
);

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

/* Expenses | Used to determine total expenses for all categories combined. */
$expenses = array();

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


/* Available Balance Form */
$availableBalanceForm = new SdmForm();

/* Previously submitted values */
$availableCash = floatval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableCash'));
$availableDebit = floatval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableDebit'));
$availableCredit = floatval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableCredit'));

/* Form Object */
$availableBalanceForm->formHandler = 'budget';
$availableBalanceForm->formElements = array(
    array(
        'id' => 'availableCash',
        'type' => 'text',
        'element' => 'Available Cash <table class="rounded"><tr class="' . ($availableCash > 0 ? 'positive' : 'negative') . '"><td>$' . strval($availableCash) . '</td></tr></table>',
        'value' => ($availableCash > 0 || $availableCash < 0 ? strval($availableCash) : '0.00'),
        'place' => '1',
    ),
    array(
        'id' => 'availableDebit',
        'type' => 'text',
        'element' => 'Available Debit <table class="rounded"><tr class="' . ($availableDebit > 0 ? 'positive' : 'negative') . '"><td>$' . strval($availableDebit) . '</td></tr></table>',
        'value' => ($availableDebit > 0 || $availableDebit < 0 ? strval($availableDebit) : '0.00'),
        'place' => '2',
    ),
    array(
        'id' => 'availableCredit',
        'type' => 'text',
        'element' => 'Available Credit <table class="rounded"><tr class="' . ($availableCredit > 0 ? 'positive' : 'negative') . '"><td>$' . strval($availableCredit) . '</td></tr></table>',
        'value' => ($availableCredit > 0 || $availableCredit < 0 ? strval($availableCredit) : '0.00'),
        'place' => '3',
    ),
);
$availableBalanceForm->method = 'post';
$availableBalanceForm->submitLabel;
$availableBalanceForm->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());

/* Form HTML */
$availableBalanceFormHtml = $availableBalanceForm->sdmFormGetForm();

/* Budget Title. */
$budgetTitle = '<h4 class="center">Budget on ' . date('F d, Y') . ' at ' . date('g:ia') . '</h4>';

/**
 * Calculations.
 * Note: Calculations must be performed before constructing Balance Overview Table and after all other components
 * have been constructed.
 */
$availableBalance = $availableCash + $availableDebit + $availableCredit;
$availableAfterExpenses = $availableBalance - $totalExpenses;

/* Balance Overview Table */
$balanceOverviewTable = '<table class="rounded">
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

/* App Output */
$output = $budgetTitle;
$output .= $availableBalanceFormHtml;
$output .= $balanceOverviewTable;
$output .= $categorizedExpensesTable;
$output .= $expensesTable;

/* Incorporate output. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);