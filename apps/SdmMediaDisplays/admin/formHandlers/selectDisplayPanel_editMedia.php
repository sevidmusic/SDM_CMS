<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/13/16
 * Time: 7:35 PM
 */

if ($adminMode === 'saveMedia') {

    /* Added confirmation message to panel description. */
    $panelDescription .= '<p>Saved changes to media "' . $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaDisplayName') . '".</p>';

}