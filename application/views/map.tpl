<html>
<body style="margin:0px;">
<img src="http://banweibo.com/img/map4704.jpg" onclick="GetPosition(event)"/>
<script>
//onclick="GetPosition(event)"
function GetPosition(e) {
var x = getX(e);
var y = getY(e);
alert(x+"|||"+y);
}
function getX(e) {
e = e || window.event;
return e.pageX || e.clientX + document.body.scroolLeft;
}
function getY(e) {
e = e|| window.event;
return e.pageY || e.clientY + document.boyd.scrollTop;
}
</script>
</body>
</html>