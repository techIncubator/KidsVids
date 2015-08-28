<?php
//require_once 'twitteroauth.php';
require_once('twitteroauth/twitteroauth.php');
require_once 'db_init.php';

session_start();
//define("CONSUMER_KEY", "xxUD0ZIMJE0amVfemilrrczn4");
//define("CONSUMER_SECRET", "CjimHxJed0rJoN4jQI5JVA5rlcX451uEXDqSTjsZAvY8F8SLrX");
//define('OAUTH_CALLBACK', 'http://localhost/temp/KidsVids/twitter-signin.php');

define("CONSUMER_KEY", "WNo0UDbrkL2TrVXD0BMjfoGlc");
define("CONSUMER_SECRET", "FgIsS3vskSbZazj8Vl1M9eC48eWx5hCM18uaxf1hoLwnPoJm8z");
define('OAUTH_CALLBACK', 'http://10.0.10.17/KidsVids/twitter-signin.php');
if(!isset($_SESSION['access_token']))
{
header("Location: main.php");
}
else{
$access_token=$_SESSION['access_token'];

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
			$params =array();
			$params['include_entities']='false';
			$content = $connection->get('account/verify_credentials',$params);

			if($content && isset($content->screen_name) && isset($content->name))
			{
				$_SESSION['name']=$content->name;
				$_SESSION['image']=$content->profile_image_url;
				$_SESSION['twitter_id']=$content->screen_name;
			}
}

require_once 'parent-session-isset.php';
//get all children records for Parent on basis of Twitter_id to display a list of children to Parent where confirmation=true&false
if($db)
{
	$query = $db -> prepare('SELECT * FROM `users` WHERE twitter_id=? AND (confirmation="true" OR confirmation="false")');
	$query->bindValue(1, $twitter_id, PDO::PARAM_STR);
	$query -> execute();
	if($query==true)
	{
	$i=0;
	$children = array();
	$child_names=array();
	$child_emails=array();
	$child_confirmations=array();
	$total_video_count=array();
	$child_ids=array();
	while($result=$query->fetch(PDO::FETCH_ASSOC)){
	//$child = array('name'=>$result['user_name'], 'email'=>$result['user_email'], 'id'=>$result['id']);
	//$children.push($child);
	$child_names[$i]=$result['user_name'];
	$child_emails[$i]=$result['email'];
	$child_ids[$i]=$result['id'];
	$child_confirmations[$i]=$result['confirmation'];
	// calculate total videos watched for each child
	$queryy = $db -> prepare("SELECT count(*) as total_video_count FROM `video` WHERE user_id=:user_id");
	$queryy -> bindParam(':user_id',$child_ids[$i], PDO::PARAM_INT);
	$queryy -> execute();
	while($result=$queryy->fetch(PDO::FETCH_ASSOC)){
	$total_video_count[$i]=$result['total_video_count'];
	}
	$i++;
	
	}
	}
}
if($i <= 0){
 header("Location: nochild.php");
 exit();
}
require_once 'parent-side-tabs-data.php';
$truestr = "";
$falsestr = "";
$leftlist = "";
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
			<div class="col-xs-1"></div>
			<!-- side tabs --> 
			<?php require_once 'parent-side-tabs-html.php';?>
			
			<div class="col-xs-8" style="margin-top:3%;">
			
			
			<div class="row" style="margin-top:3%;margin-bottom:3%;">
					 
					 <div class="col-xs-11">
						
						<!--Div that will hold the children list-->
						
						<?php //if i=0 that means no child 
								
								
								if($i <= 0) { ?>
								<div><b>Sorry you do not have your children registered here on this site.<b></div>
								
								<?php } 
								
								else{
								
								 for($j=0;$j<$i;$j++){
								 
								 
								 if($child_confirmations[$j]=="true")
								{ 
									global $truestr;
									$truestr = $truestr.'<a href="pie.php?id='.$child_ids[$j].'"><div style="border:1px solid #B2B2B2;border-radius:5px;background:#E6E6E6;padding:5%;margin-bottom:3%;">'.
									'<div class="row">'.
									'<div class="col-xs-2"></div>'.
									'<div class="col-xs-4">Child Name:</div>'.
									'<div class="col-xs-5">'.$child_names[$j].'</div></div>'.
									'<div class="row" style="margin-top:2%;">'.
									'<div class="col-xs-2"></div>'.
									'<div class="col-xs-4">Child Email:</div>'.
									'<div class="col-xs-5">'.$child_emails[$j].'</div></div>'.
									'<div class="row" style="margin-top:2%;">'.
									'<div class="col-xs-2"></div>'.
									'<div class="col-xs-4">Total Videos Watched:</div>'.
									'<div class="col-xs-5">'.$total_video_count[$j].'</div></div>'.
									'<div class="row" style="margin-top:6%;">'.
									'<div class="col-xs-3"></div>'.
									'<div class="col-xs-6" style="color:black;">Click to view '.$child_names[$j].'\'s dashboard.</div></div>'.
								 '</div></a>';
								}
								 else if($child_confirmations[$j]=="false"){ 
								 $falsestr = $falsestr.'<div id="await_confirm_div" class="await_conf">'.
									'<div  style="border:1px solid #B2B2B2;border-radius:5px;background:#E6E6E6;padding:5%;margin-bottom:3%;">'.
									'<div class="row">'.
									'<div class="col-xs-2"></div>'.
									'<div class="col-xs-4">Child Name:</div>'.
									'<div class="col-xs-5">'.$child_names[$j].'</div>'.
									'</div>'.
									'<div class="row" style="margin-top:2%;">'.
									'<div class="col-xs-2"></div>'.
									'<div class="col-xs-4">Child Email:</div>'.
									'<div class="col-xs-5">'.$child_emails[$j].'</div>'.
									'</div>'.
									'<div class="row" style="margin-top:4%;">'.
									'<div class="col-xs-3"></div>'.
									'<div class="col-xs-7"><b>This user is awaiting confirmation.</b></div>'.
									'<div class="col-xs-2"></div>'.
									'</div>'.
									'<div class="row" style="margin-top:2%;">'.
									'<div class="col-xs-2"></div>'.
									'<div class="col-xs-10">Do you confirm '.$child_names[$j].' to be your child?'.
									'<input type="button" data-id="'.$child_ids[$j].'" value="YES" style="margin-left:2%;">'.
									'<input type="button" data-id="'.$child_ids[$j].'" value="NO" style="margin-left:2%;">'.
									'</div>'.
									'</div>'.
								'</div>'.
								'</div>';
								}
							}
							?>
							<div id="confirmed">
							<?php echo $truestr; ?>
							</div>
							<div id="unconfirmed">
								<?php echo $falsestr; ?>
							</div>
							
							
									 <script>
										 //if parent confirms child clicks YES
										 $(document).ready(function(){
													$("#unconfirmed input[value=YES]").click(function() {
													var curr_element = $(this);
													var id = $(this).attr("data-id");
														$.ajax({
																	type : "POST",
																	url : "parent-yes-confirmation.php",
																	data : "child_id="+id,
																	success : function(result) {
																		if(result != false)
																		{
																			var d = curr_element.parents(".await_conf");
																			d.remove();
																			
																			$("#confirmed").append(result);
																			
																			//alert();
																			/* $("").remove(); */
																		}
																	}
																});
													});
													
													$("#unconfirmed input[value=NO]").click(function() {
													var curr_element = $(this);
													var id = $(this).attr("data-id");
														$.ajax({
																	type : "POST",
																	url : "parent-no-confirmation.php",
																	data : "child_id="+id,
																	success : function(result) {
																		if(result)
																		{
																			var d = curr_element.parents(".await_conf");
																			d.remove();
																		}
																	}
																});
													});
										});
									 </script>
											
						<?php
							 }
							 
						?>
				
					 </div>
					<div class="col-xs-1"></div>
			</div>
	
		</div>       
   </div>
   	</div>
	
	</div>
</body>
</html>		