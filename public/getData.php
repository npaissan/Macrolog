<?php
$graficoRichiesto=$_GET["grafico"];
require "handle.php";

if($graficoRichiesto=="calendario"){
	$visitatori = Database::ask("SELECT anno, mese, giorno, COUNT (DISTINCT ip) AS visitatori FROM get 
	                    GROUP BY anno, mese, giorno", []);
	$visitatoriJSON = json_encode($visitatori);

	print_r($visitatoriJSON);
}

elseif ($graficoRichiesto=="barChart") {
	$richiestePerPagina = Database::ask("SELECT cartella_pagina, COUNT (cartella_pagina) AS richieste FROM get 
						GROUP BY (cartella_pagina) ORDER BY richieste DESC LIMIT 20", []);
	$richiestePerPaginaJSON = json_encode($richiestePerPagina);

	print_r($richiestePerPaginaJSON);
}

elseif($graficoRichiesto=="story"){
	$file_configurazione=file_get_contents('/home/norbert/macrolog/config.json');
	$configurazione=json_decode($file_configurazione, true);
	$nome_sito=$configurazione["protocol_used"] . $configurazione["website_name"];

	$array_finale = array();
	

	$cartelle_piu_richieste = Database::ask("SELECT cartella_pagina FROM get WHERE cartella_pagina NOT LIKE '%?%' 
			GROUP BY cartella_pagina ORDER BY COUNT (cartella_pagina) DESC LIMIT 10");
	foreach ($cartelle_piu_richieste as $cartella ) {
		$arr_data = array(
			"refferrer" =>$nome_sito.$cartella["cartella_pagina"]."%"
		);

		$data = Database::ask("SELECT refferrer AS cartella_partenza, cartella_pagina AS cartella_destinazione, COUNT (cartella_pagina) FROM get WHERE refferrer like :refferrer
			GROUP BY cartella_pagina ORDER BY (COUNT (cartella_pagina)) DESC", $arr_data);

		array_push($array_finale, $data);

	}
	
	
	$dataJSON=json_encode($array_finale);

	print_r($dataJSON);

}
 ?>