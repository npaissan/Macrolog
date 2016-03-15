<?php

?>

<html>
    <head>
    	<title>Nuovo Calendario</title>

        <link rel="stylesheet" type="text/css" href="style/calendario.css">
        <?php require_once("header.php") ?>
    </head>
    <body>

        

        <div class="jumbotron">
            <div class="container" id="titolo">
                <h2><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> CALENDARIO </h2>
                <p id="spiegazione">« Il grafico in questa pagina mostra le visite giornaliere ottenute dal sito. 
                    In pochi istanti è possibile capire in quali periodi dell’anno oppure del mese sono state ottenute le maggiori visite. 
                    Con il passaggio del mouse sul singolo giorno è invece possibile visualizzare il numero esatto delle visite giornaliere. »
                </p>            
            </div>
        </div>
            <hr>

        <!--<div id="legenda">-->

    	 <table id="legenda" border="1px">
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
                        <td>0 visite</td>     
                    </tr>

                    <tr>
                        <td bgcolor ="#A50026" ></td> <!--165,0,38-->
                        <td>1-2 visite</td>     
                    </tr>
                    
                    <tr>
                        <td bgcolor ="#D73027" ></td><!--215,48,39-->
                        <td>3-5 visite</td>
                    </tr>
                    
                    <tr>
                        <td bgcolor ="#F46D43"></td><!--204,109,67-->
                        <td>6-8 visite</td>
                    </tr>

                    <tr>
                        <td bgcolor="#FDAE61"></td><!--253,174,97-->
                        <td>9-10 visite</td>
                    </tr>

                    <tr>
                        <td bgcolor ="#FEE061" ></td><!--254,224,97-->
                        <td>11-13 visite</td>     
                    </tr>
                    
                    <tr>
                        <td bgcolor = "#FFFFBF"></td><!---255,255,191-->
                        <td>14-16 visite</td>
                    </tr>
                    
                    <tr>
                        <td bgcolor = "#D9EF8B"></td><!--217,239,139-->
                        <td>17-19 visite</td>
                    </tr>

                    <tr>
                        <td bgcolor= "#A6D96A"></td><!--166,217,106-->
                        <td>20-21 visite</td>
                    </tr>

                    <tr>
                        <td bgcolor ="#66BD63" ></td><!--102,189,99-->
                        <td>22-24 visite</td>     
                    </tr>
                    
                    <tr>
                        <td bgcolor ="#1A9850" ></td><!--26,152,80-->
                        <td>25-27 visite</td>
                    </tr>
                    
                    <tr>
                        <td bgcolor ="#006837"></td><!--0,104,55-->
                        <td> >27 visite</td>
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