<?php
global $db;
if($db)
{
	$query = $db -> prepare('SELECT user_name FROM `users` WHERE id=?');
	$query->bindValue(1, $child_id, PDO::PARAM_STR);
	$query -> execute();
	if($query==true)
	{
	while($result=$query->fetch(PDO::FETCH_ASSOC)){
	$child_name=$result['user_name'];
	}
	}
	else
	{
	echo "no name set";
	}
}
?>