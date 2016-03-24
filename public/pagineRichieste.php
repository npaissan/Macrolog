<html>
	<head>
		<title>Pagina più richiesta</title>
	    <?php require_once("header.php") ?>
		<link rel="stylesheet" type="text/css" href="style/pagineRichieste.css">
	</head>
	<body>

		<div class="jumbotron">
			<div class="container" id="titolo">
				<h2><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span> PAGINE PIU' RICHIESTE</h2>
				<p id="titolo">« Il grafico sottostante mostra in ordine decrescente le 20 pagine che hanno ottenuto le maggiori visite.
								È possibile selezionare una delle colonne per farla sparire, così 
								l’intero grafico si ridimensiona in funzione delle colonne rimaste. 
								In un secondo momento cliccando sul nome della pagina è possibile far ricomparire la colonna per permettere 
								visioni differenti. »
				</p>		    
			</div>
		</div>
			<hr>
		
			<script type="text/javascript">
					var data;
					$.get("getData.php?grafico=barChart", function(response){
						data = JSON.parse(response);
						console.log(data);
						disegnaBarChart();
					})
			</script>
		
	</body>
</html>