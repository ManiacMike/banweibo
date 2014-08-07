/** 
 * geodata.js 1.0 (21-Jul-2008)
 * (c) by Christian Effenberger 
 * All Rights Reserved
 * Source: geomap.netzgesta.de
 * Distributed under Netzgestade Software License Agreement
 * http://www.netzgesta.de/cvi/LICENSE.txt
 * License permits free of charge
 * use on non-commercial and 
 * private web sites only 

geodata format: (utf-8)
	index ISO-3166 2-letter code
	lc == ISO-3166 3-letter code
	nc == ISO-3166 numerical code
	sn == ISO-3166 int. eng. country/state/local area name
	cc == ISO-3166 int. eng. continent/global area name
	cn == ISO-3166 int. eng. country/state capital name
	bw == bounding border coordinate west [-180|+180 float]
	be == bounding border coordinate east [-180|+180 float]
	bn == bounding border coordinate north [-90|+90 float]
	bs == bounding border coordinate south [-90|+90 float]
	
**/
/*一流素材网www.16sucai.com*/
var geodata = new Array();
geodata["world"] = {
	AQ: {nc:'010',lc:'ATA',cn:'',cc:'South Pole',sn:'Antarctica',bw:-179.999923706055,bn:-60.5155258178711,be:179.999923706055,bs:-89.9999084472656},

	CA: {nc:'124',lc:'CAN',cn:'Ottawa',cc:'North America',sn:'Canada',bw:-141.000015258789,bn:83.1106338500977,be:-52.6362838745117,bs:41.6759757995605},
	US: {nc:'840',lc:'USA',cn:'Washington D.C.',cc:'North America',sn:'United States',bw:-124.733261108398,bn:49.3886184692383,be:-66.9547958374023,bs:24.5442428588867},

	BZ: {nc:'084',lc:'BLZ',cn:'Belmopan',cc:'Central America',sn:'Belize',bw:-89.2248229980469,bn:18.4965591430664,be:-87.776969909668,bs:15.8892984390259},
	CR: {nc:'188',lc:'CRI',cn:'San Jose',cc:'Central America',sn:'Costa Rica',bw:-85.9506378173828,bn:11.2168207168579,be:-82.5559768676758,bs:8.03297328948975},
	SV: {nc:'222',lc:'SLV',cn:'San Salvador',cc:'Central America',sn:'El Salvador',bw:-90.1286697387695,bn:14.445068359375,be:-87.6921539306641,bs:13.1486778259277},
	GT: {nc:'320',lc:'GTM',cn:'Guatemala City',cc:'Central America',sn:'Guatemala',bw:-92.2363052368164,bn:17.8152236938477,be:-88.2231903076172,bs:13.7373008728027},
	HN: {nc:'340',lc:'HND',cn:'Tegucigalpa',cc:'Central America',sn:'Honduras',bw:-89.3508071899414,bn:16.5102596282959,be:-83.155387878418,bs:12.9824094772339},
	MX: {nc:'484',lc:'MEX',cn:'Mexico City',cc:'Central America',sn:'Mexico',bw:-118.453964233398,bn:32.7167663574219,be:-86.7033767700195,bs:14.5328645706177},
	NI: {nc:'558',lc:'NIC',cn:'Managua',cc:'Central America',sn:'Nicaragua',bw:-87.6903228759766,bn:15.0259103775024,be:-82.73828125,bs:10.7075414657593},
	PA: {nc:'591',lc:'PAN',cn:'Panama City',cc:'Central America',sn:'Panama',bw:-83.0514526367188,bn:9.6375150680542,be:-77.1740951538086,bs:7.19790506362915},

	CU: {nc:'192',lc:'CUB',cn:'Havana',cc:'Caribbean',sn:'Cuba',bw:-84.9574432373047,bn:23.2260456085205,be:-74.1317672729492,bs:19.8280792236328},
	DO: {nc:'214',lc:'DOM',cn:'Santo Domingo',cc:'Caribbean',sn:'Dominican Republic',bw:-72.0035018920898,bn:19.9298610687256,be:-68.3199920654297,bs:17.543155670166},
	HT: {nc:'332',lc:'HTI',cn:'Port-au-Prince',cc:'Caribbean',sn:'Haiti',bw:-74.4785919189453,bn:20.0878219604492,be:-71.6133499145508,bs:18.0210285186768},
	JM: {nc:'388',lc:'JAM',cn:'Kingston',cc:'Caribbean',sn:'Jamaica',bw:-78.3666458129883,bn:18.5269794464111,be:-76.1803131103516,bs:17.7035503387451},
	PR: {nc:'630',lc:'PRI',cn:'San Juan',cc:'Caribbean',sn:'Puerto Rico',bw:-67.9427337646484,bn:18.5201683044434,be:-65.2427291870117,bs:17.9264030456543},

	AR: {nc:'032',lc:'ARG',cn:'Buenos Aires',cc:'South America',sn:'Argentina',bw:-73.5829849243164,bn:-21.7812747955322,be:-53.5918273925781,bs:-55.0613212585449},
	BR: {nc:'076',lc:'BRA',cn:'Brasilia',cc:'South America',sn:'Brazil',bw:-73.9855499267578,bn:5.2648777961731,be:-32.392993927002,bs:-33.7507133483887},
	BO: {nc:'068',lc:'BOL',cn:'Sucre',cc:'South America',sn:'Bolivia',bw:-69.6407699584961,bn:-9.68056583404541,be:-57.4580917358398,bs:-22.8961353302002},
	CL: {nc:'152',lc:'CHL',cn:'Santiago',cc:'South America',sn:'Chile',bw:-109.455902099609,bn:-17.5075492858887,be:-66.4175491333008,bs:-55.9163551330566},
	CO: {nc:'170',lc:'COL',cn:'Bogota',cc:'South America',sn:'Colombia',bw:-81.7281188964844,bn:13.38050365448,be:-66.8698272705078,bs:-4.22586965560913},
	EC: {nc:'218',lc:'ECU',cn:'Quito',cc:'South America',sn:'Ecuador',bw:-91.6618881225586,bn:1.41893422603607,be:-75.1845779418945,bs:-4.99882364273071},
	GF: {nc:'254',lc:'GUF',cn:'Cayenne',cc:'South America',sn:'French Guiana',bw:-54.5425186157227,bn:5.77649688720703,be:-51.6139450073242,bs:2.12709355354309},
	GY: {nc:'328',lc:'GUY',cn:'Georgetown',cc:'South America',sn:'Guyana',bw:-61.3847694396973,bn:8.55756855010986,be:-56.4802436828613,bs:1.17507982254028},
	PY: {nc:'600',lc:'PRY',cn:'Asuncion',cc:'South America',sn:'Paraguay',bw:-62.6470832824707,bn:-19.294038772583,be:-54.259349822998,bs:-27.6087417602539},
	PE: {nc:'604',lc:'PER',cn:'Lima',cc:'South America',sn:'Peru',bw:-81.3267517089844,bn:-0.012976998463273,be:-68.6779708862305,bs:-18.3497314453125},
	SR: {nc:'740',lc:'SUR',cn:'Paramaribo',cc:'South America',sn:'Suriname',bw:-58.0865669250488,bn:6.00454664230347,be:-53.9774856567383,bs:1.8311448097229},
	TT: {nc:'780',lc:'TTO',cn:'Port-of-Spain',cc:'South America',sn:'Trinidad and Tobago',bw:-61.9237785339355,bn:11.3383436203003,be:-60.5179290771484,bs:10.0361032485962},
	UY: {nc:'858',lc:'URY',cn:'Montevideo',cc:'South America',sn:'Uruguay',bw:-58.4427261352539,bn:-30.0822219848633,be:-53.0739288330078,bs:-34.9808235168457},
	VE: {nc:'862',lc:'VEN',cn:'Caracas',cc:'South America',sn:'Venezuela',bw:-73.3540878295898,bn:12.201904296875,be:-59.8037757873535,bs:0.626310884952545},

	AL: {nc:'008',lc:'ALB',cn:'Tirane',cc:'Europe',sn:'Albania',bw:19.2939682006836,bn:42.6656150817871,be:21.0684757232666,bs:39.6483535766602},
	AT: {nc:'040',lc:'AUT',cn:'Vienna',cc:'Europe',sn:'Austria',bw:9.53591442108154,bn:49.0170631408691,be:17.1627254486084,bs:46.3780250549316},
	BY: {nc:'112',lc:'BLR',cn:'Minsk',cc:'Europe',sn:'Belarus',bw:23.1768856048584,bn:56.1658134460449,be:32.770809173584,bs:51.2564086914062},
	BE: {nc:'056',lc:'BEL',cn:'Brussels',cc:'Europe',sn:'Belgium',bw:2.54694366455078,bn:51.5054512023926,be:6.40386152267456,bs:49.4936027526855},
	BA: {nc:'070',lc:'BIH',cn:'Sarajevo',cc:'Europe',sn:'Bosnia Herzegovina',bw:15.7189435958862,bn:45.239200592041,be:19.6222267150879,bs:42.5461044311523},
	BG: {nc:'100',lc:'BGR',cn:'Sofia',cc:'Europe',sn:'Bulgaria',bw:22.3711624145508,bn:44.2176475524902,be:28.6121692657471,bs:41.2420768737793},
	HR: {nc:'191',lc:'HRV',cn:'Zagreb',cc:'Europe',sn:'Croatia',bw:13.4932203292847,bn:46.5387573242188,be:19.4273910522461,bs:42.4358825683594},
	CY: {nc:'196',lc:'CYP',cn:'Nicosia',cc:'Europe',sn:'Cyprus',bw:32.273078918457,bn:35.7015342712402,be:34.5979232788086,bs:34.5634918212891},
	CZ: {nc:'203',lc:'CZE',cn:'Prague',cc:'Europe',sn:'Czech Republic',bw:12.0937032699585,bn:51.0536079406738,be:18.8522205352783,bs:48.5813751220703},
	DK: {nc:'208',lc:'DNK',cn:'Copenhagen',cc:'Europe',sn:'Denmark',bw:8.07560920715332,bn:57.7484245300293,be:15.1588354110718,bs:54.5623817443848},
	EE: {nc:'233',lc:'EST',cn:'Tallinn',cc:'Europe',sn:'Estonia',bw:21.8375816345215,bn:59.6762313842773,be:28.2099742889404,bs:57.516185760498},
	FI: {nc:'246',lc:'FIN',cn:'Helsinki',cc:'Europe',sn:'Finland',bw:19.5207176208496,bn:70.096061706543,be:31.5809459686279,bs:59.8087730407715},
	FR: {nc:'250',lc:'FRA',cn:'Paris',cc:'Europe',sn:'France',bw:-5.14222288131714,bn:51.0928115844727,be:9.56155776977539,bs:41.3715744018555},
	DE: {nc:'276',lc:'DEU',cn:'Berlin',cc:'Europe',sn:'Germany',bw:5.865638256073,bn:55.0556411743164,be:15.0398902893066,bs:47.2757720947266},
	GR: {nc:'300',lc:'GRC',cn:'Athens',cc:'Europe',sn:'Greece',bw:19.3744430541992,bn:41.7574234008789,be:28.2463912963867,bs:34.8096351623535},
	GL: {nc:'304',lc:'GRL',cn:'Nuuk',cc:'Europe',sn:'Greenland',bw:-73.0420379638672,bn:83.6273651123047,be:-11.3123178482056,bs:59.7773971557617},
	HU: {nc:'348',lc:'HUN',cn:'Budapest',cc:'Europe',sn:'Hungary',bw:16.1118869781494,bn:48.5856742858887,be:22.9060020446777,bs:45.7436027526855},
	IS: {nc:'352',lc:'ISL',cn:'Reykjavik',cc:'Europe',sn:'Iceland',bw:-24.5465259552002,bn:66.5346450805664,be:-13.495813369751,bs:63.3932456970215},
	IE: {nc:'372',lc:'IRL',cn:'Dublin',cc:'Europe',sn:'Ireland',bw:-10.4785575866699,bn:55.3879241943359,be:-6.00238847732544,bs:51.4515800476074},
	IT: {nc:'380',lc:'ITA',cn:'Rome',cc:'Europe',sn:'Italy',bw:6.61488819122314,bn:47.0952033996582,be:18.51344871521,bs:36.652774810791},
	LV: {nc:'428',lc:'LVA',cn:'Riga',cc:'Europe',sn:'Latvia',bw:20.9742736816406,bn:58.0823135375977,be:28.2411689758301,bs:55.6688537597656},
	LT: {nc:'440',lc:'LTU',cn:'Vilnius',cc:'Europe',sn:'Lithuania',bw:20.9415245056152,bn:56.4469223022461,be:26.8719463348389,bs:53.9012985229492},
	LU: {nc:'442',lc:'LUX',cn:'Luxembourg',cc:'Europe',sn:'Luxembourg',bw:5.7345552444458,bn:50.1849479675293,be:6.52847290039062,bs:49.4465789794922},
	MK: {nc:'807',lc:'MKD',cn:'Skopje',cc:'Europe',sn:'Macedonia',bw:20.464693069458,bn:42.3618125915527,be:23.0381412506104,bs:40.8601875305176},
	MD: {nc:'498',lc:'MDA',cn:'Chisinau',cc:'Europe',sn:'Moldova',bw:26.6189403533936,bn:48.4901733398438,be:30.1354484558105,bs:45.468879699707},
	ME: {nc:'499',lc:'MNE',cn:'Podgorica',cc:'Europe',sn:'Montenegro',bw:18.4613037109375,bn:43.570140838623,be:20.3588352203369,bs:41.8501586914062},
	NL: {nc:'528',lc:'NLD',cn:'Amsterdam',cc:'Europe',sn:'Netherland',bw:3.36255574226379,bn:53.5122032165527,be:7.22794485092163,bs:50.7539138793945},
	NO: {nc:'578',lc:'NOR',cn:'Oslo',cc:'Europe',sn:'Norway',bw:4.65016651153564,bn:71.188117980957,be:30.9455585479736,bs:57.977912902832},
	PL: {nc:'616',lc:'POL',cn:'Warsaw',cc:'Europe',sn:'Poland',bw:14.1229982376099,bn:54.8391418457031,be:24.1507511138916,bs:49.0063591003418},
	PT: {nc:'620',lc:'PRT',cn:'Lisbon',cc:'Europe',sn:'Portugal',bw:-9.49594497680664,bn:42.1456451416016,be:-6.18269348144531,bs:36.9806594848633},
	RO: {nc:'642',lc:'ROU',cn:'Bucharest',cc:'Europe',sn:'Romania',bw:20.2699699401855,bn:48.2669525146484,be:29.6910572052002,bs:43.6272964477539},
	RS: {nc:'688',lc:'SRB',cn:'Belgrade',cc:'Europe',sn:'Serbia',bw:18.8170185089111,bn:46.1813926696777,be:23.0049991607666,bs:41.8558235168457},
	SK: {nc:'703',lc:'SVK',cn:'Bratislava',cc:'Europe',sn:'Slovakia',bw:16.8477478027344,bn:49.6031723022461,be:22.5704460144043,bs:47.7281036376953},
	SI: {nc:'705',lc:'SVN',cn:'Ljubljana',cc:'Europe',sn:'Slovenia',bw:13.3830814361572,bn:46.8779220581055,be:16.5660037994385,bs:45.4131317138672},
	ES: {nc:'724',lc:'ESP',cn:'Madrid',cc:'Europe',sn:'Spain',bw:-18.169641494751,bn:43.7917251586914,be:4.31538963317871,bs:27.6388168334961},
	SE: {nc:'752',lc:'SWE',cn:'Stockholm',cc:'Europe',sn:'Sweden',bw:11.1186923980713,bn:69.0625076293945,be:24.1608924865723,bs:55.3371047973633},
	CH: {nc:'756',lc:'CHE',cn:'Bern',cc:'Europe',sn:'Switzerland',bw:5.95747137069702,bn:47.8053359985352,be:10.491473197937,bs:45.8256874084473},
	UA: {nc:'804',lc:'UKR',cn:'Kiev',cc:'Europe',sn:'Ukraine',bw:22.128885269165,bn:52.3693695068359,be:40.2073974609375,bs:44.3904075622559},
	GB: {nc:'826',lc:'GBR',cn:'London',cc:'Europe',sn:'United Kingdom',bw:-8.62355613708496,bn:60.8458099365234,be:1.75900018215179,bs:49.9061889648438},

	DZ: {nc:'012',lc:'DZA',cn:'Algers',cc:'Africa',sn:'Algeria',bw:-8.67386913299561,bn:37.0937271118164,be:11.979549407959,bs:18.9600257873535},
	AO: {nc:'024',lc:'AGO',cn:'Luanda',cc:'Africa',sn:'Angola',bw:11.679217338562,bn:-4.3768253326416,be:24.0821228027344,bs:-18.0420780181885},
	BJ: {nc:'204',lc:'BEN',cn:'Port-Novo',cc:'Africa',sn:'Benin',bw:0.774574935436249,bn:12.4183483123779,be:3.85170125961304,bs:6.22574710845947},
	BW: {nc:'072',lc:'BWA',cn:'Gaborone',cc:'Africa',sn:'Botswana',bw:19.999532699585,bn:-17.7808094024658,be:29.3607845306396,bs:-26.9072494506836},
	BF: {nc:'854',lc:'BFA',cn:'Ouagadougou',cc:'Africa',sn:'Burkina Faso',bw:-5.51891660690308,bn:15.082594871521,be:2.40539526939392,bs:9.40110683441162},
	BI: {nc:'108',lc:'BDI',cn:'Bujumbura',cc:'Africa',sn:'Burundi',bw:28.9930572509766,bn:-2.31012272834778,be:30.8477325439453,bs:-4.46571350097656},
	CM: {nc:'120',lc:'CMR',cn:'Yaounde',cc:'Africa',sn:'Cameroon',bw:8.49476146697998,bn:13.0780572891235,be:16.1921195983887,bs:1.65254783630371},
	CF: {nc:'140',lc:'CAF',cn:'Bangui',cc:'Africa',sn:'Central African Republic',bw:14.4200954437256,bn:11.0075702667236,be:27.4634246826172,bs:2.22051358222961},
	TD: {nc:'148',lc:'TCD',cn:"N'Djamena",cc:'Africa',sn:'Chad',bw:13.4734735488892,bn:23.4503726959229,be:24.00266456604,bs:7.44106721878052},
	CG: {nc:'178',lc:'COG',cn:'Brazzaville',cc:'Africa',sn:'Congo',bw:11.2050075531006,bn:3.70308232307434,be:18.6498413085938,bs:-5.02722358703613},
	CD: {nc:'180',lc:'COD',cn:'Kinshasa',cc:'Africa',sn:'Democratic Republic of the Congo',bw:12.2041425704956,bn:5.38609886169434,be:31.3059139251709,bs:-13.4556760787964},
	DJ: {nc:'262',lc:'DJI',cn:'Djibouti',cc:'Africa',sn:'Djibouti',bw:41.7734680175781,bn:12.7068347930908,be:43.4169769287109,bs:10.9099159240723},
	EG: {nc:'818',lc:'EGY',cn:'Cairo',cc:'Africa',sn:'Egypt',bw:24.6981086730957,bn:31.6673374176025,be:35.7948684692383,bs:21.7253856658936},
	GQ: {nc:'226',lc:'GNQ',cn:'Malabo',cc:'Africa',sn:'Equatorial Guinea',bw:9.34686374664307,bn:2.34698939323425,be:11.3357257843018,bs:0.920859932899475},
	ER: {nc:'232',lc:'ERI',cn:'Asmara',cc:'Africa',sn:'Eritrea',bw:36.4387741088867,bn:18.0030860900879,be:43.1346473693848,bs:12.3595533370972},
	ET: {nc:'231',lc:'ETH',cn:'Addis Ababa',cc:'Africa',sn:'Ethiopia',bw:32.9999351501465,bn:14.8937511444092,be:47.9861831665039,bs:3.40242171287537},
	GA: {nc:'266',lc:'GAB',cn:'Liberville',cc:'Africa',sn:'Gabon',bw:8.69546985626221,bn:2.32261228561401,be:14.5023488998413,bs:-3.97880625724792},
	GM: {nc:'270',lc:'GMB',cn:'Banjul',cc:'Africa',sn:'Gambia',bw:-16.8250827789307,bn:13.8265724182129,be:-13.7977914810181,bs:13.0642509460449},
	GH: {nc:'288',lc:'GHA',cn:'Accra',cc:'Africa',sn:'Ghana',bw:-3.25542044639587,bn:11.1733026504517,be:1.19178116321564,bs:4.73672246932983},
	GN: {nc:'324',lc:'GIN',cn:'Conakry',cc:'Africa',sn:'Guinea',bw:-14.9266204833984,bn:12.6762218475342,be:-7.64107036590576,bs:7.19355249404907},
	GW: {nc:'624',lc:'GNB',cn:'Bissau',cc:'Africa',sn:'Guinea-Bissau',bw:-16.7175369262695,bn:12.6807909011841,be:-13.6365203857422,bs:10.9242639541626},
	CI: {nc:'384',lc:'CIV',cn:'Yamoussoukro',cc:'Africa',sn:'Ivory Coast',bw:-8.59930324554443,bn:10.7366437911987,be:-2.49489665031433,bs:4.35706615447998},
	KE: {nc:'404',lc:'KEN',cn:'Nairobi',cc:'Africa',sn:'Kenya',bw:33.9088516235352,bn:5.01993894577026,be:41.8990821838379,bs:-4.67804765701294},
	LS: {nc:'462',lc:'LSO',cn:'Maseru',cc:'Africa',sn:'Lesotho',bw:27.0290660858154,bn:-28.5720558166504,be:29.465763092041,bs:-30.668966293335},
	LR: {nc:'430',lc:'LBR',cn:'Monrovia',cc:'Africa',sn:'Liberia',bw:-11.4920845031738,bn:8.55179214477539,be:-7.3651123046875,bs:4.35305643081665},
	LY: {nc:'434',lc:'LBY',cn:'Tripoli',cc:'Africa',sn:'Libya',bw:9.38701820373535,bn:33.1690063476562,be:25.1506156921387,bs:19.5080413818359},
	MG: {nc:'450',lc:'MDG',cn:'Antananarivo',cc:'Africa',sn:'Madagascar',bw:43.2248687744141,bn:-11.9454317092896,be:50.4837875366211,bs:-25.6089553833008},
	MW: {nc:'454',lc:'MWI',cn:'Lilongwe',cc:'Africa',sn:'Malawi',bw:32.673942565918,bn:-9.36753940582275,be:35.9168281555176,bs:-17.1250019073486},
	ML: {nc:'466',lc:'MLI',cn:'Bamako',cc:'Africa',sn:'Mali',bw:-12.2426156997681,bn:25.0000057220459,be:4.2449688911438,bs:10.1595115661621},
	MR: {nc:'478',lc:'MRT',cn:'Nouakchott',cc:'Africa',sn:'Mauritania',bw:-17.0665245056152,bn:27.2980766296387,be:-4.82767343521118,bs:14.7155456542969},
	MA: {nc:'504',lc:'MAR',cn:'Rabat',cc:'Africa',sn:'Morocco',bw:-13.1685876846313,bn:35.9280319213867,be:-0.991749882698059,bs:27.6621112823486},
	MZ: {nc:'508',lc:'MOZ',cn:'Maputo',cc:'Africa',sn:'Mozambique',bw:30.2173156738281,bn:-10.4718818664551,be:40.8430023193359,bs:-26.868688583374},
	NA: {nc:'516',lc:'NAM',cn:'Windhoek',cc:'Africa',sn:'Namibia',bw:11.7156286239624,bn:-16.9598903656006,be:25.2567043304443,bs:-28.9714336395264},
	NE: {nc:'562',lc:'NER',cn:'Niamey',cc:'Africa',sn:'Niger',bw:0.166249975562096,bn:23.5250282287598,be:15.995644569397,bs:11.6969738006592},
	NG: {nc:'566',lc:'NGA',cn:'Abuja',cc:'Africa',sn:'Nigeria',bw:2.66843175888062,bn:13.8920087814331,be:14.6800746917725,bs:4.27714347839355},
	RW: {nc:'646',lc:'RWA',cn:'Kigali',cc:'Africa',sn:'Rwanda',bw:28.8567905426025,bn:-1.05348086357117,be:30.8959617614746,bs:-2.84067940711975},
	SN: {nc:'686',lc:'SEN',cn:'Dakar',cc:'Africa',sn:'Senegal',bw:-17.5352382659912,bn:16.6916351318359,be:-11.3558855056763,bs:12.3072738647461},
	SL: {nc:'694',lc:'SLE',cn:'Freetown',cc:'Africa',sn:'Sierra Leone',bw:-13.3076324462891,bn:10.0000009536743,be:-10.284236907959,bs:6.92961025238037},
	SO: {nc:'706',lc:'SOM',cn:'Mogadishu',cc:'Africa',sn:'Somalia',bw:40.9865875244141,bn:11.9791669845581,be:51.4126434326172,bs:-1.67486822605133},
	ZA: {nc:'710',lc:'ZAF',cn:'Tshwane/Pretoria',cc:'Africa',sn:'South Africa',bw:16.4580173492432,bn:-22.1266098022461,be:32.8959770202637,bs:-34.8398323059082},
	SD: {nc:'736',lc:'SDN',cn:'Khartoum',cc:'Africa',sn:'Sudan',bw:21.8389434814453,bn:23.1468925476074,be:38.5800361633301,bs:3.48638963699341},
	SZ: {nc:'748',lc:'SWZ',cn:'Mbabane',cc:'Africa',sn:'Swaziland',bw:30.7941036224365,bn:-25.7196445465088,be:32.137264251709,bs:-27.3171043395996},
	TZ: {nc:'834',lc:'TZA',cn:'Dodoma',cc:'Africa',sn:'Tanzania',bw:29.3271656036377,bn:-0.99073588848114,be:40.4432258605957,bs:-11.7456970214844},
	TG: {nc:'768',lc:'TGO',cn:'Lome',cc:'Africa',sn:'Togo',bw:-0.147324025630951,bn:11.1389780044556,be:1.80669319629669,bs:6.10441637039185},
	TN: {nc:'788',lc:'TUN',cn:'Tunis',cc:'Africa',sn:'Tunisia',bw:7.52483224868774,bn:37.5439224243164,be:11.5982789993286,bs:30.2404136657715},
	UG: {nc:'800',lc:'UGA',cn:'Kampala',cc:'Africa',sn:'Uganda',bw:29.5732498168945,bn:4.21442794799805,be:35.0360565185547,bs:-1.48405015468597},
	EH: {nc:'732',lc:'ESH',cn:'El Aaiún',cc:'Africa',sn:'Western Sahara',bw:-17.1031856536865,bn:27.669677734375,be:-8.67027473449707,bs:20.7741546630859},
	ZM: {nc:'894',lc:'ZMB',cn:'Lusaka',cc:'Africa',sn:'Zambia',bw:21.9993686676025,bn:-8.22435855865479,be:33.7057113647461,bs:-18.079475402832},
	ZW: {nc:'716',lc:'ZWE',cn:'Harare',cc:'Africa',sn:'Zimbabwe',bw:25.237024307251,bn:-15.6088333129883,be:33.0563125610352,bs:-22.4177417755127},

	AF: {nc:'004',lc:'AFG',cn:'Kabul',cc:'Middle East',sn:'Afghanistan',bw:60.4784355163574,bn:38.4834251403809,be:74.8794631958008,bs:29.3774700164795},
	AM: {nc:'051',lc:'ARM',cn:'Yerevan',cc:'Middle East',sn:'Armenia',bw:43.4497756958008,bn:41.3018379211426,be:49.4783973693848,bs:38.3970527648926},
	AZ: {nc:'031',lc:'AZE',cn:'Baku',cc:'Middle East',sn:'Azerbaijan',bw:44.7741088867188,bn:41.905647277832,be:50.3700904846191,bs:38.8201866149902},
	GE: {nc:'268',lc:'GEO',cn:'Tbilisi',cc:'Middle East',sn:'Georgia',bw:40.0101318359375,bn:43.5865020751953,be:46.7259750366211,bs:41.0531921386719},
	IR: {nc:'364',lc:'IRN',cn:'Tehran',cc:'Middle East',sn:'Iran',bw:44.0472717285156,bn:39.777229309082,be:63.3174781799316,bs:25.064079284668},
	IQ: {nc:'368',lc:'IRQ',cn:'Baghdad',cc:'Middle East',sn:'Iraq',bw:38.7958831787109,bn:37.3780364990234,be:48.5759201049805,bs:29.0694427490234},
	IL: {nc:'376',lc:'ISR',cn:'Jerusalem',cc:'Middle East',sn:'Israel',bw:34.2304420471191,bn:33.3401412963867,be:35.8768081665039,bs:29.4966354370117},
	JO: {nc:'400',lc:'JOR',cn:'Amman',cc:'Middle East',sn:'Jordan',bw:34.9599914550781,bn:33.3676719665527,be:39.3011741638184,bs:29.185884475708},
	KW: {nc:'414',lc:'KWT',cn:'Kuwait City',cc:'Middle East',sn:'Kuwait',bw:46.555549621582,bn:30.095947265625,be:48.4314804077148,bs:28.5246086120605},
	LB: {nc:'422',lc:'LBN',cn:'Beirut',cc:'Middle East',sn:'Lebanon',bw:35.1142730712891,bn:34.6914253234863,be:36.6391983032227,bs:33.0538558959961},
	OM: {nc:'512',lc:'OMN',cn:'Muscat',cc:'Middle East',sn:'Oman',bw:51.8819961547852,bn:26.387975692749,be:59.8365859985352,bs:16.6457462310791},
	PK: {nc:'586',lc:'PAK',cn:'Islamabad',cc:'Middle East',sn:'Pakistan',bw:60.8786087036133,bn:37.0970039367676,be:77.8409271240234,bs:23.7867183685303},
	QA: {nc:'634',lc:'QAT',cn:'Doha',cc:'Middle East',sn:'Qatar',bw:50.7572135925293,bn:26.1547241210938,be:51.636646270752,bs:24.4829406738281},
	SA: {nc:'682',lc:'SAU',cn:'Riyadh',cc:'Middle East',sn:'Saudi Arabia',bw:34.4956855773926,bn:32.1583404541016,be:55.6665878295898,bs:15.6142482757568},
	SY: {nc:'760',lc:'SYR',cn:'Damascus',cc:'Middle East',sn:'Syria',bw:35.7272148132324,bn:37.3191452026367,be:42.3850364685059,bs:32.3106575012207},
	TJ: {nc:'762',lc:'TJK',cn:'Dushanbe',cc:'Middle East',sn:'Tajikistan',bw:67.3871231079102,bn:41.0422592163086,be:75.1372299194336,bs:36.674129486084},
	TR: {nc:'792',lc:'TUR',cn:'Ankara',cc:'Middle East',sn:'Turkey',bw:25.6684989929199,bn:42.1076202392578,be:44.8350028991699,bs:35.8154106140137},
	TM: {nc:'795',lc:'TKM',cn:'Ashgabat',cc:'Middle East',sn:'Turkmenistan',bw:46.6846046447754,bn:47.0156173706055,be:66.6843109130859,bs:35.1410789489746},
	AE: {nc:'784',lc:'ARE',cn:'Abu Dhabi',cc:'Middle East',sn:'United Arab Emirates',bw:51.583324432373,bn:26.0841617584229,be:56.381664276123,bs:22.6333274841309},
	YE: {nc:'887',lc:'YEM',cn:'Sana',cc:'Middle East',sn:'Yemen',bw:42.5325241088867,bn:19.0023365020752,be:54.5305328369141,bs:12.1110811233521},

	KZ: {nc:'398',lc:'KAZ',cn:'Astana',cc:'Asia',sn:'Kazakhstan',bw:46.4918518066406,bn:55.4512023925781,be:87.3126831054688,bs:40.9363288879395},
	KG: {nc:'417',lc:'KGZ',cn:'Bishkek',cc:'Asia',sn:'Kyrgyzstan',bw:69.2765960693359,bn:43.2382278442383,be:80.2831802368164,bs:39.1728248596191},
	MN: {nc:'496',lc:'MNG',cn:'Ulan Bator',cc:'Asia',sn:'Mongolia',bw:87.7496490478516,bn:52.1542549133301,be:119.924324035645,bs:41.5676307678223},
	RU: {nc:'643',lc:'RUS',cn:'Moscow',cc:'Asia',sn:'Russia',bw:-19.25,bn:81.8573760986328,be:180.0,bs:41.1888580322266},
	UZ: {nc:'860',lc:'UZB',cn:'Tashkent',cc:'Asia',sn:'Uzbekistan',bw:55.9966316223145,bn:45.575008392334,be:73.1322860717773,bs:37.1844367980957},

	BD: {nc:'050',lc:'BGD',cn:'Dhaka',cc:'South East Asia',sn:'Bangladesh',bw:88.0283279418945,bn:26.6319484710693,be:92.6736831665039,bs:20.7433319091797},
	BN: {nc:'096',lc:'BRN',cn:'Bander Seri Begawan',cc:'South East Asia',sn:'Brunei',bw:114.071434020996,bn:5.04716777801514,be:115.35945892334,bs:4.00308227539062},
	BT: {nc:'064',lc:'BTN',cn:'Thimphu',cc:'South East Asia',sn:'Buthan',bw:88.7597122192383,bn:28.3237800598145,be:92.1252059936523,bs:26.7076377868652},
	KH: {nc:'116',lc:'KHM',cn:'Phnom Penh',cc:'South East Asia',sn:'Cambodia',bw:102.339981079102,bn:14.6864185333252,be:107.627738952637,bs:10.4090814590454},
	CN: {nc:'156',lc:'CHN',cn:'Beijing',cc:'South East Asia',sn:'China',bw:73.5576782226562,bn:53.5608673095703,be:134.773941040039,bs:15.7754144668579},
	IN: {nc:'356',lc:'IND',cn:'New Delhi',cc:'South East Asia',sn:'India',bw:68.1866760253906,bn:35.5042304992676,be:97.4033126831055,bs:6.74713850021362},
	ID: {nc:'360',lc:'IDN',cn:'Jakarta',cc:'South East Asia',sn:'Indonesia',bw:95.0093231201172,bn:5.90441751480103,be:141.021835327148,bs:-10.9418621063232},
	JP: {nc:'392',lc:'JPN',cn:'Tokyo',cc:'South East Asia',sn:'Japan',bw:122.938522338867,bn:45.5231475830078,be:145.820907592773,bs:24.2494697570801},
	TW: {nc:'158',lc:'TWN',cn:'Taipei',cc:'South East Asia',sn:'Taiwan',bw:119.534683227539,bn:25.2982521057129,be:122.000457763672,bs:21.9018039703369},
	LA: {nc:'418',lc:'LAO',cn:'Vientiane',cc:'South East Asia',sn:'Laos',bw:100.093048095703,bn:22.5003910064697,be:107.697036743164,bs:13.9100255966187},
	MY: {nc:'458',lc:'MYS',cn:'Kuala Lumpur',cc:'South East Asia',sn:'Malaysia',bw:99.643440246582,bn:7.36341762542725,be:119.267517089844,bs:0.855221927165985},
	MM: {nc:'104',lc:'MMR',cn:'Yangon',cc:'South East Asia',sn:'Myanmar',bw:92.1892700195312,bn:28.5432510375977,be:101.176795959473,bs:9.78458118438721},
	NP: {nc:'524',lc:'NPL',cn:'Kathmandu',cc:'South East Asia',sn:'Nepal',bw:80.0562591552734,bn:30.4333934783936,be:88.1993408203125,bs:26.3567199707031},
	KP: {nc:'408',lc:'PRK',cn:'Pyongyang',cc:'South East Asia',sn:'North Korea',bw:124.315872192383,bn:43.0060615539551,be:130.674896240234,bs:37.6733245849609},
	PH: {nc:'608',lc:'PHL',cn:'Manila',cc:'South East Asia',sn:'Philippines',bw:116.931549072266,bn:21.1206130981445,be:126.601531982422,bs:4.64330530166626},
	TH: {nc:'764',lc:'THA',cn:'Bangkok',cc:'South East Asia',sn:'Thailand',bw:97.3456268310547,bn:20.4631977081299,be:105.63939666748,bs:5.60999917984009},
	KR: {nc:'410',lc:'KOR',cn:'Seoul',cc:'South East Asia',sn:'South Korea',bw:125.887100219727,bn:38.6124534606934,be:129.584686279297,bs:33.1909408569336},
	LK: {nc:'144',lc:'LKA',cn:'Colombo',cc:'South East Asia',sn:'Sri Lanka',bw:79.6529083251953,bn:9.8313627243042,be:81.8812942504883,bs:5.916832447052},
	VN: {nc:'704',lc:'VNM',cn:'Hanoi',cc:'South East Asia',sn:'Viet Nam',bw:102.148216247559,bn:23.3888378143311,be:109.464653015137,bs:8.55960941314697},


	AU: {nc:'036',lc:'AUS',cn:'Canberra',cc:'Oceania',sn:'Australia',bw:112.91104888916,bn:-10.0628032684326,be:153.639282226562,bs:-43.6439743041992},
	NZ: {nc:'554',lc:'NZL',cn:'Wellington',cc:'Oceania',sn:'New Zealand',bw:165.996170043945,bn:-29.2410945892334,be:176.275848388672,bs:-52.6075859069824},
	PG: {nc:'598',lc:'PNG',cn:'Port Moresby',cc:'Oceania',sn:'Papua New Guinea',bw:140.842849731445,bn:-1.31863880157471,be:155.963470458984,bs:-11.657862663269}
};
