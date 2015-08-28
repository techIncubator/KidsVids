<?php
session_start();
require_once 'page-constants.php';
$user = '';
$twitter_id = '';
$email = '';
function session_check()
{
	if (isset($_SESSION['user_name'])) {
			
			if (isset($_SESSION['user_name'])) {
				global $user;
				$user = $_SESSION['user_name'];
			}

			
			if (isset($_SESSION['twitter_id'])) {
				global $twitter_id;
				$twitter_id = $_SESSION['twitter_id'];
			}

			
			if (isset($_SESSION['email'])) {
				global $email;
				$email = $_SESSION['email'];
			}
			return true;
	} 
	else{
		return false;
	}
}
$login_check=session_check();
if($login_check==false)
{
	header("Location: $LOGIN");
	exit();
}
?>