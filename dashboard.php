<?php
require_once 'db_init.php';
session_start();

$child_id = $_GET['id'];
// default pie chart data for last month
include_once 'pie-data.php';

//default bar chart data for last month
include_once 'barchart-data.php';
?>
<html>
	<head>
		<title>KidsVids</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel='stylesheet' href='css/search.css' type='text/css' /> 


    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	
<!--default pie chart for last month-->	
    <script type="text/javascript">

      				// Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(pie_draw_chart1);

function pie_draw_chart1() {
var categories=<?php echo json_encode($categories); ?>;
	var count =  <?php echo json_encode($video_count_per_category); ?>;
	pie_draw_chart2(categories, count, "pastmonth", "Past month");
	}

	function pie_draw_chart2(categories, count, option_selected, pie_title) {
		var data = new google.visualization.DataTable();
		data.addColumn('string', 'Category');
		data.addColumn('number', 'Count');
		data.addRows(categories.length);
		for (var i = 0; i < categories.length; i++) {
			data.setCell(i, 0, categories[i]);
			data.setCell(i, 1, count[i]);
		}
		// Create and draw the visualization.
		var t = "Percentage of videos watched per category - " + pie_title;

		var chart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
		chart.draw(data, {
			title : t,
			width : "400",
			height : "300"
		});

	}
 </script>
 
 
 <!--default bar chart for last month-->
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

				var month =  "<?php echo $month; ?>";
		var year="<?php echo $year; ?>";
			$("#selection_month").val(month);
			$("#selection_year").val(year);
			bar_draw_chart2(data,month,year);
			}

			function bar_draw_chart2(value,month,year) {
			value[0].push({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
			var data = google.visualization.arrayToDataTable(value);

			var options = {
			title: 'Videos watched per day for - '+month +' ' +year,
			legend: { position: 'right' },
			hAxis: {title: 'Days of the month',  titleTextStyle: {color: '#004411'}},
			vAxis: {title: 'Number of videos watched',  titleTextStyle: {color: '#004411'}},
			tooltip: {isHtml: true}
			};

			var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

			chart.draw(data, options);
			}
	</script>
  </head>
	
	<body>
	
	<div id="logout-bar" >
	<div id="logo-div" style="display:inline-block;margin-left:2%;"><img src="logo.png" height="30"/></div>
	<div id="kidsvids-div" style="display:inline-block;margin-left:0.3%;">KidsVids</div>
	<div id="welcome-div" style="display:inline-block;margin-left:27%;"><?php echo "Welcome " . $_SESSION['name']; ?></div>
	<div id="logout-div" style="display:inline-block;margin-left:35%;"><a href="logout.php" style="text-decoration:none;color:#33ADFF;">| Logout |</a></div>
	</div>
	
	
	
	<div class="container">
   <div class="row">
	 <div class="col-md-12" style="background:white;border-radius:15px;margin-top:3%;">
		 
			 <div class="row" style="margin-top:3%;margin-bottom:3%;">
			 <div class="col-md-1"></div>
			 <div class="col-md-5">
					<!--Div that will hold the pie chart-->
					<a href="pie.php?id=<?php echo $child_id; ?>"><div id="pie_chart_div" style="border:1px solid grey;border-radius:5px;padding:5%;margin-bottom:3%;"></div></a>
			 </div>
			  <div class="col-md-5">
				<!--Div that will hold the bar chart-->
					<a href="barchart.php?id=<?php echo $child_id; ?>"><div id="chart_div" style="width: 480px; height: 350px;border:1px solid grey;border-radius:5px;padding:5%;margin-bottom:3%;"></div></a>
			 </div>
			 </div>
		
		
	 </div>       
   </div> 
	</body>
</html>