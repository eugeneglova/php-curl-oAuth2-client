<?php

    require_once(dirname(__FILE__) . '/includes/config.inc.php');
    require_once(dirname(__FILE__) . '/includes/classes/HTML_Parser.class.php');
    require_once(dirname(__FILE__) . '/includes/classes/CURL.class.php');
    require_once(dirname(__FILE__) . '/includes/classes/OAuth2.class.php');

    // Requesting oAuth 2.0 access token without browser

    $auth = new OAuth2(
        CLIENT_ID,
        REDIRECT_URI,
        CLIENT_SECRET,
        GMAIL_ACCOUNT,
        GMAIL_PASSWORD
    );

    $token = $auth->get_token()->access_token;

    // Calling a Google API

    $curl = new CURL;
    echo $response = $curl->exec('https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $token);

?>