var follow_act_request = true;
$('.btn_follow_active').click(function () {
	if($(this).attr("data-id") != 0){
	var btn = $(this);
	var fid = $(this).attr("data-id");
	$(this).attr("data-id",0);
	$(this).removeClass('btn_follow_active btn-danger').addClass('disabled');
	$(this).html("<i class=\"icon-refresh icon-white\"></i> 处理中");
	$.post(base_url+"/relation/follow/",{"fid":fid},function(data){
		if(data == 1)
		{
			btn.html("<i class=\"icon-ok icon-white\"></i> 已关注");
		}
	});
	}
});
$('.btn_unfollow_active').click(function () {
	if($(this).attr("data-id") != 0){
	var btn = $(this);
	var fid = $(this).attr("data-id");
	$(this).attr("data-id",0);
	$(this).removeClass('btn_unfollow_active btn-primary').addClass('disabled');
	$(this).html("<i class=\"icon-refresh icon-white\"></i> 处理中");
	$.post(base_url+"/relation/unfollow/",{"fid":fid},function(data){
		if(data == 1)
		{
			btn.html("<i class=\"icon-ok icon-white\"></i> 已取消");
			$("#main_follow_count").html($("#main_follow_count").html()-1);
		}
	});
	}
});
$('.btn_reco_active').click(function () {
	recoResult = false;
	recoId = $(this).attr("data-id");
	recoName = $(this).attr("data-name");
	recoSname = $(this).attr("data-sname");
	$(this).removeClass('btn_reco_active btn-info').addClass('disabled');
	$(this).html("<i class=\"icon-refresh icon-white\"></i> 处理中");
	$('#reco_modal').modal('show');
	curRecoBtn = $(this);
//	btn.html("<i class=\"icon-ok icon-white\"></i> 已推荐");
});
$('#reco_modal').on('hide', function () {
	if(recoResult == true){
		curRecoBtn.html("<i class=\"icon-ok icon-white\"></i> 已推荐");
	}else if(recoResult == "fail"){
		curRecoBtn.html("<i class=\"icon-ok icon-white\"></i> 推荐失败");
	}else{
		curRecoBtn.removeClass('disabled').addClass('btn_reco_active btn-info');
		curRecoBtn.html("<i class=\"icon-plus icon-white\"></i> 推荐");
	}
	
})
$('#btn_reco_send').click(function () {
	if($(this).attr("data-id")==1){
	$(this).attr("data-id",0);
	$.post(base_url+"/search/recommend/",{"tuid":recoId,"tip":$("#reco_tip").val(),"tname":recoName,"tscreen_name":recoSname},
		function(data){
		if(data == 1)
		{
			recoResult = true;
			$("#reco_tip").val("");			
		}else{
			recoResult = 'fail';
		}
		$('#reco_modal').modal('hide');		
		$('#btn_reco_send').attr("data-id",1);
	});
	}
});
$("#getMoreFollow").click(function(){
	follow_page++;
	$.post(base_url+"/index/getFollow/",{"row":follow_page,"action":"ajax","noframe":1},function(data){
		if(data['count']>0)
		{
			$("#getMoreFollow").before(data['html']);
			$.getScript(base_url+"/js/follow.js");
		}
		if(data['count'] < 20)
		{
			$("#getMoreFollow").hide();
		}
	},"json");
});