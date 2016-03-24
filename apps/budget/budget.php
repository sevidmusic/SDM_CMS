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

/* Expenses */
$expenses = array(
    'Gas' => (25 - 20.05),
    'Toll' => 1.5,
);
$totalExpenses = array_sum($expenses);


/* Categorized Expenses */
$categorizedExpenses = array(
    'Category One' => array(
        'expense one' => 20.73,
        'expense two' => 4.20,
        'expense three' => 3.38,
    ),
    'Category Two' => array(
        'expense one' => 150.2,
        'expense two' => 5.00,
        'expense three' => 5.73,
        'expense four' => 20.23,
        'expense five' => .73,
    ),
    'Category Three' => array(
        'expense one' => .25,
        'expense two' => 1.73,
        'expense three' => 2.03,
        'expense four' => 12.33,
        'expense five' => 4.73,
    ),
    'Category Four' => array(
        'expense one' => 1.98,
        'expense two' => 3.37,
    ),
    'Category Five' => array(
        'expense one' => 120.08,
        'expense two' => 230.23,
        'expense three' => 23.04,
    ),
    'Category Six' => array(
        'expense one' => 98.73,
    ),
    'Category Seven' => array(
        'expense one' => 73.21,
    ),
);


/* Available Balance Form */
$availableBalanceForm = new SdmForm();

/* Submitted values */
$availableCash = floatval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableCash'));
$availableDebit = floatval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableDebit'));
$availableCredit = floatval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableCredit'));

/* Available Balance Form */
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
$availableBalanceFormHtml = $availableBalanceForm->sdmFormGetForm();


/* Calculations */

$availableBalance = $availableCash + $availableDebit + $availableCredit;
$availableAfterExpenses = $availableBalance - $totalExpenses;

/* Budget Title. */
$budgetTitle = '<h4 class="center">Budget on ' . date('F d, Y') . ' at ' . date('g:ia') . '</h4>';

/* Balance Overview Table */
$balanceOverviewTable .= '<table class="rounded">
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
$expensesTable .= '<table class="rounded">';
$expensesTable .= '<thead>Expenses:</thead>';
$color = true;
foreach ($expenses as $expense => $amount) {
    $bgColor = ($color === true ? '#000000' : '#777777');
    $expensesTable .= '<tr style="background: ' . $bgColor . '"><td>' . $expense . '</td><td>$' . $amount . '</td></tr>';
    $color = ($color === true ? false : true);
}
$expensesTable .= '</table>';

/* Categorized Expenses Table */
$categorizedExpensesTable .= '<table class="rounded">';
$categorizedExpensesTable .= '<thead>Categorized Expenses:</thead>';
$color = true;
foreach ($categorizedExpenses as $category => $categoryExpenses) {
    $categorizedExpensesTable .= '<tr><th style="text-align: left;">' . $category . '</th></tr>';
    foreach ($categoryExpenses as $expense => $amount) {
        $bgColor = ($color === true ? '#000000' : '#777777');
        $categorizedExpensesTable .= '<tr style="background: ' . $bgColor . ';"><td>' . $expense . '</td><td>$' . $amount . '</td></tr>';
        $color = ($color === true ? false : true);
    }
    $categoryExpenseTotal = array_sum($categoryExpenses);

    $categorizedExpensesTable .= '<tr class="' . ($categoryExpenseTotal <= 0 ? 'positive' : 'negative') . '"><td style="text-align: left;">' . 'Total Category Expense: ' . $categoryExpenseTotal . '</td></tr>';
}
$categorizedExpensesTable .= '</table>';


/* Incorporate output. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);