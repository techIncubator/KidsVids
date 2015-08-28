<?php
require_once 'db_init.php';
$video_titles=array();
$month="";
$year="";
$video_count_per_day=bar_get_video_data();
function bar_get_video_data() {
global $db;
global $video_titles;
global $month;
global $year;
global $child_id;

	/* $curr_date_array=getdate(); //returns date array
	$end=''.$curr_date_array['year'].'-'.$curr_date_array['mon'].'-'.'01';
	$date = new DateTime($end);
	$date->sub(new DateInterval('P1M'));
	$month=$date->format('F');
	$year=$date->format('Y');
	$start=$date->format('Y-m-d');
	$m=$date->format('m'); */
	
	$curr_date_array=getdate(); //returns date array
	$start = ''.$curr_date_array['year'].'-'.$curr_date_array['mon'].'-'.'01';
	$date = new DateTime($start);
	$month=$date->format('F');
	$year=$date->format('Y');
	$m=$date->format('m');
	$date->add(new DateInterval('P1M'));
	
	$end=$date->format('Y-m-d');
	
	
	//if month is jan mar may july aug oct dec then 31 days
if( $m == 1 || $m == 3 || $m == 5 || $m == 7 || $m == 8 || $m == 10 || $m == 12  )
{
$lastdate=31;
}
//if month is apr june sept nov then 30 days
else if($m == 4 || $m == 6 || $m == 9 || $m == 11)
{
$lastdate=30;
}
//if month is feb..check for leap year 
else if($m == 2)
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