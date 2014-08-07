{{include file='head.tpl'}}
<div class="container">
{{include file='nav.tpl'}}
<div class="space_div_2"></div>
<div class="row">
		<div class='span3'>
		<div class="bind_box">
		{{if $isBind}}
		<a class="btn disabled btn_follow" href="javascript:void(0)">
                <i class="icon_twitter"></i> 已绑定
       </a>
       <a class="btn btn-danger btn_follow" href="{{$base_url}}quit/unbind_twitter/" onclick="return confirm('您确定要解除与twitter的绑定吗');">
                 解除
       </a>
       {{else}}
       		<a class="btn btn-info btn_follow"  href="#bind_twitter_modal" role="button" data-toggle="modal">
                <i class="icon_twitter"></i> 绑定twitter
       </a>
       {{/if}}
       </div>
		<div  id="main_panel">
		<p data-id="getFeed" class="main_panel_active main_panel_top"><i class="icon-home icon-white"></i>主页<i class="icon-circle"></i></p>
		<p data-id="getFollow"><i class="icon-list-alt icon-white"></i>关注的人（<span id="main_follow_count">{{$user.follow_count_b}}</span>）<i class="icon-circle"></i></p>
		<p data-id="rt"><i class="icon-retweet icon-white"></i>转发<i class="icon-circle"></i></p>
<!--		<p data-id="tranlate"><i class="icon-bookmark icon-white"></i>翻译（{{$user.translate_count}}）<i class="icon-circle"></i></p> -->
		<p data-id="comment"><i class="icon-comment icon-white"></i>评论<i class="icon-circle"></i></p>
		<div id="sub_comment_box">
		<p data-id="received_comment"><span  class="sub_main_btn">收到的回复</span></p>
		<p data-id="sended_comment"><span  class="sub_main_btn">发出的评论</span></p>		
		</div>
		<p data-id="qiandao_records"><i class="icon-calendar icon-white"></i>签到记录（new）<i class="icon-circle"></i></p>
		<p data-id="fav" class="main_panel_bottom"><i class="icon-heart icon-white"></i>收藏（<span id="main_fav_count">{{$user.fav_count}}</span>）<i class="icon-circle"></i></p>
		</div>
		<div class=" palette palette-clouds">
		<div class="row-fluid">
		<div class="span5" style="font-size:12px;">官方微博：</div>
		<div class="span4"><wb:follow-button uid="3436468640" type="red_1" width="67" height="24" ></wb:follow-button></div>
		</div>
		</div>
		<div><a href="{{$base_url}}donate.html"><img src="{{$base_url}}img/cat_left.jpg"/></a></div>
		<div class="palette palette-info">
		<a id ="main_share_btn" class="btn btn-block btn-large btn-info" href="javascript:void(0)"><strong>分享"搬微博"</strong></a>
		</div>
		<!-- 
		<div class="ads_220">
		<script type="text/javascript">
		     document.write('<a style="display:none!important" id="tanx-a-mm_34162985_4038829_13138020"></a>');
		     tanx_s = document.createElement("script");
		     tanx_s.type = "text/javascript";
		     tanx_s.charset = "gbk";
		     tanx_s.id = "tanx-s-mm_34162985_4038829_13138020";
		     tanx_s.async = true;
		     tanx_s.src = "http://p.tanx.com/ex?i=mm_34162985_4038829_13138020";
		     tanx_h = document.getElementsByTagName("head")[0];
		     if(tanx_h)tanx_h.insertBefore(tanx_s,tanx_h.firstChild);
		</script>
		</div>
		 -->
		{{if $people}}
		<div class="palette palette-firm" ><p class="main_reco_head"><i class="icon-th icon-white"></i> 人物推荐</p>	
		{{foreach from=$people item=item}}
		<div class="main_reco_div">
		<a href="{{$base_url}}{{$item.screen_name}}" ><img data-toggle="tooltip" title="{{if $item.intro}}{{$item.intro}}{{elseif $item.whatsit}}{{$item.whatsit}}{{else}}{{$desciption}}{{/if}}"  class="img-circle" src="{{$item.profile_image_url_128}}"/></a>
		<p><a href="{{$base_url}}{{$item.screen_name}}" >{{$item.name}}</a></p>
		</div>
		{{/foreach}}
		</div>
		{{/if}}
		<div class="small_footer palette-clouds"><span><a href="{{$base_url}}about.html" title="关于本站">关于本站</a></span><span><a href="{{$base_url}}api.html" title="api合作">api合作</a></span><span><a href="{{$base_url}}suggest.html" title="意见和建议">意见建议</a></span>
		<p>沪ICP备11035920号-3</p>
		</div>
		</div>
		<div class="span9" id="right_content">
		{{if $isBind}}
		<div id="poster" >
		<p>发送tweet</p>
		<textarea id="poster_textarea"></textarea>
		<input type="submit" class="btn btn-primary btn-large" id="tweet_send_btn" value="发布"/>
		<div id="errBox" class="alert"></div>
		</div>
		{{/if}}
		{{if $count gt 0 }}
		<div class='newsfeed' id="newsfeed">	
		{{include file='ajax/feed_ajax.tpl'}}
		</div>
		{{if $count eq $pagesize}}
		<div class="feed_loading" id="loading_more_feed">点击加载更多</div>
		{{/if}}
		{{else}}
		<div class="tile">
            <img class="tile-image big-illustration" alt="" src="{{$base_url}}images/illustrations/compass.png">
            <h3 class="tile-title">hi，你好！{{$user.weibo_uname}}</h3>
            <p>您还没有关注任何twitter名人，快去关注感兴趣的人吧 <span class="fui-heart-24"></span></p>
            <br>
            <a class="btn btn-primary btn-large btn-block" href="{{$base_url}}select/">Gooooo</a>
          </div>
		{{/if}}
		</div>
</div>
</div>
<script type="text/javascript">
var modules = [{{if $count == $pagesize}}'feed',{{/if}}'feed_func','main'];
var if_homeline = 1;
var page = 1;
$(".main_reco_div img").tooltip();
</script>
{{include file='footer.tpl'}}