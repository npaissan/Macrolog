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
	$data = Database::ask("SELECT cartella_pagina, COUNT (cartella_pagina) FROM get WHERE refferrer='https://mtgfiddle.me/'
			GROUP BY cartella_pagina ORDER BY (COUNT (cartella_pagina)) DESC");
	$dataJSON=json_encode($data);

	print_r($data);

}
 ?>