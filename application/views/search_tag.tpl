{{include file='head.tpl'}}
<div class="container">
{{include file='nav.tpl'}}
<div class="space_div"></div>
		<div class="row">
		<div class='span3 tag_left_div'>
			<div class="palette palette-firm-dark">#{{$keyword}}#</div>
		</div>
		<div class='span9'>
		<div class='newsfeed' id="newsfeed">	
		{{include file='ajax/feed_ajax.tpl'}}	
		</div>
		<div class="feed_loading">加载中<img src="{{$base_url}}img/loading-small.gif"></div>
		</div>
	</div>
</div>
<script type="text/javascript">
var modules = ['feed'];
var request_url ='/search/tag_result/';
var request_data = {'q':'{{$keyword}}'};
var no_feed_func =1;
</script>
{{include file='footer.tpl'}}