<?php
require_once 'db_init.php';
include_once 'pie-selection.php';

// check if child id is passed on to this page
$child_id= false;
if(isset($_GET['id']))
{
$child_id = $_GET['id'];
}
else
{
echo "false";
}


// default pie chart data for last month
//include_once 'pie-data.php';


$result= tillnow();
$categories = $result['categories'];
$video_count_per_category = $result['frequency'];

//get data for side tabs
require_once 'parent-side-tabs-data.php';

?>
<html>
  <head>
  <style>
  
  
  </style>
    <!--Load the AJAX API-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" href='css/mybootstrap.css' type='text/css' />
	<link rel='stylesheet' href='css/search.css' type='text/css' /> 
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker3.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified JavaScript -->

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	
	<script src="js/jquery-ui.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">

      				// Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(pie_draw_chart1);

function pie_draw_chart1() {
var categories=<?php echo json_encode($categories); ?>;
	var count =<?php echo json_encode($video_count_per_category); ?>;
	pie_draw_chart2(categories, count, "tillnow", "Till Now");
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
			width : "600",
			height : "400"
		});

		google.visualization.events.addListener(chart, 'select', selectHandler);
		function selectHandler() {
			var selection = chart.getSelection();
			if (selection.length) {
				var pieSliceLabel = data.getValue(selection[0].row, 0);

				$.post("video-info.php", {
					'category' : pieSliceLabel,
					'user_id' : <?php echo $child_id;?>,
					'option_selected' : option_selected
				}, function(response) {
					$("#pie_detail").html(response);
				});

			}
		}

	}
 </script>
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
			<div class="col-xs-12">
				 <ul class="nav nav-tabs">
					  <li role="presentation" class="active"><a href="#">Videos Per Category Chart</a></li>
					  <li role="presentation"><a href="barchart.php?id=<?php echo $child_id;?>">Videos Per Day Chart</a></li>
				 </ul><!--nav tabs -->
				</div>
			</div><!-- row for nav tabs-->
	
		 
			 <div class="row" style="margin-top:3%;margin-bottom:3%;">
					 <div class="col-xs-8">
						<!--Div that will hold the pie chart-->
						<div id="pie_chart_div"></div>
					 </div>
					<div class="col-xs-3" style="padding:2%;margin-top:10%;">
								<p>Select period for which you wish to see Pie chart</p>
								<div class="dropdown">
							  <button id="sel_title" class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true" style="width:65%;">
							  Till Now
								<span class="caret"></span>
							  </button>
							  <ul id="date-ranges" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
								<li role="presentation"><a role="menuitem" tabindex="-1" data-value="tillnow" href="#">Till now</a></li>
								<li role="presentation"><a role="menuitem" tabindex="-1" data-value="pastweek" href="#">Past week</a></li>
								<li role="presentation"><a role="menuitem" tabindex="-1" data-value="pastmonth" href="#">Past month</a></li>
								<li role="presentation"><a role="menuitem" tabindex="-1" data-value="pastyear" href="#">Past Year</a></li>
								<!--<li style="color:#B2B2B2;">__________________</li>
								<li role="presentation"><a role="menuitem" tabindex="-1" data-value="custom" href="#">Custom Range</a></li>-->
							  </ul>
							</div>
					</div>
			</div><!-- row for pie chart & datepicker -->
			
			<div class="row" style="margin-top:3%;margin-bottom:3%;">
												 
												 <script>
                                                    $("#date-ranges a").click(function() {
                                                        $("#sel_title").html($(this).html() + ' <span class="caret"></span>');
                                                    });
													$("#date-ranges a").click(function() {
														var pie_title = $(this).html();
														var option_selected = $(this).attr("data-value");
														if (option_selected == "custom") {

															$("#datepicker-from").datepicker({
																format : "MM-yyyy",
																viewMode : "months",
																minViewMode : "months",
																autoclose : true
															});
															$("#datepicker-to").datepicker({
																format : "MM-yyyy",
																viewMode : "months",
																minViewMode : "months",
																autoclose : true
															});
															$("#custom-dates").css("display", "").dialog({
																resizable : false,
																height : 300,
																width: 400,
																modal : true,
																
																
																buttons : {
																	Ok : function() {
																		//get dates here and call ajax..
																		$(this).dialog("close");
																	},
																	/*Cancel : function() {
																		$(this).dialog("close");
																	}*/
																}
															});
														}
														$.ajax({
															type : "POST",
															url : "pie-selection.php",
															data : "value=" + option_selected + "&child_id=" +<?php echo $child_id;?>,
															success : function(result) {
                                                                console.log(result);
																var obj = JSON.parse(result);
																console.log(result);
																pie_draw_chart2(obj.categories, obj.frequency, option_selected, pie_title);
																$("#pie_detail").html("");
																//$("#somewhere").html(result);
															}
														});
													});
									</script>
									<div class="col-xs-1"></div>
									<div class="col-xs-10">
										<div id="pie_detail"></div>
									</div>
			</div><!-- row for pie details chart-->
	
	
	<div id="custom-dates" style="display:none;">
	<br><br>
		<input type="hidden" autofocus="true" />
		
		<label>Select Start Date:</label>
		<div class="input-append date" id="datepicker-from" data-date="02-2012" 
         data-date-format="mm-yyyy"">
			<input  type="text" readonly="readonly" name="date-from" >	 
			<span class="add-on"><i class="icon-th"></i></span>	
			</div>
			
		
		<br><br>
		
		<label>Select End Date:</label>
		<div class="input-append date" id="datepicker-to" data-date="02-2012" 
         data-date-format="mm-yyyy">
			<input  type="text" readonly="readonly" name="date-to" >	 
			<span class="add-on"><i class="icon-th"></i></span>	
		
		</div>
  </div> 
  </div>
  </div>
  </div>
  </div>
  </body>
</html>