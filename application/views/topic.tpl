{{include file='head.tpl'}}
<script src="http://banweibo.com/js/shiftzoom.js" type="text/javascript"></script>
<div id="topic_modal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="topic_name"></h3>
  </div>
  <div class="modal-body" id="topic_body">
	<img src="{{$base_url}}img/loading_simple.gif"/>
  </div>
</div>
<div class="container">
<div style="height:500px;"></div>
<div id="map_wrap">
<div id="map" style="background: url(http://banweibo.com/img/map940.jpg)  no-repeat; ">
<img id="world" src="http://banweibo.com/img/map940.jpg" /></div>
<p id="map_location_des"></p>
</div>
<script>
var map_width=$("#map").width();
var map_top = $("#map").offset().top;
if($.browser.webkit) {
	var map_left = $(window).width()-17;
}else{
	var map_left = $(window).width();
}
map_left = (map_left-map_width)/2;
var location_request =true;
function requestAllLocations()
{
	$.post("http://banweibo.com/topic/mapping/",{'key':'ndsdsyresb893rejf'},function(data){
		drawLocations(data);
		},"json");
}
function drawLocations(data)
{
	var x = map_width/data['width'];
	for(var i in data['locations']){
		if(data['locations'][i]['placeTypeCode'] == '12')
		{
			$("#map_wrap").append("<i class=\"map_pointer map_location_country\" data-id='"+data['locations'][i]['woeid']+"' data-father='' data-name='"+data['locations'][i]['name']+"'></i>");
			$("#map_wrap").children().last().css( "left", data['locations'][i]['map_x']*x-10+map_left).css( "top", data['locations'][i]['map_y']*x-26+map_top );
			var city_info = "";
			for(var j in data['locations'][i]['city']){
				city_info +="<p data="+data['locations'][i]['city'][j]['woeid']+">"+data['locations'][i]['city'][j]['name']+"</p>";
			}
			$("#map_wrap").append("<input type='hidden' value='"+city_info+"'>");			
		}else if(data['locations'][i]['placeTypeCode'] == '7')
		{
			$("#map_wrap").append("<i class=\"map_pointer map_location_city\" data-id='"+data['locations'][i]['woeid']+"' data-father='"+data['locations'][i]['country']+"' data-name='"+data['locations'][i]['name']+"'></i>");
			$("#map_wrap").children().last().css( "left", data['locations'][i]['map_x']*x-8+map_left).css( "top", data['locations'][i]['map_y']*x-8+map_top );
		}else if(data['locations'][i]['placeTypeCode'] == '250')
		{
			$("#map_wrap").append("<i class=\"map_pointer map_location_block\" data-id='"+data['locations'][i]['woeid']+"' data-father='' data-name='"+data['locations'][i]['name']+"'></i>");
			$("#map_wrap").children().last().css( "left", data['locations'][i]['map_x']*x-15+map_left).css( "top", data['locations'][i]['map_y']*x-40+map_top );
		}
	}
	$(".map_pointer").hover(function(){
		$('#map_location_des').attr("is_hover",1);
		var html= "<h3>"+$(this).attr("data-name")+"</h3>";
		if($(this).attr("data-father")!="")
			html+="<span>"+$(this).attr("data-father")+"</span>";
		else
			html+= $(this).next("input").val();
		html+= "<div>×</div>";
		$("#map_location_des").show().html(html).css('width','917px').css('left',map_left);
		$("#map_location_des").show().html(html).css('top',$(this).css('top')).css( "top", "-=20" );
		var des_height = $("#map_location_des").height();
		$("#map_location_des").css("top","-="+des_height);
//		var des_width = $("#map_location_des").width();
//		$("#map_location_des").css("left","-="+des_width/3);
		$("#map_location_des").children("div").click(function(){
			$('#map_location_des').hide();
			$('#map_location_des').attr("is_hover",0);			
		});
	},function(){
				$('#map_location_des').attr("is_hover",0);
	});
	$('#map_location_des').hover(function(){
		$(this).attr("is_hover",1);
	},function(){
		$(this).attr("is_hover",0);
	});
	$(".map_pointer").click(function(){
		$('#map_location_des').hide();
		$('#map_location_des').attr("is_hover",0);
		$("#topic_body").html("<img src=\"{{$base_url}}img/loading_simple.gif\"/>");
		if(location_request == true){
			$('#topic_modal').modal('show');
			$("#topic_name").html( $(this).attr("data-name")+" 当前的热门话题");
			location_request = false;
			var btn = $(this);
			$.post("http://banweibo.com/topic/getTopic/",{'id':$(this).attr("data-id"),'father':$(this).attr("data-father")},function(data){
				var html="<div>";
				for(var i in data['topic'])
				{
					html +="<p>"+data['topic'][i]['name']+"</p>";
				}
				html +="</div>";
				$("#topic_body").html(html);
				location_request = true;
			},'json');
		}
	});
}
requestAllLocations();
function checkDescShow(){
	if($('#map_location_des').attr("is_hover")!=1)
	{
		$('#map_location_des').hide();
	}
}
setInterval("checkDescShow()", 3000);
</script>
</div>
</body>
</html>