<?php
require_once 'db_init.php';
require_once 'parent-session-isset.php';
//get all children records for Parent on basis of Twitter_id to display children in side tabs
if($db)
{
	$query = $db -> prepare('SELECT * FROM `users` WHERE twitter_id=? AND confirmation="true"');
	$query->bindValue(1, $twitter_id, PDO::PARAM_STR);
	$query -> execute();
	if($query==true)
	{
	$z=0;
	$confirmed_children_id = array();
	while($result=$query->fetch(PDO::FETCH_ASSOC)){
	$confirmed_children_ids[$z]=$result['id'];
	$confirmed_children_names[$z]=$result['user_name'];
	$z++;
	}
	}
}
?>