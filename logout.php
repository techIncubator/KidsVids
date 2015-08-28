<?php
session_start();
require_once 'page-constants.php';
if (isset($_SESSION['access_token'])) {
	session_destroy();
}
if($_SESSION['logged_in'] == "google"){
	header("Location: https://www.google.com/accounts/Logout?continue=https://appengine.google.com/_ah/logout?continue=http://10.0.1.22/temp/KidsVids/main.php");
}
/* else if($_SESSION['logged_in'] == "twitter"){
	header("Location: https://twitter.com/logout");
} */
else {
	header("Location: ".$LOGIN);
}

exit();
?>