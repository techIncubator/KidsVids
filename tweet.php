<?PHP
session_start();
include_once 'db_init.php';

include_once 'tweet-lib.php';
$twitter_tag = '';
if (isset($_SESSION['twitter_id'])) {
	global $twitter_tag;
	$twitter_tag = "@".$_SESSION['twitter_id'];
}
$user = '';
if (isset($_SESSION['user_name'])) {
	global $user;
	$user = $_SESSION['user_name'];
}
$title = $_GET['title'];
$url = $_GET['url'];

$short_url = shortenURL($url);
$short_url = $short_url->id;
$msg = $twitter_tag . " your child " . $user . " is watching " . $title ." ". $short_url;
//echo $msg;
//var_dump($short_url);
$result = tweet($msg);
?>