<?php
require_once 'db_init.php';
global $db;

if(isset($_POST['child_id']))
{
$child_id=$_POST['child_id'];
}
else
{
echo "false";
}

if($db)
{
	$q = $db -> prepare('UPDATE users SET confirmation="true" WHERE id=:id ;');
	$q -> bindValue(':id', $child_id, PDO::PARAM_INT);
	$result = $q -> execute();
	if($result) {
		$q = $db -> prepare('SELECT u.*, count(video_title) AS vid_count FROM users u, video v WHERE u.id=v.user_id AND u.id=:id ;');
		$q -> bindValue(':id', $child_id, PDO::PARAM_INT);
		$q -> execute();
		
		if (($result = $q->fetch(PDO::FETCH_ASSOC)) != false ) { ?>
			<a href="pie.php?id=<?php echo $result['id'];?>"><div style="border:1px solid #B2B2B2;border-radius:5px;background:#E6E6E6;padding:5%;margin-bottom:3%;">
					<div class="row">
					<div class="col-md-2"></div>
					<div class="col-xs-4">Child Name:</div>
					<div class="col-xs-5"><?php echo $result['user_name'];?></div>
					</div>
					<div class="row" style="margin-top:2%;">
					<div class="col-md-2"></div>
					<div class="col-xs-4">Child Email:</div>
					<div class="col-xs-5"><?php echo $result['email'];?></div>
					</div>
					<div class="row" style="margin-top:2%;">
					<div class="col-md-2"></div>
					<div class="col-xs-4">Total Videos Watched:</div>
					<div class="col-xs-5"><?php echo $result['vid_count'];?></div>
					</div>
					<div class="row" style="margin-top:6%;">
					<div class="col-xs-3"></div>
					<div class="col-xs-6" style="color:black;">Click to view <?php echo $result['user_name']."'s";?> dashboard.</div></div>
				 </div></a>
		<?php	
		}
	
	}
	
	
}
?>
