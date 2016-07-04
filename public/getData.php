<?php
require "handle.php";

$graficoRichiesto=$_GET["grafico"];

$file_configurazione=file_get_contents('C:\Users\Moto\Documents\GitHub\Macrolog\config.json'); //TODO, Cambiare con quella del server
$configurazione=json_decode($file_configurazione, true);
$nome_sito=$configurazione["protocol_used"] . $configurazione["website_name"];

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
	$array_finale = array();

	$data = Database::ask("SELECT first_page, GROUP_CONCAT(cartella_pagina || '-' || conta) as cartelle_pagina,  sum(conta) from (
	select '/' || substr(replace(refferrer, 'http://atletica.me/', ''), 0, instr(replace(refferrer, 'http://atletica.me/', ''), '/') +1) as first_page, cartella_pagina, count(*) as conta
	from get as g1
	where refferrer like 'http://atletica.me%' and cartella_pagina not like '%.%' and cartella_pagina not like '%-%'
	 and first_page not like '%.%' and first_page not like '%-%'
	group by substr(replace(refferrer, 'http://atletica.me', ''), 0, instr(replace(refferrer, 'http://atletica.me/', ''), '/') +1), cartella_pagina
	order by count(*) desc 
	)
	group by first_page
	order by sum(conta) desc
	limit 0,10", []);

	$i = 0;
	foreach ($data as $cartella ) {
		$arr = explode( "," , $cartella["cartelle_pagina"] );
		$j = 0;
		$array_finale[$i] = array();
		foreach ($arr as $obj) {
			$po = explode( "-", $obj );
			$array_finale[$i][$j]["cartella_partenza"] = $cartella["first_page"];
			$array_finale[$i][$j]["cartella_destinazione"] = $po[0];
			$array_finale[$i][$j]["occorrenze"] = $po[1];
			$j++;
			if( $j > 9 )
				break;
		}
		$i++;
	}
	
	$dataJSON=json_encode($array_finale);
	echo $dataJSON;

}
elseif ($graficoRichiesto == "firstPage") {
	$arr_data = array(
		'site_name' => $nome_sito.'%'
	);
	$query = "	SELECT cartella_pagina, count(*) as occorrenze
				FROM get 
				WHERE refferrer NOT LIKE :site_name 
				GROUP BY cartella_pagina 
				ORDER BY count(*) DESC
				LIMIT 0,10";
	$data = Database::ask( $query, $arr_data );
	echo json_encode($data);
}
?>