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
	$q = $db -> prepare('UPDATE users SET confirmation="denied" WHERE id=:id ;');
	$q -> bindValue(':id', $child_id, PDO::PARAM_INT);
	$result = $q -> execute();
	if($result)
	{
		echo "true";
	}
}
?>