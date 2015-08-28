<?php

require_once 'src/Google/autoload.php';
require_once 'db_init.php';
require_once 'page-constants.php';
// require_once 'google-api-php-client/autoload.php';
error_reporting(-1);
session_start();
  // echo("hello");

$_SESSION['logged_in'] = "google";
$client_id = '229991432733-adv94knte21e974sb3o01d45guunhoto.apps.googleusercontent.com';
$client_secret = 'kRT-tXik-NyNMCFErGSMsTTU';
$redirect_uri = 'http://localhost/KidsVids/';


// $redirect_uri = 'postmessage';
// print_r($redirecrt);exit;
$email = false;
$displayName = false;

$client = new Google_Client();
$client -> setClientId($client_id);
$client -> setClientSecret($client_secret);
$client -> setRedirectUri($redirect_uri);
/* $client -> addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email"); */
$client -> addScope("https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email");


$code = false;
if (isset($_GET['code'])) {
	$code = $_GET['code'];
	
	try {
		$client->setRedirectUri('postmessage');
		$client->authenticate($_GET['code']);
		$_SESSION['access_token'] = $client->getAccessToken();
		
	} catch(Exception $e) {
		 // print_r($e);exit;
		
		header("Location: " . $LOGIN);
		exit();
	 }

}




$plus = new Google_Service_Plus($client);

$errors = array();

$res = db_connection();
header("Location: $res");

function db_connection() {

	global $email;
	global $displayName;
	global $plus;
	global $client;
	global $HOME;
	global $SIGNUP;
	global $AWAIT;
	global $db;
	if ($db != false) {
		try {
			
			$me = $plus -> people -> get("me");
			$displayName = $me['displayName'];
			$email = $me['emails'][0] -> value;
			// since user has signed in check if he exists in User table
			$st = $db -> prepare("SELECT * FROM users where email=:email;");
			$st -> bindParam(':email', $email, PDO::PARAM_STR);
			$st -> execute();
			if (($result = $st -> fetch(PDO::FETCH_ASSOC)) != false) {
                $_SESSION['uid']=$result['id'];
				$_SESSION['access_token'] = $client -> getAccessToken();
				$_SESSION['twitter_id'] = $result['twitter_id'];
				$_SESSION['email'] = $result['email'];
				$_SESSION['user_name'] = $result['user_name'];
				
				$confirm = $result['confirmation'];
				if ($confirm == "true") {
				// if user exists and is confirmed in User table then show him his home page
					return $HOME;
				} else {
				// if user is exists and is not confirmed in User table the show him Awaiting confirmation page
					return $AWAIT;
				}
			} else {
			// if user does not exists in table(i.e user has not registered only) but still is trying to sign in then show him sign up page
				global $email;
				global $displayName;
				return $SIGNUP . "?email=" . $email . "&fname=" . $displayName."&error_msg=Please%20Sign%20up%20to%20use%20our%20application!";
			}
		} catch(Exception $ex) {
			// print_r($ex);
			// print_r("In connection exception");exit;

			return $SIGNUP;
		}

	} else {
		return $SIGNUP;
	}

	return $SIGNUP;
}
?>