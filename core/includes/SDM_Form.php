<?php

/**
 * @todo finsih utilizeing base64_encode(serialize()) in the __build_form() method to filter all form values so the get_submitted_form_value() method can be used to get submitted form values.
 */
class SDM_Form {

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
     * $form_id : defined internally, upon the creation of an SDM_Form instance. A unique ID is assigned to this property via the internal __generate_form_id() function
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
        $this->form_id = (isset($this->form_id) ? $this->form_id : $this->__generate_form_id());
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
        $this->form = (isset($this->form) ? $this->form : $this->__build_form(str_replace('/index.php', '', 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'])));
        $this->submitLabel = (isset($this->submitLabel) ? $this->submitLabel : 'Submit');
    }

    /**
     * Builds the form.
     * @var string $rootUrl the sites root url. Insures requests are made from site of origin.
     * @return The Form html
     */
    public function __build_form($rootUrl) {
        // intial form html
        $form_html = '<!-- form "' . $this->__get_form_id() . '" --><form method="' . $this->method . '" action="' . $rootUrl . '/index.php?page=' . $this->form_handler . '">';

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
                    $form_html = $form_html . '<!-- form element "sdm_form[' . $value['id'] . ']" --><label for="sdm_form[' . $value['id'] . ']">' . $value['element'] . '</label><input name="sdm_form[' . $value['id'] . ']" type="text"><!-- close form element "sdm_form[' . $value['id'] . ']" -->';
                    break;
                case 'textarea':
                    $form_html = $form_html . '<!-- form element "sdm_form[' . $value['id'] . ']" --><label for="sdm_form[' . $value['id'] . ']">' . $value['element'] . '</label><textarea name="sdm_form[' . $value['id'] . ']">' . (isset($value['value']) ? $value['value'] : '') . '</textarea><!-- close form element "sdm_form[' . $value['id'] . ']" -->';
                    break;
                case 'select':
                    $form_html = $form_html . '<!-- form element "sdm_form[' . $value['id'] . ']" --><label for="sdm_form[' . $value['id'] . ']">' . $value['element'] . '</label><select name="sdm_form[' . $value['id'] . ']">';
                    foreach ($value['value'] as $option => $option_value) {
                        $form_html = $form_html . '<option value="' . (substr($option_value, 0, 8) === 'default_' ? base64_encode(serialize(str_replace('default_', '', $option_value))) . '" selected="selected"' : base64_encode(serialize($option_value)) . '"') . '>' . $option . '</option>';
                    }
                    $form_html = $form_html . '</select><!-- close form element "sdm_form[' . $value['id'] . ']" -->';
                    break;
                case 'radio':
                    $form_html = $form_html . '<!-- form element "sdm_form[' . $value['id'] . ']" --><p id="label-for-sdm_form[' . $value['id'] . ']">' . $value['element'] . '</p>';
                    foreach ($value['value'] as $radio => $radio_value) {
                        $form_html = $form_html . '<label  for="sdm_form[' . $value['id'] . ']">' . $radio . '</label><input type="radio" name="sdm_form[' . $value['id'] . ']" value="' . (substr($radio_value, 0, 8) === 'default_' ? base64_encode(serialize(str_replace('default_', '', $radio_value))) . '" checked="checked"' : base64_encode(serialize($radio_value)) . '"') . '><!-- close form element "sdm_form[' . $value['id'] . ']" -->';
                    }
                    break;
                case 'hidden':
                    $form_html = $form_html . '<!-- form element "sdm_form[' . $value['id'] . ']" --><input name="sdm_form[' . $value['id'] . ']" type="hidden" value="' . base64_encode(serialize($value['value'])) . '"><!-- close form element "sdm_form[' . $value['id'] . ']" -->';
                    break;
                default:
                    break;
            }
        }
        // add hidden element to store form id
        $form_html .= '<!-- built-in form element "form_id" --><input type="hidden" name="sdm_form[form_id]" value="' . $this->__get_form_id() . '"><!-- close built-in form element "form_id" -->';
        $this->form = $form_html . '<input value="' . $this->submitLabel . '" type="submit"></form><!-- close form ' . $this->__get_form_id() . ' -->';
        return $this->form;
    }

    /**
     * Used to get the Form's HTML
     * @return type The assembled Form.
     */
    public function __get_form() {
        return (isset($this->form) ? $this->form : 'Unable to load form!');
    }

    /**
     * Automatically assigns an id to a SDM_Form instance. If the Form already has an id a new id is NOT created.
     * There is NO need to call this function as it is run whenever an instance of this class is created.
     * There is also no harm in calling it because this function CANNOT overwrite an ID that is already set.
     * @return string Unique id made up of random alphanumeric characters.
     */
    public function __generate_form_id() {
        // we only set id if it is NOT already set | checked via terenary operator (condition ? true : false)
        $this->form_id = (!isset($this->form_id) ? rand(1000, 9999) . '-' . $this->__sdm_alpha_rand(8) : $this->form_id);
        return $this->form_id;
    }

    /**
     * Used to refernce the forms internal ID.
     * @return string Form ID as a string.
     */
    public function __get_form_id() {
        return (isset($this->form_id) ? $this->form_id : 'FORM ID NOT SET!');
    }

    /** sdm_alpha_rand($num_chars)
     * Random letter generator. Used in Player and Package ID generation.
     * @param int $num_chars Number of letters to generate.
     * There are spaces and indents are NOT generated, so letters are clumped up into one long string of characters.
     * i.e., sdm_alpha_rand(4) may generate the string 'sonv', or 'bUsw', etc...
     * @return string Random String $num_chars in length.
     * i.e., __sdm_alpha_rand(3) would produce a string of random alphanumeric characters 3 characters in length
     */
    public function __sdm_alpha_rand($num_chars) {
        $alphabet = 'aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ';
        $string = '';
        for ($inc = 0; $inc <= $num_chars; $inc++) {
            $i = rand(0, 51);
            $string = $string . $alphabet[$i];
        }
        return $string;
    }

    public static function get_submitted_form_value($key, $devmode = FALSE) {
        $sdmcore = new SdmCore();
        // if $key is not a string then just return the $key as the data
        if (!is_string($key)) {
            $data = $key;
        } else {
            if (isset($_POST['sdm_form'][$key])) {
                // store the unfiltered value in a var to get ready for our checks
                $value = $_POST['sdm_form'][$key];
                ($devmode === TRUE ? $sdmcore->sdm_read_array(array('GETTING $_POST[\'sdm_form\'][\'' . $key . '\']' => $value)) : null);
                // if the key string length is a multiple of 4 then it may be base64 encoded, if it is it will have to be decoded
                if (strlen($value) % 4 == 0) {
                    ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] STRING LENGTH IS MULTIPLE OF 4' => $value)) : null);
                    // check if base64  encoded
                    switch (base64_decode($value, TRUE)) {
                        case FALSE: // not base64
                            ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] IS NOT BASE 64' => $value)) : null);
                            // check if serialized | we need to surpress any errors resulting from the check, this is ok because if any errors occure we do not proceed through this part of the statement
                            if (@unserialize($value) === FALSE) { // if not serialized use as is
                                $data = $value;
                                ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] NOT BASE 64 AND IS NOT SERIALIZED' => $value, '$data' => $data)) : null);
                            } else { // if it is serialized unserialize it
                                $data = unserialize($value);
                                ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] NOT BASE 64 AND IS SERIALIZED' => $value, '$data' => $data)) : null);
                            }

                            break;

                        case TRUE: // is base 64
                            ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] IS  MOST LIKELY BASE 64' => $value)) : null);
                            // check if serialized | we need to surpress any errors resulting from the check, this is ok because if any errors occure we do not proceed through this part of the statement
                            if (@unserialize(base64_decode($value, TRUE)) !== FALSE) { // serialized, decode and unserialize
                                $data = unserialize(base64_decode($value, TRUE));
                                ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] IS BASE 64 AND IS SERIALIZED' => $value, '$data' => $data)) : null);
                            } else if (strlen(base64_decode($value, TRUE)) >= strlen($value)) { // not serialized, but we should double check that this is for sure base64 encoded, we can do this by checking if the length of the decoded string is less then the length of the original data. If it is then we can assume the string is NOT base64 because if the decoded string has fewer chars then the original value most likely the string should not be decoded... @todo do some more testing by chcking a few encoded strings against their original values , do this in the hello world app
                                $data = base64_decode($value, TRUE);
                                ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] IS BASE 64 BUT IS NOT SERIALIZED' => $value, '$data' => $data, 'base64_decode($value, TRUE) !== FALSE' => (base64_decode($value, TRUE) !== FALSE ? 'TRUE' : 'FALSE'))) : null);
                            } else { // not base64
                                $data = $value;
                                ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] IS ACTUALLY NOT BASE 64, THIS WAS DETERMINED BECAUSE THE LENGTH OF THE DECODED STRING WAS LESS THEN THE ORGININAL INDICATING A DECODING PROPBLEM MOST LIKELY RESULTING FROM THE ORIGINAL STRING NOT ACTUALLY BEING BASE 64' => $value, '$data' => $data, 'base64_decode($value, TRUE) !== FALSE' => (base64_decode($value, TRUE) !== FALSE ? 'TRUE' : 'FALSE'), 'strlen(base64_decode($value, TRUE)) >= strlen($value)' => (strlen(base64_decode($value, TRUE)) >= strlen($value) ? 'TRUE' : 'FALSE'))) : null);
                            }
                            break;
                    }
                } else { // string length is NOT a multiple of 4
                    // check if serialized
                    if (@unserialize($value) === FALSE) { // if not serialized use as is | we need to surpress any errors resulting from the check, this is ok because if any errors occure we do not proceed through this part of the statement
                        $data = $value;
                        ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] STRING LENGTH NOT A MULTIPLE OF 4 AND VALUE IS NOT BASE 64 AND IS NOT SERIALIZED' => $value, '$data' => $data)) : null);
                    } else { // if it is serialized unserialize it
                        $data = unserialize($value);
                        ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] STRING LENGTH NOT A MULTIPLE OF 4 AND VALUE IS NOT BASE 64. VALUE IS SERIALIZED' => $value, '$data' => $data)) : null);
                    }
                }
            } else {
                $data = null;
                ($devmode === TRUE ? $sdmcore->sdm_read_array(array('$_POST[\'sdm_form\'][\'' . $key . '\'] DOES NOT EXIST OR IS NULL' => $value)) : null);
            }
        }
        return $data;
    }

}
