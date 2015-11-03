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

    <!--<div id="legenda">-->
	 <table border="1px">
            <thead>
                <tr>
                    <th colspan="2">Legenda</th>
                </tr>
                <tr>
                    <th>Tipo colore</th>
                    <th>Frequenza</th>
                </tr>
            </thead>

            <tbody>
            	<tr>
                    <td bgcolor ="#FFFFFF" ></td> <!--255,255,255-->
                    <td>0 viste</td>     
                </tr>

                <tr>
                    <td bgcolor ="#A50026" ></td> <!--165,0,38-->
                    <td>1-2 viste</td>     
                </tr>
                
                <tr>
                    <td bgcolor ="#D73027" ></td><!--215,48,39-->
                    <td>3-5 viste</td>
                </tr>
                
                <tr>
                    <td bgcolor ="#F46D43"></td><!--204,109,67-->
                    <td>6-8 viste</td>
                </tr>

                <tr>
                    <td bgcolor="#FDAE61"></td><!--253,174,97-->
                    <td>9-10 viste</td>
                </tr>

                <tr>
                    <td bgcolor ="#FEE061" ></td><!--254,224,97-->
                    <td>11-13 viste</td>     
                </tr>
                
                <tr>
                    <td bgcolor = "#FFFFBF"></td><!---255,255,191-->
                    <td>14-16 viste</td>
                </tr>
                
                <tr>
                    <td bgcolor = "#D9EF8B"></td><!--217,239,139-->
                    <td>17-19 viste</td>
                </tr>

                <tr>
                    <td bgcolor= "#A6D96A"></td><!--166,217,106-->
                    <td>20-21 viste</td>
                </tr>

                <tr>
                    <td bgcolor ="#66BD63" ></td><!--102,189,99-->
                    <td>22-24 viste</td>     
                </tr>
                
                <tr>
                    <td bgcolor ="#1A9850" ></td><!--26,152,80-->
                    <td>25-27 viste</td>
                </tr>
                
                <tr>
                    <td bgcolor ="#006837"></td><!--0,104,55-->
                    <td> >27 viste</td>
                </tr>              

            </tbody>
        </table>
   <!-- </div>-->
	<script type="text/javascript">
		var visitatoriJSON;
		$.get("getData.php?grafico=calendario", function(response){
			visitatoriJSON = JSON.parse(response);
			console.log(visitatoriJSON);
			disegnaCalendario();
		})
	</script>
</body>
</html>