<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>StoryGraph</title>
    <?php require_once("header.php") ?>
    <script type="text/javascript" src="/script/story.js"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600">
    <!--<link rel="stylesheet" type="text/css" href="http://bl.ocks.org/kerryrodden/raw/7090426/8fce22c9e21711c757ee8a0df7dba5a42dea0d9c/sequences.css">
    <link rel="stylesheet" type="text/css" href="http://developers.atletica.me/css/creativeC.css">-->
    <link rel="stylesheet" type="text/css" href="/style/story.css">
    <script type="text/javascript">
      var data;
      $.get("getData.php?grafico=story", function(response){
        data = JSON.parse(response);
        console.log(data);
        preparaDati();
      })
    </script>
  </head>
  <body>

 <div id="main_2">
      <div class="container">

          <div class="settings">
              <div class="row">
                <div id = "spiegazione">
                    Cambia Grafico!
                </div>
                  <div class="switch">
                    <input id="cmn-toggle-8" class="cmn-toggle cmn-toggle-yes-no" type="checkbox">
                    <label for="cmn-toggle-8" data-on="Story!" data-off="storyGraph"></label>
                </div>
              </div><!-- /row -->
          </div>

      </div>
    </div>

<hr>
    <div class="jumbotron">
        <div class="container" id="titolo">
            <h2><span class="glyphicon glyphicon-indent-left" aria-hidden="true"></span> StoryGraph </h2>
            <p id="spiegazione">« Il grafico in questa pagina mostra le visite giornaliere ottenute dal sito. 
                In pochi istanti è possibile capire in quali periodi dell’anno oppure del mese sono state ottenute le maggiori visite. 
                Con il passaggio del mouse sul singolo giorno è invece possibile visualizzare il numero esatto delle visite giornaliere. »
            </p>            
        </div>
    </div>
            <hr>

    <div id="main">
      <div id="sequence"></div>
      <div id="chart" style="height:500px;width:500px;">
        <div id="explanation" style="visibility: hidden;">
          <span id="percentage"></span><br/>
          of visits begin with this sequence of pages
        </div>
      </div>
    </div>
    
    <script type="text/javascript">
      // Hack to make this example display correctly in an iframe on bl.ocks.org
      d3.select(self.frameElement).style("height", "700px");
  </script> 
  </body>
  
</html>