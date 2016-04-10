<?php

/**
 * Formats the he budget's json filename for use as a title.
 *
 * @param $savedBudgetFilename The saved budgets filename.
 *
 * @return string String formatted for use a a budget title.
 */
function formatBudgetTitle($savedBudgetFilename)
{
    /** @var  $stripExt string $savedBudgetFilename stripped of .json extension */
    $stripExt = str_replace('.json', '', $savedBudgetFilename);

    /**
     * @var  $pieces array Array with 2 strings, one for budget save date, and one for budget save time.
     * e.g. array('04202016', '132754') */
    $pieces = str_split($stripExt, 8);

    /**
     * @var  $timePieces array Multi-dimensional array to hold time pieces. The first sub array holds date pieces,
     * the second sub array holds time pieces. */
    $timePieces = array();

    /** Break $pieces into 4 char sets, e.g., break 04202016 into 0420 2016 and 132754 into 1327 54 */
    foreach ($pieces as $datetime) {
        array_push($timePieces, str_split($datetime, 4));
    }

    /**
     * @var  $datetimePieces array Multi-dimensional array tp hold final formatted time pieces. The first sub array
     * holds date pieces, the second sub array holds time pieces.  e.g. array(array('04', '20', '2016'), array('13', '27', '54')) */
    $datetimePieces = array();
    foreach ($timePieces as $timePieceArray) {
        $piece = str_split($timePieceArray[0], 2);
        array_push($datetimePieces, $piece);
    }
    /* Push year into $datetimePieces[0] */
    array_push($datetimePieces[0], $timePieces[0][1]);

    /* Push year into $datetimePieces[0] */
    array_push($datetimePieces[1], $timePieces[1][1]);

    /* Assemble date string */
    $date = implode('/', $datetimePieces[0]);

    /* Assemble time string */
    $time = implode(':', $datetimePieces[1]);

    /* Assemble budget title. */
    $budgetTitle = 'Budget ' . $date . ' ' . $time;

    return $budgetTitle;
}