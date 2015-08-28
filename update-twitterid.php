<?php
//session_start();
require_once 'session-check.php';

require_once 'db_init.php';
require_once 'tweet-lib.php';


$errors = array();
if (isset($_POST['submit'])) {
	$res = update_twitterid();
	if ($res) {
		print "success";
	} else {
		array_push($errors, "sorry some error has occured");
		print "error";
	}
}
// function to update the new twitter id
function update_twitterid() {
	global $id;
	global $email;
	global $user;
	if (isset($_POST['submit'])) {
		if (isset($_POST['twitter-id'])) {

			$id = $_POST['twitter-id'];
		}
	}

		global $db;
	if ($db != false) {
		$msg = "";
		$count = 0;
		
			$query = $db->prepare("SELECT twitter_id FROM USERS WHERE email=:email");
			$query->bindValue(":email", $_SESSION['email'], PDO::PARAM_STR);
			$res = $query->execute();
			if(($res = $query->fetch(PDO::FETCH_ASSOC)) != false) {
				$oldid = $res['twitter_id'];
			}
		
		
		
		if($oldid == $id){
			
			$query = $db->prepare("SELECT remind_count FROM USERS WHERE email=:email");
			$query->bindValue(":email", $_SESSION['email'], PDO::PARAM_STR);
			$res = $query->execute();
			if(($res = $query->fetch(PDO::FETCH_ASSOC)) != false) {
				$count = $res['remind_count'] + 1;
			}
			$msg = "Reminder ".$count. " @".$id . ", " . $user . " wants you to confirm him/her as your child. Please reply Yes or No to this tweet.";
		}
		else{
		    $msg = "@".$id . ", " . $user . " wants you to confirm him/her as your child. Please reply Yes or No to this tweet.";			
		}
        //post tweet to new twitter id for asking parents confirmation
        $result = tweet($msg);
        if(property_exists($result,"id_str"))
        {
			//echo $id;
            //Update the old twitter id with new twitter id in database\
			$email = $_SESSION['email'];
            $st = $db -> prepare("UPDATE  users SET twitter_id=:twitter_id, tweet_id=:tweet_id, remind_count=:rcount WHERE email=:email;");
            $st -> bindParam(':twitter_id', $id, PDO::PARAM_STR);
            $st -> bindParam(':tweet_id', $result->id_str, PDO::PARAM_STR);
            $st -> bindParam(':email', $email, PDO::PARAM_STR);
			$st -> bindParam(':rcount', $count, PDO::PARAM_INT);
            $res = $st -> execute();
            // If database update is successful 
            if ($res) {
				
                $_SESSION['twitter_id'] = $id;
                return true;
            }
			
        }
    else {
		return false;
        //tweeting failed 
    }
	
	}
	return false;
}
?>