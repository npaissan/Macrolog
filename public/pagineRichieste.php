<?php

?>

<html>
<head>
	<title>Pagina più richiesta</title>
	<link rel="stylesheet" type="text/css" href="style/pagineRichieste.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js"></script>
	<script src="script/tip.js"></script> <!-- nuvoletta per l'hover-->
	<script type="text/javascript" src="script/pagineRichieste.js"></script>
</head>
<body>
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