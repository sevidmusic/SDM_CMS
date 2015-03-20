<?php

// EMBED API KEY AND EXTRACT API URL
$apikey = 'ef1f84febaea4e97aba981a262d58bc7';
$embedlyUrl = 'http://api.embed.ly/1/extract?key=' . $apikey . '&url=';

function arrayToList($array, $parentKey, $rowcolor = '') {
    $style = 'color:' . ($rowcolor === '#000000' ? '#33CCCC' : '#00CC66') . ';background:' . ($rowcolor === '#000000' ? '#363636' : '#000000') . ';border: 1px solid ' . ($rowcolor === '#000000' ? 'white' : '#000000') . ';border-radius: 5px;margin: 5px 0px 5px 0px;padding: 10px;';
    $list = '<p style="' . $style . '">[\'' . $parentKey . '\']</p><ul style="list-style-type: none;">';
    foreach ($array as $key => $value) {
        switch (is_array($value)) {
            case TRUE:
                $list .= arrayToList($value, $key, $rowcolor);
                break;
            case FALSE:
                $list .= '<li style="' . $style . '">[\'' . $key . '\'] = "' . (substr($value, 0, 6) === 'http://' || substr($value, 0, 8) === 'https://' || substr($value, 0, 4) === 'www.' ? '<a>' . $value . '</a>' : $value) . '";</li>';
                break;
        }
    }
    $list .= '</ul>';
    return $list;
}

function assemlbeExtractTableElements($extractData, $rowcolor) {
    $styles_extractDataTableRow = 'background: ' . $rowcolor . '; color: ' . ($rowcolor === '#363636' ? '#D0D0D0' : '#F0F0F0') . ';'; // @todo alternate bg color every row
    $styles_td = 'padding: 10px;border:2px solid #777777;border-radius: 3px;'; // dont set background color here, it will be set depending on what value is returned
    $styles_headerTd = 'padding 10px; border: 2px solid #999999; border-radius: 3px;';
    $decodedData = json_decode($extractData, TRUE);
    $output = '<tr><td>' . (isset($decodedData['provider_name']) ? $decodedData['provider_name'] : '<b style="color:red;">UNKNOWN PROVIDER</b>') . '</td></tr>';
    $output .= '<tr style="' . $styles_extractDataTableRow . '"><td style="' . $styles_headerTd . '">provider_url</td><td style="' . $styles_headerTd . '">description</td><td style="' . $styles_headerTd . '">embeds</td><td style="' . $styles_headerTd . '">safe</td><td style="' . $styles_headerTd . '">provider_display</td><td style="' . $styles_headerTd . '">related</td><td style="' . $styles_headerTd . '">favicon_url</td><td style="' . $styles_headerTd . '">authors</td><td style="' . $styles_headerTd . '">images</td><td style="' . $styles_headerTd . '">cache_age</td><td style="' . $styles_headerTd . '">language</td><td style="' . $styles_headerTd . '">app_links</td><td style="' . $styles_headerTd . '">original_url</td><td style="' . $styles_headerTd . '">url</td><td style="' . $styles_headerTd . '">media</td><td style="' . $styles_headerTd . '">title</td><td style="' . $styles_headerTd . '">offset</td><td style="' . $styles_headerTd . '">lead</td><td style="' . $styles_headerTd . '">content</td><td style="' . $styles_headerTd . '">entities</td><td style="' . $styles_headerTd . '">favicon_colors</td><td style="' . $styles_headerTd . '">keywords</td><td style="' . $styles_headerTd . '">published</td><td style="' . $styles_headerTd . '">provider_name</td><td style="' . $styles_headerTd . '">type</td></tr>';
    $output .= '<tr style="' . $styles_extractDataTableRow . '">';
    // add extract data to table
    foreach ($decodedData as $key => $value) {
        switch (is_array($value)) {
            case TRUE:
                if (empty($value)) {
                    $output .= '<td style="' . $styles_td . 'background:darkred;"><div style="height:200px;overflow:auto;">' . (is_null($value) || $value === '' ? '<i style="color:aqua">null</i>' : '<i style="color:aqua">EMPTY ARRAY</i>') . '</div></td>';
                } else {
                    $output .= '<td style="' . $styles_td . '"><div style="height:200px;min-width:420px;overflow:auto;">' . (is_null($value) || $value === '' ? '<i style="color:aqua">null</i>' : arrayToList($value, $key, $rowcolor)) . '</div></td>';
                }
                break;
            default:
                $output .= '<td style="' . $styles_td . (is_null($value) || $value === '' ? 'background: red;' : '') . '"><div style="height:200px;overflow:auto;">' . (is_null($value) || $value === '' ? '<i style="color:aqua">null</i>' : (is_bool($value) === TRUE ? ($value === TRUE ? '<b style="color:green">TRUE</b>' : '<b style="color:red">FALSE</b>') : (substr($value, 0, 7) === 'http://' || substr($value, 0, 8) === 'https://' || substr($value, 0, 4) === 'www.' ? '<a href="' . $value . '">' . $value . '</a>' : $value))) . '</div></td>';
                break;
        }
    }
    $output .= '</tr>';

    return $output;
}

$options = array(
    'wrapper' => 'main_content',
    'incmethod' => 'prepend',
    'incpages' => array('embedlyTester'),
        //'ignorepages' => array('contentManager'),
); // options array determines how an apps output is incorporated into the page
$output = '<h2>Embedly Tester</h2><p>This app generates an html table that displays the data that is returned from the different EXTRACT provider apis. The urls tested can be seen in the source code in the $movieUrls array. To see the extract data table click here: <br/><br/><a href="' . $sdmcore->getRootDirectoryUrl() . '/index.php?page=embedlyTester&mode=test">Generate Extract Data Table</a></p><p>You can also test an individual movie urls by entering a movie url from a site like YouTube into the form below.</p>';
$devmode = FALSE; // if set to TRUE then dev data about the app output will be displayed on the page as well
$embedlyTesterForm = new SDM_Form();
$embedlyTesterForm->form_handler = 'embedlyTester';
$embedlyTesterForm->method = 'post';
$embedlyTesterForm->submitLabel = 'See What Embedly Returns For This Movie';
$embedlyTesterForm->form_elements = array(
    array(
        'id' => 'movieUrl',
        'type' => 'text',
        'element' => 'Movie Url',
        'value' => '',
        'place' => '0',
    ),
);
$embedlyTesterForm->__build_form($sdmcore->getRootDirectoryUrl());
$output .= $embedlyTesterForm->__get_form();

// determine if we should display the extract data table
if (isset($_POST['sdm_form'])) {
    $movieUrls = array(
        htmlentities(SDM_Form::get_submitted_form_value('movieUrl')),
    );
} elseif (isset($_GET['mode']) && $_GET['mode'] === 'test') {
    $movieUrls = array(
        // youtube
        'https://www.youtube.com/watch?v=1pqe_oqDJp0',
        // twitch tv
        'http://www.twitch.tv/esl_csgo_pl',
        // revision 3
        'http://revision3.com/sourcefednerd/grand-theft-auto-tv-movie-coming-to-bbc/',
        // daily motion
        'http://www.dailymotion.com/video/x2ipb0z_bitcoin-and-the-history-of-money_tech',
        // cllege humor
        'http://www.collegehumor.com/video/7012104/21-steps-to-making-an-oscar-movie',
//        // telly
//        'http://telly.com/lawless-movie',
//        // break
//        'http://www.break.com/video/this-grandmother-literally-killed-somebody-for-eating-2834463',
//        // myspace
//        'https://myspace.com/busking/video/shawn-mendes/109561937',
//        // metacafe
//        'http://www.metacafe.com/watch/11337959/review_griffin_guitarconnect_cable_geekbeat_tips_reviews/',
//        // blip tv
//        'http://blip.tv/animation-lookback/animat-s-classic-reviews-the-flight-of-dragons-7170787',
//        // Yahoo Screen
//        'https://screen.yahoo.com/snl/hillary-cold-open-071950438.html',
//        // Live Leak
//        'http://www.liveleak.com/view?i=087_1426549997',
//        // Dot Sub
//        'https://dotsub.com/view/5af2ea32-1aa1-4fa7-9d36-b3a01e841ca2',
//        // Overstream
//        'http://www.overstream.net/view.php?oid=n8sfmerfi2ai',
//        // Live Stream
//        'http://new.livestream.com/internetsociety/codeacrossnyc2015/videos/77989113',
//        // World Star Hip Hop
//        'http://www.worldstarhiphop.com/videos/video.php?v=wshhPxEmq52F7O6Fgty5',
//        // Bambuser
//        'http://bambuser.com/v/5363167',
//        // School Tube
//        "http://www.schooltube.com/video/a63a8383938d488eae52/SLPS's%20College%20Summit%20Visits%20Spotlight%20News", // NOTE: Urls from this site sometimes contain a single quote, ', if urls from this site seem buggy this might have something to do with it
//        // Big Think
//        'http://bigthink.com/videos/jonathan-zittrain-on-net-neutrality',
//        // Youku
//        'http://v.youku.com/v_show/id_XOTE1MjgyOTYw.html?f=23574074&ev=1',
//        // Snotr
//        'http://www.snotr.com/video/15047/Meet_MILK_the_polar_bear',
//        // Clip Fish
//        'http://www.clipfish.de/musikvideos/video/4182500/mark-daumail-monsters/',
//        // My Video
//        'http://www.myvideo.de/serien/the-biggest-loser/staffel-7/folge-7-ersehnte-umstyling-m-11829869',
//        // Coub
//        'http://coub.com/view/5f93v',
//        // Vine
//        'https://vine.co/v/OPxnt0xtaMI/',
//        // Tudou
//        'http://www.tudou.com/albumplay/Lqfme5hSolM/H35TMH12ceA.html',
//        // Mix Bit
//        'https://mixbit.com/v/1Xbo51F9BxDNFQSWiHYwfv',
//        // Lustich
//        'http://lustich.de/bilder/andere/schmerzhaftes-tier/',
//        // Reelhouse
//        'https://www.reelhouse.org/v/shepard-fairey-obey-this-film?r=hp_carousel',
//        // Web TV
//        'http://omigu.web.tv/video/impressive-lava-in-hawaii__qyqger08tbo',
//        // MyNet Sahnetv
//        'http://www.mynet.com/video/hayvanlar/yetenekli-piyanist-kopek-2052234/',
//        // iZLESENE
//        'http://www.izlesene.com/video/hirsiz-kartal-leylegin-baligini-boyle-caldi/8284670',
//        // Alkışlarla Yaşıyorum
//        'http://alkislarlayasiyorum.com/icerik/251073/the-walking-dead-8-bit-olarak-ilk-2-sezon-ozeti',
//        // 59 SANIYE
//        'https://www.59saniye.com/karadenizli-teyzenin-kopek-sevgisi/',
//        // Zie
//        'http://www.zie.nl/video/algemeen/IS-eist-aanslag-Tunis-op/py9zrwrfc72w#',
//        // The White House
//        'https://www.whitehouse.gov/photos-and-video/video/2015/03/19/press-briefing',
//        // Hulu
//        'http://www.hulu.com/watch/761665',
//        // Crackle
//        'http://www.crackle.com/c/seinfeld/the-fire/2483598',
//        // Funny Or Die
//        'http://www.funnyordie.com/articles/a6b7fb28ac/will-ferrell-debuts-as-the-new-face-of-little-debbie',
//        // Vimeo
//        'https://vimeo.com/ondemand/highmaintenance',
//        // Ted Talks
//        'https://www.ted.com/talks/andy_yen_think_your_email_s_private_think_again#',
//        // National Film Board of Canada
//        'https://www.nfb.ca/film/getting_started?hpen=feature_3&feature_type=film',
//        // The Daily Show
//        'http://thedailyshow.cc.com/videos/fsdtvk/the-snacks-of-life',
//        // Yahoo Movies
//        'https://www.yahoo.com/movies/v/second-best-exotic-marigold-hotel-192747616.html',
//        // The Colbert Report
//        'http://thecolbertreport.cc.com/videos/pjkorm/cheating-death---grimmy-s-goodbye',
//        // Comedy Central
//        'http://www.cc.com/video-collections/3e1lom/biatches-season-one/cjz3ay',
//        // The Onion
//        'http://www.theonion.com/video/onion-film-standard-oscars-edition,38051/',
//        // WordpressTV
//        'http://wordpress.tv/2015/02/17/michael-schroder-contributing-to-core-hassle-to-hobby/',
//        // Trailer Addict
//        'http://www.traileraddict.com/star-wars-episode-vii/teaser-trailer',
//        // Fora TV
//        'http://library.fora.tv/2012/12/05/How_Word_Gets_Out_Building_a_Movement_Behind_Stories/Authentic_Action_Does_a_Good_Idea_Need_Social_Media',
//        // Spike
//        'http://www.spike.com/video-clips/3o3phn/lip-sync-battle-abbi-jacobson-and-ilana-glazer-go-toe-to-toe',
//        // Game Trailers
//        'http://www.gametrailers.com/videos/db5cew/eve--valkyrie-gameplay-trailer',
//        // PBS
//        'http://video.pbs.org/video/2365418529/',
//        // Zapiks
//        'http://www.zapiks.com/j1-mercredi-18-mars-2015.html',
//        // TruTv
//        'http://www.trutv.com/shows/impractical-jokers/videos/the-permanent-punishment-full-episode.html',
//        // Nz On Screen
//        'http://www.nzonscreen.com/title/hairy-maclary-from-donaldsons-dairy-1997',
//        // New York Magazine
//        'http://nymag.com/daily/intelligencer/2013/07/subway-china-beijing-video-crowded-train.html',
//        // Grind TV
//        'http://www.grindtv.com/lifestyle/culture/post/new-surfmoto-adventure-film-tackles-classic-australian-road-trip/',
//        // iFood TV
//        'http://ifood.tv/italian/464310-italian-chicken-sammitz',
//        // Logo TV
//        ''
//        // Lonely Planet
//        ''
//        // Street Fire
//        ''
//        // Science Stage
//        ''
//        // Canal Plus
//        ''
//        // Vevo
//        ''
//        // Aol On
//        ''
//        // Khan Academy
//        ''
//        // Veoh
//        ''
//        // Univision
//        ''
//        // Muzu TV
//        ''
//        // Box Office Buz
//        ''
//        // God Tube
//        ''
//        // Media Matters
//        ''
//        // Clip Syndicate
//        ''
//        // SRF
//        ''
//        // MPORA
//        ''
//        // Vice
//        ''
//        // Video Donor
//        ''
//        // Live Live
//        ''
//        // Hurri Yet Tv
//        ''
//        // uludağ sözlük video
//        ''
//        // IGN
//        ''
//        // Ask Men
//        ''
//        // Esri
//        ''
//        // Office Mix
//        ''
//        // Zapkolik
//        ''
//        // ESPN
//        ''
//        // ABC NEWS
//        ''
//        // Washington Post
//        ''
//        // CNBC
//        ''
//        // CBS NEWS
//        ''
//        // CNN
//        ''
//        // CNN Edition
//        ''
//        // CNN Money
//        ''
//        // MSNBC
//        ''
//        // NBC
//        ''
//        // FOX Sports
//        ''
//        // Global Post
//        ''
//        // The Gaurdian
//        ''
//        // Bravo TV
//        ''
//        // Discovery Channel
//        ''
//        // Forbes
//        ''
//        // Fox News
//        ''
//        // Fox Business
//        ''
//        // Reuters
//        ''
//        // Huffington Post
//        ''
//        // The New York Times
//        ''
//        // Vorarlberg Online
//        'http://www.vol.at/melanies-und-michaels-weltreise-erste-eindruecke-von-mosambik/4269809'
//        // Spiegel Online
//        'http://www.spiegel.de/video/varoufakis-aeussert-sich-zu-varoufake-video-video-1564023.html'
//        // Play RTS
//        ''
//        // Zeit Online
//        ''
    );
} else {
    $movieUrls = array();
}

// only show extract data if form was submitted or we are in test mode, i.e., if $movieUrls is empty do NOT display extract data table
if (!empty($movieUrls)) {
    $styles_extractDataTable = '';
    $output .= '<table id="extractDataTable" style="' . $styles_extractDataTable . '">';
    $rowcolor = '#363636'; // initial row color | will alternate
    // create rows and columns of extract data
    foreach ($movieUrls as $movieUrl) {
        $embedlyRequestUrl = $embedlyUrl . $movieUrl;
        $extractData = $sdmcore->sdmCoreCurlGrabContent($embedlyRequestUrl, array());
        $output .= assemlbeExtractTableElements($extractData, $rowcolor);
        $rowcolor = ($rowcolor === '#363636' ? '#000000' : '#363636'); // alternate row colors
    }
    $output .= '</table>';
    $output .= '<h1>emalloc() MEMORY USAGE : ' . memory_get_usage() . '</h1>';
    $output .= '<h1>SYSTEM MEMORY USAGE : ' . memory_get_usage() . '</h1>';
}
$sdmassembler->incorporateAppOutput($sdmassembler_dataObject, $output, $options, $devmode);

