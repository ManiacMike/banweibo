{{include file='head.tpl'}}
<div class="container">
{{include file='nav.tpl'}}
<div class="space_div"></div>
		<div class="row">
		<div id="hot_nav"><a href="javascript:void(0)" data-id='1'  class="btn btn-large btn-info{{if $pageType ==1}} hot_nav_active{{/if}}"><i class="icon-nav-edit icon-nav"></i>小编推荐</a>
		<a href="javascript:void(0)" data-id='2'  class="btn btn-info btn-large{{if $pageType ==2}} hot_nav_active{{/if}}"><i class="icon-nav-talk icon-nav"></i>热评推文</a>
		</div>
		<div class='span3'>
		<div class="tile tile-hot hot-left" >
		<h3>相关人物</h3>
		{{foreach from=$people item=item}}
		<div class="hot-left-div"><a href="{{$base_url}}{{$item.screen_name}}"><img class="img-polaroid" src="{{$item.profile_image_url_128}}"></a>
		<p>{{$item.cname}}</p><p>{{$item.name}}</p>
		<p class="hot-left-intro">{{if $item.intro}}{{$item.intro}}{{else}}{{$item.whatsit}}{{/if}}</p>
		</div>
		{{/foreach}}
		</div>
		<div class="small_footer palette-clouds"><span><a href="{{$base_url}}about.html" title="关于本站">关于本站</a></span><span><a href="{{$base_url}}api.html" title="api合作">api合作</a></span><span><a href="{{$base_url}}suggest.html" title="意见和建议">意见建议</a></span>
		<p>沪ICP备11035920号-3</p>
		</div>
		</div>
		<div class='span9'>
		{{if $pageType ==1}}<div class="alert alert-info" id="hot_notice"><p>投稿请去小编的微博留言&nbsp&nbsp&nbsp<i class="icon-hand-right"></i><a href="http://www.weibo.com/u/1771011227" rel="nofollow" target="_blank">小编的微博</a></p></div>{{/if}}
		<div class='newsfeed'>
		{{include file='ajax/feed_ajax.tpl'}}	
		</div>
		</div>
	</div>
</div>
<script type="text/javascript">
var modules = ['feed_func','st'];
$("#hot_nav").children().each(function(){
	$(this).click(function(){
		if(!$(this).hasClass("hot_nav_active"))
		{
			st.cset("hot_page_default",$(this).attr("data-id"));
			location.href="http://banweibo.com/hot/"+$(this).attr("data-id")+"/";
		}
	});
});
</script>
{{include file='footer.tpl'}}