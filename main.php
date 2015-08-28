<!DOCTYPE html>
<html>
	<head>
	<title>KidsVids</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel='stylesheet' href='css/main.css' type='text/css' />
		
		<meta name="google-signin-clientid" content="229991432733-adv94knte21e974sb3o01d45guunhoto.apps.googleusercontent.com" />
		<!--<meta name="google-signin-scope" content="https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email" />-->
		<meta name="google-signin-scope" content="https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email" />
		<!--<meta name="google-signin-requestvisibleactions" content="http://schema.org/AddAction" />-->
		<meta name="google-signin-cookiepolicy" content="single_host_origin" />
		<script type="text/javascript" src="signup-signin.js"></script>
		<script src="https://apis.google.com/js/client:platform.js?onload=render" async defer></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	</head>
	<body>
		
		
<div class="container">
	  <div class="row">
		 <div class="col-xs-2"></div>
		 <div class="col-xs-8" style="background:white;border-radius:15px;margin-top:7%;margin-bottom:5%;">
				<div class="row" style="margin-top:10%;">
					<div class="col-xs-5"></div>
					<div class="col-xs-2"><img style="margin-left:18%;" src="logo.png" alt="logo" height="80" width="80"></div>
					<div class="col-xs-5"></div>
				</div>
				
				<div class="row" style="margin-top:2%;">
					<div class="col-xs-5"></div>
					<div class="col-xs-2">
						<div id="kidsvids-div">
							<h2> KidsVids </h2>
						</div>
					</div>
					<div class="col-xs-5"></div>
				</div>
				
				<div class="row" style="margin-top:2%;">
					<div class="col-xs-2"></div>
					<div class="col-xs-3"><div class="left-right-label"><h3>Child</h3></div></div>
					<div class="col-xs-2"></div>
					<div class="col-xs-3"><div class="left-right-label"><h3>Parent</h3></div></div>
					<div class="col-xs-2"></div>	
				</div>
				
				<div class="row" style="margin-top:2%;">
					<div class="col-xs-1"></div>
					<div class="col-xs-4">
						<div id="signin-button-div" class="customGPlusSignIn">
							<span class="buttonText">Sign In using Google</span>
						</div>
					</div>
					<div class="col-xs-2"></div>
					<div class="col-xs-4">
						<div id="twitter-sign-in" class="customGPlusSignIn">
							<a href="twitter-signin.php" style="text-decoration:none;color:white;"><span class="buttonText">Sign In using Twitter</span></a>
						</div>
					</div>
					<div class="col-xs-1"></div>	
				</div>
				
				<div class="row" style="margin-top:2%;margin-bottom:10%;">
					<div class="col-xs-1"></div>
					<div class="col-xs-4">
						<div id="signup-button-div" class="customGPlusSignIn">
								<span class="buttonText">Sign Up using Google</span>
						</div>
					</div>
					<div class="col-xs-7"></div>
				</div>
				
				
		 <div class="col-xs-2"></div>
		 </div>
	</div>
</div>
				

        <?php include_once 'error-dialog.php'; ?>  
		
		
	</body>
</html>