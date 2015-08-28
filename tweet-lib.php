<?php
// print_r("Hello in tweet-lib");exit;
require_once 'twitteroauth.php';
require_once 'src/Google/autoload.php';
//session_start();

$client_id = '229991432733-adv94knte21e974sb3o01d45guunhoto.apps.googleusercontent.com';
$client_secret = 'kRT-tXik-NyNMCFErGSMsTTU';
$redirect_uri = 'http://localhost/KidsVids/';
			
// $client_id = '812409328486-p4tiosigpmfv1esje2roiei5slpr584p.apps.googleusercontent.com';
// $client_secret = '8nw92MurGAzTwzA19kCt0yZd';
// $redirect_uri = 'http://localhost/temp/KidsVids/';
$email = false;
$displayName = false;
// $key = 'AIzaSyCocVpFeXpIUoZgQCENreNdM8Z_Mi9bpKE';
$key= 'AIzaSyC8xFdP5IAaDyMIBh_0G4E5My175STVic0';

$client = new Google_Client();
$client -> setClientId($client_id);
$client -> setClientSecret($client_secret);
$client -> setDeveloperKey($key);
$client -> setRedirectUri($redirect_uri);
$client -> addScope("https://www.googleapis.com/auth/urlshortener");


$service = new Google_Service_Urlshortener($client);

// print_r("after creating services");

// define('TWITTER_CONSUMER_KEY', 'xPxPp3qZ6khsvCev5Pzq2tJ3R');
// define('TWITTER_CONSUMER_SECRET', 'noH7M5DrQixreJvKB7dU8NaJSuw8v4RQR4aYpgy6XOeGX8ShOJ');
// define('TWITTER_OAUTH_CALLBACK', 'http://10.0.13.58/KidsVids/main.php');



//define("TWITTER_OAUTH_CALLBACK","http://kidsvids/main.php");

// define("CONSUMER_KEY", "xxUD0ZIMJE0amVfemilrrczn4");
// define("CONSUMER_SECRET", "CjimHxJed0rJoN4jQI5JVA5rlcX451uEXDqSTjsZAvY8F8SLrX");
// define("OAUTH_TOKEN", "3070619484-rJT9MPnLasDcGK81Iwx1R80lJ88l5ymxBZXm7vr");
// define("OAUTH_SECRET", "PMxMV93aV6vEHkHUpk1CFVjMozYQ4bIULoyYHEkvOOCZy");
define("CONSUMER_KEY", "WNo0UDbrkL2TrVXD0BMjfoGlc");
define("CONSUMER_SECRET", "FgIsS3vskSbZazj8Vl1M9eC48eWx5hCM18uaxf1hoLwnPoJm8z");
define("OAUTH_TOKEN", "3319700678-rn3ljOmBpn3s7lAoDJEynZ64W1C16URlPJ30sWz");
define("OAUTH_SECRET", "kmymZXCQucuW2jfWtHWGB1ronILjXRMgpMR38HpE7qGmD");

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
// print_r($connection);exit;
 $content = $connection->get("account/verify_credentials");
// print_r($content);exit;

// print_r(CONSUMER_KEY);
// print_r(TWITTER_OAUTH_CALLBACK);exit;



// if(!isset( $_SESSION['oauth_token'] )){
// 	$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
// 	$request_token = $connection->getRequestToken(TWITTER_OAUTH_CALLBACK);
// 	$_SESSION['oauth_token'] =  $request_token['oauth_token'];
// 	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
// 	switch ($connection->http_code) {
// 		case 200:
// 			$url = $connection->getAuthorizeURL($request_token['oauth_token']);
// 			break;
// 		default:
// 			$error = 'Could not connect to Twitter. Refresh the page or try again later.';
// 	}
// }else{
	
	// $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	// $access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	// $_SESSION['access_token'] = $access_token;
	// $statues = $connection->get("statuses/home_timeline", array());
	// $content = $connection->get('account/verify_credentials');

// }


 function shortenURL($url_str){
 global $service;
 $url = new Google_Service_Urlshortener_Url();
    
 $url->longUrl = $url_str; 
  
 $short = $service->url->insert($url);
 return $short;
 }

 function tweet($msg) {

 	global $connection;
 	$result = $connection -> post('statuses/update', array("status" => $msg));
 	return $result;
 }

 function getTweetId($id) {
 	global $connection;
 	$result = $connection -> get('statuses/show', array("id" => $id));
 	return $result;
 }
 function getUserMentions()
 {
     global $connection;
 	$result = $connection -> get('statuses/mentions_timeline');
 	return $result;
 }
?>