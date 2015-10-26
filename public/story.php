<html>
<head>
	<title>Story</title>
	<link rel="stylesheet" type="text/css" href="style/story.css">
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js"></script>
	<script type="text/javascript" src="script/story.js"></script>
</head>
<body>
	<script type="text/javascript">
		var storyJSON;
		$.get("getData.php?grafico=story", function(response){

			storyJSON = JSON.parse(response);
			console.log(storyJSON);
			
		})
	</script>
</body>
</html>