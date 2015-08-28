<?php
require_once 'db_init.php';

if(!isset($_POST['category']))
{
echo  "false";
}
else
{
$category=$_POST['category'];
}


if(!isset($_POST['user_id']))
{
echo  "false";
}
else
{
$user_id=$_POST['user_id'];
}


if(!isset($_POST['option_selected']))
{
echo  "false";
}
else
{
	if($db)
	{
	$result="";
		if($_POST['option_selected']=="tillnow")
		{
			$result=tillnow();
			echo $result;
		}	
		else if($_POST['option_selected']=="pastweek")
		{
			$result=pastweek();
			echo $result;
		}
		else if($_POST['option_selected']=="pastmonth")
		{	
			$result=pastmonth();
			echo $result;
		}
		else if($_POST['option_selected']=="pastyear")
		{
			$result=pastyear();
			echo $result;
		}
		
	}
}

function myformat($unformatteddate)
{
$mydate = new DateTime($unformatteddate);
$mydate = $mydate-> format('Y-m-j');
return $mydate;
}

function getDBResults($start, $end){
global $db;
global $category;
global $user_id;
$style="text-decoration:none;";

$query = $db->prepare("SELECT * FROM video WHERE video_category=:category AND user_id=:user_id AND DATE(date_time) BETWEEN  DATE('{$start}') AND DATE('{$end}')");
$query->bindParam(':category', $category, PDO::PARAM_STR);
$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();
$str="<div><h3>Video Category:"." " .$category."</h3></div>";
$str = $str."<table border='1'><tr><td>Video Title</td><td>Video Duration (h:m:s)</td><td>Date (yyyy-mm-dd)</td></tr>";
while (($result = $query -> fetch(PDO::FETCH_ASSOC)) != false) {
	$url=$result['url'];
	$mydate=myformat($result['date_time']);
	
	$dat = new DateInterval($result['video_duration']);
	$dt=$dat->format("%h:%i:%s");
	$s = "<tr><td><a href='".$url."' target='_blank' style='".$style."'>{$result['video_title']}</a></td><td>{$dt}</td><td>{$mydate}</td></tr>";
	$str = $str . $s;
}
$str = $str . "</table>";
return $str;

}

function tillnow()
{
global $db;
global $category;
global $user_id;
$style="text-decoration:none;cursor:pointer;cursor:hand;";

$query = $db->prepare("SELECT * FROM video WHERE video_category=:category AND user_id=:user_id");
$query->bindParam(':category', $category, PDO::PARAM_STR);
$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();
$str="<div><h3>Video Category:"." " .$category."</h3></div>";
$str = $str."<table border='1'><tr><td>Video Title</td><td>Video Duration</td><td>Date (yyyy-mm-dd)</td></tr>";
while (($result = $query -> fetch(PDO::FETCH_ASSOC)) != false) {
	$url=$result['url'];
	$mydate=myformat($result['date_time']);
	$dat = new DateInterval($result['video_duration']);
	$dt=$dat->format("%h:%i:%s");
	$s = "<tr><td><a href='".$url."' target='_blank' style='".$style."'>{$result['video_title']}</a></td><td>{$dt}</td><td>{$mydate}</td></tr>";
	$str = $str . $s;
}
$str = $str . "</table>";
return $str;
}

function pastweek()
{
	$end=new DateTime('now');
	$end = $end->format('Y-m-j');
	$start=new DateTime('now');
	$start=$start->sub(new DateInterval('P7D'));
	$start=$start->format('Y-m-j');

/* $query = $db->prepare("SELECT * FROM video WHERE video_category=:category AND user_id=:user_id AND DATE(date_time) BETWEEN  DATE('{$start}') AND DATE('{$end}')");
$query->bindParam(':category', $category, PDO::PARAM_STR);
$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();
$str="<div><h2>Video Category :"." " .$category."</h2></div>";
$str = $str."<table border='1'><tr><td>Video Title</td><td>Video Duration</td><td>Watched Duration</td><td>Date</td></tr>";
while (($result = $query -> fetch(PDO::FETCH_ASSOC)) != false) {
	$s = "<tr><td>{$result['video_title']}</td><td>{$result['video_duration']}</td><td>{$result['watched_duration']}</td><td>{$result['date_time']}</td></tr>";
	$str = $str . $s;
}
$str = $str . "</table>"; */
return getDBResults($start, $end);
}

function pastmonth()
{

	$curr_date_array=getdate(); //returns date array
	$end=''.$curr_date_array['year'].'-'.$curr_date_array['mon'].'-'.$curr_date_array['mday'];
	$date = new DateTime($end);
	$date->sub(new DateInterval('P1M'));
	$start=$date->format('Y-m-d');

/* $query = $db->prepare("SELECT * FROM video WHERE video_category=:category AND user_id=:user_id AND DATE(date_time) >= DATE('{$start}') AND DATE(date_time) < DATE('{$curr_date}')");
$query->bindParam(':category', $category, PDO::PARAM_STR);
$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();
$str="<div><h2>Video Category :"." " .$category."</h2></div>";
$str = $str."<table border='1'><tr><td>Video Title</td><td>Video Duration</td><td>Watched Duration</td><td>Date</td></tr>";
while (($result = $query -> fetch(PDO::FETCH_ASSOC)) != false) {
	$s = "<tr><td>{$result['video_title']}</td><td>{$result['video_duration']}</td><td>{$result['watched_duration']}</td><td>{$result['date_time']}</td></tr>";
	$str = $str . $s;
}
$str = $str . "</table>"; */
return getDBResults($start, $end);
}


function pastyear()
{

	$curr_date_array=getdate(); //returns date array
	$end=''.$curr_date_array['year'].'-'.$curr_date_array['mon'].'-'.$curr_date_array['mday'];
	$date = new DateTime($end);
	$date->sub(new DateInterval('P1Y'));
	$start=$date->format('Y-m-d');

/* $query = $db->prepare("SELECT * FROM video WHERE video_category=:category AND user_id=:user_id AND DATE(date_time) >= DATE('{$start}') AND DATE(date_time) <= DATE('{$end}')");
$query->bindParam(':category', $category, PDO::PARAM_STR);
$query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$query->execute();
$str="<div><h2>Video Category :"." " .$category."</h2></div>";
$str = $str."<table border='1'><tr><td>Video Title</td><td>Video Duration</td><td>Watched Duration</td><td>Date</td></tr>";
while (($result = $query -> fetch(PDO::FETCH_ASSOC)) != false) {
	$s = "<tr><td>{$result['video_title']}</td><td>{$result['video_duration']}</td><td>{$result['watched_duration']}</td><td>{$result['date_time']}</td></tr>";
	$str = $str . $s;
}
$str = $str . "</table>"; */
return getDBResults($start, $end);
}

?>