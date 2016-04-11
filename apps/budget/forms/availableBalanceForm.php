<?php
/* Available Balance Form */
$availableBalanceForm = new SdmForm();

/* Set $availableCash, $availableDebit, and $availableCredit vars */
switch(isset($savedBudget)) {
    case true:
        $availableCash = floatval($savedBudget->availableCash);
        $availableDebit = floatval($savedBudget->availableDebit);
        $availableCredit = floatval($savedBudget->availableCredit);
        break;
    default:
        $availableCash = floatval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableCash'));
        $availableDebit = floatval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableDebit'));
        $availableCredit = floatval($availableBalanceForm->sdmFormGetSubmittedFormValue('availableCredit'));
        break;
}
/* Form Object */
$availableBalanceForm->formHandler = 'budget';
$availableBalanceForm->formElements = array(
    array(
        'id' => 'availableCash',
        'type' => 'text',
        'element' => 'Available Cash <table class="budget-rounded"><tr class="' . ($availableCash > 0 ? 'budget-positive' : 'budget-negative') . '"><td>$' . strval($availableCash) . '</td></tr></table>',
        'value' => ($availableCash > 0 || $availableCash < 0 ? strval($availableCash) : '0.00'),
        'place' => '1',
    ),
    array(
        'id' => 'availableDebit',
        'type' => 'text',
        'element' => 'Available Debit <table class="budget-rounded"><tr class="' . ($availableDebit > 0 ? 'budget-positive' : 'budget-negative') . '"><td>$' . strval($availableDebit) . '</td></tr></table>',
        'value' => ($availableDebit > 0 || $availableDebit < 0 ? strval($availableDebit) : '0.00'),
        'place' => '2',
    ),
    array(
        'id' => 'availableCredit',
        'type' => 'text',
        'element' => 'Available Credit <table class="budget-rounded"><tr class="' . ($availableCredit > 0 ? 'budget-positive' : 'budget-negative') . '"><td>$' . strval($availableCredit) . '</td></tr></table>',
        'value' => ($availableCredit > 0 || $availableCredit < 0 ? strval($availableCredit) : '0.00'),
        'place' => '3',
    ),
    array(
        'id' => 'categorizedExpenses',
        'type' => 'hidden',
        'element' => 'Available Credit',
        'value' => json_encode($categorizedExpenses),
        'place' => '4',
    ),
);
$availableBalanceForm->method = 'post';
$availableBalanceForm->submitLabel = "Submit available balances";
$availableBalanceForm->sdmFormBuildForm($sdmassembler->sdmCoreGetRootDirectoryUrl());

/* Form HTML */
$availableBalanceFormHtml = $availableBalanceForm->sdmFormGetForm();