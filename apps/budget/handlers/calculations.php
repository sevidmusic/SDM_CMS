<?php

/**
 * Calculations.
 * Note: Calculations must be performed before constructing Balance Overview Table and after all other components
 * have been constructed.
 */
$availableBalance = $availableCash + $availableDebit + $availableCredit;
$availableAfterExpenses = $availableBalance - $totalExpenses;