<html>
	<head>
		<title>Pagina più richiesta</title>
	    <?php require_once("header.php") ?>
		<link rel="stylesheet" type="text/css" href="style/pagineRichieste.css">
	</head>
	<body>
		<div id="titolo">
	        <h1>PAGINE PIU' RICHIESTE</h1>
	    </div>

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