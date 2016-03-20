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

/* Hardcoded values */

/* Expenses */
$numExp = 12;
$numExpForm = new SdmForm();
$numExpForm->formHandler = 'budget';
$numExpForm->formElements = array();
$numExpForm->method = 'post';
$numExpForm->submitLabel = 'Add another expense';
$numExpForm->sdmFormBuildForm();

$expenses = array();

$totalExpenses = array_sum($expenses);

/* Form */
$form = new SdmForm();

/* Submitted values */
$availableCash = floatval($form->sdmFormGetSubmittedFormValue('availableCash'));
$availableDebit = floatval($form->sdmFormGetSubmittedFormValue('availableDebit'));
$availableCredit = floatval($form->sdmFormGetSubmittedFormValue('availableCredit'));

/* Form elements */
$form->formHandler = 'budget';
$form->formElements = array(
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
$form->method = 'post';
$form->submitLabel;
$form->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());
$formHtml = $form->sdmFormGetForm();


/* Calculations */

$availableBalance = $availableCash + $availableDebit + $availableCredit;
$availableAfterExpenses = $availableBalance - $totalExpenses;

/* Create some output. */
$output = '<div id="helloWorld"><h4 class="center">Budget on ' . date('F d, Y') . ' at ' . date('g:ia') . '</h4>';

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

/* Budget Form */
$output .= $formHtml;

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
$output .= $numExpForm->sdmFormGetForm();

/* Incorporate output. */
$sdmassembler->sdmAssemblerIncorporateAppOutput($output, $options);