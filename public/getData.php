<?php
require "handle.php";

$graficoRichiesto=$_GET["grafico"];

if($graficoRichiesto=="calendario"){
	$visitatori = Database::ask("SELECT anno, mese, giorno, COUNT (DISTINCT ip) AS visitatori FROM get 
	                    GROUP BY anno, mese, giorno", []);
	$visitatoriJSON = json_encode($visitatori);

	echo $visitatoriJSON;
}

elseif ($graficoRichiesto=="barChart") {
	$richiestePerPagina = Database::ask("SELECT cartella_pagina, COUNT (cartella_pagina) AS richieste FROM get 
						GROUP BY (cartella_pagina) ORDER BY richieste DESC LIMIT 20", []);
	$richiestePerPaginaJSON = json_encode($richiestePerPagina);

	echo $richiestePerPaginaJSON;
}

elseif($graficoRichiesto=="story"){
	$file_configurazione=file_get_contents('C:\Users\Moto\Documents\GitHub\Macrolog\config.json'); //TODO, Cambiare con quella del server
	$configurazione=json_decode($file_configurazione, true);
	$nome_sito=$configurazione["protocol_used"] . $configurazione["website_name"];

	$array_finale = array();

	$cartelle_piu_richieste = Database::ask("SELECT cartella_pagina FROM get WHERE cartella_pagina NOT LIKE '%?%' 
			GROUP BY cartella_pagina ORDER BY COUNT (cartella_pagina) DESC LIMIT 10", []);
	foreach ($cartelle_piu_richieste as $cartella ) {
		$url = $nome_sito.$cartella["cartella_pagina"];
		if(substr($url,-1) != "/"){
			$url = $url."/";
		}
		$arr_data = array(
			"refferrer" =>$url,
			"not_slash_refferrer" =>$url."%/%",
			"dot_refferrer" =>$url."%.%"
		);

		$data = Database::ask("SELECT :refferrer AS cartella_partenza, cartella_pagina AS cartella_destinazione, count(cartella_pagina) as occorrenze
				FROM get WHERE cartella_pagina NOT LIKE '%:%'
				AND (refferrer = :refferrer OR refferrer LIKE :dot_refferrer) AND refferrer NOT LIKE :not_slash_refferrer
				group by cartella_pagina 
				ORDER BY count(cartella_pagina) desc", $arr_data);

		array_push($array_finale, $data);

	}
	
	$dataJSON=json_encode($array_finale);
	echo $dataJSON;

}
?>