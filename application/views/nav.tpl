	<div class="navbar navbar-fixed-top navbar-inverse">
	  <div class="navbar-inner" id="top_nav_div">
	  	<div class="container">
	    <a class="brand" href="{{$base_url}}index/"><img src="{{$base_url}}img/logo/logo_black.jpg"></a>
	    <ul class="nav" id="top_nav">
	      <li{{if $current=="index"}} class="active"{{/if}}><a href="{{$base_url}}index/" title="主页">主页 <i class="icon-nav-home icon-nav"></i></a></li>
	      <li{{if $current=="list"}} class="active"{{/if}}><a href="{{$base_url}}select/" title="人物列表">人物 <i class="icon-nav-list icon-nav"></i></a></li>
	      <li{{if $current=="hot"}} class="active"{{/if}}><a href="{{$base_url}}hot/" title="热门微博">热门 <i class="icon-nav-fire icon-nav"></i></a></li>
	      <li{{if $current=="search"}} class="active"{{/if}}><a href="{{$base_url}}search/" title="搜索">搜索 <i class="icon-nav-search icon-nav"></i></a></li>  	  
	  	   </ul>
	  	   <ul class="nav" id="sub_nav">
	  	  {{if $isLogin}}
	       <li data-toggle="tooltip" title="{{$user.weibo_uname}}" data-placement="bottom"><img title="{{$user.weibo_uname}}" class="nav_uicon" src="{{$user.weibo_profile_image}}">
	       <li data-toggle="tooltip" title="退出"  data-placement="bottom"><a id="quit_btn" onclick="return confirm('您确定要退出吗');" href="{{$quit_url}}" ><i class="icon-off icon-white"></i></a></li>
	      {{else}}
	        <li data-toggle="tooltip" title="登陆"  data-placement="bottom"><a href="#login_modal"  role="button" data-toggle="modal"><i  class="icon-nav-user icon-nav"></i></a></li>
	      {{/if}}      
	  	   <li id="nav_fav_icon" data-toggle="tooltip"  title="收藏本站"  data-placement="bottom"><a  href="javascript:void(0)"><i class="icon-nav-star icon-nav"></i></a></li>
	  	   <li data-toggle="tooltip" title="官方微博"  data-placement="bottom"><a href="http://weibo.com/u/3436468640"  rel="nofollow" target="_blank"><i class='weibo_logo'></i></a></li>
	  	   <li data-toggle="tooltip" title=""  data-placement="bottom"><a href="javascript:void(0)"  id="toggle_sound_btn"><i class='icon-sound'></i></a></li>
	      </li>
		</ul>
	  </div>
	</div>
	</div>
{{if !$isLogin}}
<div id="login_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">请先登陆哦</h3>
  </div>
  <div class="modal-body">
    <a class="btn btn-primary btn-large login_modal_btn" href="https://api.weibo.com/oauth2/authorize?client_id=2885675204&redirect_uri={{$redirect_url}}&response_type=code"><i class="weibo_logo"></i>用新浪微博账户登陆</a>
  </div>
</div>
{{else}}
<div id="bind_twitter_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">绑定twitter账号</h3>
  </div>
  <div class="modal-body">
  	<p>点击跳转到twitter进行应用授权【请忽视跳转页面浏览器的警告信息】</p>
  	<p>绑定后评论将会与twiiter同步，发布twitter，其它的功能我们正在开发中</p>
    <a class="btn btn-primary btn-large login_modal_btn" href="http://yuandupi.com/twitter_api/redirect.php"><i class="icon-twitter icon-white"></i>绑定</a>
  </div>
</div>
<div id="rt_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">转发至新浪微博<i class="weibo_logo_red"></i></h3>
  </div>
    <div class="modal-body">
    <textarea id="rt_textarea"  class="rt_com" max_cn_length="140"></textarea>
    <p class="rt_com"> 剩余字数：<span>*</span></p>
    <img id="rt_img" src="{{$base_url}}img/noimg.jpg" />
    <div id="rt_loading">
    <img src="{{$base_url}}img/loading_simple.gif"/>
    </div>
    <div class="alert alert-success rt_notice">
	  <strong>发布成功!</strong> 
	</div>
    <div class="alert alert-error rt_notice">
	  <strong>sorry,发布失败,请稍后再试</strong> 
	</div>
	</div>
  <div class="modal-footer rt_com">
    <a class="btn btn-primary login_modal_btn" href="javascript:void(0)" id="rt_send_btn">转发</a>
  </div>
</div>
<script type="text/javascript">
var weibo_uname = '{{$user.weibo_uname}}';
var weibo_uid = {{$user.weibo_id}};
var weibo_profile_image ='{{$user.weibo_profile_image}}';
</script>
{{/if}}
<a href="javascript:void(0);"><div id="back-top" class="palette palette-silver"><img src="{{$base_url}}img/space_art.png"/></div></a>