<?php
require_once 'db_init.php';
$video_titles=array();

get_video_data();

function get_video_data() {
global $db;
global $video_titles;

if($db != false) {
	//select all video titles watched by a particular child and store it in 'categories array'
	$query = $db -> prepare("SELECT video_title from video where user_id='27' ");
	$query -> execute();
	$i=0;
	
	while (($result = $query -> fetch(PDO::FETCH_ASSOC)) != false) {
		$title=$result['video_title'];
		$video_titles[$i]=$title;
		$i++;
	}
}
}
?>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="http://visapi-gadgets.googlecode.com/svn/trunk/wordcloud/wc.css"/>
    <script type="text/javascript" src="http://visapi-gadgets.googlecode.com/svn/trunk/wordcloud/wc.js"></script>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
  </head>
  <body>
    <div id="wcdiv" style="width:40%;margin:10% 30% 10% 30%;"></div>
    <script type="text/javascript">
      google.load("visualization", "1");
      google.setOnLoadCallback(draw_wordcloud);
	  
	  
      function draw_wordcloud() {
	  var titles=<?php echo json_encode($video_titles);?> ;
	  
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Video_Title');
		data.addRows(titles.length);
		for  (var i = 0; i < titles.length; i++)
		{
			data.setCell(i,0,titles[i]);
		}
       
        var outputDiv = document.getElementById('wcdiv');
        var wc = new WordCloud(outputDiv);
          wc.draw(data, {stopWords: 'a an and is or the of for to full more official top best new list | 1 2 3 4 5 6 7 8 9 0 11 12 13 14 15 16 17 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 2000 2001 2002 2003 2004 2005 2006 2007 2008 2009 2010 2011 2012 2013 2014 2015'});
      }
    </script>
  </body>
</html>