var historyUrl = [{"categoryId":1,"subCategoryId":20,"page":1,"offset":60}];
var historyCur = 0;
//historyInit();
$("ul#category > li").click(function(){
	if(!$(this).hasClass('li_active')){
	$("#select_data").html("<div class=\"loading_gif\"><img src='"+base_url+"/img/loading_simple_blue.gif'/></div>");
		$("ul#category > li").each(function(){
			if($(this).hasClass('li_active'))
			{
				$(this).removeClass('li_active');
			}
		});
		$(this).addClass('li_active');
		request_list_data($(this).children("a").attr('data-id'),0,1,60);
		subCategoryId = 0;
		categoryId = $(this).children("a").attr('data-id');
		historyUrl.push({"categoryId":categoryId,"subCategoryId":0,"page":1,"offset":60});
		historyCur=0;
	}
	return false;
});
function request_list_data(categoryId,subCategoryId,page,offset,order)
{
	page = page ==''?1:page;
	if(typeof(order)=="undefined")
	{
		order = st.cget("list_order")?st.cget("list_order"):'alphabet';
	}
	$.post(base_url+"/select/listing/",{"categoryId":categoryId,"subCategoryId":subCategoryId,"page":page,"action":"ajax","order":order},function(data){
		if(subCategoryId==0 && page==1)
		{
			var subhtml ="";
			for(var i in data['son'])
			{
				subhtml+="<li><a href=\"javascript:void(0)\" data-id=\""+data['son'][i]['id']+"\">"+data['son'][i]['name']+"</a></li>";
			}
			if(subhtml !="")
				subhtml = "<i class=\"icon-tags icon-white\"></i>"+subhtml;
			$("#sub_category").html(subhtml);
		}
		$("#select_data").html(data['html']);
//		historyInit();
	},"json");
	$('body,html').animate({
		scrollTop: offset
	}, 0);
}
function historyInit(){
	var count = historyUrl.length;
	if(count > 1 )
	{
		$("#select_history_back").show();
	}
	if(historyCur < 0)
	{
		$("#select_history_foward").show();
	}
}
$("#select_history_back").click(function(){
	var curent = historyUrl.length+historyCur-2;
	var current_ = historyUrl[curent];
	$("#select_data").html("<div class=\"loading_gif\"><img src='"+base_url+"/img/loading_simple_blue.gif'/></div>");
	$("ul#category > li").each(function(){
		if($(this).hasClass('li_active'))
		{
			$(this).removeClass('li_active');
		}

		if($(this).children("a").attr('data-id')==current_['categoryId']){
			$(this).addClass('li_active');
		}
	});
	return false;
});
$("#orderby_bar > a").click(function(){
		if(!$(this).hasClass("active"))
		{
			$("#orderby_bar").children().each(function(){
				$(this).removeClass('active');
			});
			$(this).addClass('active');
			$("#select_data").html("<div class=\"loading_gif\"><img src='"+base_url+"/img/loading_simple_blue.gif'/></div>");
			request_list_data(categoryId,subCategoryId,1,240,$(this).attr("data-id"));
			st.cset("list_order",$(this).attr("data-id"));
		}
		return false;
});
$("#orderby_bar > a").tooltip();