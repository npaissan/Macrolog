<?php 
require "handle.php";
$visitatori = Database::ask("SELECT anno, mese, giorno, COUNT (DISTINCT ip) AS visitatori FROM get 
                    GROUP BY anno, mese, giorno", []);
$visitatoriJSON = json_encode($visitatori);

print_r($visitatoriJSON);
 ?>