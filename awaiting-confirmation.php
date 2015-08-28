<?php
require_once 'session-check.php';
include_once 'db_init.php';
include_once 'tweet-lib.php';
//print_r("hello in awaiting confirmation");exit;
 include_once 'page-constants.php';



 $denied = false;
 global $db;
 if ($db != false) {
 	global $twitter_id;
 	global $email;
 	$email = $_SESSION['email'];
 	$query = $db -> prepare("SELECT * FROM users WHERE email=:email;");
 	$query -> bindValue(':email', $email, PDO::PARAM_STR);
 	$query -> execute();
 	$result = $query -> fetch(PDO::FETCH_ASSOC);
	
 	if ($result != false) {
        
         $confirmation = $result['confirmation'];
 		if($confirmation == 'true'){
 			header("Location: ".$HOME."?error_msg=Welcome $user");
 			exit();
 		}
	
         if($confirmation == 'denied'){

             $denied = true;
         }
         else if($confirmation == 'false'){

 		//get tweet_id and parent twitter_id from the db for user whose session is set
 		$id = $result['tweet_id'];
 		$twitter_id = $result['twitter_id'];
	
 		/* //@xyz is parent twitter_id in table so split that,store only screen_name=xyz
         $screen_name=substr($twitter_id,1);
 		echo $screen_name; */
 		$screen_name = $twitter_id;
 		//get userMentions of KidsVids twitter account(i.e notifications/replies Kidsvids is tagged in) 
         $ans= getUserMentions();
         //check for possible errors
	
		
 		//multiple userMentions will be returned so loop through all of them
         for($i=0;$i<count($ans);$i++)
         {
			
 			// check for reply which is a reply to tweet id stored for user  
            // echo $ans[$i]->in_reply_to_status_id_str. " ";
           if($ans[$i]->in_reply_to_status_id_str==$id)
           {
  
 				// there can be mutiple replies to a particular tweet/tweet_id
 				// so check if reply is from the parent only by matching the twitter reply scren_name to the parent twitter_id in our table
               //  echo $ans[$i]->user->screen_name. " ". $screen_name;
               if($ans[$i]->user->screen_name==$screen_name)
               {
                   //if there exists a reply then check if reply is YES/NO 
		  
                   if(preg_match("/yes/i", $ans[$i]->text) == 1){
			  
 						//if reply is YES then update user confirmation=true
                         $query = $db->prepare('UPDATE users SET confirmation="true" WHERE email=:email');
                         $query->bindValue(":email", $email, PDO::PARAM_STR);
                         $query->execute();
                         //check if database query fails..
					
 						//since confirmation has changed to true, redirect this user to home page
                         header("Location: ".$HOME."?error_msg=Welcome $user");
 						exit();
                   }
 				  else if(preg_match("/no/i", $ans[$i]->text) == 1){
 				  //if reply is YES then update user confirmation=true
                         $query = $db->prepare('UPDATE users SET confirmation="denied" WHERE email=:email');
                         $query->bindValue(":email", $email, PDO::PARAM_STR);
                         $query->execute();
                         //check if database query fails..
 						$denied=true;
			  
 				  }
               }
           }
         }
 //		var_dump($ans);
 		//die();
 	}
     }
 		//die();
 }
?>
<!doctype html>
<html>
<head>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<link rel='stylesheet' href='css/awaiting-confirmation.css' type='text/css' />

<script>
	$(document).ready(function() {
		$("form").submit(function(event) {
			event.preventDefault();
			var tid = $("input[name=twitter-id]").val();
			$.post("update-twitterid.php", {
				"twitter-id" : tid,
				submit : "update"
			}, function(response) {
				if (response == "success") {
					$("#success").html("<b> Twitter ID successfully updated.</b>");
				} else if (response == "error") {
					$("#success").html("<b> Sorry!! Unfortunately Twitter ID failed to update.</b>");
				}
			});
			return false;
		});
	}); 
</script>

</head>
<body>
<div id="logout-bar" >
	<div id="logo-div" style="display:inline-block;margin-left:2%;"><img src="logo.png" height="30"/></div>
	<div id="kidsvids-div" style="display:inline-block;margin-left:0.3%;">KidsVids</div>
	<div id="welcome-div" style="display:inline-block;margin-left:27%;"><?php  echo "Welcome $user"; ?></div>
	<div id="logout-div" style="display:inline-block;margin-left:35%;"><a href="logout.php" style="text-decoration:none;color:#33ADFF;">| Logout |</a></div>
	</div>
<div id="container">
  
<form action="update-twitterid.php" method="post">
<label><b><?php echo " Welcome $user"; ?></b></label>
    <?php
    if($denied){
        echo "<p>Sorry your confirmation has been denied by <b>$twitter_id</b>. If this twitter id is wrong, please update the twitter id.</p>";
    } 
else { ?>
<p>KidsVids is Awaiting your Confirmation. KidsVids has sent your parent a tweet. Please ask your Parent to confirm you, only then you can Sign In.</p>

<p>Incase you wish to edit the Twitter Id Submitted:<p>
    <?php } ?>
<label>Twitter ID: </label><input type="text" name="twitter-id" value="<?php echo $twitter_id; ?>" required>

<input type="submit" name="update" value="Update">
<input type="submit" name="remind" value="Remind">
<a style="text-decoration:none;"  href="logout.php">Logout</a>

</form>
<div id="success" style="margin-left:10%;"></div>

</div>
</body>
</html> 