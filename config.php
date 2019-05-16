<?php
    require "./vendor/autoload.php";

    use Abraham\TwitterOAuth\TwitterOAuth;

    $CONSUMER_KEY = "RdNzOCAxoHHLesTHaTM4IaGBC";
    $CONSUMER_SECRET = "2WhXzIptzVvImuyNpPnZ5OaKVrILCsVgN6SVn2dQ4TSvFihoO7";

    $ACCESS_TOKEN = "773408577759408128-jRas3sNDb7xdapbrnAutqwLjtPve0ro";
    $ACCESS_TOKEN_SECRET = "mnnoqwUXzv0stLEf4kvZdlH2V5n2TjOS3Mk95OT8JSipk";

    $ID = "773408577759408128"; // Account ID

    $connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $ACCESS_TOKEN, $ACCESS_TOKEN_SECRET);
    
    $settings = array(
        'oauth_access_token' => $ACCESS_TOKEN,
        'oauth_access_token_secret' => $ACCESS_TOKEN_SECRET,
        'consumer_key' => $CONSUMER_KEY,
        'consumer_secret' => $CONSUMER_SECRET
    );
?>