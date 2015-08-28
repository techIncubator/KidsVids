function keyWordsearch() {
	gapi.client.setApiKey('AIzaSyDcl_qryp7O2CqBR5cGoVwKBGXOzP-nQb0');
	gapi.client.load('youtube', 'v3', function() {
		makeRequest();

	});
}

function makeRequest() {
	var q = $('#query').val();
	var request = gapi.client.youtube.search.list({
		q : q,
		part : 'snippet',
		maxResults : 5

	});
	request.execute(function(response) {
		loadBody(q, response);
	});
}

function loadBody(q, response) {
	var no_res = response.pageInfo.resultsPerPage;
	var obj = response;
	$('#search-container').html("");
	for (var i = 0; i < no_res; i++) {
		var vid = obj.items[i].id.videoId;
		var title = obj.items[i].snippet.title;
		var frameid = 'Identity' + i;
		var v = obj.items[i];

		$('#search-container').append('<p class="results-item" data-id="' + vid + '"><img class="thumbnail" src="' + v.snippet.thumbnails.medium.url + '"/>&nbsp&nbsp&nbsp<a href="#sec_player">' + title + '</a></p>');
		//titles=title;
	}
	$('#search-container').append('<br><a style="margin-left:80%;" class="pagination" id="prev" data-token="' + obj.prevPageToken + '" href="#"> Prev </a> | <a class="pagination" data-token="' + obj.nextPageToken + '" id="next" href="#">Next </a>');
	$('#search-container').find(".pagination").click(function() {
		loadNextPrev(q, $(this).attr("data-token"));
	});
}

function loadNextPrev(q, token) {
	var request = gapi.client.youtube.search.list({
		q : q,
		part : 'snippet',
		maxResults : '5',
		pageToken : token
	});

	request.execute(function(response) {
		loadBody(q, response);
	});
}
var vid = false;
var player = false;
function onYouTubeIframeAPIReady() {

	$("#search-container").on("click", "p", function() {
		$("#player-div").html("");
		$("#player-div").html('<div id="yt-player"></div>');
		vid = $(this).attr("data-id");
		id = false;
		player = new YT.Player('yt-player', {
			height : '390',
			width : '640',
			videoId : vid,
			playerVars : {
				autoplay : 1
			},
			events : {
                onStateChange: saveVideoDetails
            }

		});
		var title = $(this).find("a").html();
        
	});

}

function onPlayerReady1(event) {
	
}
var id=false; //unique id for video in db
function saveVideoDetails(event){

    console.log(event.target);
	var state=event.target.getPlayerState();
//	alert(event.target.getPlayerState());
	if(state==1 && id==false)
	{
			var request = gapi.client.youtube.videos.list({
				id : vid ,
				part : 'snippet,contentDetails'
			});
			
			request.execute(function(response) {
				var title = response.items[0].snippet.title;
				var duration =response.items[0].contentDetails.duration;
				var category_id = response.items[0].snippet.categoryId;
				
				var request = gapi.client.youtube.videoCategories.list({
				id : category_id ,
				part : 'snippet'
			   });
				var url=event.target.getVideoUrl();
				//alert(url);
				request.execute(function(response){
					var category = response.items[0].snippet.title;
					$.post("save-video-details.php",{state:"insert",title:title,category:category,vduration:duration,vid:vid,url:url},function(response){
					if(response!="false")
					{
						id=response;
					}
					});
				});
				tweet(title, url);
        
			});
	}
	else if(state==2 || state==0)
	{
		//alert(event.target.getCurrentTime());
		var watched_time=event.target.getCurrentTime();
		if(id!=false)
		{
					$.post("save-video-details.php",{state:"update",id:id,watched_time:watched_time},function(response){
						console.log(response);
					});
			
		}
		
	}
}

function tweet(t, url) {
	$.get("tweet.php?title=" + t + "&url=" + encodeURIComponent(url));
	
}