function disegnaBarChart(){
  var margin = {top: 40, right: 20, bottom: 30, left: 40},
      width = 960 - margin.left - margin.right,
      height = 500 - margin.top - margin.bottom;

  var formatPercent = d3.format(".0%");

  var x = d3.scale.ordinal()
      .rangeRoundBands([0, width], .1);

  var y = d3.scale.linear()
      .range([height, 0]);

  var xAxis = d3.svg.axis()
      .scale(x)
      .orient("bottom");
      

  var yAxis = d3.svg.axis()
      .scale(y)
      .orient("left")
      //.tickFormat(formatPercent);

  var tip = d3.tip()
    .attr('class', 'd3-tip')
    .offset([-10, 0])
    .html(function(d) {
      return "<strong>Frequency:</strong> <span style='color:red'>" + d.richieste + "</span>";
    })

  var svg = d3.select("body").append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
    .append("g")
      .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  svg.call(tip);

  x.domain(data.map(function(d) { return d.cartella_pagina; }));
  y.domain([0, d3.max(data, function(d) { return +d.richieste; })]);
  console.log(d3.max(data, function(d) { return +d.richieste; })); //TODO controllare rage scala, da 95 e non 5000!

  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
      .selectAll("text")  
            .style("text-anchor", "start")
            //.attr("dx", "-.8em")
            //.attr("dy", ".15em")
            .attr("transform", "rotate(30)" );

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("y", 6)
      .attr("dy", "-1.29em")
      .attr("dx", "-4em")
      .style("text-anchor", "start")
      .text("Numero visite");

  svg.selectAll(".bar")
      .data(data)
    .enter().append("rect")
      .attr("class", "bar")
      .attr("x", function(d) { return x(d.cartella_pagina); })
      .attr("width", x.rangeBand())
      .attr("y", function(d) { return y(d.richieste); })
      .attr("height", function(d) { return height - y(d.richieste); })
      .on('mouseover', tip.show)
      .on('mouseout', tip.hide)

  

  function type(d) {
    d.richieste = +d.richieste;
    return d;
  }
}