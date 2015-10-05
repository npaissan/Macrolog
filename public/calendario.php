<?php

?>

<html>
<head>
	<title>Calendario</title>
	<link rel="stylesheet" type="text/css" href="style/calendario.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js"></script>
	<script type="text/javascript" src="script/calendario.js"></script>
</head>
<body>
	<script type="text/javascript">
		var visitatoriJSON;
		$.get("getData.php", function(response){
			visitatoriJSON = JSON.parse(response);
			console.log(visitatoriJSON);
			disegnaCalendario();
		})
	</script>
</body>
</html>