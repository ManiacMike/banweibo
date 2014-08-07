var input = $("form input:text").css({color:"#999"});
input.focus(function(){
	if(input.val() == default_search)
	{
		$(this).val("");
		$(this).css({color:"#222"});
	}
});
input.blur(function(){
	if(input.val() == "")
	{
		$(this).val(default_search);
		$(this).css({color:"#999"});
	}
});
$("form").submit(function(){
	var val = $(this).children("input:text").val();
	if(val == "" || val == default_search)
	{
		return false;
	}else{
		keyword = val;
	}
});
$(document).scroll(function(){
	if($(document).scrollTop()>($(window).height()+200))
	{
		$("#back-top").show();
	}else{
		$("#back-top").hide();
	}
	if($(document).height()- $(document).scrollTop()<($(window).height()+200) && feed_request_ready==true){
		feed_request_ready = false;
		$(".loading_search").show();
		$.post(base_url+"/search/result/",{"q":keyword,"page":request_row},function(data){
			if(data !='')
			{
				$("#search_data_wall").append(data);
				request_row++;
				feed_request_ready = true;
				$(".pic img").tooltip();
				$.getScript(base_url+"/js/follow.js");
			}
			else
			{
				$(".loading_search").hide();
			}
		});
	}
});