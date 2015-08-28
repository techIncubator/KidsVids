<?php
//require_once 'twitteroauth.php';
require_once('twitteroauth/twitteroauth.php');
session_start();
define("CONSUMER_KEY", "WNo0UDbrkL2TrVXD0BMjfoGlc");
define("CONSUMER_SECRET", "FgIsS3vskSbZazj8Vl1M9eC48eWx5hCM18uaxf1hoLwnPoJm8z");
define('OAUTH_CALLBACK', 'http://10.0.10.17/KidsVids/twitter-signin.php');

// define("CONSUMER_KEY", "xxUD0ZIMJE0amVfemilrrczn4");
// define("CONSUMER_SECRET", "CjimHxJed0rJoN4jQI5JVA5rlcX451uEXDqSTjsZAvY8F8SLrX");
// define('OAUTH_CALLBACK', 'http://10.0.1.22/temp/KidsVids/twitter-signin.php');

if (isset($_REQUEST['oauth_token']) && $_SESSION['token']  !== $_REQUEST['oauth_token']) {

	// if token is old, destroy any session and redirect user to index.php
    //die("hi");
	session_destroy();
	header('Location: main.php');
	
}elseif(isset($_REQUEST['oauth_token']) && $_SESSION['token'] == $_REQUEST['oauth_token']) {

	// everything looks good, request access token
	//successful response returns oauth_token, oauth_token_secret, user_id, and screen_name
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['token'] , $_SESSION['token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	if($connection->http_code=='200')
	{
		//redirect user to twitter
		$_SESSION['status'] = 'verified';
		$_SESSION['access_token'] = $access_token;
		
		// unset no longer needed request tokens
		unset($_SESSION['token']);
		unset($_SESSION['token_secret']);
        
		header('Location: parent-home.php');
	}else{
		die("error, try again later!");
	}
		
}else{
//fresh authentication
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
	$request_token = $connection->getRequestToken(OAUTH_CALLBACK);
	
	
	/* var_dump($connection);
	die(); */
	// any value other than 200 is failure, so continue only if http code is 200
	if($connection->http_code=='200')
	{
	//received token info from twitter
	$_SESSION['token'] 			= $request_token['oauth_token'];
	$_SESSION['token_secret'] 	= $request_token['oauth_token_secret'];
	$_SESSION['logged_in'] = "twitter";
		//redirect user to twitter
		/* $twitter_url = $connection->getAuthorizeURL($request_token['oauth_token']); */
		$twitter_url = $connection->authenticateURL()."?oauth_token=".$request_token['oauth_token']."&force_login=true";
		header('Location: ' . $twitter_url); 
	}else{
	//echo($connection->http_code);
		die("error connecting to twitter! try again later!");
	}
    
}

?>