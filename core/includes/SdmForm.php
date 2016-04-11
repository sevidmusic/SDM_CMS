<?php

/**
 * @todo POST works great, however the SdmForm class has a lot of trouble with GET, this needs to be remedied.
 * @todo Make the following methods an option if possible: POST, GET, SESSION. POST is the only one that works at the moment.
 * @todo Make the static methods in this class non-static so they can self reference SdmForm() objects
 * @todo Consider making this class a child of Sdm Core so it can directly utilize it's methods and properties.
 * @todo Test what happens when no $formHandler is specified. The default should be to use the current page since
 *       the form is most likely on the current page.
 * @todo Make sure post is used when method not specified.
 * @todo Make it possible to asign classes to the form and the form elements via the
 *       new $formClass and $formElementClasses properties respectively.
 * @todo Some values are not being encoded, fix this as this could lead to security issues.
 */

/**
 *
 * The SdmForm() provides an object for creating and handling html forms in php.
 *
 * @property string $formHandler The name of the page or app that handles the form. If not set the current
 *                            page will be assigned as the formHandler.
 * @property array $formElements Array of form elements. The following form elements are available:
 *                               - text
 *                               - textarea
 *                               - password
 *                               - select
 *                               - radio
 *                               - hidden
 *                               This property is optional. If not specified the default form elements
 *                               defined in the __constructor will be used.
 *                          Example of a form element definition:
 *                          $form->formElements = array(
 *                              array(
 *                                'id' => 'text_form_element',
 *                                'type' => 'text',
 *                                'element' => 'Text Element',
 *                                'value' => 'default value...',
 *                                'place' => '0',
 *                              ),
 *                          );
 *
 * @property string $method The method the form should be submitted through. Either (session), get, or post.
 *                          If not set the post method will be used. (session may not be possible, still in
 *                          development)
 * @property string $submitLabel The label for the form's submit button. If not set the word "Submit" will be used.
 * @property string $formId The form's id. The id is generated internally by sdmFormGenerateFormId().
 * @property string $formClass The css class to use for the form. (optional) (in dev)
 * @property array $formElementClasses Associative array of classes to use for form elements. The array uses keys
 *                                      to identify the form element type that should use the class, and the value
 *                                      should specify the name of the class to use.
 *                                      For example, the array should be structured as follows:
 *                                      array(
 *                                        'text' => 'classForTextElements',
 *                                        'textArea' => 'classForTextAreaElements',
 *                                        'password' => 'classForPasswordElements',
 *                                        'select' => 'classForSelectElements',
 *                                        'radio' => 'classForRadioElements',
 *                                        'hidden' => 'classForHiddenElements'
 *                                      );
 *
 * @property string $form The assembled form. Do not use this to get the form's html.
 *                     Instead, use the sdmFormGetForm() method to get the forms html.
 *                     The form html is generated on call to the sdmFormBuildForm()
 *                     method.
 */
class SdmForm
{
    public $formHandler;
    public $formElements;
    public $method;
    public $submitLabel;
    public $formClass;
    public $formElementClasses;
    private $formId;
    private $form;
    private $formElementHtml;

    /**
     * SdmForm constructor. Defines $property values, and assigns default property values
     * where necessary.
     */
    public function __construct()
    {
        /* If formId is set use it, otherwise call sdmFormGenerateFormId() to generate a new one. */
        $this->formId = (isset($this->formId) ? $this->formId : $this->sdmFormGenerateFormId());

        /* If formElements is set, use it, otherwise initialize an empty array. This array will be populated
           by either sdmFormCreateFormElement() or sdmFormUseDefaultFormElements() */
        $this->formElements = (isset($this->formElements) ? $this->formElements : array());

        /* If method is set use it, otherwise default to post. */
        $this->method = (isset($this->method) ? $this->method : 'post');

        /* If formHandler is set use it, otherwise default to current page. @todo determine current page by default */
        $this->formHandler = (isset($this->formHandler) ? $this->formHandler : '');

        /* If form is set use it, otherwise set an empty string as a placeholder. */
        $this->form = (isset($this->form) === true ? $this->form : '');

        /* If submitLabel set use it, otherwise default to the string 'Submit'. */
        $this->submitLabel = (isset($this->submitLabel) ? $this->submitLabel : 'Submit');
    }

    /**
     * Automatically assigns an id to an SdmForm object. If the object already has an id a new id will not be
     * assigned, and the original id will be kept intact.
     *
     * This method is used internally, and is not meant to be called from outside the class.
     *
     * @return string Unique id made up of random alphanumeric characters.
     */
    private function sdmFormGenerateFormId()
    {
        /* If formId is set, use it, otherwise generate a random alpha-numeric id for the sdmForm object. */
        $this->formId = (!isset($this->formId) ? rand(1000, 9999) . '-' . $this->sdmFormAlphaRand(8) : $this->formId);
        /* Return the formId. */
        return $this->formId;
    }

    /**
     * Random letter generator. Generate a sting of random letters $numChars in length.
     *
     * @param int $numChars Number of characters to generate.
     *
     * @return string Random string of random letters $numChars in length.
     *
     */
    private function sdmFormAlphaRand($numChars)
    {
        /* Alphabet to draw characters from. */
        $alphabet = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ';

        /* Initialize $string. This variable will hold the random chars. */
        $string = '';

        /* For as long as $inc is less then $numChars add a random letter to the $string. */
        for ($inc = 0; $inc <= $numChars; $inc++) {
            /* Pick a random index. */
            $index = rand(0, 51);

            /* Use the random $index to pick a random letter from the $alphabet. */
            $string = $string . $alphabet[$index];
        }

        /* Return the $string of random letters. */
        return $string;
    }

    /**
     * Gets a submitted form value.
     *
     * This method is static so it can be used without instantiating a new SdmForm() object. Keeping it static is
     * useful for instance if the form handler is defined in a different file then the form, in which case a new
     * SdmForm() object would have to be instantiated in order to call sdmFormGetSubmittedFormValue() if
     * sdmFormGetSubmittedFormValue() was not static.
     *
     * Basically, this is method is static for accessibility.
     *
     * @param $key string The submitted values key. This method can retrieve any submitted form value that still exists
     *                    in either post, get, or (session).
     *
     *
     * All SdmForm() values are stored in POST or GET under the 'SdmForm' array and indexed by $key. For example,
     * to grab the value stored in $_POST['SdmForm']['key'] call sdmFormGetSubmittedFormValue('key').
     *
     * Note: Only top level values can be retrieved from the 'SdmForm' array, it is not possible to grab $_POST['SdmForm']['key']['subKey']
     * you will have to call sdmFormGetSubmittedFormValue('key') and recurse through the sub array values yourself.
     *
     * @return mixed The submitted form value or null on failure.
     */
    public static function sdmFormGetSubmittedFormValue($key = 'all')
    {
        switch ($key === 'all') {
            case true:
                $data = $_POST['SdmForm'];
                break;
            default:
                /* If $key is not a string then just use the $key as the data. This allows arrays to be retrieved. */
                if (!is_string($key) === true) {
                    /* Use $key as data. */
                    $data = $key;
                } else {
                    if (isset($_POST['SdmForm'][$key]) === true) {
                        /* Get value from post. */
                        $value = $_POST['SdmForm'][$key];

                        /* Decode the value. */
                        $data = SdmForm::sdmFormDecode($value);
                    } else {
                        /* No value was found in the SdmForm array so set $data to null. */
                        $data = null;
                    }
                }
            /* Return the $data. */
        }
        return $data;
    }

    /**
     *
     * Decodes a value encoded with sdmFormEncode().
     *
     * This method is static because it is called by the static method sdmFormGetSubmittedFormValue().
     *
     * @param string $value The value to decode.
     *
     * Note: It is safe to pass in values that were not encoded by sdmFormEncode().
     *       sdmFormDecode() will return values that cannot be decoded unchanged.
     *
     * @return mixed The decoded value.
     */
    public static function sdmFormDecode($value)
    {
        /* Only attempt to decode strings, other types should be handled separately. */
        if (is_string($value) === true) {
            /* If the $value's string length is a multiple of 4 then it may be base64 encoded so attempt
               to decode it. */
            if (strlen($value) % 4 == 0) {
                /* Perform a final check to see if $value is really base64 encoded, if it is decode it. */
                switch (base64_decode($value, true)) {
                    /* If $value is not base64 encoded test if $value is serialized. */
                    case false:
                        /* Determine if serialized. If not serialized pass to sdmFormDecodeArrayValues() in case $value
                           is an array. If $value is not an array it will not be modified. */
                        if (@unserialize($value) === false) {
                            /* Pass $value to sdmFormDecodeArrayValues(), if it is not an array it will
                               not be modified. */
                            $finalData = SdmForm::sdmFormDecodeArrayValues($value);
                        } else {
                            /* Un-serialize $value. */
                            $data = unserialize($value);

                            /* Pass $value to sdmFormDecodeArrayValues(), if it is not an array it will
                               not be modified. */
                            $finalData = SdmForm::sdmFormDecodeArrayValues($data);
                        }
                        break;

                    /* If $value is base46 decode it. */
                    case true:
                        /* Check if $value is serialized. */
                        if (@unserialize(base64_decode($value, true)) !== false) {
                            /* Un-serialize and decode $value. */
                            $data = unserialize(base64_decode($value, true));
                            /* Pass $data to sdmFormDecodeArrayValues(), if it is not an array it will
                               not be modified. */
                            $finalData = SdmForm::sdmFormDecodeArrayValues($data);
                        } else if (strlen(base64_decode($value, true)) >= strlen($value)) {
                            /*
                             * $value is not serialized. Double check that the $value is definitely base64 encoded.
                             *
                             * This is accomplished by checking if the length of the decoded string is less then the
                             * length of the original $value.
                             *
                             * If it is then we can assume the string is not base64 because if the decoded string
                             * has fewer chars then the original $value. The $value should not be decoded.
                             */
                            $data = base64_decode($value, true);

                            /* Pass $data to sdmFormDecodeArrayValues(), if it is not an array it will
                               not be modified. */
                            $finalData = SdmForm::sdmFormDecodeArrayValues($data);
                        } else {
                            /* $value is not base64 encoded. Pass $data to sdmFormDecodeArrayValues(), if
                               it is not an array it will not be modified. */
                            $finalData = SdmForm::sdmFormDecodeArrayValues($value);
                        }
                        break;
                }
            } else {
                /* $value is not base64 encoded. Check if $value is serialized. */
                if (@unserialize($value) === false) {
                    /* $value is not serialized or base64 encoded, do not modify. */
                    $finalData = $value;
                } else {
                    /* $value is serialized. Un-serialize it. */
                    $data = unserialize($value);

                    /* Pass $data to sdmFormDecodeArrayValues(), if it is not an array it will
                       not be modified. */
                    $finalData = SdmForm::sdmFormDecodeArrayValues($data);
                }
            }
        } else if (is_array($value) === true) {
            /* If $value is an array call SdmForm::sdmFormDecodeArrayValues() in order to recursively
               decode any encode array values. */
            $finalData = SdmForm::sdmFormDecodeArrayValues($value);
        } else {
            /* If value is not a string or an array just return without modification. */
            $finalData = $value;
        }
        return $finalData;
    }

    /**
     * Recursively decodes the values of a multi-dimensional array. If $data is not an array it is returned
     * without modification.
     *
     * This method is used by SdmForm::sdmFormDecode() to insure that decoded arrays also have their values
     * decoded.
     *
     * This method is static because it is used by SdmForm::sdmFormDecode() which is called by the static
     * function SdmForm::sdmFormGetSubmittedFormValue().
     *
     * @param array $data The array to recurse through.
     *
     * @return array The array with all it's values decoded by SdmForm::sdmFormDecode().
     */
    public static function sdmFormDecodeArrayValues($data)
    {
        /* If $data is an array decode it's values. */
        if (is_array($data)) {
            /* Pass each array value to sdmFormDecodeArrayValues(). */
            foreach ($data as $key => $value) {
                /* Unset the encoded $value. */
                unset($data[$key]);

                /* Re-assign $value under original $key after passing it to SdmForm::sdmFormDecode(). */
                $data[$key] = SdmForm::sdmFormDecode($value);
            }
        }

        /* Return the decoded $data. */
        return $data;
    }

    /**
     * Takes an associative array and prepends any values with the string 'default_'.
     *
     * This method was crated so complex form elements like the radio and select types
     * could define a default value.
     *
     * This method is static due to historical reasons. This may change in the future.
     *
     * @param array $values The array of values to check, any value that matches the
     *                      $testValue will be prepended with the string 'default_'
     *
     * Note: Type Enforced for this argument! must be an array.
     *
     * @param mixed $testValue The value to test the array's values against. Any $values
     *                         that match $testValue will be prepended with the string 'default_'.
     *                         Note: If $testValue is an array then $values will be checked
     *                         against the values in $testValue.
     *
     * @return array The $values array with all values that matched $testValue prepended with the string
     *               'default_'.
     *
     */
    public static function setDefaultValues(array $values, $testValue)
    {
        /* Determine if $testValue is an array. */
        switch (is_array($testValue)) {
            case true:
                /* If it is an array loop through the $values in the $values array checking
                   each $value against the values in the $testValue array. */
                foreach ($values as $key => $value) {
                    /* Unset the original $value in the $values array */
                    unset($values[$key]);

                    /* Re-assign $value to $values array pre-pending the string 'default_' to any $value
                       that matches a value in the $testValue array. */
                    $values[$key] = (in_array($value, $testValue) == true ? 'default_' . $value : $value);
                    /*
                     * Dev Note: use == instead of === to allow for type juggling. === was causing problems
                     * with non string types, specifically the boolean false was not being set to default
                     * when it should have been
                     */
                }
                break;
            default:
                /* Loop through the $values in the $values array checking each $value against the $testValue. */
                foreach ($values as $key => $value) {
                    /* Unset the original $value in the $values array */
                    unset($values[$key]);

                    /* Re-assign $value to $values array pre-pending the string 'default_' to any $value
                       that matches the $testValue. */
                    $values[$key] = ($value == $testValue ? 'default_' . $value : $value);
                    /*
                     * Dev Note: use == instead of === to allow for type juggling. === was causing problems
                     * with non string types, specifically the boolean false was not being set to default
                     * when it should have been
                     */
                }
                break;
        }

        /* Return the $values array with the string 'default_' pre-pended to any values that matched the $testValue */
        return $values;
    }

    public function sdmFormUseDefaultFormElements()
    {
        $this->formElements = array(
            /* default text form element */
            array(
                'id' => 'text_form_element',
                'type' => 'text',
                'element' => 'Default Text Element',
                'value' => '',
                'place' => '0',
            ),
            /* default textarea form element */
            array(
                'id' => 'textarea_form_element',
                'type' => 'textarea',
                'element' => 'Default Text Area Element',
                'value' => '',
                'place' => '3',
            ),
            /* default password form element */
            array(
                'id' => 'password_form_element',
                'type' => 'password',
                'element' => 'Default Password Element',
                'value' => 'password',
                'place' => '3',
            ),
            /* default select form element */
            array(
                'id' => 'select_form_element',
                'type' => 'select',
                'element' => 'Default Select Form Element',
                'value' => array('Default Select Value 1' => 'default_select_value_1', 'Default Select Value 2' => 'select_value_2', 'Default Select Value 3' => 'select_value_3'),
                'place' => '1',
            ),
            /* default radio form element */
            array(
                'id' => 'radio_form_element',
                'type' => 'radio',
                'element' => 'Default Radio From Element',
                'value' => array('Default Radio Value 1' => 'default_radio_value_1', 'Default Radio Value 2' => 'radio_value_2', 'Default Radio Value 3' => 'radio_value_3'),
                'place' => '2',
            ),
            /* default hidden form element */
            array(
                'id' => 'hidden_form_element',
                'type' => 'hidden',
                'element' => 'Default Hidden From Element',
                'value' => 'Default Hidden From Element',
                'place' => '4',
            ),
        );
        return (empty($this->formElements) === true ? false : true);
    }

    /**
     * Builds the form's html.
     *
     * @param string $rootUrl The sites root url. Insures requests are made from site of origin.
     *
     * @return string The form's html.
     */
    public function sdmFormBuildForm($rootUrl = null)
    {
        /* If the $rootUrl was not specified attempt to set it to the site's root url for security.
           It is best to specify the $rootUrl. */
        if (!isset($rootUrl)) {
            /* Try to determine root url using $_SERVER variables. */
            $rootUrl = $this->sdmFormDetermineRootUrl();
        }

        /* Create opening form html. This includes an html comment showing the formId, and the opening form tags
           with the appropriate attributes defined. */
        $formHtml = $this->sdmFormOpenForm($rootUrl);

        /* Build html for form elements. */
        $formHtml .= $this->sdmFormBuildFormElements();

        /* Create closing form html. */
        $formHtml .= $this->sdmFormCloseForm();

        /* Assign the constructed form html to the 'form' property. */
        $this->form = $formHtml;

        /* Return the assembled html. */
        return $this->form;
    }

    /**
     * Attempts to determine the site's root url.
     *
     * @return string The sites root url.
     */
    public function sdmFormDetermineRootUrl()
    {
        /* Try to determine root url using $_SERVER variables. */
        return str_replace('/index.php', '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
    }

    /**
     * Constructs the opening html for the form.
     *
     * @param string $rootUrl The sites root url. Insures requests are made from site of origin.
     *
     * @return string The forms opening html.
     */
    public function sdmFormOpenForm($rootUrl = null)
    {
        /* If the $rootUrl was not specified attempt to set it to the site's root url for security.
           It is best to specify the $rootUrl. */
        if (!isset($rootUrl)) {
            /* Try to determine root url using $_SERVER variables. */
            $rootUrl = $this->sdmFormDetermineRootUrl();
        }

        return '<!-- form "' . $this->sdmFormGetFormId() . '" --><form class="" method="' . $this->method . '" action="' . $rootUrl . '/index.php?page=' . $this->formHandler . '">';
    }

    /**
     * Retrieves the form's id.
     * @return string The formId property as a string or false on failure
     */
    public function sdmFormGetFormId()
    {
        /* If form id is set use it. */
        switch (isset($this->formId)) {
            case true:
                $formId = $this->formId;
                break;
            default:
                /* Otherwise log an error. */
                error_log('Form missing id. Which form cannot be determined since id does not exist.');

                /* Assign false to $formId since $this->formId was not set. */
                $formId = false;
                break;
        }
        return $formId;
    }

    public function sdmFormBuildFormElements()
    {
        /* Sort elements based on element's "place". */
        $elementOrder = array(); // used to sort items
        foreach ($this->formElements as $key => $value) {
            $elementOrder[$key] = $value['place'];
        }
        array_multisort($elementOrder, SORT_ASC, $this->formElements);

        /* Initialize the string that will hold form element's html. */
        $formElementsHtml = '';

        /* Build formElement property. This property stores form element
           html as items in an array. */
        $this->formElementHtml = array();

        /* Build form elements. */ // @todo : appending to $formElementsHtml causes element html to get appened to other element's html in te formElementHtml() property. To solve this create a new var $elementsHtml which will replace $formElementsHtml. $formElementsHtml will remain but will now be used to add to the $formElementsHtml
        foreach ($this->formElements as $key => $value) {
            switch ($value['type']) {
                case 'text':
                    /* Build element html and add $formElementsHtml to $this->formElementHtml array. */
                    $this->formElementHtml[$value['id']] = '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><input name="SdmForm[' . $value['id'] . ']" type="text" ' . (isset($value['value']) ? 'value="' . $value['value'] . '"' : '') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';

                    /*  Add form element html to $this->form  */
                    $formElementsHtml .= $this->formElementHtml[$value['id']];
                    break;
                case 'password':
                    /* Build element html and add $formElementsHtml to $this->formElementHtml array. */
                    $this->formElementHtml[$value['id']] = '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><input name="SdmForm[' . $value['id'] . ']" type="password" ' . (isset($value['value']) ? 'value="' . $value['value'] . '"' : '') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';

                    /*  Add form element html to $this->form  */
                    $formElementsHtml .= $this->formElementHtml[$value['id']];
                    break;
                case 'textarea':
                    /* Build element html and add $formElementsHtml to $this->formElementHtml array. */
                    $this->formElementHtml[$value['id']] = '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><textarea name="SdmForm[' . $value['id'] . ']">' . (isset($value['value']) ? $value['value'] : '') . '</textarea><!-- close form element "SdmForm[' . $value['id'] . ']" -->';

                    /* Add $formElementsHtml to $this->formElementHtml array. */
                    $formElementsHtml .= $this->formElementHtml[$value['id']];

                    break;
                case 'select':
                    /* Build element html and add $formElementsHtml to $this->formElementHtml array. */
                    $this->formElementHtml[$value['id']] = '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><select name="SdmForm[' . $value['id'] . ']">';
                    foreach ($value['value'] as $option => $optionValue) {
                        $this->formElementHtml[$value['id']] .= '<option value="' . (substr($optionValue, 0, 8) === 'default_' ? $this->sdmFormEncode(str_replace('default_', '', $optionValue)) . '" selected="selected"' : $this->sdmFormEncode($optionValue) . '"') . '>' . $option . '</option>';
                    }
                    $this->formElementHtml[$value['id']] .= '</select><!-- close form element "SdmForm[' . $value['id'] . ']" -->';

                    /* Add $formElementsHtml to $this->formElementHtml array. */
                    $formElementsHtml .= $this->formElementHtml[$value['id']];
                    break;
                case 'radio':
                    /* Build element html and add $formElementsHtml to $this->formElementHtml array. */
                    $this->formElementHtml[$value['id']] = '<!-- form element "SdmForm[' . $value['id'] . ']" --><p id="label-for-SdmForm[' . $value['id'] . ']">' . $value['element'] . '</p>';
                    foreach ($value['value'] as $radio => $radioValue) {
                        $this->formElementHtml[$value['id']] .= '<label  for="SdmForm[' . $value['id'] . ']">' . $radio . '</label><input type="radio" name="SdmForm[' . $value['id'] . ']" value="' . (substr($radioValue, 0, 8) === 'default_' ? $this->sdmFormEncode(str_replace('default_', '', $radioValue)) . '" checked="checked"' : $this->sdmFormEncode($radioValue) . '"') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    }
                    $this->formElementHtml[$value['id']] .= '<br>';

                    /* Add $formElementsHtml to $this->formElementHtml array. */
                    $formElementsHtml .= $this->formElementHtml[$value['id']];
                    break;
                case 'checkbox':
                    /* Build element html and add $formElementsHtml to $this->formElementHtml array. */
                    $this->formElementHtml[$value['id']] = '<!-- form element "SdmForm[' . $value['id'] . ']" --><p id="label-for-SdmForm[' . $value['id'] . ']">' . $value['element'] . '</p>';
                    foreach ($value['value'] as $checkbox => $checkboxValue) {
                        $this->formElementHtml[$value['id']] .= '<label  for="SdmForm[' . $value['id'] . ']">' . $checkbox . '</label><input type="checkbox" name="SdmForm[' . $value['id'] . '][]" value="' . (substr($checkboxValue, 0, 8) === 'default_' ? $this->sdmFormEncode(str_replace('default_', '', $checkboxValue)) . '" checked="checked"' : $this->sdmFormEncode($checkboxValue) . '"') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    }

                    $formElementsHtml .= $this->formElementHtml[$value['id']];
                    break;
                case 'hidden':
                    /* Build element html and add $formElementsHtml to $this->formElementHtml array. */
                    $this->formElementHtml[$value['id']] = '<!-- form element "SdmForm[' . $value['id'] . ']" --><input name="SdmForm[' . $value['id'] . ']" type="hidden" value="' . $this->sdmFormEncode($value['value']) . '"><!-- close form element "SdmForm[' . $value['id'] . ']" -->';

                    /* Add $formElementsHtml to $this->formElementHtml array. */
                    $formElementsHtml .= $this->formElementHtml[$value['id']];
                    break;
                default:
                    /* Do nothing. */
                    break;
            }
        }
        /* Return a string of form element html. */
        return $formElementsHtml;
    }

    /**
     * Utilizes serialize() and base64_encode() to encode a form value. This method
     * was created to fix bug#13.
     *
     * @see https://github.com/sevidmusic/SDM_CMS/issues/13 : See bug#13 on git for more information.
     *
     * Note: Value is only encoded if value is not of type boolean. Booleans are not encoded because
     * it was determined that encoding booleans, specifically the boolean false, led to bugs in
     * sdmFormGetSubmittedFormValue() because it interfered with sdmFormGetSubmittedFormValue()'s
     * ability to determine if a value was serialized or base64 encoded which led to the value not
     * being decoded at all by sdmFormGetSubmittedFormValue().
     *
     * This resulted in sdmFormGetSubmittedFormValue() returning a boolean false as the encoded string
     * "YjowOw==" which of course would not equate to the boolean false which meant the data was
     * returned corrupted in both type and value.
     *
     * @see https://github.com/sevidmusic/SDM_CMS/issues/13 : See bug#13 on git for more information.
     *
     * @param mixed $value The value to encode.
     *
     * @return mixed The encoded value unless value was of type boolean, in which case the original
     *               boolean value is returned.
     */
    final public function sdmFormEncode($value)
    {
        /* As long as $value is not a boolean, encode the $value. */
        if (is_bool($value) === false) {
            /* First serialize, and then base64encode the $value. */
            $encodedValue = base64_encode(serialize($value));
        } else {
            /* $value was a boolean, do not modify. */
            $encodedValue = $value;
        }
        /* Return the encoded value. If original value was a boolean it will be returned unmodified. */
        return $encodedValue;
    }

    /**
     * Constructs the opening html for the form.
     *
     * @return string The forms opening html.
     */
    public function sdmFormCloseForm()
    {
        /* Add hidden element to store form id */
        $closingFormHtml = '<!-- built-in form element "form_id" --><input type="hidden" name="SdmForm[form_id]" value="' . $this->sdmFormGetFormId() . '"><!-- close built-in form element "form_id" -->';

        /* Add submit button. */
        $closingFormHtml .= '<input value="' . $this->submitLabel . '" type="submit"></form><!-- close form ' . $this->sdmFormGetFormId() . ' -->';

        return $closingFormHtml;
    }

    /**
     *
     * Get's the html for a specified element from the $formElementHtml property.
     *
     * @param string $elementName The name of the form element to get html for.
     *
     * @return string The specified form element's html.
     */
    public function sdmFormGetFormElementHtml($elementName)
    {
        return $this->formElementHtml[$elementName];
    }

    /**
     * Used to get the Form's HTML
     * @return string The form's html.
     */
    public function sdmFormGetForm()
    {
        /* make sure the form's html has been built. */
        switch (isset($this->form)) {
            case true:
                /* Get form's html. */
                $formHtml = $this->form;
                break;
            default:
                /* Indicate that an error occured while trying to load the form. */
                $formHtml = '<p>Could not load form.</p>';

                /* Issue an error to the error log. */
                error_log('Missing form id in call to method sdmFormGetForm() in file ' . __FILE__ . ' near line' . __LINE__);
                break;
        }

        /* Return form's html. */
        return $formHtml;
    }

    /**
     * Creates an array that defines a form element. This method must be called
     * before sdmFormBuildForm() for it to work properly.
     *
     * @param $id string The id to be used to index the submitted value for this element.
     *                   For instance, if the form's method is post, this id would identify
     *                   the post value at $_POST['SdmForm'][$id].
     *
     * @param $type string The type of form element. The following types are supported:
     *                     - 'text' : A text form element. i.e., <input type="text">
     *                     - 'textArea' : A text area form element. i.e., <textarea>
     *                     - 'password' : A password form element. i.e., <input type="password">
     *                     - 'select' : A select form element. i.e., <select>
     *                     - 'radio' : A radio form element. i.e., <input type="radio">
     *                     - 'checkbox' : A checkbox form element. i.e., <input type="checkbox">
     *                     - 'hidden' : A hidden form element. i.e., <input type="hidden">
     * @param $element string A human readable name for the element to be used as a label for the element.
     *
     * @param $value mixed The elements default value. For select, radio, and checkbox types this will be an array.
     *
     * @param $place int An integer to represent the place of the form element relative to other elements.
     *                   This value is used by sdmFormBuildForm() to determine what order form elements
     *                   should be constructed in. Lower values will be added to the form first,
     *                   higher values last.
     *
     *                   Note: $place only applies when using sdmFormGetForm() to get the form's html.
     *                         When getting form element's html individually with sdmFormGetFormElementHtml()
     *                         $place will not have any effect.
     *
     * @return array An array defining a single form element.
     */
    public function sdmFormCreateFormElement($id, $type, $element, $value, $place)
    {
        /* Create form element. */
        $formElement = array(
            'id' => $id,
            'type' => $type,
            'element' => $element,
            'value' => $value,
            'place' => $place,
        );

        /* Push new element into $formElements propert. */
        array_push($this->formElements, $formElement);
        return ($this->sdmFormRecursiveInArray($id, $this->formElements) === true ? true : false);
    }

    /**
     * Recursively searches for a value in an array.
     *
     * @param mixed $needle The value to search for.
     * @param $haystack array The array to search in. Can be multi-dimensional.
     * @param bool $strict If set to true then the sdmFormRecursiveInArray() will
     *                     also check the types of the needle in the haystack.
     * @return bool True if $needle was found, false otherwise.
     */
    private function sdmFormRecursiveInArray($needle, $haystack, $strict = true)
    {
        /* Make sure the $haystack is an array. */
        switch (is_array($haystack)) {
            case true:
                /* Fist check if needle is in $haystack. */
                if (in_array($needle, $haystack, $strict) === true) {
                    /* Finding one occurrence is enough, return true. */
                    return true;
                }

                /* Look in sub arrays */
                $inSubArray = array();
                foreach ($haystack as $item) {
                    /* Only handle arrays */
                    if (is_array($item) === true) {
                        /* Recursively check sub array for $needle. */
                        $inSubArray[] = $this->sdmFormRecursiveInArray($needle, $item, $strict);
                    }
                }

                /* If $needle was found in any sub array return true, if not return false. */
                return (in_array(true, $inSubArray, true) === true ? true : false);
            /* Haystack was not an array. */
            default:
                /* Issue an error if $haystack was not an array. */
                error_log('Invalid type passed to sdmFormRecursiveInArray(). $array must be of type array.');

                /* Return false since $needle could not be searched for because $haystack was not an array. */
                return false;
        }
    }
}
