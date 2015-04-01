<?php

/**
 * Functions reuqired by the WIKI TESTER app.
 */

/**
 * Builds an array suited for MMh's needs based a query of the titles passed via $querytitles.
 * It utilizes the function movieApiRequest() to make the request and obtain the data, it then
 * sorts throught the data and organizes it into an array that contains only the information needed
 * by MMh and also filters all discovered wikitext into an array representation of the original wikitext values.
 * i.e. [[val1|val2|val3]] should become array('val1','val2','val3')
 * @param type $querytitles The movie titles to request in the query
 * @return array Array of info boxes for the requested movie titles ($querytitles)
 */
function buildMovieApiQueryArray($querytitles) {
    $queryData = movieApiRequest($querytitles);
    $pageids = array();
    foreach ($queryData->query->pageids as $id) {
        $pageids[$id] = $id;
    }
    $wikitextArray = array();
    foreach ($pageids as $pageid) {
        $revision = $queryData->query->pages->$pageid->revisions[0];
        $revkey = '*'; // this is a workaround, the wiki api returns our revisions in an object property named *, the problem with the name * is the * character is not allowed in php because it has special meaning, so to get around this so we can access the property * we store * as a string in a var $revkey and then we can call $object->$revkey to accsess that property
        $wikitextArray[$pageid] = $revision->$revkey;
    }
    $infoboxes = array();
    foreach ($wikitextArray as $pid => $wikitext) {
        $infoboxes[$pid] = getInfoBoxes($wikitext);
    }
    $infoboxes['mmhQueryUrl'] = $queryData->mmhQueryUrl;
    return $infoboxes;
}

/**
 * Returns an array as an unorderd HTML list.
 * @param array $array The array to turn into a list. Multi-dimensional arrays are supported.
 * @param string $parentKey The name of the array. This var is also used if a multi-dimensional array is passed as the first argument, in which case, this function will set the $parentKey automatically as it recurses through the child arrays of $array.
 * @return string
 */
function wikiArrayToList($array, $parentKey = null) {
    if (is_object($array)) {
        $array = json_decode(json_encode($array), TRUE);
    }
    $wrappingDivStyle = 'color:#D0D0D0;background:#000000;border: 3px solid #99FF99;border-radius:5px;padding:15px;margin:20px 0px 20px 0px;';
    $liStyle = 'color:#D2D2D2;background:#111111;border: 3px dashed #99FF99;border-radius:5px;padding:15px;margin:20px 0px 20px 0px;overflow:auto;';
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

function movieApiRequest($querytitles) {
// args
    $post = array(
        'action' => 'query', // we use the query action to query wikipedia, there are many other actions @see http://www.mediawiki.org/wiki/API:Main_page
        'titles' => $querytitles, // the titles to get data for
        'prop' => 'revisions', // tells the query to grab the most recent revisions of the wiki pages returned
        'rvprop' => 'content|timestamp|user|ids|contentmodel|size|tags', // get wiki page content, and any other indicated page properties (for options @see http://www.mediawiki.org/wiki/API:Revisions)
        'rvsection' => 0, // important | gets the infobox
        'redirects' => 'redirects', // important | reolves any redirects related to the titles
        'format' => 'json', // important | we need json
        'indexpageids' => 'indexpageids', // important | returns an array of all the page ids for the returned pages
            /* Properties that are important to IGNORE b/c they effect how and what data is returned in a way that would make it harder for us to get the data we need */
            //    'rvexpandtemplates' => 'rvexpandtemplates', // changes how the returned page items are fomratted. i.e., if this property does NOT exist then pure wikitext is returend, if it is present then wikitext formatted for readability is returned. THIS SHOULD NOT BE USED outside of development as it will make it impossible to parse the returned data efficiently.
            //    'continue' => '||',
            //    'rvlimit' => 1, // impacts how many revisions are returned, but i noticed that if set it impacts how much data is returned for each page limiting what we get back negativly
    );
    $i = 0;
    $postArgsCount = count($post);
    $get = '';
    foreach ($post as $key => $value) {
        $get .= $key . '=' . str_replace(' ', '%20', $value) . ($i === ($postArgsCount - 1) ? '' : '&');
        $i++;
    }

    // build api request url
    $apiRequestUrl = 'http://en.wikipedia.org/w/api.php?' . $get;
    // api request
    $apiRequest = json_decode(curlGrabContent($apiRequestUrl));
    // add the api request url to the returend request object
    $apiRequest->mmhQueryUrl = $apiRequestUrl;
    return $apiRequest;
}

/**
 * Make a curl request on a $url and include any $post values along with the request
 * @param type $url The url to request content from
 * @param array $post Post data
 * @return string Either a string of data returned by the request or FALSE
 */
function curlGrabContent($url, array $post = array()) {
    if (strval($url) !== $url) { // if the string value of $url is not equal to $url than $url is not a string...
        throw new Exception('Bad type passed to sdm_curl_grab_content. $url must be a string              ');
    }
    // -- CURL session --
    $ch = curl_init();
    // we need to mimic a browser to get the actual web page data | otherwise some servers will "withhold" some of that data
    $useragent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36"; // mimics a browser | we use an older browser to further prevent the target site from stopping us from getting the data we want
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIESESSION, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    if (isset($post) && !empty($post)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/**
 * getInfoBoxes() is based on code from Jungle Wikipedia Syntax Parser with some modifications for use on MMh.
 *
 * @link https://github.com/donwilson/PHP-Wikipedia-Syntax-Parser/blob/master/wiki_parser.php
 *
 * @author Don Wilson <donwilson@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package Jungle
 * @subpackage Wikipedia Syntax Parser
 *
 */
function getInfoBoxes($wikitext) {
    $infobox = array();

    preg_match_all("#\{\{(?:\s*?)Infobox(?:\s*?)(.+?)" . PHP_EOL . "(.+?)" . PHP_EOL . "\}\}" . PHP_EOL . "#si", $wikitext, $matches);

    if (!empty($matches[0])) {
        foreach ($matches[0] as $key => $nil) {
            $infobox_values = array();
            $infobox_tmp = $matches[2][$key];

            $infobox_tmp = explode("\n", $infobox_tmp);
            $last_line_key = "";

            foreach ($infobox_tmp as $line) {
                $line = trim($line);

                if (preg_match("#^\|#si", $line)) {
                    $line = preg_replace("#^\|(\s*?)#si", "", $line);
                    $bits = explode("=", $line, 2);

                    $line_key = trim(preg_replace("#[^A-Za-z0-9]#si", "_", strtolower($bits[0])), "_");
                    $line_value = trim($bits[1]);

                    $infobox_values[$line_key] = array();
                } else {
                    if (!isset($infobox_values[$last_line_key])) {
                        continue;   // this is likely an editor message of some sort
                    }

                    $line_key = $last_line_key;
                    $line_value = $line;
                }

                $line_values = preg_split("#<(?:\s*?)br(?:\s*?)(/?)(?:\s*?)>#si", $line_value, -1, PREG_SPLIT_NO_EMPTY);

                $infobox_values[$line_key] = array_merge($infobox_values[$line_key], $line_values);

                $last_line_key = $line_key;
            }

            $infobox[] = array(
                'type' => $matches[1][$key]
                , 'contents' => $infobox_values
            );
        }
    }

    return $infobox;
}

?>
