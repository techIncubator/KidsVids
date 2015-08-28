<?php
require_once 'db_init.php';
session_start();

if(isset ($_POST['child_id']))
{
	$child_id = $_POST['child_id'];
}


if (!isset($_POST['value'])) {
	echo "false";
} else {
	if ($db) {
		$result = false;
		if ($_POST['value'] == "tillnow") {
			$result = tillnow();
		} else if ($_POST['value'] == "pastweek") {
			$result = pastweek();
		} else if ($_POST['value'] == "pastmonth") {
			$result = pastmonth();
		} else if ($_POST['value'] == "pastyear") {
			$result = pastyear();
		}
		echo json_encode($result);
	}
}

function tillnow() {
	global $db;
	global $child_id;
	$categories = array();
	$video_count_per_category = array();

	//select all video categories watched by a particular child and store it in 'categories array' but fetch these categories in descending order of count
	$query = $db -> prepare("SELECT count(id) as cat_count, video_category FROM video WHERE user_id=:user_id GROUP BY video_category ORDER BY cat_count DESC");
	$query -> bindParam(':user_id', $child_id, PDO::PARAM_INT);
	$query -> execute();
	$i = 0;

	while (($result = $query -> fetch(PDO::FETCH_ASSOC)) != false) {
		$category = $result['video_category'];
		$categories[$i] = $category;
        $video_count_per_category[$i] = $result['cat_count'];
		$i++;
	}

	
	$result_arr = array('categories' => $categories, 'frequency' => $video_count_per_category);
	return $result_arr;
}

function getdata($start, $end, $userid) {
    global $db;
    $query = $db -> prepare("SELECT count(id) as cat_count, video_category FROM video WHERE user_id=:user_id AND DATE(date_time) >=  DATE('{$start}') AND DATE(date_time)< DATE('{$end}') GROUP BY video_category ORDER BY cat_count DESC  ");
    $query -> bindParam(':user_id', $userid, PDO::PARAM_INT);
	$query -> execute();
	$i = 0;
	$categories = array();
	$video_count_per_category = array();
	while (($result = $query -> fetch(PDO::FETCH_ASSOC)) != false) {
		$categories[$i] = $result['video_category'];
        $video_count_per_category[$i] = $result['cat_count'];
		$i++;
	}


	$result_arr = array('categories' => $categories, 'frequency' => $video_count_per_category, 'from' => $start, 'end' =>$end);
	return $result_arr;

}

function pastweek() {
    global $child_id;
	global $db;
	$end = new DateTime('now');
	$end = $end -> format('Y-m-j');
	$start = new DateTime('now');
	$start = $start -> sub(new DateInterval('P7D'));
	$start = $start -> format('Y-m-j');
	return getdata($start, $end, $child_id);
}

function pastmonth() {
	global $db;
    global $child_id;
	$curr_date_array = getdate();
	//returns date array
	$end = '' . $curr_date_array['year'] . '-' . $curr_date_array['mon'] . '-' . $curr_date_array['mday'];
	$date = new DateTime($end);
	$date -> sub(new DateInterval('P1M'));
	$start = $date -> format('Y-m-d');
	return getdata($start, $end, $child_id);
}

function pastyear() {
	global $db;
    global $child_id;
	$curr_date_array = getdate();
	//returns date array
	$end = '' . $curr_date_array['year'] . '-' . $curr_date_array['mon'] . '-' . $curr_date_array['mday'];
	$date = new DateTime($end);
	$date -> sub(new DateInterval('P1Y'));
	$start = $date -> format('Y-m-d');
	return getdata($start, $end, $child_id);
}
