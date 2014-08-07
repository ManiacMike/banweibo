var follow_page = 1;
$("#main_panel p").click(function(){
	$("#right_content").html("<div class=\"palette palette-clouds\" style=\"padding-left:220px;\"><img src='"+base_url+"/img/loading-small.gif'/>加载中</div>");
	if(!$(this).hasClass('main_panel_active')){
		var type=$(this).attr("data-id");
		$("#main_panel p").each(function(){
			if($(this).attr("data-id") == type)
			{
				if(!$(this).hasClass('main_panel_active'))$(this).addClass('main_panel_active');
			}else{
				if($(this).hasClass('main_panel_active'))$(this).removeClass('main_panel_active');
			}
		});
		if(type=="comment"){
			$("#sub_comment_box").slideDown("fast");
			$("#sub_comment_box").children(":first").addClass('main_panel_active');
		}else if(type!="received_comment" && type!="sended_comment" ){
			$("#sub_comment_box").slideUp("fast");
		}else{
			$("#sub_comment_box").prev().addClass('main_panel_active');
		}
		$.post(base_url+"/index/"+type+"/",{"row":page,"action":"ajax","if_homeline":1},function(data){
			if(data['count'] == 0){
				if(type == 'getFollow' && type == 'getFollow'){
					$("#right_content").html(getIntroHtml("您还没有关注任何twitter名人，快去关注感兴趣的人吧 ","select","compass"));
				}else if(type == 'fav'){
					$("#right_content").html(getIntroHtml("您还没有关注收藏任何推文，快去看看热门推文吧 ","hot","bag"));
				}else{
					$("#right_content").html("<div class='palette palette-clouds'>暂无数据</div>");
				}
			}else{
				if(type == 'getFollow'){
					follow_page = 1;
					$(document).unbind("scroll");
					$("#right_content").html(data['html']);
					$.getScript(base_url+"/js/follow.js");
				}else if(type=="qiandao_records"){
					$("#right_content").html(data['html']);
				}else{
						request_url = "/index/"+type+"/";
						if(data['count'] == data['pagesize']){
							$("#right_content").html("<div class='newsfeed' id=\"newsfeed\">"+data['html']+"</div><div class=\"feed_loading\">加载中<img src=\"./img/loading-small.gif\"></div>");
						}else{
							$("#right_content").html("<div class='newsfeed' id=\"newsfeed\">"+data['html']+"</div>");
							feed_request_ready = false;
						}
						$.getScript(base_url+"/js/feed.js");
						$.getScript(base_url+"/js/feed_func.js");
				}
			}
		},"json");
		page = 1;
		}
});
$("#main_share_btn").click(function(){
	$('#rt_modal').find(".rt_notice").hide();
	$('#rt_modal').find(".rt_com").show();
	$('#rt_modal').modal('show');
	var str = "神器啊，banweibo.com，不用翻墙也能看世界名人的推特了。信息同步，一键转发到微博，高端洋气上档次。http://banweibo.com";
	var img =base_url+"/img/intro.jpg";
    var _area = $('textarea#rt_textarea');
    var _info = _area.next().children("span").text("*");
    var _max = _area.attr('max_cn_length');
    _area.bind('keyup change focus', function() {
        if (_info.find('span').size() < 1) {
            _info.append(_max);
        }
        _val = $(this).val();
        _cur = Math.ceil(getByteLen(_val)/2);
        if (_cur == 0) {
            _info.text(_max);
        } else if (_cur < _max) {
            _info.text(_max - _cur);
        } else {
            _info.text(0);
            $(this).val(getByteVal(_val,_max));
        }
    });
	_area.val(str);
	_area.focus();
	$('#rt_img').attr("src",img);
	$('#rt_img').show();
});
function getIntroHtml(text,link,image)
{
	var str='<div class="tile">'+
    '<img class="tile-image big-illustration" alt="" src="'+base_url+'/'+'images/illustrations/'+image+'.png">'+
    '<h3 class="tile-title">hi，你好！'+weibo_uname+'</h3>'+
    '<p>'+text+' <span class="fui-heart-24"></span></p>'+
    '<br>'+
    '<a class="btn btn-primary btn-large btn-block" href="'+base_url+'/'+link+'/">Gooooo</a>'+
    '</div>';
	return str;
}
$("#tweet_send_btn").click(function(){
	var txt = $("#poster_textarea").val().replace(/\ /g,"");
	var btn =$(this);
	if(txt!="" && status_request == true){
		status_request == false;
		btn.val("稍等");
		$.post(base_url+"/index/send_tweet/",{"text":txt},function(data){
			if(data == 1)
			{
				$("#errBox").removeClass(" alert-error").addClass("alert-success");
				$("#errBox").html("发送成功");
				$("#poster_textarea").val("");
			}else{
				$("#errBox").removeClass(" alert-success").addClass("alert-error");
				$("#errBox").html("发送失败");
			}
			$("#errBox").show();
			setTimeout("$('#errBox').hide()",3000);
			btn.val("发布");
			status_request == true;
		});
	}
});