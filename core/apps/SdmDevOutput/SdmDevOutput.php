<?php

$options = array(
    'incpages' => array(
        'SdmDevOutput',
    ),
);
$text = 'SDM sdm ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi. SDM sdm';
$encryptedText = $sdmcore->sdmKind($text);
$decryptedText = $sdmcore->sdmNice($encryptedText);
$output = '<p>TEXT: "' . $text . '"</p><p>ENCRYPTED TEXT: "' . htmlspecialchars(htmlentities(str_replace('<', '&lt;', $encryptedText))) . '"</p><p>DECRYPTED TEXT: "' . $decryptedText . '"</p>';
$sdmassembler->sdmAssemblerIncorporateAppOutput($sdmassembler_dataObject, $output, $options);