<?php

/**
 * @todo POST works great, however the SdmForm class has a lot of trouble with GET, this needs to be remedied.
 * @todo Make the static methods in this class non-static so they can self reference SdmForm() objects
 * @todo Consider making this class a child of Sdm Core so it can directly utilize it's methods and properties.
 */
class SdmForm {

    // properties //
    private $form_id;
    private $form;
    public $form_handler;
    public $form_elements;
    public $method;
    public $submitLabel;

    /**
     * Creates an HTML form based on the defined property values.
     *
     * PROPERTIES:
     *
     * $form_id : defined internally, upon the creation of an SdmForm instance. A unique ID is assigned to this property via the internal sdmFormGenerateFormId() function
     *
     * $form : The assembled form.
     *
     * $form_handler : The form handler, either a path to a file that handles the form, or a the name of a function that handles the form
     *
     * $form_elements : (array) The elements that make up the form. i.e., <select>, <input>, <textarea>, etc...
     *
     * $method : The type of request, either 'get' or 'post', to issue.
     *
     */
    public function __construct() {
        $this->form_id = (isset($this->form_id) ? $this->form_id : $this->sdmFormGenerateFormId());
        $this->form_elements = (isset($this->form_elements) ? $this->form_elements : array(
                    // default form element example
                    array(
                        'id' => 'text_form_element',
                        'type' => 'text',
                        'element' => 'Text',
                        'value' => 'defualt value',
                        'place' => '0',
                    ),
                    // default form element example 2
                    array(
                        'id' => 'textarea_form_element',
                        'type' => 'textarea',
                        'element' => 'Textarea',
                        'value' => 'defualt value2',
                        'place' => '3',
                    ),
                    array(
                        'id' => 'select_form_element',
                        'type' => 'select',
                        'element' => 'Select',
                        'value' => array('yes' => 'yes', 'no' => 'no', 'maybe' => 'maybe'),
                        'place' => '1',
                    ),
                    array(
                        'id' => 'radio_form_element',
                        'type' => 'radio',
                        'element' => 'Radio',
                        'value' => array('yes' => 'yes', 'no' => 'no', 'maybe' => 'maybe'),
                        'place' => '2',
                    ),
                    array(
                        'id' => 'hidden_form_element',
                        'type' => 'hidden',
                        'element' => 'Hidden',
                        'value' => 'defualt hidden value',
                        'place' => '4',
                    ),
        ));
        $this->method = (isset($this->method) ? $this->method : 'post');
        $this->form_handler = (isset($this->form_handler) ? $this->form_handler : '');
        $this->form = (isset($this->form) ? $this->form : $this->sdmFormBuildForm(str_replace('/index.php', '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'])));
        $this->submitLabel = (isset($this->submitLabel) ? $this->submitLabel : 'Submit');
    }

    /**
     * Builds the form.
     * @var string $rootUrl the sites root url. Insures requests are made from site of origin.
     * @return The Form html
     */
    public function sdmFormBuildForm($rootUrl) {
        // intial form html
        $form_html = '<!-- form "' . $this->sdmFormGetFormId() . '" --><form method="' . $this->method . '" action="' . $rootUrl . '/index.php?page=' . $this->form_handler . '">';

        // first sort elements based on element's "place"
        $element_order = array(); // used to sort items
        foreach ($this->form_elements as $key => $value) {
            $element_order[$key] = $value['place'];
        }
        array_multisort($element_order, SORT_ASC, $this->form_elements);

        // build form
        foreach ($this->form_elements as $key => $value) {
            switch ($value['type']) {
                case 'text':
                    $form_html = $form_html . '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><input name="SdmForm[' . $value['id'] . ']" type="text"><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    break;
                case 'textarea':
                    $form_html = $form_html . '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><textarea name="SdmForm[' . $value['id'] . ']">' . (isset($value['value']) ? $value['value'] : '') . '</textarea><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    break;
                case 'select':
                    $form_html = $form_html . '<!-- form element "SdmForm[' . $value['id'] . ']" --><label for="SdmForm[' . $value['id'] . ']">' . $value['element'] . '</label><select name="SdmForm[' . $value['id'] . ']">';
                    foreach ($value['value'] as $option => $option_value) {
                        $form_html = $form_html . '<option value="' . (substr($option_value, 0, 8) === 'default_' ? $this->sdmFormEncode(str_replace('default_', '', $option_value)) . '" selected="selected"' : $this->sdmFormEncode($option_value) . '"') . '>' . $option . '</option>';
                    }
                    $form_html = $form_html . '</select><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    break;
                case 'radio':
                    $form_html = $form_html . '<!-- form element "SdmForm[' . $value['id'] . ']" --><p id="label-for-SdmForm[' . $value['id'] . ']">' . $value['element'] . '</p>';
                    foreach ($value['value'] as $radio => $radio_value) {
                        $form_html = $form_html . '<label  for="SdmForm[' . $value['id'] . ']">' . $radio . '</label><input type="radio" name="SdmForm[' . $value['id'] . ']" value="' . (substr($radio_value, 0, 8) === 'default_' ? $this->sdmFormEncode(str_replace('default_', '', $radio_value)) . '" checked="checked"' : $this->sdmFormEncode($radio_value) . '"') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    }
                    break;
                case 'checkbox':
                    $form_html = $form_html . '<!-- form element "SdmForm[' . $value['id'] . ']" --><p id="label-for-SdmForm[' . $value['id'] . ']">' . $value['element'] . '</p>';
                    foreach ($value['value'] as $checkbox => $checkbox_value) {
                        $form_html = $form_html . '<label  for="SdmForm[' . $value['id'] . ']">' . $checkbox . '</label><input type="checkbox" name="SdmForm[' . $value['id'] . '][]" value="' . (substr($checkbox_value, 0, 8) === 'default_' ? $this->sdmFormEncode(str_replace('default_', '', $checkbox_value)) . '" checked="checked"' : $this->sdmFormEncode($checkbox_value) . '"') . '><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    }
                    break;
                case 'hidden':
                    $form_html = $form_html . '<!-- form element "SdmForm[' . $value['id'] . ']" --><input name="SdmForm[' . $value['id'] . ']" type="hidden" value="' . $this->sdmFormEncode($value['value']) . '"><!-- close form element "SdmForm[' . $value['id'] . ']" -->';
                    break;
                default:
                    break;
            }
        }
        // add hidden element to store form id
        $form_html .= '<!-- built-in form element "form_id" --><input type="hidden" name="SdmForm[form_id]" value="' . $this->sdmFormGetFormId() . '"><!-- close built-in form element "form_id" -->';
        $this->form = $form_html . '<input value="' . $this->submitLabel . '" type="submit"></form><!-- close form ' . $this->sdmFormGetFormId() . ' -->';
        return $this->form;
    }

    /**
     * <p>Utilizes serialize() and base64_encode() to encode a form value. This method
     * was created to fix bug#13</p>
     * <p><i>Note: Value is only encoded if value is not of type boolean. Booleans
     * are not encoded because it was determined that encoding booleans, specifically
     * the boolean false, led to bugs in SdmForm::sdmFormGetSubmittedFormValue() because
     * it interfered with SdmForm::sdmFormGetSubmittedFormValue()'s ability to determine if
     * a value was serilaized or base64 encoded which led to the value not being decoded at all
     * by SdmForm::sdmFormGetSubmittedFormValue().</i></p>
     * <p><i>This resulted in SdmForm::sdmFormGetSubmittedFormValue() returning a boolean FALSE as
     * the encoded string "YjowOw==" which of course would not equate to the boolean FALSE which meant
     * the data was returned corrupted in both type and value.</i></p>
     * @see https://github.com/sevidmusic/SDM_CMS/issues/13 : See bug#13 on git for more information</p>.
     * @param mixed $value
     * @return mixed <p>The encoded value unless value was of type boolean, in whcih case the original
     * value is returned</p>
     */
    final public function sdmFormEncode($value) {
        if (is_bool($value) === FALSE) {
            $encodedValue = base64_encode(serialize($value));
        } else {
            $encodedValue = $value;
        }
        return $encodedValue;
    }

    /**
     * Used to get the Form's HTML
     * @return type The assembled Form.
     */
    public function sdmFormGetForm() {
        return (isset($this->form) ? $this->form : 'Unable to load form!');
    }

    /**
     * Automatically assigns an id to a SdmForm instance. If the Form already has an id a new id is NOT created.
     * There is NO need to call this function as it is run whenever an instance of this class is created.
     * There is also no harm in calling it because this function CANNOT overwrite an ID that is already set.
     * @return string Unique id made up of random alphanumeric characters.
     */
    public function sdmFormGenerateFormId() {
        // we only set id if it is NOT already set | checked via terenary operator (condition ? true : false)
        $this->form_id = (!isset($this->form_id) ? rand(1000, 9999) . '-' . $this->sdmFormAlphaRand(8) : $this->form_id);
        return $this->form_id;
    }

    /**
     * Used to refernce the forms internal ID.
     * @return string Form ID as a string.
     */
    public function sdmFormGetFormId() {
        return (isset($this->form_id) ? $this->form_id : 'FORM ID NOT SET!');
    }

    /** sdm_alpha_rand($num_chars)
     * Random letter generator. Used in Player and Package ID generation.
     * @param int $num_chars Number of letters to generate.
     * There are spaces and indents are NOT generated, so letters are clumped up into one long string of characters.
     * i.e., sdm_alpha_rand(4) may generate the string 'sonv', or 'bUsw', etc...
     * @return string Random String $num_chars in length.
     * i.e., sdmFormAlphaRand(3) would produce a string of random alphanumeric characters 3 characters in length
     */
    public function sdmFormAlphaRand($num_chars) {
        $alphabet = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ';
        $string = '';
        for ($inc = 0; $inc <= $num_chars; $inc++) {
            $i = rand(0, 51);
            $string = $string . $alphabet[$i];
        }
        return $string;
    }

    /**
     * @param type $key <p>The key of the value we want to grab from the last submitted SdmForm(). All SdmForm() values are
     * stored in POST* or GET under the 'SdmForm' array and indexed by $key. For example, to grab the value stored in
     * $_POST['SdmForm']['key'] you would call sdmFormGetSubmittedFormValue('key').</p>
     * <p>NOTE: Only top level values can be retrieved from the 'SdmForm' array, so if you wish to grab $_POST['SdmForm']['key']['subKey']
     * you will have to call sdmFormGetSubmittedFormValue('key') and recurse through the sub array values yourself.</p>
     * <p>*NOTE: At the moment only POST is accsessible, in general the GET logic of the enitre SDM FORM class needs work.</p>
     * @param bool $devmode If set to TRUE then this method will display dev information related to the different stages of decodeing on the page via SdmCore::sdmCoreSdmReadArray().
     * @return mixed <p>The value.</p>
     */
    public static function sdmFormGetSubmittedFormValue($key, $devmode = TRUE) {
        $sdmcore = new SdmCore();
        // if $key is not a string then just return the $key as the data
        if (!is_string($key) === TRUE) {
            $data = $key;
        } else {
            if (isset($_POST['SdmForm'][$key]) === TRUE) {
                // store the unfiltered value in a var to get ready for our checks
                $value = $_POST['SdmForm'][$key];
                ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('</xmp><xmp style="color:violet;">GETTING $_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . '</xmp>' => $value)) : null);
                $data = SdmForm::sdmFormDecode($value, $devmode, $key);
            } else { // key does not exist | set $data to null
                $data = null;
                ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('</xmp><xmp style="color:violet;">$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' DOES NOT EXIST OR IS NULL</xmp>' => $value)) : null);
            }
        }
        return $data;
    }

    /**
     * @param type $value <p>The value encoded wtih sdmFormEncode() to decode.</p>
     * <p><i>NOTE: It is safe to pass in values that were not encoded, sdmFormDecode() provides checks
     * to insure that values that cannot be decoded are returned unchanged.</i></p>
     * @param bool $devmode If set to TRUE then this method will display dev information related to the different stages of decodeing on the page via SdmCore::sdmCoreSdmReadArray().
     * @return mixed <p>The decoded value</p>
     */
    public static function sdmFormDecode($value, $devmode = FALSE, $key = null) {
        if (is_array($value) === FALSE) {
            $sdmcore = new SdmCore();
            // if the key string length is a multiple of 4 then it may be base64 encoded, if it is it will have to be decoded
            if (strlen($value) % 4 == 0) {
                ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('</xmp><xmp style="color:green;">$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' STRING LENGTH IS MULTIPLE OF 4, MAY BE BASE 64 ENCODED AND POSSIBLY SERIALIZED</xmp>' => $value)) : null);
                // check if base64  encoded
                switch (base64_decode($value, TRUE)) {
                    case FALSE: // not base64
                        ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' IS NOT BASE 64' => $value)) : null);
                        // check if serialized | we need to surpress any errors resulting from the check, this is ok because if any errors occure we do not proceed through this part of the statement
                        if (@unserialize($value) === FALSE) { // if not serialized use as is
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($value, $devmode, $key);
                            ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' NOT BASE 64 AND IS NOT SERIALIZED' => $value, '$finaldata' => $finaldata)) : null);
                        } else { // if it is serialized unserialize it
                            $data = unserialize($value);
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($data, $devmode, $key);
                            ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' NOT BASE 64 AND IS SERIALIZED' => $value, '$data' => $data, '$finaldata' => $finaldata)) : null);
                        }

                        break;
                    case TRUE: // is base 64
                        ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' IS  MOST LIKELY BASE 64' => $value)) : null);
                        // check if serialized | we need to surpress any errors resulting from the check, this is ok because if any errors occure we do not proceed through this part of the statement
                        if (@unserialize(base64_decode($value, TRUE)) !== FALSE) { // serialized, decode and unserialize
                            $data = unserialize(base64_decode($value, TRUE));
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($data, $devmode, $key);
                            ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' IS BASE 64 AND IS SERIALIZED' => $value, '$data' => $data, '$finaldata' => $finaldata)) : null);
                        } else if (strlen(base64_decode($value, TRUE)) >= strlen($value)) { // not serialized, but we should double check that this is for sure base64 encoded, we can do this by checking if the length of the decoded string is less then the length of the original data. If it is then we can assume the string is NOT base64 because if the decoded string has fewer chars then the original value most likely the string should not be decoded... @todo do some more testing by chcking a few encoded strings against their original values , do this in the hello world app
                            $data = base64_decode($value, TRUE);
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($data, $devmode, $key);
                            ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' IS BASE 64 BUT IS NOT SERIALIZED' => $value, '$data' => $data, 'base64_decode($value, TRUE) !== FALSE' => (base64_decode($value, TRUE) !== FALSE ? 'TRUE' : 'FALSE'))) : null);
                        } else { // not base64
                            $finaldata = SdmForm::sdmFormDecodeArrayValues($value, $devmode, $key);
                            ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' IS ACTUALLY NOT BASE 64, THIS WAS DETERMINED BECAUSE THE LENGTH OF THE DECODED STRING WAS LESS THEN THE ORGININAL INDICATING A DECODING PROBLEM MOST LIKELY RESULTING FROM THE ORIGINAL STRING NOT ACTUALLY BEING BASE 64' => $value, '$value' => $value, 'base64_decode($value, TRUE) !== FALSE' => (base64_decode($value, TRUE) !== FALSE ? 'TRUE' : 'FALSE'), 'strlen(base64_decode($value, TRUE)) >= strlen($value)' => (strlen(base64_decode($value, TRUE)) >= strlen($value) ? 'TRUE' : 'FALSE'))) : null);
                        }
                        break;
                }
            } else { // string length is NOT a multiple of 4
                // check if serialized
                if (@unserialize($value) === FALSE) { // if not serialized use as is | we need to surpress any errors resulting from the check, this is ok because if any errors occure we do not proceed through this part of the statement
                    $finaldata = $value;
                    ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' STRING LENGTH NOT A MULTIPLE OF 4 AND VALUE IS NOT BASE 64 AND IS NOT SERIALIZED' => $value, '$data' => $finaldata)) : null);
                } else { // if it is serialized unserialize it
                    $data = unserialize($value);
                    $finaldata = SdmForm::sdmFormDecodeArrayValues($data, $devmode, $key);
                    ($devmode === TRUE ? $sdmcore->sdmCoreSdmReadArray(array('$_POST[\'SdmForm\']' . (is_array($key) === TRUE ? '[\'' . implode('\'][\'', $key) . '\']' : '[\'' . strval($key) . '\']') . ' STRING LENGTH NOT A MULTIPLE OF 4 AND VALUE IS NOT BASE 64. VALUE IS SERIALIZED' => $value, '$data' => $finaldata)) : null);
                }
            }
        } else { // if $value is an array we need to call SdmForm::sdmFormDecodeArrayValues() to recurse through the array makeing sure none of the values need to be decoded
            $finaldata = SdmForm::sdmFormDecodeArrayValues($value, $devmode, $key);
        }
        return $finaldata;
    }

    /**
     * <p>Recursively decodes the values of a multi-dimensional array. If $data is not an array it is returned unchanged.</p>
     * <p>This method is used by SdmForm::sdmFormDecode() to insure that decoded
     * arrays also have their values decoded.</p>
     * @param type $data <p>The array to recurse through, usually an array decoded by SdmForm::sdmFormDecode()</p>
     * @param bool $devmode If set to TRUE then this method will display dev information related to the different stages of decodeing on the page via SdmCore::sdmCoreSdmReadArray().
     * @return array <p>The array with all it's values decoded with SdmForm::sdmFormDecode()</p>
     */
    public static function sdmFormDecodeArrayValues($data, $devmode, $parentkey = null) {
        // if $data is an array at this point we want to decode any array values that are encoded.
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$key] = SdmForm::sdmFormDecode($value, $devmode, array($parentkey, $key));
            }
        }
        return $data;
    }

}
