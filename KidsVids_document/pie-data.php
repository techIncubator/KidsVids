<?php
require_once 'db_init.php';
$categories = array();
$video_count_per_category = array();

pie_get_video_data();

function pie_get_video_data() {
	global $db;
	global $categories;
	global $video_count_per_category;
	global $child_id;

	$curr_date_array = getdate();
	//returns date array
	$end = '' . $curr_date_array['year'] . '-' . $curr_date_array['mon'] . '-' . $curr_date_array['mday'];
	$date = new DateTime($end);
	$date -> sub(new DateInterval('P1M'));
	$start = $date -> format('Y-m-d');

	$query = $db -> prepare("SELECT count(id) as cat_count, video_category FROM video WHERE user_id=:user_id AND DATE(date_time) >= DATE('{$start}') AND DATE(date_time) < DATE('{$end}') GROUP BY video_category ORDER BY cat_count DESC;");
	$query -> bindParam(':user_id', $child_id, PDO::PARAM_INT);
	$query -> execute();
	$i = 0;
	while (($result = $query -> fetch(PDO::FETCH_ASSOC)) != false) {
		$category = $result['video_category'];
		$categories[$i] = $category;
		$video_count_per_category[$i] = $result['cat_count'];
		$i++;

	}

}//function
?>