$("ul#list_page_ul > li a").click(function(){
	$("#select_data").html("<div class=\"loading_gif\"><img src='"+base_url+"/img/loading_simple_blue.gif'/></div>");
	request_list_data(categoryId,subCategoryId,$(this).attr('href').replace(base_url,"").replace("/",""),270);
	historyUrl.push({"categoryId":categoryId,"subCategoryId":subCategoryId,"page":$(this).attr('href').replace(base_url,"").replace("/",""),"offset":270});
	historyCur=0;
	return false;
});
$("ul#sub_category > li").unbind("click");
$("ul#sub_category > li").click(function(){
	$("#select_data").html("<div class=\"loading_gif\"><img src='"+base_url+"/img/loading_simple_blue.gif'/></div>");
	if($(this).hasClass('li_active'))
	{
		request_id = 0;
	}else{
		request_id = $(this).children("a").attr('data-id');
	}
	$("ul#sub_category > li").each(function(){
		if($(this).hasClass('li_active'))
		{
			$(this).removeClass('li_active');
			$(this).attr("title","");
		}
	});
	if(request_id != 0){
		$(this).addClass('li_active');
		$(this).attr("title","点击取消选中");
	}
	request_list_data(categoryId,request_id,1,200);
	subCategoryId = request_id;
	historyUrl.push({"categoryId":categoryId,"subCategoryId":subCategoryId,"page":1,"offset":200});
	historyCur=0;
	return false;
});