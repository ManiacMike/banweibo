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
<div class="space_div_2"></div>
     <div class="row">
     <div class="span3">
     	<div class="alert alert-warning" id="search_warning" style="display:none">
              <button type="button" class="close" data-dismiss="alert" id="search_warning_close">×</button>
              <strong>1.</strong>通过搜索，您可以向我们推荐搬运某一账户<br/>
              <strong>2.</strong>我们审核的标准一是非政治敏感人物，二是t粉丝大于3000<br/>
              <strong>3.</strong>开通后该账号页banweibo的个人主页会加上您的名字
     </div>
     <div class="alert alert-warning" id="search_warning_short">
     	展开<i class="icon-chevron-down close" style=""></i>
     </div>
     {{if $data}}
     <div class="small_search_container">
      	<form action="" method="get" id="search_form" class="small_form">
          	<input name="q" type="text"  value="{{if $keyword}}{{$keyword}}{{else}}{{$default_search}}{{/if}}" class="small_input" />
     		<button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i></button>
     	</form>
     </div>
     {{/if}}
     <div class="palette palette-firm-dark search_left">
     <p><i class="icon-th-list"></i>&nbsp最近推荐</p>
     {{foreach from=$recent item=item}}
     <p><a href="http://weibo.com/u/{{$item.wuid}}" rel="nofollow" target="_blank">{{$item.wname}}</a>成功推荐了<a href="{{$base_url}}{{$item.tscreen_name}}">{{$item.tname}}</a></p>
     {{foreachelse}}
     暂无成功推荐
     {{/foreach}}
     </div>
          {{if $data}}
         <div class="small_footer palette-clouds"><span><a href="{{$base_url}}about.html" title="关于本站">关于本站</a></span><span><a href="{{$base_url}}api.html" title="api合作">api合作</a></span><span><a href="{{$base_url}}suggest.html" title="意见和建议">意见建议</a></span>
		<p>沪ICP备11035920号-3</p>
		</div>
          {{/if}}
     </div>
     <div class='span9' >
     {{if !$data}}
     <div class="big_search_container">
     <form action="" method="get" id="search_form" class="default_form">
     	<input name="q" type="text"  value="{{if $keyword}}{{$keyword}}{{else}}{{$default_search}}{{/if}}" class="big_search_input" />
     	<button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i></button>
     	<p>{{$error_message}}</p>
     </form>
     </div>
     {{/if}}
     	{{if $data}}
     	<div class="headline"></div>
     	<div class="pic_wall_container" id="search_data_wall">
     	<p class="pic_wall_select_notice"><i class="icon-th icon-white"></i>搜索“<strong>{{$keyword}}</strong>”的结果</p>
		{{include file='ajax/search_data.tpl'}}
     	</div>
     	{{if $data_count eq 20}}
     	<div class="loading_search palette palette-night-dark">正在请求数据<img src="{{$base_url}}img/loading_plane_2.gif"></div>
		{{/if}}
		{{/if}}
     </div>
     </div>
</div>
<script type="text/javascript">
var modules = ['search','follow','st'];
var default_search = "{{$default_search}}";
var keyword = "{{$keyword}}";
var request_row = 2;
{{if $data_count <20}}
var feed_request_ready = false;
{{else}}
var feed_request_ready = true;
{{/if}}
$(".pic img").tooltip();
function init_warn(){
if(st.sget("search_warning") == 1)
{
	$("#search_warning_short").show();
}else{
	$("#search_warning").slideDown();
}
}
setTimeout("init_warn()",1000);
$("#search_warning_close").click(function(){
	st.sset("search_warning", 1);
});
$("#search_warning_short").click(function(){
	st.sdel("search_warning");
	$("#search_warning_short").hide();
	$("#search_warning").show();
});
var recoId,recoName,recoSname,recoResult,curRecoBtn;
</script>
{{include file='footer.tpl'}}