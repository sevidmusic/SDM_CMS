<?php

/**
 * Functions reuqired by the WIKI TESTER app.
 */

/**
 * Returns an array as an unorderd HTML list.
 * @param array $array The array to turn into a list. Multi-dimensional arrays are supported.
 * @param string $parentKey The name of the array. This var is also used if a multi-dimensional array is passed as the first argument, in which case, this function will set the $parentKey automatically as it recurses through the child arrays of $array.
 * @return string
 */
function wikiArrayToList($array, $parentKey = null) {
    $wrappingDivStyle = 'color:#D0D0D0;background:#000000;border: 3px solid #99FF99;border-radius:5px;padding:15px;margin:20px 0px 20px 0px;';
    $liStyle = 'color:#D2D2D2;background:#111111;border: 3px dashed #99FF99;border-radius:5px;padding:15px;margin:20px 0px 20px 0px;';
    $list = '<div style="' . $wrappingDivStyle . '' . (isset($parentKey) === TRUE ? '' : 'text-align:center;') . '">' . (isset($parentKey) === TRUE ? ' <i style="color:cornflowerblue;">(type : <span style="color:' . (gettype($array) === 'integer' ? '#0066ff' : (gettype($array) === 'array' ? '#66FF66' : '#009966')) . ';">' . gettype($array) . '</span>) </i> <span style="color:' . (gettype($array) === 'integer' ? '#0066ff' : (gettype($array) === 'array' ? '#66FF66' : '#009966')) . ';">[\'' . $parentKey . '\']</span>' : '-- Array Data --') . '</div><ul style="list-style-type:none;">';
    foreach ($array as $key => $value) {
        switch (is_array($value)) {
            case TRUE:
                $list .= wikiArrayToList($value, $key);
                break;
            case FALSE:
                $list .= '<li style="' . $liStyle . '"><i style="color:cornflowerblue;">(type : <span style="color:' . (gettype($value) === 'integer' ? '#0066ff' : (gettype($value) === 'array' ? '#33FFFF' : '#009966')) . ';">' . gettype($value) . '</span>)</i> <span style="color:' . (gettype($array) === 'integer' ? '#0066ff' : (gettype($array) === 'array' ? '#66FF66' : '#009966')) . ';">[\'' . $parentKey . '\']</span>[\'' . $key . '\'] = ' . (gettype($value) === 'integer' ? '' : '\'') . '<span style="color:' . (gettype($value) === 'integer' ? '#0066ff' : (gettype($value) === 'array' ? '#33FFFF' : '#009966')) . ';">' . (substr($value, 0, 7) === 'http://' || substr($value, 0, 8) === 'https://' || substr($value, 0, 4) === 'www.' ? '<a href="' . $value . '">' . $value . '</a>' : $value) . '</span>' . (gettype($value) === 'integer' ? '' : '\'') . ';</li>';
                break;
        }
    }
    $list .= '</ul>';
    return $list;
}

?>
