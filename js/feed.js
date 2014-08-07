var request_row = 2;
var feed_request_ready = true;
if(typeof(tid) == "undefined"){
	var tid=0;
}
if(typeof(if_homeline) == "undefined"){
	var if_homeline = 0;
}
if(typeof(request_url) == "undefined"){
	var request_url = "/index/getFeed/";
}
if(typeof(request_data) == "undefined"){
	var request_data = {"action":"ajax","tid":tid,'if_homeline':if_homeline};
}
if(typeof(no_feed_func) == "undefined"){
	no_feed_func = 0;
}
$(document).scroll(function(){
	if($(document).scrollTop()>($(window).height()+200))
	{
		$("#back-top").show();
	}else{
		$("#back-top").hide();
	}
//	if($(document).height()- $(document).scrollTop()<($(window).height()+400) && feed_request_ready==true){}
});
$("#loading_more_feed").click(function(){
	$(this).html("加载中<img src=\""+base_url+"/img/loading-small.gif\">");
	feed_request_ready = false;
	request_data ['row'] = request_row;
	$.post(base_url+request_url,request_data,function(data){
		if(data['count'] !=0)
		{
			if( no_feed_func == 0)
				$.getScript(base_url+"/js/feed_func.js");
			$("#newsfeed").append(data['html']);
			request_row++;
			feed_request_ready = true;
			$("#loading_more_feed").html("点击加载更多");
		}
		else
		{
			$(".feed_loading").html("没有更多了");
		}
	},"json");
});