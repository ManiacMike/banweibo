var follow_request_ready = true;
$("#get_follow_btn").click(function(){
	$("#right_part1").fadeOut('fast');
	if($("#right_part2").html() ==""){
		request_follow_list(1);
	}else{
		$("#right_part2").fadeIn('fast');
	}
});
function request_follow_list(page)
{
	if(follow_request_ready == true)
	{
		follow_request_ready = false;
		$("#right_part2").show().html("<div style=\"margin-left:160px;\"><img src='"+base_url+"/img/loading-small.gif'/><strong>加载中</strong></div>");
		$.post(base_url+"/people/tuser_follow/",{'id':tid,'page':page,'total':follow_total,'name':tuser_name},function(data){
			$("#right_part2").html(data);
			follow_request_ready = true;
			$.getScript(base_url+"/js/follow.js");
		});
	}
	$('body,html').animate({
		scrollTop: 0
	}, 'fast');
	return false;
}