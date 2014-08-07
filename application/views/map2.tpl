<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>话题</title>
	<meta http-equiv="Content-Style-Type" content="text/css">
	<style type="text/css">
		body {margin: 20px; color: black; background: white;}	
		#wrapper { margin: auto; width: 600px; height: auto;}
		a {color: gray; text-decoration:none;}
		a:hover {color: blue; text-decoration:none;}
		table {border: none;}
		td {vertical-align: top;}
		ul {padding-left: 0px;}
		li {font-size: 75%; list-style-type: none; padding-right: 1em;}
		small {font-size: 75%;}
		b {color: orange;}
		td span {font-size: 75%; font-family: georgia, serif; font-style: italic; color: gray;}
		#crosshair_x { 
			display: block; 
			position: absolute;
			top: 150px;
			left: 0px;
			width: 600px; 
			height: 1px;
			padding: 0px; 
			margin: 0px;
			font-size: 1px;
			line-height: 1px;
			border: none;
			background: white;
			visibility: hidden;
			opacity: 0.5;
			filter: alpha(opacity=50);
			z-index: 99;
		}
		#crosshair_y { 
			display: block; 
			position: absolute;
			top: 0px;
			left: 300px;
			width: 1px; 
			height: 300px;
			padding: 0px; 
			margin: 0px;
			font-size: 1px;
			line-height: 1px;
			border: none;
			background: white;
			visibility: hidden;
			opacity: 0.5;
			filter: alpha(opacity=50);
			z-index: 99;
		}
		
	</style>
<link rel="stylesheet" type="text/css" href="http://banweibo.com/css/tooltip.css" />
<!--[if lt IE 7]>
<style type="text/css">
#cvi_tooltip {
	width:expression(this.offsetWidth>200?'200px':'auto');
}
</style>
<![endif]-->
	<meta http-equiv="Content-Script-Type" content="text/javascript">
<script src="http://banweibo.com/js/shiftzoom.js" type="text/javascript"></script>
<script src="http://banweibo.com/js/geodata.js" type="text/javascript"></script>
<script src="http://banweibo.com/js/cvi_tip_lib.js" type="text/javascript"></script>
<script type="text/javascript">
if(typeof $=='undefined') {function $(v) {return(document.getElementById(v));}}
if(document.images&&document.createElement&&document.getElementById){ 
    document.writeln('<style type="text/css">'); 
    document.writeln('img.shiftzoom { visibility: hidden; }');
    document.writeln('<\/style>'); 
} 
shiftzoom.defaultCurpath = 'images/cursors/';
function nocrosshair(v) {
	$('crosshair_x').style.visibility='hidden';
	$('crosshair_y').style.visibility='hidden';
	var p=getGeoPosition('world',v.toUpperCase(),2700,600,1350,300); last_icon=v;
	shiftzoom.destruct($('world'),geodata['world'][v].lc);
	shiftzoom.construct($('world'),[{x:p.l, y:p.t, w:53, h:64, id:geodata['world'][v].lc, pos:7, title:'', href:'http://maps.google.com/?q='+geodata['world'][v].sn, target:'Google_Maps', src:'images/icons/pin_out.png', src2:'images/icons/pin_over.png'}]);
	cvi_tip.add(cvi_tip.$(geodata['world'][v].lc),'<small>CONTINENT</small><br/><big><b><u>'+geodata['world'][v].cc+'</u></b></big><br/><small>COUNTRY</small><br/><big><b>'+geodata['world'][v].sn+'</b></big><br/><small>CAPITAL</small><br/><big><b><i>'+geodata['world'][v].cn+'</i></b></big>');
}
function clearicons() {last_icon=""; shiftzoom.destruct($('world'),true);}
function get_Country(v) {
	$('crosshair_x').style.visibility='visible';
	$('crosshair_y').style.visibility='visible';
	if(last_icon!='') {
		shiftzoom.destruct($('world'),geodata['world'][last_icon].lc);
		var q=getGeoPosition('world',last_icon.toUpperCase(),2700,600,1350,300);
		shiftzoom.construct($('world'),[{x:q.l, y:q.t, w:28, h:48, id:geodata['world'][last_icon].lc, pos:7, title:'', href:'http://maps.google.com/?q='+geodata['world'][last_icon].sn, target:'Google_Maps', src:'images/icons/pin_flag.png'}]);
		cvi_tip.add(cvi_tip.$(geodata['world'][last_icon].lc),'<small>CONTINENT</small><br/><big><b><u>'+geodata['world'][last_icon].cc+'</u></b></big><br/><small>COUNTRY</small><br/><big><b>'+geodata['world'][last_icon].sn+'</b></big><br/><small>CAPITAL</small><br/><big><b><i>'+geodata['world'][last_icon].cn+'</i></b></big>');
	}
	var p=getGeoPosition('world',v.toUpperCase(),2700,600,1350,300);
	shiftzoom.kenburns($('world'),[p.x,p.y,p.z,3,false,false,'nocrosshair',v]);
}
function getGeoPosition(map,lc,xw,iw,xh,ih) {
	function lat2y(lat,h) {return ((lat*-1)+90)*(h/180);};
	function lng2x(lng,w) {return (lng+180)*(w/360);};
	var x,y,z,f,l,t,k,x1,y1,x2,y2,f=(iw/xw)*100,s=100,d=geodata[map][lc];
	x1=lng2x(d.bw,s); y1=lat2y(d.bn,s); x2=lng2x(d.be,s); y2=lat2y(d.bs,s);	
	x=((x2-x1)/2)+x1; y=((y2-y1)/2)+y1; k=Math.max(x2-x1,y2-y1); 
	z=s-(k<f?0:k); l=parseFloat((x/100)*xw); t=parseFloat((y/100)*xh);
	f=x/100; x1=f*((xw-iw)*(z/100)); x2=(0.5-f)*iw; x=(((x1-x2))/((xw-iw)*(z/100)))*100;
	f=y/100; y1=f*((xh-ih)*(z/100)); y2=(0.5-f)*ih; y=(((y1-y2))/((xh-ih)*(z/100)))*100;
	return {x:x,y:y,z:z,l:l,t:t};
}
var last_icon="";
</script>
</head>
<body>
<div id="wrapper">
<div style="width:600px; height:300px; background: url(http://banweibo.com/images/earth_low.jpg) 50% 50% no-repeat; -webkit-box-shadow: 0px 0px 8px black; -moz-box-shadow: 0px 0px 8px black;"><img id="world" class="shiftzoom" onLoad="shiftzoom.add(this,{fading:true,showcoords:true,pixelcoords:false,lowres:'http://banweibo.com/images/earth_low.jpg'});" src="http://banweibo.com/images/earth_high.jpg" width="600" height="300" alt="large image" border="0" /><div id="crosshair_x"></div><div id="crosshair_y"></div></div>
<table>
<tr><td colspan="4" align="left"><span>...using user commands: <b>construct</b>, <b>destruct</b>, and <b>kenburns</b></span></td><td align="right"><small><a href="javascript:clearicons();">Remove all icons</a></small></td><td>
<tr><td>
<ul>North America
<li><a title="Capital: Ottawa" onclick="get_Country('CA');" href="#">Canada</a></li>
<li><a title="Capital: Washington D.C." onclick="get_Country('US');" href="#">United States</a></li>
</ul>
<ul>Central America
<li><a title="Capital: Belmopan" onclick="get_Country('BZ');" href="#">Belize</a></li>
<li><a title="Capital: San Jose" onclick="get_Country('CR');" href="#">Costa Rica</a></li>
<li><a title="Capital: San Salvador" onclick="get_Country('SV');" href="#">El Salvador</a></li>
<li><a title="Capital: Guatemala City" onclick="get_Country('GT');" href="#">Guatemala</a></li>
<li><a title="Capital: Tegucigalpa" onclick="get_Country('HN');" href="#">Honduras</a></li>
<li><a title="Capital: Mexico City" onclick="get_Country('MX');" href="#">Mexico</a></li>
<li><a title="Capital: Managua" onclick="get_Country('NI');" href="#">Nicaragua</a></li>
<li><a title="Capital: Panama City" onclick="get_Country('PA');" href="#">Panama</a></li>
</ul>
<ul>Caribbean
<li><a title="Capital: Havana" onclick="get_Country('CU');" href="#">Cuba</a></li>
<li><a title="Capital: Santo Domingo" onclick="get_Country('DO');" href="#">Dominican Republic</a></li>
<li><a title="Capital: Port-au-Prince" onclick="get_Country('HT');" href="#">Haiti</a></li>
<li><a title="Capital: Kingston" onclick="get_Country('JM');" href="#">Jamaica</a></li>
<li><a title="Capital: San Juan" onclick="get_Country('PR');" href="#">Puerto Rico</a></li>
</ul>
<ul>South America
<li><a title="Capital: Buenos Aires" onclick="get_Country('AR');" href="#">Argentina</a></li>
<li><a title="Capital: Brasilia" onclick="get_Country('BR');" href="#">Brazil</a></li>
<li><a title="Capital: Sucre" onclick="get_Country('BO');" href="#">Bolivia</a></li>
<li><a title="Capital: Santiago" onclick="get_Country('CL');" href="#">Chile</a></li>
<li><a title="Capital: Bogota" onclick="get_Country('CO');" href="#">Colombia</a></li>
<li><a title="Capital: Quito" onclick="get_Country('EC');" href="#">Ecuador</a></li>
<li><a title="Capital: Cayenne" onclick="get_Country('GF');" href="#">French Guiana</a></li>
<li><a title="Capital: Georgetown" onclick="get_Country('GY');" href="#">Guyana</a></li>
<li><a title="Capital: Asuncion" onclick="get_Country('PY');" href="#">Paraguay</a></li>
<li><a title="Capital: Lima" onclick="get_Country('PE');" href="#">Peru</a></li>
<li><a title="Capital: Paramaribo" onclick="get_Country('SR');" href="#">Suriname</a></li>
<li><a title="Capital: Port-of-Spain" onclick="get_Country('TT');" href="#">Trinidad and Tobago</a></li>
<li><a title="Capital: Montevideo" onclick="get_Country('UY');" href="#">Uruguay</a></li>
<li><a title="Capital: Caracas" onclick="get_Country('VE');" href="#">Venezuela</a></li>
</ul>
</td><td>
<ul>Europe
<li><a title="Capital: Tirane" onclick="get_Country('AL');" href="#">Albania</a></li>
<li><a title="Capital: Vienna" onclick="get_Country('AT');" href="#">Austria</a></li>
<li><a title="Capital: Minsk" onclick="get_Country('BY');" href="#">Belarus</a></li>
<li><a title="Capital: Brussels" onclick="get_Country('BE');" href="#">Belgium</a></li>
<li><a title="Capital: Sarajevo" onclick="get_Country('BA');" href="#">Bosnia Herzegovina</a></li>
<li><a title="Capital: Sofia" onclick="get_Country('BG');" href="#">Bulgaria</a></li>
<li><a title="Capital: Zagreb" onclick="get_Country('HR');" href="#">Croatia</a></li>
<li><a title="Capital: Nicosia" onclick="get_Country('CY');" href="#">Cyprus</a></li>
<li><a title="Capital: Prague" onclick="get_Country('CZ');" href="#">Czech Republic</a></li>
<li><a title="Capital: Copenhagen" onclick="get_Country('DK');" href="#">Denmark</a></li>
<li><a title="Capital: Tallinn" onclick="get_Country('EE');" href="#">Estonia</a></li>
<li><a title="Capital: Helsinki" onclick="get_Country('FI');" href="#">Finland</a></li>
<li><a title="Capital: Paris" onclick="get_Country('FR');" href="#">France</a></li>
<li><a title="Capital: Berlin" onclick="get_Country('DE');" href="#">Germany</a></li>
<li><a title="Capital: Athens" onclick="get_Country('GR');" href="#">Greece</a></li>
<li><a title="Capital: Nuuk" onclick="get_Country('GL');" href="#">Greenland</a></li>
<li><a title="Capital: Budapest" onclick="get_Country('HU');" href="#">Hungary</a></li>
<li><a title="Capital: Reykjavik" onclick="get_Country('IS');" href="#">Iceland</a></li>
<li><a title="Capital: Dublin" onclick="get_Country('IE');" href="#">Ireland</a></li>
<li><a title="Capital: Rome" onclick="get_Country('IT');" href="#">Italy</a></li>
<li><a title="Capital: Riga" onclick="get_Country('LV');" href="#">Latvia</a></li>
<li><a title="Capital: Vilnius" onclick="get_Country('LT');" href="#">Lithuania</a></li>
<li><a title="Capital: Luxembourg" onclick="get_Country('LU');" href="#">Luxembourg</a></li>
<li><a title="Capital: Skopje" onclick="get_Country('MK');" href="#">Macedonia</a></li>
<li><a title="Capital: Chisinau" onclick="get_Country('MD');" href="#">Moldova</a></li>
<li><a title="Capital: Podgorica" onclick="get_Country('ME');" href="#">Montenegro</a></li>
<li><a title="Capital: Amsterdam" onclick="get_Country('NL');" href="#">Netherland</a></li>
<li><a title="Capital: Oslo" onclick="get_Country('NO');" href="#">Norway</a></li>
<li><a title="Capital: Warsaw" onclick="get_Country('PL');" href="#">Poland</a></li>
<li><a title="Capital: Lisbon" onclick="get_Country('PT');" href="#">Portugal</a></li>
<li><a title="Capital: Bucharest" onclick="get_Country('RO');" href="#">Romania</a></li>
<li><a title="Capital: Belgrade" onclick="get_Country('RS');" href="#">Serbia</a></li>
<li><a title="Capital: Bratislava" onclick="get_Country('SK');" href="#">Slovakia</a></li>
<li><a title="Capital: Ljubljana" onclick="get_Country('SI');" href="#">Slovenia</a></li>
<li><a title="Capital: Madrid" onclick="get_Country('ES');" href="#">Spain</a></li>
<li><a title="Capital: Stockholm" onclick="get_Country('SE');" href="#">Sweden</a></li>
<li><a title="Capital: Bern" onclick="get_Country('CH');" href="#">Switzerland</a></li>
<li><a title="Capital: Kiev" onclick="get_Country('UA');" href="#">Ukraine</a></li>
<li><a title="Capital: London" onclick="get_Country('GB');" href="#">United Kingdom</a></li>
</ul>
</td><td>
<ul>Africa
<li><a title="Capital: Algers" onclick="get_Country('DZ');" href="#">Algeria</a></li>
<li><a title="Capital: Luanda" onclick="get_Country('AO');" href="#">Angola</a></li>
<li><a title="Capital: Port-Novo" onclick="get_Country('BJ');" href="#">Benin</a></li>
<li><a title="Capital: Gaborone" onclick="get_Country('BW');" href="#">Botswana</a></li>
<li><a title="Capital: Ouagadougou" onclick="get_Country('BF');" href="#">Burkina Faso</a></li>
<li><a title="Capital: Bujumbura" onclick="get_Country('BI');" href="#">Burundi</a></li>
<li><a title="Capital: Yaounde" onclick="get_Country('CM');" href="#">Cameroon</a></li>
<li><a title="Capital: Bangui" onclick="get_Country('CF');" href="#">Central African Republic</a></li>
<li><a title="Capital: N'Djamena" onclick="get_Country('TD');" href="#">Chad</a></li>
<li><a title="Capital: Brazzaville" onclick="get_Country('CG');" href="#">Congo</a></li>
<li><a title="Capital: Kinshasa" onclick="get_Country('CD');" href="#">Democratic Republic of the Congo</a></li>
<li><a title="Capital: Djibouti" onclick="get_Country('DJ');" href="#">Djibouti</a></li>
<li><a title="Capital: Cairo" onclick="get_Country('EG');" href="#">Egypt</a></li>
<li><a title="Capital: Malabo" onclick="get_Country('GQ');" href="#">Equatorial Guinea</a></li>
<li><a title="Capital: Asmara" onclick="get_Country('ER');" href="#">Eritrea</a></li>
<li><a title="Capital: Addis Ababa" onclick="get_Country('ET');" href="#">Ethiopia</a></li>
<li><a title="Capital: Liberville" onclick="get_Country('GA');" href="#">Gabon</a></li>
<li><a title="Capital: Banjul" onclick="get_Country('GM');" href="#">Gambia</a></li>
<li><a title="Capital: Accra" onclick="get_Country('GH');" href="#">Ghana</a></li>
<li><a title="Capital: Conakry" onclick="get_Country('GN');" href="#">Guinea</a></li>
<li><a title="Capital: Bissau" onclick="get_Country('GW');" href="#">Guinea-Bissau</a></li>
<li><a title="Capital: Yamoussoukro" onclick="get_Country('CI');" href="#">Ivory Coast</a></li>
<li><a title="Capital: Nairobi" onclick="get_Country('KE');" href="#">Kenya</a></li>
<li><a title="Capital: Maseru" onclick="get_Country('LS');" href="#">Lesotho</a></li>
<li><a title="Capital: Monrovia" onclick="get_Country('LR');" href="#">Liberia</a></li>
<li><a title="Capital: Tripoli" onclick="get_Country('LY');" href="#">Libya</a></li>
<li><a title="Capital: Antananarivo" onclick="get_Country('MG');" href="#">Madagascar</a></li>
<li><a title="Capital: Lilongwe" onclick="get_Country('MW');" href="#">Malawi</a></li>
<li><a title="Capital: Bamako" onclick="get_Country('ML');" href="#">Mali</a></li>
<li><a title="Capital: Nouakchott" onclick="get_Country('MR');" href="#">Mauritania</a></li>
<li><a title="Capital: Rabat" onclick="get_Country('MA');" href="#">Morocco</a></li>
<li><a title="Capital: Maputo" onclick="get_Country('MZ');" href="#">Mozambique</a></li>
<li><a title="Capital: Windhoek" onclick="get_Country('NA');" href="#">Namibia</a></li>
<li><a title="Capital: Niamey" onclick="get_Country('NE');" href="#">Niger</a></li>
<li><a title="Capital: Abuja" onclick="get_Country('NG');" href="#">Nigeria</a></li>
<li><a title="Capital: Kigali" onclick="get_Country('RW');" href="#">Rwanda</a></li>
<li><a title="Capital: Dakar" onclick="get_Country('SN');" href="#">Senegal</a></li>
</ul>
</td><td>
<ul>Africa
<li><a title="Capital: Freetown" onclick="get_Country('SL');" href="#">Sierra Leone</a></li>
<li><a title="Capital: Mogadishu" onclick="get_Country('SO');" href="#">Somalia</a></li>
<li><a title="Capital: Tshwane/Pretoria" onclick="get_Country('ZA');" href="#">South Africa</a></li>
<li><a title="Capital: Khartoum" onclick="get_Country('SD');" href="#">Sudan</a></li>
<li><a title="Capital: Mbabane" onclick="get_Country('SZ');" href="#">Swaziland</a></li>
<li><a title="Capital: Dodoma" onclick="get_Country('TZ');" href="#">Tanzania</a></li>
<li><a title="Capital: Lome" onclick="get_Country('TG');" href="#">Togo</a></li>
<li><a title="Capital: Tunis" onclick="get_Country('TN');" href="#">Tunisia</a></li>
<li><a title="Capital: Kampala" onclick="get_Country('UG');" href="#">Uganda</a></li>
<li><a title="Capital: El Aaiún" onclick="get_Country('EH');" href="#">Western Sahara</a></li>
<li><a title="Capital: Lusaka" onclick="get_Country('ZM');" href="#">Zambia</a></li>
<li><a title="Capital: Harare" onclick="get_Country('ZW');" href="#">Zimbabwe</a></li>
</ul>
<ul>Middle East
<li><a title="Capital: Kabul" onclick="get_Country('AF');" href="#">Afghanistan</a></li>
<li><a title="Capital: Yerevan" onclick="get_Country('AM');" href="#">Armenia</a></li>
<li><a title="Capital: Baku" onclick="get_Country('AZ');" href="#">Azerbaijan</a></li>
<li><a title="Capital: Tbilisi" onclick="get_Country('GE');" href="#">Georgia</a></li>
<li><a title="Capital: Tehran" onclick="get_Country('IR');" href="#">Iran</a></li>
<li><a title="Capital: Baghdad" onclick="get_Country('IQ');" href="#">Iraq</a></li>
<li><a title="Capital: Jerusalem" onclick="get_Country('IL');" href="#">Israel</a></li>
<li><a title="Capital: Amman" onclick="get_Country('JO');" href="#">Jordan</a></li>
<li><a title="Capital: Kuwait City" onclick="get_Country('KW');" href="#">Kuwait</a></li>
<li><a title="Capital: Beirut" onclick="get_Country('LB');" href="#">Lebanon</a></li>
<li><a title="Capital: Muscat" onclick="get_Country('OM');" href="#">Oman</a></li>
<li><a title="Capital: Islamabad" onclick="get_Country('PK');" href="#">Pakistan</a></li>
<li><a title="Capital: Doha" onclick="get_Country('QA');" href="#">Qatar</a></li>
<li><a title="Capital: Riyadh" onclick="get_Country('SA');" href="#">Saudi Arabia</a></li>
<li><a title="Capital: Damascus" onclick="get_Country('SY');" href="#">Syria</a></li>
<li><a title="Capital: Dushanbe" onclick="get_Country('TJ');" href="#">Tajikistan</a></li>
<li><a title="Capital: Ankara" onclick="get_Country('TR');" href="#">Turkey</a></li>
<li><a title="Capital: Ashgabat" onclick="get_Country('TM');" href="#">Turkmenistan</a></li>
<li><a title="Capital: Abu Dhabi" onclick="get_Country('AE');" href="#">United Arab Emirates</a></li>
<li><a title="Capital: Sana" onclick="get_Country('YE');" href="#">Yemen</a></li>
</ul>
</td><td>
<ul>Asia
<li><a title="Capital: Astana" onclick="get_Country('KZ');" href="#">Kazakhstan</a></li>
<li><a title="Capital: Bishkek" onclick="get_Country('KG');" href="#">Kyrgyzstan</a></li>
<li><a title="Capital: Ulan Bator" onclick="get_Country('MN');" href="#">Mongolia</a></li>
<li><a title="Capital: Moscow" onclick="get_Country('RU');" href="#">Russia</a></li>
<li><a title="Capital: Tashkent" onclick="get_Country('UZ');" href="#">Uzbekistan</a></li>
</ul>
<ul>South East Asia
<li><a title="Capital: Dhaka" onclick="get_Country('BD');" href="#">Bangladesh</a></li>
<li><a title="Capital: Bander Seri Begawan" onclick="get_Country('BN');" href="#">Brunei</a></li>
<li><a title="Capital: Thimphu" onclick="get_Country('BT');" href="#">Buthan</a></li>
<li><a title="Capital: Phnom Penh" onclick="get_Country('KH');" href="#">Cambodia</a></li>
<li><a title="Capital: Beijing" onclick="get_Country('CN');" href="#">China</a></li>
<li><a title="Capital: New Delhi" onclick="get_Country('IN');" href="#">India</a></li>
<li><a title="Capital: Jakarta" onclick="get_Country('ID');" href="#">Indonesia</a></li>
<li><a title="Capital: Tokyo" onclick="get_Country('JP');" href="#">Japan</a></li>
<li><a title="Capital: Vientiane" onclick="get_Country('LA');" href="#">Laos</a></li>
<li><a title="Capital: Kuala Lumpur" onclick="get_Country('MY');" href="#">Malaysia</a></li>
<li><a title="Capital: Yangon" onclick="get_Country('MM');" href="#">Myanmar</a></li>
<li><a title="Capital: Kathmandu" onclick="get_Country('NP');" href="#">Nepal</a></li>
<li><a title="Capital: Pyongyang" onclick="get_Country('KP');" href="#">North Korea</a></li>
<li><a title="Capital: Manila" onclick="get_Country('PH');" href="#">Philippines</a></li>
<li><a title="Capital: Seoul" onclick="get_Country('KR');" href="#">South Korea</a></li>
<li><a title="Capital: Colombo" onclick="get_Country('LK');" href="#">Sri Lanka</a></li>
<li><a title="Capital: Taipei" onclick="get_Country('TW');" href="#">Taiwan</a></li>
<li><a title="Capital: Bangkok" onclick="get_Country('TH');" href="#">Thailand</a></li>
<li><a title="Capital: Hanoi" onclick="get_Country('VN');" href="#">Viet Nam</a></li>
</ul>
<ul>Oceania
<li><a title="Capital: Canberra" onclick="get_Country('AU');" href="#">Australia</a></li>
<li><a title="Capital: Wellington" onclick="get_Country('NZ');" href="#">New Zealand</a></li>
<li><a title="Capital: Port Moresby" onclick="get_Country('PG');" href="#">Papua New Guinea</a></li>
</ul>
</td></tr>
</table>
</div>
</body>
</html>