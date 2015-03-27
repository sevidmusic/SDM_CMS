<?php

/**
 * Functions reuqired by the OMDB TESTER app.
 */

/**
 * Returns an array as an unorderd HTML list.
 * @param array $array The array to turn into a list. Multi-dimensional arrays are supported.
 * @param string $parentKey The name of the array. This var is also used if a multi-dimensional array is passed as the first argument, in which case, this function will set the $parentKey automatically as it recuses through the child arrays of $array.
 * @param string $rowcolor This var is used to alternate the text color based on the row this data is placed in. Should be set to #000000 on intial call to this function, the function will alternate the $rowcolor accordingly. This exists because this function is meant to be used alongside the assemlbeExtractTableElements() function which alternate the row color of each table row.
 * @return string
 */
function omdbArrayToList($array, $parentKey, $rowcolor = '#000000') {
    $style = 'color:' . ($rowcolor === '#000000' ? '#33CCCC' : '#00CC66') . ';background:' . ($rowcolor === '#000000' ? '#363636' : '#000000') . ';border: 1px solid ' . ($rowcolor === '#000000' ? 'white' : '#000000') . ';border-radius: 5px;margin: 5px 0px 5px 0px;padding: 10px;';
    $list = '<p style="' . $style . '">[\'' . $parentKey . '\']</p><ul style="list-style-type: none;">';
    foreach ($array as $key => $value) {
        switch (is_array($value)) {
            case TRUE:
                $list .= omdbArrayToList($value, $key, $rowcolor);
                break;
            case FALSE:
                $list .= '<li style="' . $style . '">[\'' . $key . '\'] = "' . (substr($value, 0, 6) === 'http://' || substr($value, 0, 8) === 'https://' || substr($value, 0, 4) === 'www.' ? '<a>' . $value . '</a>' : $value) . '";</li>';
                break;
        }
    }
    $list .= '</ul>';
    return $list;
}

/**
 * <p>Bulds the table elements for our Embedly Data Table based on the data
 * returned from the tested url which.</p>
 * @param json $embedlyData <p>The embedly data returned from our url.</p>
 * @param string $rowcolor <p>The row color for this row.</p>
 * @param type $testurl <p>The tested url.</p>
 * @return string
 */
function assemlbeOmdbTableElements($embedlyData, $rowcolor, $testurl = 'unknown') {
    $sdmcore = new SdmCore();
    $styles_omdbDataTableRow = 'background: ' . $rowcolor . '; color: ' . ($rowcolor === '#363636' ? '#D0D0D0' : '#F0F0F0') . ';'; // @todo alternate bg color every row
    $styles_td = 'color:' . ($rowcolor === '#363636' ? '#D0D0D0' : '#F0F0F0') . ';padding: 10px;border:2px solid #777777;border-radius: 3px; min-width: 288px; max-width: 500px;overflow:auto;'; // dont set background color here, it will be set depending on what value is returned
    $styles_headerTd = 'padding 10px; border: 2px solid #999999; border-radius: 3px; min-width: 288px; max-width: 500px;';
    // get embedly json string and decode it
    $decodedEmbedlyData = json_decode($embedlyData, TRUE);
    // create OMDB api request url
    //$sdmcore->sdmCoreSdmReadArray($decodedEmbedlyData);
    $omdbApiRequestUrls = array(
        'http://www.omdbapi.com/?t=' . str_replace(array(' ', '_', '+'), '-', urlencode((isset($decodedEmbedlyData['title']) ? $decodedEmbedlyData['title'] : 'null'))),
        'http://www.omdbapi.com/?t=' . str_replace(array(' ', '_', '+'), '-', urlencode((isset($decodedEmbedlyData['title']) ? $decodedEmbedlyData['title'] : 'null'))) . '&type=movie',
        'http://www.omdbapi.com/?t=' . str_replace(array(' ', '_', '+'), '-', urlencode((isset($decodedEmbedlyData['title']) ? $decodedEmbedlyData['title'] : 'null'))) . '&type=episode',
        'http://www.omdbapi.com/?t=' . str_replace(array(' ', '_', '+'), '-', urlencode((isset($decodedEmbedlyData['title']) ? $decodedEmbedlyData['title'] : 'null'))) . '&type=series',
    );
    // Create Header Rows
    $output = '<tr style="' . $styles_omdbDataTableRow . '"><td style="' . $styles_td . '">Provider Name : ' . (isset($decodedEmbedlyData['provider_name']) ? $decodedEmbedlyData['provider_name'] : '<b style="color:red;">UNKNOWN PROVIDER OR BAD URL | Tested URL : <i><a href="' . $testurl . '">' . $testurl . '</a></i></b>') . '</td></tr>';
    $output .= '<tr style="' . $styles_omdbDataTableRow . '"><td style="' . $styles_td . '">Movie Url : <i><a href="' . $testurl . '">' . $testurl . '</a></i></td></tr>';
    $output .= '<tr style="' . $styles_omdbDataTableRow . '"><td style="' . $styles_td . '">Movie Title : <i>' . (isset($decodedEmbedlyData['title']) ? $decodedEmbedlyData['title'] : '<b style="color:red;">Title Unknown</b>') . '</i></td></tr>';
    // Create Returned Data Rows
    foreach ($omdbApiRequestUrls as $index => $omdbApiRequestUrl) {
        $output .= '<tr style="' . $styles_omdbDataTableRow . '"><td style="' . $styles_td . '">OMDB API Request Url <i style="color:#95B9C7;">(' . ($index === 0 ? 'no type' : ($index === 1 ? 'type=movie' : ($index === 2 ? 'type=episode' : ($index === 3 ? 'type=series' : '')))) . ')</i> : <i>' . $omdbApiRequestUrl . '</i></td></tr>';
        /** OMDB TESTS * */
        // meke request
        $omdbJsonString = $sdmcore->sdmCoreCurlGrabContent($omdbApiRequestUrl);
        // add a row to show the json string that was returned
        $output .= '<tr style="' . $styles_omdbDataTableRow . '"><td style="' . $styles_td . '">Returned JSON String : <xmp style="color:orange;">' . $omdbJsonString . '</xmp></td></tr>';
        // decode the json string
        $decodedOmdbData = json_decode($omdbJsonString, TRUE);
        // BUILD DATA HEADER ROW //
        $output .= '<tr style="' . $styles_omdbDataTableRow . '">';
        // add header row with main array keys as column values
        foreach ($decodedOmdbData as $keyname => $keyvalue) {
            $output .= '<td style="' . $styles_headerTd . '">' . $keyname . ' (type : <i>' . gettype($keyvalue) . '</i>)</td>';
        }
        $output .= '</tr>';
        // BUILD DATA ROW //
        $output .= '<tr style="' . $styles_omdbDataTableRow . '">';
        // add omdb data to table
        foreach ($decodedOmdbData as $key => $value) {
            switch (is_array($value)) {
                case TRUE:
                    if (empty($value)) {
                        $output .= '<td style="' . $styles_td . 'background:darkred;"><div style="height:200px;overflow:auto;">' . (is_null($value) || $value === '' || $value === 'N/A' || $value === 'Null' || $value === 'null' || $value === 'False' || $value === 'Movie not found!' ? '<i style="color:aqua">null</i>' : '<i style="color:aqua">EMPTY ARRAY</i>') . '</div></td>';
                    } else {
                        $output .= '<td style="' . $styles_td . '"><div style="height:200px;min-width:420px;overflow:auto;">' . (is_null($value) || $value === '' || $value === 'N/A' || $value === 'Null' || $value === 'null' || $value === 'False' || $value === 'Movie not found!' ? '<i style="color:aqua">null</i>' : omdbArrayToList($value, $key, $rowcolor)) . '</div></td>';
                    }
                    break;
                default:
                    $output .= '<td style="' . $styles_td . (is_null($value) || $value === '' || $value === 'N/A' || $value === 'Null' || $value === 'null' || $value === 'False' || $value === 'Movie not found!' ? 'background: red;' : '') . '"><div style="height:200px;overflow:auto;">' . (is_null($value) || $value === '' || $value === 'N/A' || $value === 'Null' || $value === 'null' ? '<i style="color:aqua">null</i>' : (is_bool($value) === TRUE ? ($value === TRUE ? '<b style="color:green">TRUE</b>' : '<b style="color:red">FALSE</b>') : (substr($value, 0, 7) === 'http://' || substr($value, 0, 8) === 'https://' || substr($value, 0, 4) === 'www.' ? '<a href="' . $value . '">' . $value . '</a>' : $value))) . '</div></td>';
                    break;
            }
        }
        $output .= '</tr>';
        // END BUILD DATA ROW //
    }
    return $output;
}

/**
 * Build an html table populated with the data returned from embedly for the tested movie urls.
 * @param array $movieUrls An array of movie urls to test.
 * @return string <p>An HTML table populated with the data returned from embedly for the tested urls.</p>
 */
function buildOmdbDataTable($movieUrls) {
    $apikey = 'e5a1ffbf4c3b49cb83ae4b8832a9469e';
    $embedlyUrl = 'http://api.embed.ly/1/extract?key=' . $apikey . '&url=';
    $embedlyDataTable = '';
    // get accsess to sdm core
    $sdmcore = new SdmCore();
    // only show omdb data if form was submitted or we are in test mode, i.e., if $movieUrls is empty do NOT display omdb data table
    if (!empty($movieUrls)) {
        $styles_omdbDataTable = 'background:black;border:2px solid #767676; border-radius: 9px;';
        $embedlyDataTable .= '<table id="omdbDataTable" style="' . $styles_omdbDataTable . '">';
        $rowcolor = '#363636'; // initial row color | will alternate
        // create rows and columns of omdb data
        foreach ($movieUrls as $movieUrl) {
            $embedlyRequestUrl = $embedlyUrl . $movieUrl;
            $omdbData = $sdmcore->sdmCoreCurlGrabContent($embedlyRequestUrl, array());
            $embedlyDataTable .= assemlbeOmdbTableElements($omdbData, $rowcolor, $movieUrl);
            $rowcolor = ($rowcolor === '#363636' ? '#000000' : '#363636'); // alternate row colors
        }
        $embedlyDataTable .= '</table>';
        $embedlyDataTable .= '<h1>emalloc() MEMORY USAGE : ' . memory_get_usage() . '</h1>';
        $embedlyDataTable .= '<h1>SYSTEM MEMORY USAGE : ' . memory_get_usage() . '</h1>';
    }
    return $embedlyDataTable;
}

/**
 *
 * @return array <p>Returns an array of test movie urls</p>
 */
function getTestMovieUrls() {
    return array(
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
        // telly
        'http://telly.com/lawless-movie',
        // break
        'http://www.break.com/video/this-grandmother-literally-killed-somebody-for-eating-2834463',
        // myspace
        'https://myspace.com/busking/video/shawn-mendes/109561937',
        // metacafe
        'http://www.metacafe.com/watch/11337959/review_griffin_guitarconnect_cable_geekbeat_tips_reviews/',
        // blip tv
        'http://blip.tv/animation-lookback/animat-s-classic-reviews-the-flight-of-dragons-7170787',
        // Yahoo Screen
        'https://screen.yahoo.com/snl/hillary-cold-open-071950438.html',
        // Live Leak
        'http://www.liveleak.com/view?i=087_1426549997',
        // Dot Sub
        'https://dotsub.com/view/5af2ea32-1aa1-4fa7-9d36-b3a01e841ca2',
        // Overstream
        'http://www.overstream.net/view.php?oid=n8sfmerfi2ai',
        // Live Stream
        'http://new.livestream.com/internetsociety/codeacrossnyc2015/videos/77989113',
        // World Star Hip Hop
        'http://www.worldstarhiphop.com/videos/video.php?v=wshhPxEmq52F7O6Fgty5',
        // Bambuser
        'http://bambuser.com/v/5363167',
        // School Tube
        "http://www.schooltube.com/video/a63a8383938d488eae52/SLPS's%20College%20Summit%20Visits%20Spotlight%20News", // NOTE: Urls from this site sometimes contain a single quote, ', if urls from this site seem buggy this might have something to do with it
        // Big Think
        'http://bigthink.com/videos/jonathan-zittrain-on-net-neutrality',
        // Youku
        'http://v.youku.com/v_show/id_XOTE1MjgyOTYw.html?f=23574074&ev=1',
        // Snotr
        'http://www.snotr.com/video/15047/Meet_MILK_the_polar_bear',
        // Clip Fish
        'http://www.clipfish.de/musikvideos/video/4182500/mark-daumail-monsters/',
        // My Video
        'http://www.myvideo.de/serien/the-biggest-loser/staffel-7/folge-7-ersehnte-umstyling-m-11829869',
        // Coub
        'http://coub.com/view/5f93v',
        // Vine
        'https://vine.co/v/OPxnt0xtaMI/',
        // Tudou
        'http://www.tudou.com/albumplay/Lqfme5hSolM/H35TMH12ceA.html',
        // Mix Bit
        'https://mixbit.com/v/1Xbo51F9BxDNFQSWiHYwfv',
        // Lustich
        'http://lustich.de/bilder/andere/schmerzhaftes-tier/',
        // Reelhouse
        'https://www.reelhouse.org/v/shepard-fairey-obey-this-film?r=hp_carousel',
        // Web TV
        'http://omigu.web.tv/video/impressive-lava-in-hawaii__qyqger08tbo',
        // MyNet Sahnetv
        'http://www.mynet.com/video/hayvanlar/yetenekli-piyanist-kopek-2052234/',
        // iZLESENE
        'http://www.izlesene.com/video/hirsiz-kartal-leylegin-baligini-boyle-caldi/8284670',
        // Alkışlarla Yaşıyorum
        'http://alkislarlayasiyorum.com/icerik/251073/the-walking-dead-8-bit-olarak-ilk-2-sezon-ozeti',
        // 59 SANIYE
        'https://www.59saniye.com/karadenizli-teyzenin-kopek-sevgisi/',
        // Zie
        'http://www.zie.nl/video/algemeen/IS-eist-aanslag-Tunis-op/py9zrwrfc72w#',
        // The White House
        'https://www.whitehouse.gov/photos-and-video/video/2015/03/19/president-obama-speaks-energy-and-climate-change',
        // Hulu
        'http://www.hulu.com/watch/761665',
        // Crackle
        'http://www.crackle.com/c/seinfeld/the-fire/2483598',
        // Funny Or Die
        'http://www.funnyordie.com/articles/a6b7fb28ac/will-ferrell-debuts-as-the-new-face-of-little-debbie',
        // Vimeo
        'https://vimeo.com/ondemand/highmaintenance',
        // Ted Talks
        'https://www.ted.com/talks/andy_yen_think_your_email_s_private_think_again#',
        // National Film Board of Canada
        'https://www.nfb.ca/film/getting_started?hpen=feature_3&feature_type=film',
        // The Daily Show
        'http://thedailyshow.cc.com/videos/fsdtvk/the-snacks-of-life',
        // Yahoo Movies
        'https://www.yahoo.com/movies/v/second-best-exotic-marigold-hotel-192747616.html',
        // The Colbert Report
        'http://thecolbertreport.cc.com/videos/pjkorm/cheating-death---grimmy-s-goodbye',
        // Comedy Central
        'http://www.cc.com/video-collections/3e1lom/biatches-season-one/cjz3ay',
        // The Onion
        'http://www.theonion.com/video/onion-film-standard-oscars-edition,38051/',
        // WordpressTV
        'http://wordpress.tv/2015/02/17/michael-schroder-contributing-to-core-hassle-to-hobby/',
        // Trailer Addict
        'http://www.traileraddict.com/star-wars-episode-vii/teaser-trailer',
        // Fora TV
        'http://library.fora.tv/2012/12/05/How_Word_Gets_Out_Building_a_Movement_Behind_Stories/Authentic_Action_Does_a_Good_Idea_Need_Social_Media',
        // Spike
        'http://www.spike.com/video-clips/3o3phn/lip-sync-battle-abbi-jacobson-and-ilana-glazer-go-toe-to-toe',
        // Game Trailers
        'http://www.gametrailers.com/videos/db5cew/eve--valkyrie-gameplay-trailer',
        // PBS
        'http://video.pbs.org/video/2365418529/',
        // Zapiks
        'http://www.zapiks.com/j1-mercredi-18-mars-2015.html',
        // TruTv
        'http://www.trutv.com/shows/impractical-jokers/videos/the-permanent-punishment-full-episode.html',
        // Nz On Screen
        'http://www.nzonscreen.com/title/hairy-maclary-from-donaldsons-dairy-1997',
        // New York Magazine
        'http://nymag.com/daily/intelligencer/2013/07/subway-china-beijing-video-crowded-train.html',
        // Grind TV
        'http://www.grindtv.com/lifestyle/culture/post/new-surfmoto-adventure-film-tackles-classic-australian-road-trip/',
        // iFood TV
        'http://ifood.tv/italian/464310-italian-chicken-sammitz',
        // Logo TV
        'http://www.logotv.com/video/misc/1107755/the-straight-out-report-reacts-to-the-rupauls-drag-race-trailer.jhtml#id=1733014',
        // Lonely Planet
        'http://www.lonelyplanet.com/blog/natalietran/caribbean2/',
        // Street Fire
        'http://www.streetfire.net/video/russian-road-rage-with-car-crash-accident_2376166.htm',
        // Science Stage
        'http://sciencestage.com/v/62702/why-does-our-education-system-look-so-similar-to-the-way-it-did-50-years-ago?.html',
        // Canal Plus
        'http://www.canalplus.fr/pid3580-c-live-tv-clair.html',
        // Vevo
        'http://www.vevo.com/watch/modest-mouse/Lampshades-On-Fire/USSM21500416',
        // Aol On
        'http://on.aol.com/show/518250658.493-that-s-racist-with-mike-epps/518560853',
        // Khan Academy
        'https://www.khanacademy.org/computing/computer-science/algorithms/intro-to-algorithms/v/what-are-algorithms',
        // Veoh
        'http://www.veoh.com/watch/v20110944WgPzgFhz?h1=Goodfellas',
        // Univision
        'http://uvideos.com/shows/nuestra-belleza-latina/a-clarissa-la-traicionaron-los-nervios',
        // Muzu TV
        'http://www.muzu.tv/hozier/take-me-to-church-music-video/2054403/',
        // Box Office Buz
        'http://movies.boxofficebuz.com/video/the-avengers-age-of-ultron-trailer-2',
        // God Tube
        'http://www.godtube.com/watch/?v=KZLLZWNX',
        // Media Matters
        'http://mediamatters.org/video#watch/202974/page/1',
        // Clip Syndicate
        'http://www.clipsyndicate.com/video/playlist/8178/5670870?title=homepage_channel',
        // SRF
        'http://www.srf.ch/sport/tennis/atp-tour/federer-im-eiltempo-zum-jubilaeum',
        // MPORA
        'http://mpora.com/videos/AAe1nve6qqmk#G3IvjRp1fbYQjT5O.97',
        // Vice
        'http://www.vice.com/video/chinas-elite-female-body-guards-015',
        // Video Donor (AKA godinterest.com)
        'https://godinterest.com/post/1387145/celebrate-national-donate-life-month-2014-by-sharing-your-story',
        // Hurri Yet Tv
        'http://webtv.hurriyet.com.tr/haber/turkiye-de-gunes-tutulmasi_108971',
        // uludağ sözlük video
        'http://video.uludagsozluk.com/v/mu%C4%9Fla-%C3%BCniversitesi-130497/',
        // IGN
        'http://www.ign.com/videos/2015/03/20/mortal-kombat-every-reptile-fatality-ever',
        // Ask Men
        'http://www.askmen.com/recess/fun_lists/irish-gangster-movies.html',
        // Esri
        'http://video.esri.com/watch/3619/location-analytics',
        // Office Mix
        'https://mix.office.com/watch/2dhwh9vlfmfx',
        // Zapkolik
        'http://www.zapkolik.com/video/medcezir-65-bolum-2-fragmani-920608',
        // ESPN
        'http://espn.go.com/video/clip?id=12523072&categoryid=2378529',
        // ABC NEWS
        'http://abcnews.go.com/GMA/video/spring-off-snowy-start-29776119',
        // Washington Post
        'http://www.washingtonpost.com/posttv/politics/late-night-laughs-march-madness-edition/2015/03/20/13c26876-cf13-11e4-8730-4f473416e759_video.html',
        // Boston
        'http://www.boston.com/news/local/new-hampshire/2015/03/20/trump-eyes-presidential-race-again/leHFtga4qEysZ9nwu57dLJ/video.html',
        // CNBC
        'http://video.cnbc.com/gallery/?video=3000363354',
        // CBS NEWS
        'http://www.cbsnews.com/videos/time-lapse-fast-forward/',
        // CNN
        'http://www.cnn.com/videos/us/2015/03/19/erin-dnt-savidge-colorado-pregnant-woman-stabbed-craigslist.cnn/video/playlists/top-news-videos/',
        // CNN Edition
        'http://edition.cnn.com/videos/world/2015/03/19/orig-foster-islamic-ring-viking-sweden.cnn/video/playlists/trending-video/',
        // CNN Money
        'http://money.cnn.com/video/technology/2015/03/20/magic-leap.cnnmoney/index.html',
        // MSNBC
        'http://www.msnbc.com/msnbc/watch/barney-franks-best-moments-on-camera-415959619786',
        // NBC
        'http://www.nbcnews.com/news/us-news/mississippi-lawmaker-criticized-comments-about-african-americans-n307981',
        // FOX Sports
        'http://www.foxsports.com/video?vid=415894595821',
        // Global Post
        'http://globalpost-video.tumblr.com/post/108826877346/laws-of-men-birth-control-in-the-philippines-by',
        // The Gaurdian
        'http://www.theguardian.com/science/video/2015/mar/20/plane-passengers-solar-eclipse-from-air-murmansk-video',
        // Bravo TV
        'http://www.bravotv.com/watch-what-happens-live/season-12/episode-12053/videos?clip=2853892',
        // Discovery Channel
        'http://www.discovery.com/tv-shows/fast-n-loud/videos/ferrari-fix-part-2-1917-reo/',
        // Forbes
        'http://www.forbes.com/video/4117187745001/',
        // Fox News
        'http://video.foxnews.com/v/4123898280001/report-at-least-46-killed-100-injured-in-attacks-in-yemen/?#sp=show-clips',
        // Fox Business
        'http://video.foxbusiness.com/v/4124267840001/will-new-fracking-regulations-kill-the-industry/?playlist_id=933116651001#sp=show-clips',
        // Reuters
        'http://www.reuters.com/video/2015/03/20/biogens-alzheimers-drug-shows-early-prom?videoId=363577733&videoChannel=1&channelName=Top+News',
        // Huffington Post
        'http://videos.huffingtonpost.com/entertainment/elle-kings-secrets-to-success-going-bra-less-alcohol-and-red-bull-518720325',
        // The New York Times
        'http://www.nytimes.com/video/business/100000003538108/kodak-after-the-bankruptcy.html?playlistId=1194811622182&region=video-grid&version=video-grid-thumbnail&contentCollection=Times+Video&contentPlacement=1&module=recent-videos&action=click&pgType=Multimedia&eventName=video-grid-click',
        // Vorarlberg Online
        'http://www.vol.at/melanies-und-michaels-weltreise-erste-eindruecke-von-mosambik/4269809',
        // Spiegel Online
        'http://www.spiegel.de/video/varoufakis-aeussert-sich-zu-varoufake-video-video-1564023.html',
        // Play RTS
        'http://www.rts.ch/video/operations-speciales/6623571-selah-sue-en-showcase.html',
        // Zeit Online
        'http://www.zeit.de/video/2015-01/4018385741001/rekorder-mina-tindle-singt-taranta#autoplay',
    );
}

/**
 * Builds the OMDB Test form that allows users to submit a movie title or url and see what it returns from OMDB.
 * @return string <p>The HTML for the test form.</p>
 */
function getOmdbTestForm() {
    $sdmcore = new SdmCore();
    $omdbTesterForm = new SdmForm();
    $omdbTesterForm->form_handler = 'omdbTester';
    $omdbTesterForm->method = 'post';
    $omdbTesterForm->submitLabel = 'See What Embedly Returns For This Movie';
    $omdbTesterForm->form_elements = array(
        array(
            'id' => 'movieUrl',
            'type' => 'text',
            'element' => 'Movie Url',
            'value' => '',
            'place' => '0',
        ),
    );
    $omdbTesterForm->sdmFormBuildForm($sdmcore->sdmCoreGetRootDirectoryUrl());
    return $omdbTesterForm->sdmFormGetForm();
}

?>