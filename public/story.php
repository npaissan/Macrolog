<html>
<head>
	<title>Story</title>
	<link rel="stylesheet" type="text/css" href="http://developers.atletica.me/css/creativeC.css">
	<link rel="stylesheet" type="text/css" href="/style/story.css">
	<script type="text/javascript" src="/script/story.js"></script>

	<?php require_once("header.php") ?>

	

<!--parte nuova-->
	<!--<script type="text/javascript" src="/script/story.js"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600">
    <link rel="stylesheet" type="text/css" href="http://bl.ocks.org/kerryrodden/raw/7090426/8fce22c9e21711c757ee8a0df7dba5a42dea0d9c/sequences.css">
    <link rel="stylesheet" type="text/css" href="http://developers.atletica.me/css/creativeC.css">
    <link rel="stylesheet" type="text/css" href="/style/story.css">-->
    
<!--fine parte nuova-->
</head>
<body>

<h1 id="titoloTab">Storia delle scelte</h1>
    <hr />

        <div class="tab-panel">
            <ul class="tab-link">
                <li class="active"><a href="#FirstTab">Story</a></li>
                <li><a href="#SecondTab">storyGraph</a></li>
                <li><a href="#ThirdTab">firstPage</a></li>
            </ul>

            <div class="content-area">
                <div id="FirstTab" class="active">

                	<div class="jumbotron">
				        <div class="container" id="titolo">
				            <h2><span class="glyphicon glyphicon-indent-left" aria-hidden="true"></span> Story </h2>
				            <p id="spiegazione">« In questa sezione è possibile osservare le scelte effettuate dagli utenti, ovvero
				            						con quali probabilità si sono spostati dalla pagina che stavano visitando ad un'altra. Questo grafico
				            						ne permette una rapida visione. »
				            </p>            
				        </div>
				    </div>

                    <div class="limitatore">
	
	

						<!-- <div class="padd20 center">
							<div class="no-font split split50">
								<div class="split split50 f16 padd10 Bred">
									GRAFICO
								</div>
								<div class="split split50 f16 padd10">
									<a href="http://localhost/storyGraph.php">storyGraph<span></span></a>
								</div>
							</div>
						</div> --> 

						<div class="padd20">
							<div class="padd10 op7">
								Clicca per ridurre il numero di pagine
							</div>
							<ul class="f16 border-1 padd20-li-div" id="link_pages">
								
							</ul>
						</div>

					</div>
                </div>

                <div id="SecondTab" class="inactive">

                   <div class="jumbotron">
				        <div class="container" id="titolo">
				            <h2><span class="glyphicon glyphicon-indent-left" aria-hidden="true"></span> StoryGraph </h2>
				            <p id="spiegazione">« Rende sempre le scelte fatte dagli utenti, ma grazie alla sua struttura lo fa 
				            					in una maniera più rapida ed intuitiva, infatti con il passaggio del mouse si crea il percorso effettuato
				            					e la sua percentuale. »
				            </p>            
				        </div>
				    </div>
            <hr>

				    <div id="main">
				      <div id="sequence"></div>
				      <div id="chart" style="height:500px;width:500px;">
				        <div id="explanation" style="visibility: hidden;">
				          <span id="percentage"></span><br/>
				          of visits begin with this sequence of pages
				        </div>
				      </div>
				    </div>
				    
				    <script type="text/javascript">
				      // Hack to make this example display correctly in an iframe on bl.ocks.org
				      d3.select(self.frameElement).style("height", "700px");
				  </script> 
                </div>

                <div id="ThirdTab" class="inactive">

                	<div class="jumbotron">
				        <div class="container" id="titolo">
				            <h2><span class="glyphicon glyphicon-indent-left" aria-hidden="true"></span> FirstPage </h2>
				            <p id="spiegazione">« Qui possiamo osservare le prime pagine che un utente visita sul sito. Questo è
				            	reso possibile grazie all'utlizzo del referrer.»
				            </p>            
				        </div>
				    </div>

                    <div class="limitatore">
	
	

						<!-- <div class="padd20 center">
							<div class="no-font split split50">
								<div class="split split50 f16 padd10 Bred">
									GRAFICO
								</div>
								<div class="split split50 f16 padd10">
									<a href="http://localhost/storyGraph.php">storyGraph<span></span></a>
								</div>
							</div>
						</div> --> 

						<div class="padd20">
							<div class="padd10 op7">
								Clicca per ridurre il numero di pagine
							</div>
							<ul class="f16 border-1 padd20-li-div" id="link_top_pages">
								
							</ul>
						</div>

					</div>
                </div>
            </div>
        </div>




<script type="text/javascript">
	$(document).ready(function () {
   $('.tab-panel .tab-link a').on('click', function (e) {
        var currentAttrValue = jQuery(this).attr('href');

        // Show/Hide Tabs
        //Fade effect
        //   $('.tab-panel ' + currentAttrValue).fadeIn(1000).siblings().hide();
        //Sliding effect
        $('.tab-panel ' + currentAttrValue).slideDown(400).siblings().slideUp(400);

        //Sliding up-down effect
       // $('.tab-panel ' + currentAttrValue).siblings().slideUp(400);
        // $('.tab-panel ' + currentAttrValue).delay(400).slideDown(400);

        // Change/remove current tab to active
        $(this).parent('li').addClass('active').siblings().removeClass('active');

        e.preventDefault();
    });
});
</script>

<script type="text/javascript">
var storyJSON;
var data;
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

var bar_to_page = '<div class="split paddTB5 cool-bar" data-perc=":RATE%" style="width: :RATE%; background: :COLOR" ></div>'

$.get("getData.php?grafico=story", function(response){

	data = JSON.parse(response);
	console.log(data);
	preparaDati();
	disegnaStory( data );

});

$.get("getData.php?grafico=firstPage", function(response){

	data = JSON.parse(response);
	console.log(data);
	disegnaFirstPage( data );

});

function disegnaStory( storyJSON ){
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
	  		var num = i % color_array.length;
	  		var to_page = link.cartella_destinazione;
	  		var occorrenze = link.occorrenze;
	  		var rate = (occorrenze/total_occorrenze*100).toFixed(1);
	  		str_to_pages += tmpl_to_page.replace(":TO_PAGE", to_page).replace(":RATE", rate);
	  		str_bar      += bar_to_page.replace(/:RATE/g, rate).replace(":COLOR", color_array[num]);
	  	});

	  	str_from_page += tmpl_page.replace(":ADD_ROW", str_to_pages).replace(":FROM_PAGE", from_page).replace(":BAR_PAGE", str_bar);
	  }
	});
	
	$("#link_pages").html( str_from_page );

	$("li.open-li").on('click', function(){

		var dropul = $(this).children('ul');
		$(dropul).slideToggle('fast');
		
	});
}

function disegnaFirstPage( pages ){

	var str_to_pages = "";
	var str_bar = "";

	var total_occorrenze = 0;
  	$.each(pages, function( i, page ) {
  		total_occorrenze += parseInt(page.occorrenze);
  	});

	$.each(pages, function( i, page ){
		var num = i % color_array.length;
		var to_page = page.cartella_pagina;
  		var occorrenze = page.occorrenze;
  		var rate = (occorrenze/total_occorrenze*100).toFixed(1);
  		str_to_pages += tmpl_to_page.replace(":TO_PAGE", to_page).replace(":RATE", rate);
  		str_bar      += bar_to_page.replace(/:RATE/g, rate).replace(":COLOR", color_array[num]);
	});

	str_from_page = tmpl_page.replace(":ADD_ROW", str_to_pages).replace(":FROM_PAGE", "Percentuale di arrivi").replace(":BAR_PAGE", str_bar);
	$("#link_top_pages").html( str_from_page );
}

</script>

</body>
</html>