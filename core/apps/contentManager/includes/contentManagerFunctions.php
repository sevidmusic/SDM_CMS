<?php

/**
 * <p>Searches a string for any values in the<b>$array</b></p>
 * <p>i.e., arrstristr(array('hello', 'hola', 'aloha'), 'Hello world') | returns true because 'hello' exists in the string.</p>
 * <p>Note: arrstristr(array('hello', 'world'), 'Hello world') | returns true because both 'hello' and 'world' are in the string.</p>
 * @param array $array <p>The array of values to search for in the $string.</p>
 * @param type $string <p>The string to search.</p>
 * @return boolean
 */
function arrstristr(array $array, $string)
{
    foreach ($array as $si) {
        if (stristr($string, $si)) {
            return true;
        } else {
            return false;
        }
    }
}

/**
 * <p>Searches a string for any values in the<b>$array</b></p>
 * <p>i.e., arrstristr(array('hello', 'hola', 'aloha'), 'Hello world') | returns true because 'hello' exists in the string.</p>
 * <p>Note: arrstristr(array('hello', 'world'), 'Hello world') | returns true because both 'hello' and 'world' are in the string.</p>
 * @param array $array <p>The array of values to search for in the $string.</p>
 * @param type $string <p>The string to search.</p>
 * @return array <p>Array of substrings found</p>
 */
function arrstristrchars(array $array, $string)
{
    $substrings = array();
    foreach ($array as $si) {
        $substrings[] = stristr($string, $si);
    }
    return $substrings;
}

/**
 * Filters out "Content Manager" pages from the available pages array that is used to create our select form.
 * This function should be called from within PHP's array_filter() function.
 */
function filter_content_manager_pages($string)
{
    return strpos($string, 'contentManager') === false;
}

?>