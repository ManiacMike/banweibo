<html  xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	{{if $redirect}}<meta http-equiv="refresh" content="{{$redirect_time}};url={{$redirect}}">{{/if}}
	<title>搬微博</title>
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="icon" type="image/gif" href="favicon.gif" />
	<link href="http://banweibo.com/css/bootstrap.min.css" rel="stylesheet" media="screen"/>
	<link href="http://banweibo.com/css/flat-ui.css" rel="stylesheet"/>
	<link href="http://banweibo.com/css/global.css" rel="stylesheet"/>
</head>
<body>
<div class="container">
	<div class="navbar navbar-fixed-top navbar-inverse">
	  <div class="navbar-inner" id="top_nav_div">
	  	<div class="container">
	    <a class="brand" href="http://banweibo.com/"><img src="http://banweibo.com/img/logo/logo_black.jpg"></a>
	    <ul class="nav" id="top_nav">
	      <li><a href="http://banweibo.com/">首页 <i class="icon-home icon-white"></i></a></li>
	      <li><a href="http://banweibo.com/select/">人物 <i class="icon-star icon-white"></i></a></li>
	      <li><a href="http://banweibo.com/hot/">热门 <i class="icon-fire icon-white"></i></a></li>
	      <li><a href="http://banweibo.com/search/">搜索 <i class="icon-search icon-white"></i></a></li>
	    </ul>
	  </div>
	</div>
	</div>
</div>
<div class="container">
<div style="margin-top:300px;font-size:25px;text-align:center;"><strong>{{$message}}</strong></div>
</div>
</body>
</html>