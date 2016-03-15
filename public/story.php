<html>
<head>
	<title>Story</title>
	<link rel="stylesheet" type="text/css" href="http://developers.atletica.me/css/creativeC.css">
	<link rel="stylesheet" type="text/css" href="/style/story.css">
	<?php require_once("header.php") ?>

	<script type="text/javascript" src="/script/story.js"></script>
</head>
<body>

<div class="limitatore">
	<div class="padd20 center">
		<div class="no-font split split50">
			<div class="split split50 f16 padd10 Bred">
				GRAFICO
			</div>
			<div class="split split50 f16 padd10">
				NO-GRAFICO
			</div>
		</div>
	</div>

	<div class="padd20">
		<div class="padd10 op7">
			Clicca per ridurre il numero di pagine
		</div>
		<ul class="f16 border-1 padd20-li-div" id="link_pages">
			
		</ul>
	</div>

</div>

<script type="text/javascript">
var storyJSON;
var color_array = ["#0F26D8", "#0F2", "#FF1744", "#1DE9B6", "violet"];

var tmpl_page = '<li class="open-li">'+
				'	<div> :FROM_PAGE </div>'+
				'	<ul  style="padding: 0">'+
				'		:ADD_ROW'+
				'		<div class="padd10"><div class="no-font">:BAR_PAGE</div></div>'+
				'	</ul>'+
				'</li>';

var tmpl_to_page = 	'<li class="split to-page" style="width: :--%; background: :COLOR">'+
					'	<div class="num">:RATE<span>%</span></div>'+
					'	<div class="dest">:TO_PAGE </div>'+
					'</li>';

var bar_to_page = '<div class="split paddTB5" style="width: :RATE%; background: :COLOR" ></div>'

$.get("getData.php?grafico=story", function(response){

	storyJSON = JSON.parse(response);
	var str_from_page = "";

	$.each(storyJSON, function( i, pages ) {
	  if(pages.length > 0){
	  	var from_page = pages[0].cartella_partenza;

	  	var str_to_pages = "";
	  	var str_bar = "";
	  	var total_occorrenze = 0;
	  	$.each(pages, function( i, link ) {
	  		console.log(link.occorrenze);
	  		total_occorrenze += parseInt(link.occorrenze);
	  	});

	  	$.each(pages, function( i, link ) {
	  		var to_page = link.cartella_destinazione;
	  		var occorrenze = link.occorrenze;
	  		var rate = (occorrenze/total_occorrenze*100).toFixed(1);
	  		str_to_pages += tmpl_to_page.replace(":TO_PAGE", to_page).replace(":RATE", rate);
	  		str_bar      += bar_to_page.replace(":RATE", rate).replace(":COLOR", color_array[i]);
	  	});

	  	str_from_page += tmpl_page.replace(":ADD_ROW", str_to_pages).replace(":FROM_PAGE", from_page).replace(":BAR_PAGE", str_bar);
	  }
	});
	
	$("#link_pages").html( str_from_page );

	$("li.open-li").on('click', function(){
		var $dropdown = $(this);
		$dropdown.toggleClass("dropdown");
	});
});
</script>

</body>
</html>