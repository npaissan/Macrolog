function disegnaStory(){
  d3.text("csv/visit-sequences.csv", function(text) {
    var csv = d3.csv.parseRows(text);
    var json = buildHierarchy(csv);
    console.log(json)
  })

}

function buildHierarchy(csv) {
  var root = {"name": "root", "children": []};
  for (var i = 0; i < csv.length; i++) {
    var sequence = csv[i][0];
    var size = +csv[i][1];
    if (isNaN(size)) { // e.g. if this is a header row
      continue;
    }
    var parts = sequence.split("-");
    var currentNode = root;
    for (var j = 0; j < parts.length; j++) {
      var children = currentNode["children"];
      var nodeName = parts[j];
      var childNode;
      if (j + 1 < parts.length) {
   // Not yet at the end of the sequence; move down the tree.
  var foundChild = false;
  for (var k = 0; k < children.length; k++) {
    if (children[k]["name"] == nodeName) {
      childNode = children[k];
      foundChild = true;
      break;
    }
  }

  // If we don't already have a child node for this branch, create it.
  if (!foundChild) {
    childNode = {"name": nodeName, "children": []};
    children.push(childNode);
  }
  currentNode = childNode;
      } else {
  // Reached the end of the sequence; create a leaf node.
  childNode = {"name": nodeName, "size": size};
  children.push(childNode);
      }
    }
  }
  return root;
};
