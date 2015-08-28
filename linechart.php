<?php
require_once 'db_init.php';
$video_titles=array();
$month="";
$year="";

$video_count_per_day=get_video_data();

function get_video_data() {
global $db;
global $video_titles;
global $month;
global $year;
global $i;
	$curr_date_array=getdate(); //returns date array
	$end=''.$curr_date_array['year'].'-'.$curr_date_array['mon'].'-'.'01';
	$date = new DateTime($end);
	$date->sub(new DateInterval('P1M'));
	$month=$date->format('F');
	$year=$date->format('Y');
	$start=$date->format('Y-m-d');

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
<html>
  <head>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript"
          src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }"></script>

    <script type="text/javascript">
      google.setOnLoadCallback(drawChart);

	  function drawChart() {
	  var data = <?php echo $video_count_per_day; ?>;
	  var month="<?php echo $month;?>";
	  var year="<?php echo $year;?>";
	  $("#selection_month").val(month);
	  $("#selection_year").val(year); 
	  drawChart2(data,month,year);
	  }
	  
      function drawChart2(value,month,year) {
        var data = google.visualization.arrayToDataTable(value);

        var options = {
          title: 'Videos watched per day for - '+month +' ' +year,  
          legend: { position: 'right' },
		  hAxis: {title: 'Days of the month',  titleTextStyle: {color: '#004411'}},
		  vAxis: {title: 'Number of videos watched',  titleTextStyle: {color: '#004411'}},
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>
	
<link rel='stylesheet' href='jquery-ui.css'> 
<style>
			.ui-datepicker-calendar {
    display: none;
}
.ui-datepicker-header, .ui-widget-header{
	background:#66C2FF; 
	border:#66C2FF;
}
#ui-datepicker-div{
width:230px;
}
.ui-state-hover{
background:#99D6FF;
color:black;
}

</style>
  </head>
 
 <body>
 <div>
 <div id="chart_div" style="width: 650px; height: 240px;"></div>
 <br><br>	<label for="month">Select month: </label>
<input type="text" id="month" name="month" class="monthPicker" readonly> </div>
<div id="somewhere"></div>
<script src='//code.jquery.com/jquery-1.11.2.min.js'></script> 
<script src='jquery-ui.js'></script> 

<script>
var month;
var year;

$(function() {
 $('.monthPicker').datepicker({
  changeMonth: true,
  changeYear: true,
  showButtonPanel: true,
  dateFormat: 'MM yy'
 }).focus(function() {
  var thisCalendar = $(this);
  $('.ui-datepicker-calendar').detach();
  $('.ui-datepicker-close').click(function() {
   month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
   year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
  
  var month_array = [ "January", "February", "March", "April", "May", "June", 
               "July", "August", "September", "October", "November", "December" ];
var month_in_words=month_array[month];
   thisCalendar.datepicker('setDate', new Date(year, month, 1));
   month++;
  
   $.ajax({
                       type: "POST",
                       url: "linechart-selection.php",
                       data: "month="+month+"&year="+year,
                      success: function(result){
					 
					   var obj=JSON.parse(result);
						  drawChart2(obj,month_in_words,year);
						
					  }
                     });       
  });
 });
});

</script>
  </body>
</html>