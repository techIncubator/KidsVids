<?php
require_once 'db_init.php';
session_start();
$video_titles=array();
$month=$_POST['month'];
$year=$_POST['year'];
$child_id = $_POST['child_id'];

$result=bar_get_video_data();
echo $result;

function bar_get_video_data() {
global $db;
global $video_titles;
global $month;
global $year;
global $i;
global $child_id;
//if month is jan mar may july aug oct dec then 31 days
if( $month == 1 || $month == 3 || $month == 5 || $month == 7 || $month == 8 || $month == 10 || $month == 12  )
{
$lastdate=31;
}
//if month is apr june sept nov then 30 days
else if($month == 4 || $month == 6 || $month == 9 || $month == 11)
{
$lastdate=30;
}
//if month is feb..check for leap year 
else if($month == 2)
{
if (($year % 400 === 0) || (($year % 100 !== 0) && ($year % 4 === 0)))
{
$lastdate=29;
}
else
{
$lastdate=28;
}
}

	$start=''.$year.'-'.$month.'-'.'01';
	$date = new DateTime($start);
	$month=$date->format("F");
	$date->add(new DateInterval('P1M'));
	$end=$date->format('Y-m-d');

if($db != false){
	
	$query = $db -> prepare("SELECT count(id) as count, date_time FROM `video` WHERE user_id=:user_id AND DATE(date_time) >= DATE('{$start}') AND DATE(date_time) < DATE('{$end}') GROUP BY DATE(date_time) ORDER BY date_time");
	$query -> bindParam(':user_id', $child_id, PDO::PARAM_INT);
	$query -> execute();
	$arr = array(['Day', 'Videos Watched']);
	$results = array();
	while($result=$query->fetch(PDO::FETCH_ASSOC)){
		array_push($results, $result);
	}
	$j = 0;
	$results_arr_length=count($results);
	for($i=1; $i <= $lastdate; $i++) {
		
		if($j<$results_arr_length)
		{
		
			$dt = $results[$j]['date_time'];
			
			$day = date_parse($dt);
			
			$day = $day['day'];
		
			if($i == $day) {
				$arr[$i] = array($i,intval($results[$j]['count']), "<div style='padding:10px;'><div>$month ".$day."</div><b>Videos watched: ".intval($results[$j]['count'])."</b></div>");
				$j = $j + 1;
			}
			else{
				$arr[$i] = array($i,0, "$month ".$i."<br/><strong>Videos watched: 0</strong>");
			}
		 }
		else
		{
		$arr[$i] = array($i,0, "$month ".$i."<br/><strong>Videos watched: 0</strong>");
		} 
			
		
	}
	return json_encode($arr);
	
}
}
?>