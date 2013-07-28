// Coffee Flavour Wheel by Jason Davies,
// http://www.jasondavies.com/coffee-wheel/
// License: http://www.jasondavies.com/coffee-wheel/LICENSE.txt

//Variables
var width = 780,
	height = width,
	radius = width / 2,
	x = d3.scale.linear().range([0, 2 * Math.PI]),
	y = d3.scale.pow().exponent(1.3).domain([0, 1]).range([0, radius]),
	padding = 3,
	duration = 1000;

var div = d3.select("#vis");

div.select("img").remove();

var color = d3.scale.category10();

var vis = div.append("svg")
	.attr("width", width + padding )
	.attr("height", height + padding)
	.append("g")
	.attr("transform", "translate(" + [radius + padding, radius + padding] + ")");

div.append("p")
	.attr("id", "intro")

var partition = d3.layout.partition()
	.sort(null)
	.value(function(d) { return 5.8 - d.depth; });

var arc = d3.svg.arc()
	.startAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x))); })
	.endAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x + d.dx))); })
	.innerRadius(function(d) { return Math.max(0, d.y ? y(d.y) : d.y); })
	.outerRadius(function(d) { return Math.max(0, y(d.y + d.dy)); });

var archivo = "json/remeri2";
d3.json("data?id=3&level=2",formando);

function formando(json) 
{ 
	var nodes = partition.nodes({children: json});

	var path = vis.selectAll("path").data(nodes);
	
	path.enter().append("path")
		.attr("id", function(d, i) { return "path-" + i; })
		.attr("d", arc)
		.attr("fill-rule", "evenodd")
		.style("fill", colour)
		.on("click", click);

	var text = vis.selectAll("text").data(nodes);
	
	var textEnter = text.enter().append("text")
		.style ("fill","white")
		//.style("fill", function(d) {return brightness(d3.rgb(colour(d))) < 125 ? "#fff" : "#000";}) //color de la letra
		.attr("text-anchor", "start"
			/*function(d) {
				return x(d.x + d.dx / 2) > Math.PI ? "end" : "start";
			}*/
		) //distancia del centro
		.attr("dy", "-1.5em") //posición a lo horizontal
		.attr("transform", 
			function(d) {
				var multiline = (d.name || "").split(" ").length > 1,
					angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90,
					rotate = angle + (multiline ? -.5 : 0);
					//return "rotate(" + rotate + ")translate(" + (y(d.y) + padding) + ")rotate(" + (angle > 90 ? -180 : 0) + ")";
					return "rotate(" + rotate + ")translate(" + (y(d.y) + padding ) + ")rotate(" + (0) + ")";
			}
		) //Rotación de las palabras
	  
	textEnter.append("tspan")
		.attr("x", 0)
<<<<<<< HEAD
		.text(function(d) { return d.depth ? d.name.split("/p")[0] : ""; });

	for (var num=1; num<6 ;num++)
	{
		textEnter.append("tspan")
			.attr("x", 0)
			.attr("dy", "1em")
			.text(function(d) { return d.depth ? d.name.split("/p")[num] || "" : ""; });
	}
=======
		.text(function(d) { return d.depth ? d.name.split(" ")[0] : ""; });
	textEnter.append("tspan")
		.attr("x", 0)
		.attr("dy", "1em")
		.text(function(d) { return d.depth ? d.name.split(" ")[1] || "" : ""; });
	textEnter.append("tspan")
		.attr("x", 0)
		.attr("dy", "1em")
		.text(function(d) { return d.depth ? d.name.split(" ")[2] || "" : ""; });
	textEnter.append("tspan")
		.attr("x", 0)
		.attr("dy", "1em")
		.text(function(d) { return d.depth ? d.name.split(" ")[3] || "" : "" ; });
>>>>>>> 7b2635803f7bffd4aa37555be75ff049134e79f8

	function click(d) {  
			
					
		var angulo = x(d.x + d.dx / 2) * 180 / Math.PI - 90;
		var rotacion = 360 - angulo;
		var path = vis.selectAll("path").data(nodes);
		path
			.style("fill", colour)
			.transition ()
			.delay(0)
			.duration(1000)
			.attr("transform", "rotate(" + rotacion + ")");
		theta = 0
		var text = vis.selectAll("text").data(nodes);
		text
			.transition ()
			.delay(0)
			.duration(1000)
			.attr("transform", 
				function r(d) {
						var multiline = (d.name || "").split(" ").length > 1,
							angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90 + rotacion,
							rotate = angle + (multiline ? -.5 : 0);
							//return "rotate(" + rotate + ")translate(" + (y(d.y)) + ")";
							return "rotate(" + angle + ")translate(" + (y(d.y) + padding) + ")";
			}.bind(theta));
		
			
		d3.select(this)
			.style("fill", "000");
						
		if (d.children){}
		else{
			$("pre").remove();
			if (d.name == 'social')
			{
				var opcion = 9;
			} else {
				opcion = 4;
			};
			
			var nm=d.name.replace(/\/p/g, ',')
			var nm2= nm.replace(/\ /g, ',')
			var nm3= nm2.replace(/\,/g, ', ')
			
			$("#panel_publicaciones").html("<h4>" + nm3 + "</h4>");
				
			for (u=1; u<opcion; u++)
			{ 
				$.getJSON('select_click.php?id=' + u,function(result)
				{
					$('#panel_publicaciones').append("<pre>" + "<a href='" + result ['file_name'] + "' target='_blank'>" + result ['titulo'] + "</a>" )
				});
			}
		}

		//var archivo = "remeri2.json";
		//alert ("adios");
		//d3.json(archivo,hola);
	
		/*path.transition()
			.duration(duration)
			.attrTween("d", arcTween(d))
			// Somewhat of a hack as we rely on arcTween updating the scales.
		text.style("visibility", 
			function(e) {
				return isParentOf(d, e) ? null : d3.select(this).style("visibility");
			}
		)//???
			.transition()
			.duration(duration)
			.attrTween("text-anchor", 
				function(d) {
					return function() {
						return x(d.x + d.dx / 2) > Math.PI ? "end" : "start";
					};
				}
			)
			.attrTween("transform", 
				function(d) 
				{
					var multiline = (d.name || "").split(" ").length > 1;
					return function() {
						var angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90,
							rotate = angle + (multiline ? -.5 : 0);
							return "rotate(" + rotate + ")translate(" + (y(d.y) + padding) + ")rotate(" + (angle > 90 ? -180 : 0) + ")";
					};
				}
			)	
			.style("fill-opacity", function(e) { return isParentOf(d, e) ? 2 : 1e-6; })
			.each("end", 
				function(e) {
					d3.select(this).style("visibility", isParentOf(d, e) ? null : "hidden");
				}
			);*/	
	}
};	

function rota(d,rotacion) {
	console.log(rotacion);
	var multiline = (d.name || "").split(" ").length > 1,
		angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90 + rotacion,
		rotate = angle + (multiline ? -.5 : 0);
	console.log(rotacion);
		//return "rotate(" + rotate + ")translate(" + (y(d.y)) + ")";
	return "rotate(" + angle + ")translate(" + (y(d.y)) + ")";	
}
		
function isParentOf(p, c) {
  if (p === c) return true;
  if (p.children) {
    return p.children.some(function(d) {
      return isParentOf(d, c);
    });
  }
  return false;
}

function colour(d) {
  return color((d.children ? d : d.parent).name);
}


// Interpolate the scales!
function arcTween(d) {
  var my = maxY(d),
      xd = d3.interpolate(x.domain(), [d.x, d.x + d.dx]),
      yd = d3.interpolate(y.domain(), [d.y, my]),
      yr = d3.interpolate(y.range(), [d.y ? 20 : 0, radius]);
  return function(d) {
    return function(t) { x.domain(xd(t)); y.domain(yd(t)).range(yr(t)); return arc(d); };
  };
}

function maxY(d) {
  return d.children ? Math.max.apply(Math, d.children.map(maxY)) : d.y + d.dy;
}

// http://www.w3.org/WAI/ER/WD-AERT/#color-contrast
function brightness(rgb) {
  return rgb.r * .299 + rgb.g * .587 + rgb.b * .114;
}
