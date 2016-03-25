<?php
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