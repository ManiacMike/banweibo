{{include file='head.tpl'}}
<div class="container">
{{include file='nav.tpl'}}
		<div class="midline index_midline"></div>
		<div class="pic_wall_container house_bg">
			<p class="pic_wall_notice"><i class="icon-volume-down"></i>我们的官方微博<a href="http://weibo.com/u/3436468640" title="搬微博" target="_blank"> 搬微博<i class="weibo_logo_red" style="float:none;"></i></a></p>
			{{foreach from=$popular item=item key=key}}
			<div class="pic">
			<div class="select_img_bg">
				<a href="{{$base_url}}{{$item.screen_name}}"><img src="{{$item.profile_image_url_128}}"></a>
				</div>
				<p class="pic_p1">{{if $item.name_all}}<abbr title="{{$item.name_all}}">{{$item.name}}</abbr>{{else}}{{$item.name}}{{/if}}{{if $item.verified}}&nbsp<i class="icon_verified"></i>{{/if}}</p>
				<p class="pic_p">{{if $item.cname}}<strong>{{$item.cname}}</strong>{{else}}{{$item.statuses_count}}条微博{{/if}}</p>
				<p class="pic_p">{{if $item.intro}}{{if $item.intro_all}}<abbr title="{{$item.intro_all}}">{{$item.intro}}</abbr>{{else}}{{$item.intro}}{{/if}}{{elseif $item.whatsit}}{{$item.whatsit}}{{else}}暂无介绍{{/if}}</p>
				<a class="btn btn-danger btn_follow"  href="#login_modal"  role="button"  data-toggle="modal">
                <i class="icon-plus icon-white"></i> 关注
                </a>
			</div>
			{{/foreach}}
			{{foreach from=$people item=item key=key}}
			<div class="pic">
			<div class="select_img_bg">
				<a href="{{$base_url}}{{$item.screen_name}}"><img src="{{$item.profile_image_url_128}}"></a>
				</div>
				<p class="pic_p1">{{if $item.name_all}}<abbr title="{{$item.name_all}}">{{$item.name}}</abbr>{{else}}{{$item.name}}{{/if}}{{if $item.verified}}&nbsp<i class="icon_verified"></i>{{/if}}</p>
				<p class="pic_p">{{if $item.cname}}<strong>{{$item.cname}}</strong>{{else}}{{$item.statuses_count}}条微博{{/if}}</p>
				<p class="pic_p">{{if $item.intro}}{{if $item.intro_all}}<abbr title="{{$item.intro_all}}">{{$item.intro}}</abbr>{{else}}{{$item.intro}}{{/if}}{{elseif $item.whatsit}}{{$item.whatsit}}{{else}}暂无介绍{{/if}}</p>
				<a class="btn btn-danger btn_follow"  href="#login_modal"  role="button"  data-toggle="modal">
                <i class="icon-plus icon-white"></i> 关注
                </a>
			</div>
			{{/foreach}}
			<p class="pic_wall_morelink"> <a href="{{$base_url}}select/" class="btn btn-large btn-primary"><i class="icon-th icon-white"></i> 查看更多人物</a></p>
		</div>
		<!--  
		<div class="row">
		<div class='span3'>
		<div class="palette-firm-dark index_left">
		<p>信息与twitter实时同步</p>
		<p>可一键转发至新浪微博</p>
		</div>
		<div class="small_footer palette-clouds"><span><a href="{{$base_url}}about.html" title="关于本站">关于本站</a></span><span><a href="{{$base_url}}api.html" title="api合作">api合作</a></span><span><a href="{{$base_url}}suggest.html" title="意见和建议">意见建议</a></span>
		<p>沪ICP备11035920号-3</p>
		</div>
		</div>
		<div class='span9'>
		<div class='newsfeed' id="newsfeed">	
		{{include file='ajax/feed_ajax.tpl'}}	
		</div>
		{{if $count == $pagesize}}
		<div class="feed_loading" id="loading_more_feed">点击加载更多</div>
		{{/if}}
		</div>
	</div>
	-->
</div>
<script type="text/javascript">
{{if $body_img}}
$("body").css({
    "background": "url({{$body_img}})"
  });
$(".pic_wall_container").removeClass("house_bg").removeClass("pic_wall_container").css("margin-top","70px");
$(".index_midline").hide();
{{/if}}
</script>
{{include file='footer.tpl'}}