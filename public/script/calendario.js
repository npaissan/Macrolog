function disegnaCalendario(){
  var csv = visitatoriJSON;
  var width = 1000, //960
      height = 195, //136
      cellSize = 17; // grandezza celle

  var percent = d3.format(".1%"),
      format = d3.time.format("%Y-%m-%d")
      doppiaCifra = d3.format("02d");

  var color = d3.scale.quantize()
      .domain([0, 1000])
      .range(d3.range(11).map(function(d) { return "q" + d + "-11"; }));

  var svg = d3.select("body").selectAll("svg")
      .data(d3.range(2014, 2017))
    .enter().append("svg")
      .attr("width", width)
      .attr("height", height)
      .attr("class", "RdYlGn")
    .append("g")
      .attr("transform", "translate(" + ((width - cellSize * 53) / 2) + "," + (height - cellSize * 7 - 1) + ")");

  svg.append("text")
      .attr("transform", "translate(-6," + cellSize * 3.5 + ")rotate(-90)")
      .style("text-anchor", "middle")
      .text(function(d) { return d; });

/*SICURAMENTE DA RIVEDERE*/


  svg.append("text")
      .attr("transform", "translate(+30," + cellSize * -0.1 + ")rotate(-45)")
      .text("Gennaio");

  svg.append("text")
      .attr("transform", "translate(+110," + cellSize * -0.1 + ")rotate(-45)")
      .text("Febbraio");

  svg.append("text")
      .attr("transform", "translate(+190," + cellSize * -0.1 + ")rotate(-45)")
      .text("Marzo");

  svg.append("text")
      .attr("transform", "translate(+270," + cellSize * -0.1 + ")rotate(-45)")
      .text("Aprile");

  svg.append("text")
      .attr("transform", "translate(+350," + cellSize * -0.1 + ")rotate(-45)")
      .text("Maggio");

  svg.append("text")
      .attr("transform", "translate(+430," + cellSize * -0.1 + ")rotate(-45)")
      .text("Giugno");

  svg.append("text")
      .attr("transform", "translate(+500," + cellSize * -0.1 + ")rotate(-45)")
      .text("Luglio");

  svg.append("text")
      .attr("transform", "translate(+560," + cellSize * -0.1 + ")rotate(-45)")
      .text("Agosto");

  svg.append("text")
      .attr("transform", "translate(+640," + cellSize * -0.1 + ")rotate(-45)")
      .text("Settembre");

  svg.append("text")
      .attr("transform", "translate(+720," + cellSize * -0.1 + ")rotate(-45)")
      .text("Ottobre");

  svg.append("text")
      .attr("transform", "translate(+790," + cellSize * -0.1 + ")rotate(-45)")
      .text("Novembre");

  svg.append("text")
      .attr("transform", "translate(+865," + cellSize * -0.1 + ")rotate(-45)")
      .text("Dicembre");

  var rect = svg.selectAll(".day")
      .data(function(d) { return d3.time.days(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
    .enter().append("rect")
      .attr("class", "day")
      .attr("width", cellSize)
      .attr("height", cellSize)
      .attr("x", function(d) { return d3.time.weekOfYear(d) * cellSize; })
      .attr("y", function(d) { return d.getDay() * cellSize; })
      .datum(format);

  rect.append("title")
      .text(function(d) { return d; });

  svg.selectAll(".month")
      .data(function(d) { return d3.time.months(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
    .enter().append("path")
      .attr("class", "month")
      .attr("d", monthPath);

  
  var data = d3.nest()
    .key(function(d) { 
    	return (d.anno + "-" + doppiaCifra(d.mese) + "-" + doppiaCifra(d.giorno) ); })
    .rollup(function(d) { return (d[0].visitatori); })
    .map(csv);

  rect.filter(function(d) { 
  	return d in data; })
      .attr("class", function(d) { return "day " + color(data[d]); })
    .select("title")
      .text(function(d) { 
      	//console.log(d)
      	return "In data " + "'" + d + "' " + "numero visite: " + (data[d]); });
  

  function monthPath(t0) {
    var t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0),
        d0 = t0.getDay(), w0 = d3.time.weekOfYear(t0),
        d1 = t1.getDay(), w1 = d3.time.weekOfYear(t1);
    return "M" + (w0 + 1) * cellSize + "," + d0 * cellSize
        + "H" + w0 * cellSize + "V" + 7 * cellSize
        + "H" + w1 * cellSize + "V" + (d1 + 1) * cellSize
        + "H" + (w1 + 1) * cellSize + "V" + 0
        + "H" + (w0 + 1) * cellSize + "Z";
  }

  d3.select(self.frameElement).style("height", "2910px");
}