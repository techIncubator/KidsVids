<?php 
session_start();
?>
<html>
	<head>
		<title>KidsVids</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" href='css/mybootstrap.css' type='text/css' />
		<link rel='stylesheet' href='css/search.css' type='text/css' /> 
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	</head>
	<body>
	
	<?php include_once 'parent-welcome-header.php';?>

	<div class="container">
	  <div class="row">
		 <div class="col-xs-12" style="border-radius:15px;margin-top:3%;background:white;">
			<div class="row" style="margin-top:5%;">
					<div class="col-xs-3"></div>
					<div class="col-xs-6"><p><strong>Sorry !! You do not have children registered on this application.</strong></p></div>
					<div class="col-xs-3"></div>
			</div>
			<div class="row" style="margin-top:5%;">
					<div class="col-xs-5"></div>
					<div class="col-xs-2"><img src="logo.png" alt="logo" height="80" width="80"></div>
					<div class="col-xs-5"></div>
			</div>
			<div class="row" style="margin-top:1%;">
					<div class="col-xs-5"></div>
					<div class="col-xs-2" style="color:#33ADFF;"><h3>KidsVids</h3></div>
					<div class="col-xs-5"></div>
			</div>
			<div class="row" style="margin-top:5%;margin-bottom:5%;">
					<div class="col-xs-2"></div>
					<div class="col-xs-8">
					<p>KidsVids is an application which can be used to by a parent to monitor videos watched by his/her child.
					Children need to register to the app and provide parent twitter id for the same. KidsVidsApp will update the parent
					on twitter about the videos watched by the child using this app. Also parents can login to the app to view statistics 
					of the videos watched by the child. 
					<br><br>To check KidsVids on twitter <a href="https://twitter.com/KidsVidsApp/with_replies" target="_blank">click here</a>
					<p></div>
					<div class="col-xs-2"></div>
			</div>
		 </div>
					
	  </div>
	</div>       
   
</body>
</html>		