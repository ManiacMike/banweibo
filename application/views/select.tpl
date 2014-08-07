{{include file='head.tpl'}}
<div class="container">
<script type="text/javascript">
var categoryId ={{$categoryId}};
var subCategoryId ={{$subCategoryId}};
</script>
{{include file='nav.tpl'}}
		<div class="space_div_2"></div>
		<div class="select_box">
		<p class="pic_wall_notice">&nbsp&nbsp已接入{{$total}}个账号&nbsp&nbsp&nbsp&nbsp<a target="_blank"href="{{$base_url}}about.html" title="什么是接入"><i class="icon-question-sign"></i></a></p>
			<div class="select_row">
				<ul class="inline" id="category">
  					{{foreach from=$category item=item}}
  					<li{{if $item.id == $categoryId}} class="li_active"{{/if}}><a href="javascript:void(0)" data-id="{{$item.id}}">{{$item.name}} <img title ="{{$item.cover}}" src="{{$item.cover_url}}" class="img-circle"/></a>				
  					</li>
  					{{/foreach}}
				</ul>
			</div>		
			<div class="select_row">
				<ul class="inline" id="sub_category">
				<i class="icon-tags icon-white"></i>
  					{{foreach from=$people.son item=item}}
  					<li{{if $item.id == $subCategoryId}} class="li_active"{{/if}}><a href="javascript:void(0)" data-id="{{$item.id}}">{{$item.name}}</a></li>
  					{{/foreach}}
				</ul>
			</div>
			<p id="orderby_bar"><a data-toggle="tooltip" class="btn btn-primary{{if $order=='alphabet'}} active{{/if}}" href="javacript:void(0)" title="字母排序" data-id="alphabet"><i class="icon-font icon-white"></i></a> <a data-toggle="tooltip" data-id="hot" class="btn btn-primary{{if $order=='hot'}} active{{/if}}"  href="javacript:void(0)" title="本站粉丝排序"><i class="icon-fire icon-white"></i></a> <a data-toggle="tooltip" class="btn btn-primary{{if $order=='tfans'}} active{{/if}}" href="javacript:void(0)"  title="twitter粉丝排序" data-id="tfans"><i class="icon_twitter_2 icon-white"></i></a></p>
			<p id="select_history_bar"><a class="btn btn-primary" href="javacript:void(0)" id="select_history_back"><i class="icon-arrow-left icon-white"></i></a><a  class="btn btn-primary"  id="select_history_foward" href="javacript:void(0)"><i class="icon-arrow-right icon-white"></i></a></p>
		</div>
		<div class="midline"></div>
		<div class="pic_wall_container" id="select_data">
		{{include file='ajax/select_data.tpl'}}
		</div>
</div>
<div class="space_div"></div>
<script type="text/javascript">
var modules = ['select','st'];
</script>
{{include file='footer.tpl'}}