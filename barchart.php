<?php
require_once 'db_init.php';
session_start();

// check if child id is passed on to this page
if(isset($_GET['id']))
{
$child_id = $_GET['id'];
}
else
{
echo "false";
}

// default bar chart data for last month
include_once 'barchart-data.php';

//get data for side tabs
require_once 'parent-side-tabs-data.php';


$min_date = false;
//find min date for user so that we can limit the calender start date
if ($db) {
	$query = $db -> prepare("SELECT min(date_time) as m_date FROM `video` WHERE user_id=?");
	$query -> bindValue(1,$child_id,PDO::PARAM_INT);
	$query -> execute();
	$min_date = $query -> fetch(PDO::FETCH_ASSOC);
	if ($min_date) {
		$min_date = $min_date['m_date'];
	}
}
?>
<html>
  <head>
<!--  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->
    <script type="text/javascript"
          src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart']
            }]
          }"></script>
    <script type="text/javascript">
      						google.setOnLoadCallback(bar_draw_chart);
			function bar_draw_chart() {
	  var data = <?php echo $video_count_per_day; ?>;
		var month =   "<?php echo $month; ?>";
	var year="<?php echo $year; ?>";
	$("#selection_month").val(month);
	$("#selection_year").val(year);
	bar_draw_chart2(data,month,year);
	}

	function bar_draw_chart2(value,month,year) {
	value[0].push({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
	var data = google.visualization.arrayToDataTable(value);

	var options = {
	legend: { position: 'right' },
	hAxis: {title: 'Days of the month',  titleTextStyle: {color: '#004411'}},
	vAxis: {title: 'Number of videos watched',  titleTextStyle: {color: '#004411'}},
	tooltip: {isHtml: true}
	};

	var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

	chart.draw(data, options);
	}
	</script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href='css/mybootstrap.css' type='text/css' />
	<link rel='stylesheet' href='css/search.css' type='text/css' /> 

	<!--<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css">-->
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker3.css">
	
	<script src='//code.jquery.com/jquery-1.11.2.min.js'></script> 
	<link rel="stylesheet" href="css/jquery-ui.css">
	<script src="js/jquery-ui.js"></script>
	<!--<script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>-->
	 <script src="js/bootstrap-datepicker.js"></script>



</style>
  </head>
 
 <body>
 <?php include_once 'parent-welcome-header.php';?>
 
<div class="container">
	  <div class="row">
		 <div class="col-xs-12" style="background:white;border-radius:15px;margin-top:3%;margin-bottom:3%;">
				<div class="row" style="margin-top:2%;">
					<div class="col-xs-4"></div>
					<div class="col-xs-6">
						<?php include_once 'get-child-name.php'; ?>
						<p style="font-size:16px;">Videos watched by <b><?php echo $child_name;?></b></p>
					</div>
					<div class="col-xs-2"></div>
				</div>
			<!--display side tabs -->
			<div class="row" style="margin-bottom:3%;">
			<?php require_once 'parent-side-tabs-html.php';?>
		 
			<div class="col-xs-9" style="margin-top:3%;">
			<div class="row" style="margin-top:3%;margin-bottom:3%;">
				<div class="col-xs-12" style="background:white;">
					 <ul class="nav nav-tabs">
						  <li role="presentation"><a href="pie.php?id=<?php echo $child_id;?>">Videos Per Category Chart</a></li>
						  <li role="presentation" class="active"><a href="#">Videos Per Day Chart</a></li>
					 </ul><!--nav tabs -->
					</div>
			</div><!-- row for nav tabs-->
	
		 
			 <div class="row" style="margin-top:3%;margin-bottom:3%;">
					<div class="col-xs-2"></div>
					<div class="col-xs-8">
							<label>Videos watched per day for -</label>
							 <div class="input-append date" id="datepicker" data-date="02-2012" 
								 data-date-format="mm-yyyy"  data-date-autoclose="true" style="display:inline-block;">

							 <input  id="calendar_input" type="text" readonly="readonly" name="date" class="btn btn-default dropdown-toggle" value="<?php echo $month."-".$year;?>"></input>	
							 </div>	
					</div>
					<div class="col-xs-2" ></div>
			 </div>
			 <div class="row" style="margin-top:3%;margin-bottom:3%;">
					 <div class="col-xs-12">
						
						 <!--Div that will hold the bar chart-->
							 <div id="chart_div" style="height:400px;"></div>
					 </div>
			</div> 
	
    <script type="text/javascript">
					var month;
	var year;
	var month_in_words;
    	$("#datepicker").datepicker( {
	    format: "MM-yyyy",
	    viewMode: "months", 
	    minViewMode: "months",
		autoclose: true,
        startDate: new Date("<?php echo $min_date; ?>"),
			endDate: new Date()

			});

			$("#calendar_input").change(function(){
			data=$("#datepicker").find("input").val();
			if(data == "")
			{
			$("#calendar_input").val("<?php echo $month."-".$year;?>");
/*
			$("#custom-dates").css("display","").dialog({
			resizable: false,
			height:150,
			modal: true,
			buttons: {
			Ok: function() {
			//get dates here and call ajax..
			$( this ).dialog( "close" );
			}
			}
			}); */
			}
			else
			{
			var dt = $("#datepicker").datepicker("getDate");
			month= dt.getMonth();
			year=dt.getFullYear();
			var month_array = [ "January", "February", "March", "April", "May", "June",
			"July", "August", "September", "October", "November", "December" ];
			month_in_words=month_array[month];
			month++;
			$.ajax({
			type: "POST",
			url: "barchart-selection.php",
			data: "month="+month+"&year="+year+"&child_id="+<?php echo $child_id;?>,

			success: function(result){
			var obj=JSON.parse(result);
			bar_draw_chart2(obj,month_in_words,year);

			}
			});

			}
			});
</script>
			 
			 </div>
			 <div class="col-md-2"></div>
			</div>
	  </div>			
	</div>				
</div>
</div> 

</body>
</html>