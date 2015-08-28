<?php
global $db;
if(isset($_SESSION['twitter_id']))
{
$twitter_id=$_SESSION['twitter_id'];
}
else
{
echo "false";
}
?>