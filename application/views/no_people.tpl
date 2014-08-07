{{include file='head.tpl'}}
<div class="container">
{{include file='nav.tpl'}}
<div class="space_div_2"></div>
<div class="space_div_2"></div>
<div class="row">
<div class="tile span4 offset4">
            <img class="tile-image" alt="" src="{{$base_url}}images/illustrations/infinity.png">
            <h3 class="tile-title">搬微博暂时没有接入该账号数据</h3>
            <div class="space_div"></div>
            <p>想了解更多或推荐，去搜搜看看{{$screen_name}}</p>
           	<br>
            <a class="btn btn-primary btn-large btn-block" href="{{$base_url}}search/{{if $screen_name neq "用户"}}?q={{$screen_name}}{{/if}}">搜索</a>
          </div>
 </div>
 <div class="space_div_2"></div>
<div class="space_div_2"></div>
</div>
{{include file='footer.tpl'}}