var rt_tid,status_request = true;
$(function(){$(".fav_ico").click(function(){
	if(typeof(weibo_uid) == "undefined"){
		$('#login_modal').modal('show');
	}else{
	var btn = $(this);
	if(status_request == true)
	{
		status_request = false;
		$.post(base_url+"/fav/add/",{"tweet_id":$(this).parent().attr("data-id")},function(data){
			if(data ==1){
				btn.html("已收藏");
				btn.removeClass("fav_ico");
				btn.unbind("click");
				$("#main_fav_count").html(parseInt($("#main_fav_count").html())+1);
			}
			status_request = true;
		});
	}
	}
});
$(".unfav_ico").click(function(){
	var btn = $(this);
	if(status_request == true)
	{
		status_request = false;
		$.post(base_url+"/fav/del/",{"fav_id":$(this).parent().attr("data-id")},function(data){
			if(data ==1){
				btn.unbind("click");
				btn.parents(".one_feed").slideUp('slow');
				$("#main_fav_count").html(parseInt($("#main_fav_count").html())-1);
			}
			status_request = true;
		});
	}
});
$(".rt_ico").click(function(){
	if(typeof(weibo_uid) == "undefined"){
		$('#login_modal').modal('show');
	}else{
	$('#rt_modal').find(".rt_notice").hide();
	$('#rt_modal').find(".rt_com").show();
	$('#rt_modal').modal('show');
	var html = $(this).parents('.feed_content').children(".feed_text").html();
	var author = $(this).parents('.feed_content').children(".feed_author").children("a").html();
	var img = $(this).parents('.feed_content').find(".feed_img_bg").children("img").attr("src");
	var author_url = $(this).parents('.feed_content').children(".feed_author").children("a").attr("href");
	var str;
    var _area = $('textarea#rt_textarea');
    var _info = _area.next().children("span").text("*");
    var _max = _area.attr('max_cn_length');
    rt_tid = $(this).parents('.feed_content').find(".ico_group").attr("data-id");
	if(typeof(html)!="undefined"){
		str = author+"："+$(this).parents('.feed_content').children(".feed_text").html();
	}else{
		var rt_author = $(this).parents('.feed_content').find(".feed_repost_author").children("a").html();
		str = author+"："+rt_author+"//"+$(this).parents('.feed_content').find(".feed_repost_content").html();
	}
//	str ="【转自twitter】//"+str+"【"+author+"的搬微博主页"+author_url+"】";
	str = "【转自twitter】"+str;
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
	_area.val(rt_preg(str));
	_area.focus();
	if(typeof(img)!="undefined"){
		$('#rt_img').attr("src",img);
		$('#rt_img').show();
	}else{
		$('#rt_img').attr("src",base_url+"/img/noimg.jpg");
		$('#rt_img').hide();
	}
	}
});
$("#rt_send_btn").click(function(){
	if(status_request == true)
	{
		$(".rt_com").hide();
		$("#rt_loading").show();
		status_request = false;
		var p_data ={"text":$('#rt_textarea').val(),"tid":rt_tid};
		if($('#rt_img').attr("src")!=base_url+"/img/noimg.jpg")
		{		
			p_data ['url'] = $('#rt_img').attr("src");
		}
		$.post(base_url+"/index/post_weibo/",p_data,function(data){
			$("#rt_loading").hide();
			if(data == 1)
			{
				$('#rt_modal').find(".alert-success.rt_notice").show();
				$('#rt_modal').modal("hide");
			}else{
				$('#rt_modal').find(".alert-error.rt_notice").show();
			}
			status_request = true;
		});
	}
});
$(".comm_ico").click(function(){
	if($(this).parent().attr('data-toggle')==0){
	var btn = $(this);
	if(status_request == true)
	{
		status_request = false;
		$.post(base_url+"/comment/getComment/",{"tweet_id":$(this).parent().attr("data-id"),"comm_row":$(this).parent().attr("data-comm-row")},function(data){
			if(data !=""){
				btn.parents(".feed_content").append(data);
				btn.parent().attr("data-toggle",1);
				$(".feed_comm_checkbox").tooltip();
				btn.parent().attr("data-comm-row",parseInt(btn.parent().attr("data-comm-row"))+1);
				$(".feed_comment_btn").click(function(){
					send_event($(this));
				});
				$(".feed_more_comment").click(function(){
					get_more_comm($(this));
				});
				$(".up_comments").click(function(){
					up_comments($(this));
				});
				$(".one_feed_comment").hover(function(){
					show_reply($(this));
				},function(){
					hide_reply($(this));
				});
				$(".one_comm_reply").click(function(){
					reply_comm($(this));
				});
				$(".one_comm_del").click(function(){
					del_comm($(this));
				});
			}
			status_request = true;
		});
	}}else if($(this).parent().attr('data-toggle')==1){
		$(this).parents(".feed_content").children(".feed_comments").slideUp('fast');
		$(this).parent().attr("data-toggle",2);
	}else if($(this).parent().attr('data-toggle')==2){
		$(this).parents(".feed_content").children(".feed_comments").slideDown('fast');
		$(this).parent().attr("data-toggle",1);
	}
});
function reply_comm(btn)
{
	var uid = btn.attr("data-id");
	var  wuid= btn.attr("data-wuid");
	var  name= btn.attr("data-name");
	var textarea = btn.parents(".feed_comments").find("textarea");
	textarea.nextAll("input[type=hidden].to_user_id").val(uid);
	textarea.nextAll("input[type=hidden].to_user_name").val(name);
	textarea.nextAll("input[type=hidden].to_user_wuid").val(wuid);
	textarea.focus();
	textarea.val("回复 "+name+"：");
	$('body,html').scrollTop(textarea.offset().top-160);
}
function del_comm(btn)
{
	if(status_request == true)
	{
		status_request = false;
		var comm_id = btn.attr("data-id");
		var user_id = btn.attr("data-uid");
		var tweet_id = btn.attr("data-tid");
		$.post(base_url+"/comment/del/",{"comm_id":comm_id,"user_id":user_id,"tweet_id":tweet_id},function(data){
			if(data==1)
			{
				btn.parents(".one_feed_comment").slideUp();
			}
			status_request = true;
		});
	}
}
function show_reply(div)
{
	div.children(".span1:last").children(".one_comm_reply").show();
}
function hide_reply(div)
{
	div.children(".span1:last").children(".one_comm_reply").hide();
}
function up_comments(btn)
{
	btn.parents(".feed_content").children(".feed_comments").slideUp('fast');
	btn.parents(".feed_content").find(".ico_group").attr("data-toggle",2);
	$('body,html').scrollTop(btn.parents(".feed_content").offset().top-60);
}
function get_more_comm(btn){
	if(status_request == true)
	{
		status_request = false;
		var data_bar = btn.parents(".feed_content").find(".ico_group");
		$.post(base_url+"/comment/getCommentMore/",{"tweet_id":data_bar.attr("data-id"),"comm_row":data_bar.attr("data-comm-row")},function(data){
			if(data['count'] >0){
				btn.prev(".feed_comments_con").append(data.html);
				data_bar.attr("data-comm-row",parseInt(data_bar.attr("data-comm-row"))+1);
			}
			if(data['pagesize'] > data['count'])
			{
				btn.hide();
			}
			status_request = true;
		},"json");
	}
}
function send_event(btn){
	if(status_request==true){
		btn.html("稍等");
		status_request = false;
		var textarea = btn.prevAll(".feed_comment_textarea");
		if(textarea.val().replace(/^\s+|\s+$/g, "") != "")
		{
			var is_translate = 0;
			btn.parent().next(".comment_op_group").children().each(function(){
				if($(this).hasClass("checked")){
					is_translate = $(this).children("input").val();
				}
			});
			var to_user_id = textarea.nextAll("input[type=hidden].to_user_id").val();
			var to_user_name = textarea.nextAll("input[type=hidden].to_user_name").val();
			var to_user_wuid = textarea.nextAll("input[type=hidden].to_user_wuid").val();
			var text = textarea.val();
			$.post(base_url+"/comment/add/",{"tweet_id":btn.parents('.feed_content').find('.ico_group:first').attr("data-id"),"text":text,"is_translate":is_translate,"weibo_uid":weibo_uid,"weibo_uname":weibo_uname,"to_user_id":to_user_id,"to_user_wuid":to_user_wuid,"to_user_name":to_user_name},function(data){
				if(data == 1)
				{
					textarea.before(get_alert_html("评论成功","success"));
					textarea.val("");
					btn.prevAll("input[type=checkbox]:checked").attr("checked",false);
					if(btn.parents(".feed_comments").find(".feed_comments_con").html()==""){
						btn.parents(".feed_comments").find(".feed_comments_con").html(getCommHtml(text));
					}else{
						btn.parents(".feed_comments").find(".feed_comments_con").children(":first").before(getCommHtml(text));
					}
					var comm_count = btn.parents(".feed_content").find(".nf_comm_count");
					comm_count.html(parseInt(comm_count.html())+1);
				}else{
					textarea.before(get_alert_html("评论失败,sorry,请稍后再试","error"));
				}
				status_request = true;
				btn.html("回复");
			});
		}else{
			textarea.before(get_alert_html("不能为空","warning"));
		}
	}
}
});
function getCommHtml(text)
{
	return '<div class="one_feed_comment row-fluid"><div class="one_feed_comment_ico span1"><a href="http://weibo.com/u/'+weibo_uid+'" target="_blank" rel="nofollow"><img width="24" height="24" src="'+weibo_profile_image+'"></a></div><div class="one_feed_comment_content span10"><a href="http://weibo.com/u/'+weibo_uid+'" target="_blank" rel="nofollow">'+weibo_uname+'</a>'
	+"："+text+'</div></div><hr>';
}
$("label.radio").click(
		
);