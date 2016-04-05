<?php

/**
 * @todo POST works great, however the SdmForm class has a lot of trouble with GET, this needs to be remedied.
 * @todo Make the following methods an option: POST, GET, SESSION. POST is the only one that works at the moment.
 * @todo Make the static methods in this class non-static so they can self reference SdmForm() objects
 * @todo Consider making this class a child of Sdm Core so it can directly utilize it's methods and properties.
 * @todo Test what happens when no $formHandler is specified. The default should be to use the current page since
 *       the form is most likely on the current page.
 * @todo Make sure post is used when method not specified.
 * @todo Make it possible to asign classes to the form and the form elements via the
 *       new $formClass and $formElementClasses properties respectively.
 * @todo Make any properties that should not be publically accsessible private or protected. If needed, create
 *       getter and setter methods for any properties that should be private or protected but still somewhat
 *       accsessible.
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

    /**
     *
     * The SdmForm() provides an object for creating and handling html forms in php.
     *
     * @param string $formHandler The name of the page or app that handles the form. (optional)
     * @param array $formElements Array of form elements. The following form elements are available:
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
     *                                'element' => 'Default Text Element',
     *                                'value' => '',
     *                                'place' => '0',
     *                              ),
     *                          );
     *
     * @param string $method The method the form should be submitted through. Either session, get, or post.
     *                          If not set the post method will be used.
     * @param string $submitLabel The label for the form's submit button. If not set the word "Submit" will be used.
     * @param string $formId The form's id. The id is generated internally by sdmFormGenerateFormId().
     * @param string $formClass The css class to use for the form. (optional)
     * @param array $formElementClasses Associative array of classes to use for form elements. The array uses keys
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
     * @param string $form The assembled form. Do not use this to get the form's html.
     *                     Instead, use the sdmFormGetForm() method to get the forms html.
     *                     The form html is generated on call to the sdmFormBuildForm()
     *                     method.
     */

    public function __construct()
    {
        /* If formId is set use it, otherwise call sdmFormGenerateFormId() to generate a new one. */
        $this->formId = (isset($this->formId) ? $this->formId : $this->sdmFormGenerateFormId());

        /* If formElements is set, use it, otherwise use default form elements. */
        $this->formElements = (isset($this->formElements) ? $this->formElements : array(
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
        ));

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
    public function sdmFormAlphaRand($numChars)
    {
        /* Alphabet to draw characters from. */
        $alphabet = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ';

        /* Initialize $string. This will variable will hold the random chars. */
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
     * @param string $key The key of the value we want to grab from the last submitted SdmForm(). All SdmForm() values are
     * stored in POST or GET under the 'SdmForm' array and indexed by $key. For example, to grab the value stored in
     * $_POST['SdmForm']['key'] you would call sdmFormGetSubmittedFormValue('key').
     *
     * Note: Only top level values can be retrieved from the 'SdmForm' array, so if you wish to grab $_POST['SdmForm']['key']['subKey']
     * you will have to call sdmFormGetSubmittedFormValue('key') and recurse through the sub array values yourself.
     *
     * @return mixed The submitted form value.
     */
    public static function sdmFormGetSubmittedFormValue($key)
    {
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
        return $data;
    }

    /**
     *
     * Decodes a value encoded with sdmFormEncode().
     *
     * @param string $value The value to decode.
     *
     * Note: It is safe to pass in values that were not encoded, sdmFormDecode()
     *       makes sure that values that cannot be decoded are returned unchanged.
     *
     * @return mixed <p>The decoded value</p>
     */
    public static function sdmFormDecode($value)
    {
        if (is_string($value) === true) { // we only want to attempt to decode strings, other types should be handled seperatly
            $sdmcore = new SdmCore();
            // if the value's string length is a multiple of 4 then it may be base64 encoded, if it is it will have to be decoded
            if (strlen($value) % 4 == 0) {
                // check if base64  encoded
                switch (base64_decode($value, true)) {
                    case false: // not base64
                        // check if serialized | we need to surpress any errors resulting from the check, this is ok because if any errors occure we do not proceed through this part of the statement
                        if (@unserialize($value) === false) { // if not serialized use as is
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($value);
                        } else { // if it is serialized unserialize it
                            $data = unserialize($value);
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($data);
                        }

                        break;
                    case true: // is base 64
                        // check if serialized | we need to surpress any errors resulting from the check, this is ok because if any errors occure we do not proceed through this part of the statement
                        if (@unserialize(base64_decode($value, true)) !== false) { // serialized, decode and unserialize
                            $data = unserialize(base64_decode($value, true));
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($data);
                        } else if (strlen(base64_decode($value, true)) >= strlen($value)) { // not serialized, but we should double check that this is for sure base64 encoded, we can do this by checking if the length of the decoded string is less then the length of the original data. If it is then we can assume the string is NOT base64 because if the decoded string has fewer chars then the original value most likely the string should not be decoded... @todo do some more testing by chcking a few encoded strings against their original values , do this in the hello world app
                            $data = base64_decode($value, true);
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($data);
                        } else { // not base64
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($value);
                        }
                        break;
                }
            } else { // string length is NOT a multiple of 4
                // check if serialized
                if (@unserialize($value) === false) { // if not serialized use as is | we need to surpress any errors resulting from the check, this is ok because if any errors occure we do not proceed through this part of the statement
                    $finaldata = $value;
                } else { // if it is serialized unserialize it
                    $data = unserialize($value);
                    $finaldata = SdmForm::sdmFormDecodeArrayValues($data);
                }
            }
        } else if (is_array($value) === true) { // if $value is an array we need to call SdmForm::sdmFormDecodeArrayValues() to recurse through the array makeing sure none of the values need to be decoded
            $finaldata = SdmForm::sdmFormDecodeArrayValues($value);
        } else { // if value is not a string or an array just return it | this will mostly apply to integers and objects
            $finaldata = $value;
        }
        return $finaldata;
    }

    /**
     * <p>Recursively decodes the values of a multi-dimensional array. If $data is not an array it is returned unchanged.</p>
     * <p>This method is used by SdmForm::sdmFormDecode() to insure that decoded
     * arrays also have their values decoded.</p>
     * @param type $data <p>The array to recurse through, usually an array decoded by SdmForm::sdmFormDecode()</p>
     * @param bool $devmode If set to true then this method will display dev information related to the different stages of decodeing on the page via SdmCore::sdmCoreSdmReadArray().
     * @return array <p>The array with all it's values decoded with SdmForm::sdmFormDecode()</p>
     */
    public static function sdmFormDecodeArrayValues($data)
    {
        // if $data is an array at this point we want to decode any array values that are encoded.
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$key] = SdmForm::sdmFormDecode($value);
            }
        }
        return $data;
    }

    /**
     * <p>Takes an associative array and prepends any values with 'default_'
     * so the SdmForm will know to treat these items as defaults for form elements
     * such as radio buttoms, or select lists.</p>
     * @param array $values <p>The array of values to check, any value that matches the
     *                         $testvalue will be prepended with the string 'default_'<br>
     * <i>Note: Type Enforced for this argument! must be an array.</i></p>
     * @param mixed $testvalue <p>The value to test the array's values against. Any $values
     *                           that match $testvalue will be prepended with the string 'default_'.
     *                           <br>Note: If $testvalue is an array then $values will be checked
     *                           against the values in $testvalue</p>
     * @return array <p>The $values array with all values that matched $testvalue prepened with the string
     *                  'default_'.</p>
     */
    public static function setDefaultValues(array $values, $testvalue)
    {
        switch (is_array($testvalue)) {
            case true:
                foreach ($values as $key => $value) {
                    unset($values[$key]);
                    // using == instead of === to allow for type juggling | === was causing problems with non sting types, specifically the boolean false was not being set to default when it should have been
                    $values[$key] = (in_array($value, $testvalue) == true ? 'default_' . $value : $value);
                }
                break;
            default:
                foreach ($values as $key => $value) {
                    unset($values[$key]);
                    // using == instead of === to allow for type juggling | === was causing problems with non sting types, specifically the boolean false was not being set to default when it should have been
                    $values[$key] = ($value == $testvalue ? 'default_' . $value : $value);
                }
                break;
        }
        return $values;
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
            $rootUrl = str_replace('/index.php', '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        }

        /* Create opening form html. This includes an html comment showing the formId, and the opeing form tags
           with the appropriate attributes defined. */
        $formHtml = '<!-- form "' . $this->sdmFormGetFormId() . '" --><form class="" method="' . $this->method . '" action="' . $rootUrl . '/index.php?page=' . $this->formHandler . '">';

        /* Sort elements based on element's "place" */
        $elementOrder = array(); // used to sort items
        foreach ($this->formElements as $key => $value) {
            $elementOrder[$key] = $value['place'];
        }
        array_multisort($elementOrder, SORT_ASC, $this->formElements);


        /* Build form elements. */
        foreach ($this->formElements as $key => $value) {
            switch ($value['type']) {
                case 'text':
                    $formHtml = $formHtml . '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><input name="SdmForm[' . $value['id'] . ']" type="text" ' . (isset($value['value']) ? 'value="' . $value['value'] . '"' : '') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    break;
                case 'password':
                    $formHtml = $formHtml . '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><input name="SdmForm[' . $value['id'] . ']" type="password" ' . (isset($value['value']) ? 'value="' . $value['value'] . '"' : '') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    break;
                case 'textarea':
                    $formHtml = $formHtml . '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><textarea name="SdmForm[' . $value['id'] . ']">' . (isset($value['value']) ? $value['value'] : '') . '</textarea><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    break;
                case 'select':
                    $formHtml = $formHtml . '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><select name="SdmForm[' . $value['id'] . ']">';
                    foreach ($value['value'] as $option => $optionValue) {
                        $formHtml = $formHtml . '<option value="' . (substr($optionValue, 0, 8) === 'default_' ? $this->sdmFormEncode(str_replace('default_', '', $optionValue)) . '" selected="selected"' : $this->sdmFormEncode($optionValue) . '"') . '>' . $option . '</option>';
                    }
                    $formHtml = $formHtml . '</select><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    break;
                case 'radio':
                    $formHtml = $formHtml . '<!-- form element "SdmForm[' . $value['id'] . ']" --><p id="label-for-SdmForm[' . $value['id'] . ']">' . $value['element'] . '</p>';
                    foreach ($value['value'] as $radio => $radioValue) {
                        $formHtml = $formHtml . '<label  for="SdmForm[' . $value['id'] . ']">' . $radio . '</label><input type="radio" name="SdmForm[' . $value['id'] . ']" value="' . (substr($radioValue, 0, 8) === 'default_' ? $this->sdmFormEncode(str_replace('default_', '', $radioValue)) . '" checked="checked"' : $this->sdmFormEncode($radioValue) . '"') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    }
                    break;
                case 'checkbox':
                    $formHtml = $formHtml . '<!-- form element "SdmForm[' . $value['id'] . ']" --><p id="label-for-SdmForm[' . $value['id'] . ']">' . $value['element'] . '</p>';
                    foreach ($value['value'] as $checkbox => $checkboxValue) {
                        $formHtml = $formHtml . '<label  for="SdmForm[' . $value['id'] . ']">' . $checkbox . '</label><input type="checkbox" name="SdmForm[' . $value['id'] . '][]" value="' . (substr($checkboxValue, 0, 8) === 'default_' ? $this->sdmFormEncode(str_replace('default_', '', $checkboxValue)) . '" checked="checked"' : $this->sdmFormEncode($checkboxValue) . '"') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    }
                    break;
                case 'hidden':
                    $formHtml = $formHtml . '<!-- form element "SdmForm[' . $value['id'] . ']" --><input name="SdmForm[' . $value['id'] . ']" type="hidden" value="' . $this->sdmFormEncode($value['value']) . '"><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    break;
                default:
                    break;
            }
        }

        /* Add hidden element to store form id */
        $formHtml .= '<!-- built-in form element "form_id" --><input type="hidden" name="SdmForm[form_id]" value="' . $this->sdmFormGetFormId() . '"><!-- close built-in form element "form_id" -->';

        /* Add submit button. */
        $this->form = $formHtml . '<input value="' . $this->submitLabel . '" type="submit"></form><!-- close form ' . $this->sdmFormGetFormId() . ' -->';

        /* Return the assembled html. */
        return $this->form;
    }

    /**
     * Retrieves the form's id.
     * @return string The formId property as a string or false on failure
     */
    public function sdmFormGetFormId()
    {
        /* If form id is set use it. */
        switch(isset($this->formId)) {
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

    /**
     * Utilizes serialize() and base64_encode() to encode a form value. This method
     * was created to fix bug#13.
     *
     * @see https://github.com/sevidmusic/SDM_CMS/issues/13 : See bug#13 on git for more information.
     *
     * Note: Value is only encoded if value is not of type boolean. Booleans
     * are not encoded because it was determined that encoding booleans, specifically
     * the boolean false, led to bugs in sdmFormGetSubmittedFormValue() because
     * it interfered with sdmFormGetSubmittedFormValue()'s ability to determine if
     * a value was serialized or base64 encoded which led to the value not being decoded at all
     * by sdmFormGetSubmittedFormValue().
     *
     * This resulted in sdmFormGetSubmittedFormValue() returning a boolean false as
     * the encoded string "YjowOw==" which of course would not equate to the boolean false which meant
     * the data was returned corrupted in both type and value.
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
     * Used to get the Form's HTML
     * @return type The assembled Form.
     */
    public function sdmFormGetForm()
    {
        return (isset($this->form) ? $this->form : 'Unable to load form!');
    }

}
