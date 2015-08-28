<?php
// print_r("hello");
require_once 'db_init.php';
require_once 'tweet-lib.php';
 require_once 'page-constants.php';
 $_SESSION['logged_in'] = "google";
 $errors = array();
 if (isset($_POST['submit'])) {
 	$res = add_user();
 	if ($res) {
 		header('Location: '.$LOGIN.'?error_msg=You%20are%20now%20registered%20successfully%20on%20our%20application!%20Please%20Sign%20In.');
	
 	} else {
 		array_push($errors, "sorry some error has occurred");
 		header('Location: '.$LOGIN.'?error_msg=You%20are%20already%20registered%20on%20our%20application!%20Please%20Sign%20In.');
 	}
 }
 // Insert a User who has registered in User table
 function add_user() {

//     // fetch data from form fields
 	$fName = $email = $twitter = false;
 	if (isset($_POST['fname']) && isset($_POST['email']) && isset($_POST['inputTwitter'])) {
 		$fName = $_POST['fname'];
 		$email = $_POST['email'];
 		$twitter = $_POST['inputTwitter'];
 	}
 	global $db;
 	if ($db != false) {

 		if ($fName != false) {
 			// need to check that user does not already exist
 			$st = $db -> prepare("SELECT * FROM users where email=:email;");
 			$st -> bindParam(':email', $email, PDO::PARAM_STR);
 			$st -> execute();
	
 			// if user email does not exists in table
 			if (($result = $st -> fetch(PDO::FETCH_ASSOC)) == false) {
 				$result = $db -> exec('INSERT INTO users(user_name, email, twitter_id, confirmation) VALUES("' . $fName . '","' . $email . '","' . $twitter . '","false");');
 				if ($result) {
 					$msg = "@".$twitter . ", " . $fName . " wants you to confirm him/her as your child. Please reply Yes or No to this tweet.";
	
// 					//post tweet for asking parents confirmation
 					$result = tweet($msg);
                   
// 					// fetch the posted tweet id
// 					//if posted tweet id is not empty store it in database for that child
 					if(property_exists($result, 'id_str'))
 					{
 						$id = $result -> id_str;
 						$q = $db -> prepare('UPDATE users SET tweet_id=:id WHERE email=:email;');
 						$q -> bindValue(':id', $id, PDO::PARAM_STR);
 						$q -> bindValue(':email', $email, PDO::PARAM_STR);
 						$ans = $q -> execute();
 						if ($ans) {
 						//return true if user is inserted and tweet id is updated in table(i.e user is successfully registered but awaits confirmation). 
// 						//Redirect user to main.php and ask him to sign in.
 							return true;
 						}
						
 					}
 					else
 					{
 					//couldn't fetch tweet id since tweet was not posted
 					}
					
				

 				}
 			}
 			else {
 				// if user email exists in table already
 			}
 		}
 	}
 	return false;
 }
  mysql_close($con);
?>