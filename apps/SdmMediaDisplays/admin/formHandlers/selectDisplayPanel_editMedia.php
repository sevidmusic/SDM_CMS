<?php
/**
 * Created by PhpStorm.
 * User: sevidmusic
 * Date: 5/13/16
 * Time: 7:35 PM
 */

if ($adminMode === 'saveMedia') {

    /* Get submitted form values */
    $submittedEditMediaFormValues = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('all');

    /* Initialize array to hold new media property values. */
    $newMediaPropertyValues = array();

    /* Add each submitted form value to the $newMediaPropertyValues array. It's ok if values unrelated to the SdmMedia object
       are included because they will simply be ignored on creation of new SdmMedia() object. */
    foreach ($submittedEditMediaFormValues as $submittedEditMediaFormKey => $submittedEditMediaFormValue) {
        $newMediaPropertyValues[$submittedEditMediaFormKey] = $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue($submittedEditMediaFormKey);
    }

    /* Create new SdmMedia() object instance. */
    $updateMediaObject = new SdmMedia();

    /* Create new media object from new media property values. */
    $newMediaObject = $updateMediaObject->sdmMediaCreateMediaObject($newMediaPropertyValues);

    /* Json encode new media object to prepare for storage. */
    $newMediaObjectJson = json_encode($newMediaObject);

    /* Save new media object  */
    var_dump($newMediaObject, $newMediaObjectJson);

    /* Added confirmation message to panel description. */
    $panelDescription .= '<p>Saved changes to media "' . $sdmMediaDisplaysAdminForm->sdmFormGetSubmittedFormValue('sdmMediaDisplayName') . '".</p>';

}