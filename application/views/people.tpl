{{include file='head.tpl'}}
<div class="container">
{{include file='nav.tpl'}}
<div id="reco_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">可以填写推荐的分类，如音乐：流行音乐</h3>
  </div>
  <div class="modal-body">
    <p>推荐后可在<a href="http://weibo.com/u/3436468640" target="_blank">官方微博</a>看到接入审核结果</p>
  	<textarea id="reco_tip"></textarea><br>
    <a class="btn btn-info btn_follow"  id="btn_reco_send" data-id='1'><i class="icon-plus icon-white"></i>推荐</a>
  </div>
</div>
<div style="display:none"><img src="{{$body_img}}"></div>
<div class="space_div_2"></div>
<div class="row">
		<div class='span3'>
		{{if !$data.isfollow}}
				{{if $isLogin}}
				<a class="btn_follow_active"  data-id="{{$data.id}}" href="javascript:void(0)">
				{{else}}
				<a href="#login_modal"  role="button"  data-toggle="modal">
				{{/if}}
			<div class="palette palette-alizarin left_part_btn_follow" >
             <i class="icon-plus icon-white"></i> 关注
            </div>
            </a>
       {{else}}
      		<a href="javascript:void(0)" data-id="{{$data.id}}"  class="btn_unfollow_active">
			<div class="palette palette-firm" >
             <i class="icon-minus icon-white"></i> 取消关注
            </div>
           </a>
       {{/if}}
       <div class="left_part_intro palette-firm-dark row-fluid">
       	<div class="span4 left_part_num">{{if $data.friend_count_input}}<a href="javascript:void(0)" id="get_follow_btn">{{/if}}<div>{{$data.friends_count}}</div><p>关注</p>{{if $data.friend_count_input}}</a>{{/if}}</div>
        <div class="span4 left_part_num"><div>{{$data.followers_count}}</div><p>粉丝</p></div>
		<div class="span4 left_part_num"><div>{{$data.statuses_count}}</div><p>微博</p></div>
	    </div>
			<div  class="palette palette-info-dark">
				<p><i class="icon_twitter"></i> 推特签名：</p>
				<p>{{$data.description}}</p>
			</div>
		<div class="left_part_intro palette palette-info" id="qiandao_div">
	    <p>
	    <a href="javascript:void(0)" title="在{{$data.name}}页面签到" style="display:none;"><i class="icon-calendar"></i>&nbsp&nbsp签到</a>
	    <img src='{{$base_url}}img/loading_blue_16.gif' style="display:none;">
	    <font style="display:none;"><i class="icon-white icon-ok"></i> 第&nbsp<span></span>&nbsp位</font>
	    </p>
	    <p>本日已签到：&nbsp<span id="qiandao_num">{{$qiandao_num}}</span>&nbsp&nbsp人</p>
	    </div>
			<div  class="left_part_fans palette-firm-dark" >
			<p><i class="icon-th icon-white"></i> 本站粉丝（{{$data.follower_count_b}}）</p>
			{{foreach from=$follower item=item}}
			<a href="http://weibo.com/u/{{$item.wuid}}" target="_blank" rel="nofollow"><img data-toggle="tooltip" data-original-title="{{$item.wname}}" class="img-circle fans_img_circle" src="{{$item.w_profile_image_url}}"/></a>
			{{foreachelse}}
			<p class="no_fans_p">快来成为第一个粉丝吧！<span class="fui-heart-24"></span></p>
			{{/foreach}}
			{{if $data.follow_count_b!=0}}<p><a href="#">全部粉丝&gt&gt&gt</a></p>{{/if}}
			</div>
			{{if $products}}
			<div class="palette palette-clouds">
			<h4>{{$data.name}}周边</h4>
			<div class="row-fluid">
			{{foreach from=$products item=item key=key}}
			{{if $key<2}}
			<div class="product_div span6">
			<a target="_blank" href="{{$item.link}}" rel="nofollow" title="{{$item.name}}"><img src="{{$item.img}}"></a><p>￥{{$item.price}}</p>
			</div>
			{{/if}}
			{{/foreach}}
			</div>
			<div class="row-fluid">
			{{foreach from=$products item=item key=key}}
			{{if $key>1}}
			<div class="product_div span6">
			<a  target="_blank" href="{{$item.link}}" rel="nofollow" title="{{$item.name}}"><img src="{{$item.img}}"></a><p>￥{{$item.price}}</p>
			</div>
			{{/if}}
			{{/foreach}}
			</div>
			</div>
			{{/if}}
		<div class="left_part_intro palette palette-info">
		<a {{if $isLogin}}id ="ppl_share_btn" href="javascript:void(0)" {{else}} href="#login_modal"  role="button"  data-toggle="modal"{{/if}} class="btn btn-block btn-large btn-info" ><strong><i class="weibo_logo"></i>分享{{$data.name}}的页面</strong></a>
		</div>
		</div>
		<div class="span9" id="right_part1">
		<div class="row-fluid">
			<div class="icon_wall span9">
			<div class="icon_wall_icon">
				<img src="{{$data.profile_image_url_128}}" width="128" height="128"/>
			</div>
			<p class="icon_wall_name">{{$data.name}} {{$data.cname}}{{if $data.verified}}&nbsp<i class="icon_verified"></i>{{/if}}</p>
			<p class="icon_wall_view_big"><a href="{{$data.profile_image_url_ori}}" rel="nofollow" title="头像大图" target="_blank"><i class="icon-picture icon-white"></i></a></p>
			<p class="icon_wall_close_bg_btn"><a href="javascript:void(0)"></a></p>
			</div>
			<div class="intro_block span3 palette palette-firm-dark" id="intro_block">
			{{if $data.url}}<p><a href="{{$data.url}}" rel="nofollow"  title="{{$data.url}}" target="_blank"><i class="icon-hand-right icon-white"></i> 个人主页</a></p>{{/if}}
			{{if $data.location || $data.location_show}}<p><i class="icon-map-marker icon-white"></i> 所在地：{{if $data.location_show}}{{$data.location_show}}{{else if $data.location}}{{$data.location}}{{/if}}</p>{{/if}}
			{{if $data.intro || $data.whatsit}}<p> {{if $data.intro}}{{$data.intro}}{{else}}{{$data.whatsit}}{{/if}}</p>{{/if}}			
			{{if $data.reco_by_wname}}<p><i class="icon-thumbs-up icon-white"></i> <a href="http://weibo.com/u/{{$data.reco_by_wuid}}" rel="nofollow"  title="微博主页" target="_blank">{{$data.reco_by_wname}}</a>  推荐</p>{{/if}}
			</div>
			</div>
		<div class='newsfeed' id="newsfeed">
		{{if $data.input_statuses_count==0 && $data.statuses_count!=0}}
		<br><br>
			<a class="btn btn-large btn-primary btn-block"  href="{{$base_url}}catch_data/tweet/{{$data.tid}}/?action=fill" target="_blank">抓取历史推特</a>
			<br><div class="alert">
		  <strong>Warning!</strong> 请不要关闭打开的新窗口，耐心等待几分钟，直到出现end字样，抓取完成后,刷新页面就有数据啦
		</div>
		{{/if}}
		{{include file='ajax/feed_ajax.tpl'}}	
		</div>
		{{if $count == $pagesize}}
		<div class="feed_loading" id="loading_more_feed">点击加载更多</div>
		{{/if}}
		</div>
		<div class="span9" id="right_part2"></div>
</div>
</div>
<script type="text/javascript">
var tid ={{$data.id}};
var follow_total = {{$data.friend_count_input}};
var tuser_name = '{{$data.name}}';
var modules = ['feed','feed_func','st'{{if $data.friend_count_input}},'people'{{/if}}];
$('.btn_follow_active').click(function () {
		if($(this).attr("data-id") != 0){
		var btn = $(this);
		var fid = $(this).attr("data-id");
		$(this).attr("data-id",0);
		$(this).removeClass('btn_follow_active').addClass('disabled');
		$(this).html("<div class=\"palette palette-silver\" ><i class=\"icon-refresh icon-white\"></i> 处理中</div>");
		$.post(base_url+"/relation/follow/",{"fid":fid},function(data){
			if(data == 1)
			{
				btn.html("<div class=\"palette palette-silver\" ><i class=\"icon-ok icon-white\"></i> 已关注</div>");
			}
		});
		}
});
$('.btn_unfollow_active').click(function () {
		if($(this).attr("data-id") != 0){
		var btn = $(this);
		var fid = $(this).attr("data-id");
		$(this).attr("data-id",0);
		$(this).removeClass('btn_unfollow_active').addClass('disabled');
		$(this).html("<div class=\"palette palette-silver\" ><i class=\"icon-refresh icon-white\"></i> 处理中</div>");
		$.post(base_url+"/relation/unfollow/",{"fid":fid},function(data){
			if(data == 1)
			{
				btn.html("<div class=\"palette palette-silver\" ><i class=\"icon-ok icon-white\"></i> 取消成功</div>");
			}
		});
		}
});
$(".fans_img_circle").tooltip();
$("#ppl_share_btn").click(function(){
	$('#rt_modal').find(".rt_notice").hide();
	$('#rt_modal').find(".rt_com").show();
	$('#rt_modal').modal('show');
	var str = "各位亲，这里可以关注{{$data.cname}}{{$data.name}} {{$data.intro}}的最新twitter，不用翻墙，挺强大的 {{$base_url}}{{$data.screen_name}}";
	var img = '{{$data.profile_image_url_ori}}';
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
$(".icon_wall_close_bg_btn").click(function()
{
	if($(".icon_wall_close_bg_btn").children("a").html()=="关闭背景图")
	{
		$("body").css({
		    "background": "url("+default_bg+")"
		  });
		st.sset("close_bg_{{$data.screen_name}}",1);
		$(".icon_wall_close_bg_btn").children("a").html("开启背景图")
	}else
	{
		$("body").css({
		    "background": "url({{$body_img}})"
		  });
		st.sdel("close_bg_{{$data.screen_name}}");
		$(".icon_wall_close_bg_btn").children("a").html("关闭背景图")
	}
});
{{if $body_img}}
function init_bg(){
	if (st.sget("close_bg_{{$data.screen_name}}") !=1)
	{
		$("body").css({
		    "background": "url({{$body_img}})"
		  });
		$(".icon_wall_close_bg_btn").children("a").html("关闭背景图");
	}else
	{
		$(".icon_wall_close_bg_btn").children("a").html("开启背景图");
	}
}
setTimeout("init_bg()",1000);
{{/if}}
var sign_request = true;
$("#qiandao_div a").click(function(){
	if(sign_request == true){
		var btn=$(this);
		$(this).hide();
		$(this).parent().children("img").show();
		sign_request == false;
		$.post("{{$base_url}}qiandao/",{"tuid":"{{$data.id}}"},function(data){
			btn.nextAll("img").hide();
			if(data['result'] ==1){
				$("#qiandao_num").html(data['num']);
				btn.nextAll("font").show().children("span").html(data['num']);
			    st.sset("qiandao_{{$data.id}}",getDateStr()+"^"+data['num']);
			}else{
				btn.show();
			}
			sign_request =true;
		},"json");
	}
});
$(function(){
	if(st.sget("qiandao_{{$data.id}}")){
		var str = st.sget("qiandao_{{$data.id}}");
		var dateStr = getDateStr();
		if(str.indexOf(dateStr)>-1){
			var num = str.split("^")[1];
			$("#qiandao_div a").nextAll("font").fadeIn().children("span").html(num);
		}else{
			$("#qiandao_div a").show();
			st.sdel("qiandao_{{$data.id}}");
		}
	}else{
		$("#qiandao_div a").show();
	}
});
function getDateStr(){
	var myDate = new Date();
	return myDate.getFullYear()+"_"+((myDate.getMonth()+1).length>1?myDate.getMonth()+1:"0"+(myDate.getMonth()+1))+"_"+myDate.getDate();
}
</script>
{{include file='footer.tpl'}}