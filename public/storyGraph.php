<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Sequences sunburst</title>
    <script type="text/javascript" src="/script/story.js"></script>
    <link rel="stylesheet" type="text/css" href="http://developers.atletica.me/css/creativeC.css">
    <link rel="stylesheet" type="text/css" href="/style/story.css">
   <?php require_once("header.php") ?>
  </head>
  
  <body id="newBody">
    <div id="main">
      <div id="sequence"></div>
      <div id="chart">
        <div id="explanation" style="visibility: hidden;">
          <span id="percentage"></span><br/>
          of visits begin with this sequence of pages
        </div>
      </div>
    </div>
    <div id="sidebar">
      <input type="checkbox" id="togglelegend"> Legend<br/>
      <div id="legend" style="visibility: hidden;"></div>
    </div>

    <script type="text/javascript">
          var data;
          $.get("getData.php?grafico=story", function(response){
            data = JSON.parse(response);
            console.log(data);
            disegnaStory();
          })
    </script>

  </body>
</html>