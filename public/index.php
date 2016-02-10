<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Macrolog</title>
    <?php require_once("header.php") ?>
  </head>

  <body>

    <div class="jumbotron">
      <div class="container">
        <h1><span class="glyphicon glyphicon-signal" aria-hidden="true"></span> Macrolog</h1>
        <p><span class="glyphicon glyphicon-link" aria-hidden="true"></span> <a href="https://github.com/npaissan/Macrolog" target="_blank">github.com/npaissan/Macrolog</a></p>
        <p>Mostra statistiche su access log</p>
        <p>Dà la possibilità di creare grafici utilizzando i file di log di un server web. Utilizza un database SQLite3 nel quale immagazzina i dati ad ogni rotazione del log, così da avere i dati sempre aggiornati.</p>
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-4 col-xs-12">
          <h2>Calendario</h2>
          <p>Mostra il numero di visite ricevute dal sito giornalmente</p>
          <p><a class="btn btn-success" href="/calendario.php" role="button">Apri &raquo;</a></p>
        </div>
        <div class="col-md-4 col-xs-12">
          <h2>Pagina più richiesta</h2>
          <p>Mostra le 20 pagine più visitate del tuo sito</p>
          <p><a class="btn btn-success" href="/pagineRichieste.php" role="button">Apri &raquo;</a></p>
       </div>
        <div class="col-md-4 col-xs-12">
          <h2>Story</h2>
          <p>Mostra le decisioni prese dagli utenti</p>
          <p><a class="btn btn-success" href="/story.php" role="button">Apri &raquo;</a></p>
        </div>
      </div>

      <hr>

      <footer>
        <p><strong>Norbert Paissan</strong></p>
        <p><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> <a href="mailto:npaissan@gmail.com">npaissan@gmail.com</a></p>
      </footer>
    </div>
  </body>
</html>
