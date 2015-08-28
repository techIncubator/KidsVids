<?php
require_once 'db_init.php';

$video_titles=array();
$month=$_POST['month'];
$year=$_POST['year'];

$result=get_video_data();
echo $result;

function get_video_data() {
global $db;
global $video_titles;
global $month;
global $year;
global $i;
	$start=''.$year.'-'.$month.'-'.'01';
	$date = new DateTime($start);
	$date->add(new DateInterval('P1M'));
	$end=$date->format('Y-m-d');

if($db != false){
	
	$query = $db -> prepare("SELECT count(id) as count, date_time FROM `video` WHERE user_id='27' AND DATE(date_time) >= DATE('{$start}') AND DATE(date_time) < DATE('{$end}') GROUP BY DATE(date_time) ORDER BY date_time");
	$query -> execute();
	$arr = array(['Day', 'Videos Watched']);
	$results = array();
	while($result=$query->fetch(PDO::FETCH_ASSOC)){
		array_push($results, $result);
	}
	$j = 0;
	$results_arr_length=count($results);
	for($i=1; $i <= 31; $i++) {
		
		if($j<$results_arr_length)
		{
		
			$dt = $results[$j]['date_time'];
			
			$day = date_parse($dt);
			
			$day = $day['day'];
		
			if($i == $day) {
				$arr[$i] = array($i,intval($results[$j]['count']));
				$j = $j + 1;
			}
			else{
				$arr[$i] = array($i,0);
			}
		}
		else
		{
		$arr[$i] = array($i,0);
		}
			
		
	}
	return json_encode($arr);
	
}
}
?>