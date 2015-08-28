<?php
include_once 'session-check.php';
$user = '';
global $user;
$user = $_SESSION['user_name'];
?>
<!doctype html>
<html>
	<head>
		<title>KidsVids</title>
		<link rel='stylesheet' href='css/search.css' type='text/css' /> 
	</head>
	<body>
	
	<div id="logout-bar" >
	<div id="logo-div" style="display:inline-block;margin-left:2%;"><img src="logo.png" height="30"/></div>
	<div id="kidsvids-div" style="display:inline-block;margin-left:0.3%;">KidsVids</div>
	<div id="welcome-div" style="display:inline-block;margin-left:27%;"><?php  echo "Welcome $user"; ?></div>
	<div id="logout-div" style="display:inline-block;margin-left:35%;"><a href="logout.php" style="text-decoration:none;color:#33ADFF;">| Logout |</a></div>
	</div>
	
	<div id="container">
		<center><div id="buttons">
			<label>
				<input id="query" type="text"/>
				<button id="search-button"  onclick="keyWordsearch()">
					Search Youtube Videos
				</button></label>
		</div></center>
		<div id="search-container"></div>
		
		<center>
		<div id="player-div">
			<div id="yt-player">
			</div>
		</div></center>
</div>
		
		<script src="apikey.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>
		<script src="https://www.youtube.com/iframe_api"></script>
		<script src="https://apis.google.com/js/client:platform.js?onload=render" async defer></script>
        <script>
			$(document).ready(function(){
				$("#logout-div").click(function(){
					gapi.auth.signOut();
				});
				
				$('#query').keypress(function(e){
				  if(e.keyCode==13)
				  $('#search-button').click();
				});
			});
		</script>
		
			
			</body>
			</html>
