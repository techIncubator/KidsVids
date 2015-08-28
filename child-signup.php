<?php
require_once 'db_init.php';
require_once 'page-constants.php';
session_start();
$_SESSION['logged_in'] = "google";
$fName = $_GET['fname'];
$email = $_GET['email'];

$errors = array();

$res = user_check();
if ($res) {
	header('Location: '.$LOGIN.'?error_msg=You%20are%20already%20registered%20on%20our%20application!%20Please%20Sign%20In.');
    exit();
}

function user_check() {
global $db;
	if ($db) {
		

		try {
			global $email;
			//check if user exists
			$q = $db -> prepare('SELECT user_name FROM users WHERE email = :email;');
			$q -> bindValue(':email', $email, PDO::PARAM_STR);
			$q -> execute();
			$check = $q -> fetch(PDO::FETCH_ASSOC);

			if (!empty($check)) {
				//if user exists then return true
				return true;
			} else {
			 //if user does not exists show him registration form ,where he can submit twitter id.
				return false;
			}
		} catch(Exception $ex) {
			print_r($ex);
			return false;
		}
	} else {
		return false;
	}

}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='css/child-signup.css' type='text/css' />
		<link rel='stylesheet' href='css/awaiting-confirmation.css' type='text/css' />
        
	</head>
	<body>
	<div id="logout-bar" >
	<div id="logo-div" style="display:inline-block;margin-left:2%;"><img src="logo.png" height="30"/></div>
	<div id="kidsvids-div" style="display:inline-block;margin-left:0.3%;">KidsVids</div>
	<div id="welcome-div" style="display:inline-block;margin-left:27%;">Welcome to KidsVids</div>
	<div id="logout-div" style="display:inline-block;margin-left:35%;"><a href="logout.php" style="text-decoration:none;color:#33ADFF;">| Logout |</a></div>
	</div>
		<div id="container">
			<div style="color:#0099FF;margin-left:16%;"><h3><?php if(isset($_GET['error_msg'])){ echo $_GET['error_msg']; }?></h3></div></br></br>
			<form action="add-user.php" method="post">

				<div class="labels">
					<label>Your Full Name: &nbsp &nbsp &nbsp &nbsp &nbsp <?php echo $fName;?></label>
				</div>

				<input type="hidden" id="inputFullname" name="fname" class="form-control" value="<?php echo $fName;?>">

				<div class="labels">
					<label>Your Email Address: &nbsp &nbsp  <?php echo $email;?></label>
				</div>

				<input type="hidden" id="inputEmail" name="email" class="form-control" value="<?php echo $email;?>">

				<div class="labels" style="display:inline-block;">
					<label>Please enter your Parent's Twitter ID:&nbsp</label>
				</div>
				<input type="text" id="inputTwitter" name="inputTwitter" class="form-control" required>
				<br>

				<input name="submit" type="submit" value="Sign up"/>

			</form>

                
		</div>
        
	</body>
</html>
