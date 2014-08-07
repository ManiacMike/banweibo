	<div class="navbar navbar-fixed-top navbar-inverse">
	  <div class="navbar-inner" id="top_nav_div">
	  	<div class="container">
	    <a class="brand" href="{{$base_url}}"><img src="{{$base_url}}img/logo/logo_black.jpg"></a>
	    <ul class="nav" id="top_nav">
	      <li{{if $current=="index"}} class="active"{{/if}}><a href="{{$base_url}}">首页 <i class="icon-home icon-white"></i></a></li>
	      <li{{if $current=="list"}} class="active"{{/if}}><a href="{{$base_url}}select/">人物 <i class="icon-star icon-white"></i></a></li>
	      <li{{if $current=="hot"}} class="active"{{/if}}><a href="{{$base_url}}hot/">热门 <i class="icon-fire icon-white"></i></a></li>
	      <li{{if $current=="search"}} class="active"{{/if}}><a href="{{$base_url}}search/">搜索 <i class="icon-search icon-white"></i></a></li>  	  
	  	  {{if $isLogin}}
	  	  <li id="about"><a  href="{{$base_url}}about.html" target="_blank" title="关于搬微博"><i class="icon-info-sign icon-white"></i></a></li> 
	  	 {{if !$isBind}} <li id="twitter_logo"><a  role="button"  data-toggle="modal" href="#bind_twitter_modal"  title="绑定到twitter"><i class="icon-twitter icon-white"></i></a></li>{{/if}} 
	  	  <li id="quit_btn"><a onclick="return confirm('您确定要退出吗');" href="{{$quit_url}}" ><i class="icon-off icon-white"></i></a></li>
	      <li id="user" ><img class="nav_uicon" src="{{$user.weibo_profile_image}}"><span class="nav_uname">{{$user.weibo_uname}}</span>
	      {{else}}
	      <li id="about" class="about_un"><a href="{{$base_url}}about.html" target="_blank" title="关于搬微博"><i class="icon-info-sign icon-white"></i></a></li>   
	       <li id="user"><a href="https://api.weibo.com/oauth2/authorize?client_id=2885675204&redirect_uri={{$redirect_url}}&response_type=code" >登陆<i class="weibo_logo"></i></a></li>
	      {{/if}}
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
  	<p>此功能需要您有可以使用网络环境来访问twitter进行应用授权</p>
  	<p>绑定后您将可以使用twitter的所有数据</p>
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