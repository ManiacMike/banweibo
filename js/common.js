var base_url = "http://banweibo.com";
var default_bg = "http://banweibo.com/img/bg/bg1.png";
if(typeof(modules) !="undefined")
{
	for(var i in modules)
	{
		$.getScript(base_url+"/js/"+modules[i]+".js");
	}
}
function get_alert_html(text,type){
	return "<div class=\"alert alert-self alert-"+type+"\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>"+text+"</div>"
}
function rt_preg(s) {
	var p = /<a.*?href=['"\s].*?(.*?)['"\s].*?.*?>(.*?)<\/a>/img;
	var r, str ,a = new Array();
	while ((r = p.exec(s)) != null) {
		if (r[2].indexOf('@') > -1) {
			a[r[2]] = r[0];
		} else if (r[2].indexOf('#') > -1) {
			a[r[2]+"#"] = r[0];
		} else if (r[2].indexOf('http://t.co') == 0) {
			a[r[1]] = r[0];
		}
	}
	for(var i in a)
	{
		s = s.replace(a[i],i);
	}
	return s;
}
function getByteLen(val) {
    var len = 0;
    for (var i = 0; i < val.length; i++) {
        if (val[i].match(/[^\x00-\xff]/ig) != null) //全角
            len += 2;
        else
            len += 1;
    }
    return len;
}
function getByteVal(val, max) {
    var returnValue = '';
    var byteValLen = 0;
    for (var i = 0; i < val.length; i++) {
        if (val[i].match(/[^\x00-\xff]/ig) != null)
            byteValLen += 2;
        else
            byteValLen += 1;

        if (byteValLen > max*2)
            break;

        returnValue += val[i];
    }
    return returnValue;
}
$('#back-top').click(function () {
	$('body,html').animate({
		scrollTop: 0
	}, 'fast');
	return false;
});
function add_fav(title) {
	try {
		window.external.addFavorite(base_url, title);
	} catch (e) {
		try {
			window.sidebar.addPanel(title, base_url, "");
		} catch (e) {
			alert("\u60a8\u53ef\u4ee5\u5c1d\u8bd5\u901a\u8fc7\u5feb\u6377\u952e Ctrl+D \u52a0\u5165\u5230\u6536\u85cf\u5939~");
		}
	}
	return false;
}
$("#nav_fav_icon").click(function(){
	add_fav("搬微博");
});
if(st.sget("sound_on") == 1)
{
	$("#toggle_sound_btn").parent().attr("title","点击关闭声音");
	$("#toggle_sound_btn").children().addClass("icon-sound-on");
}else{
	$("#toggle_sound_btn").parent().attr("title","点击开启声音");
	$("#toggle_sound_btn").children().addClass("icon-sound-off");
}
$("#toggle_sound_btn").click(function(){
	if(st.sget("sound_on") == 1){
		st.sdel("sound_on");
		$("#toggle_sound_btn").parent().attr("title","点击开启声音").attr("data-original-title","点击开启声音");;
		$("#toggle_sound_btn").children().removeClass("icon-sound-on").addClass("icon-sound-off");
	}else{
		st.sset("sound_on",1);
		$("#toggle_sound_btn").parent().attr("title","点击关闭声音").attr("data-original-title","点击关闭声音");
		$("#toggle_sound_btn").children().removeClass("icon-sound-off").addClass("icon-sound-on");
	}
	location.reload();
});
$("#toggle_sound_btn").parent().hide();
//if(st.sget('sound_on')==1)
//	fartscroll(400);
$("#sub_nav li").tooltip();