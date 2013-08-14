// Coffee Flavour Wheel by Jason Davies,
// http://www.jasondavies.com/coffee-wheel/
// License: http://www.jasondavies.com/coffee-wheel/LICENSE.txt

//Variables
var width = 645,
	height = width,
	radius = width / 2,
	x = d3.scale.linear().range([0, 2 * Math.PI]),
	y = d3.scale.pow().exponent(1.5).domain([0, 1]).range([0, radius]),
	padding = 10,
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

//var archivo = "json/remeri";
//d3.json("data?level=2",formando);
d3.json("json/remeri",formando);
	
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
		.attr("dy", "0") //posición a lo horizontal
		.attr("transform", 
			function(d) {
				var multiline = (d.name || "").split(" ").length > 1,
					angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90,
					rotate = angle + (multiline ? -.1 : 0);
					//return "rotate(" + rotate + ")translate(" + (y(d.y) + padding) + ")rotate(" + (angle > 90 ? -180 : 0) + ")";
					return "rotate(" + rotate + ")translate(" + (y(d.y) + padding ) + ")rotate(" + (0) + ")";
			}
		) //Rotación de las palabras
	  
	textEnter.append("tspan")
		.attr("x", 0)
		.text(function(d) { return d.depth ? d.name.split(" ")[0] : ""; });

	for (var num=1; num<2 ;num++)
	{
		textEnter.append("tspan")
			.attr("x", 0)
			.attr("dy", "1em")
			.text(function(d) { return d.depth ? d.name.split(" ")[num] || "" : ""; });
	}
	
	function click(d) {  
					
		/*path.transition()
			.duration(duration)
			.attrTween("d", arcTween(d));*/
		
		/*d3.json("data?id="+ d.id +"&level=2", function(data) {
		vis.selectAll("path")
			.style("opacity", 1)
			.transition()
			.duration(1000)
			.style("opacity", 0)
			.remove();									
		});	
		
		d3.json("json/remeri2",formando2);*/
		
		/*if (d.name=="teatro"){*/
			var nm=d.name.replace(/\/p/g, ',')
			var nm2= nm.replace(/\ /g, ',')
			var nm3= nm2.replace(/\,/g, ', ');
				
			$("h4").remove();	
			$('#panel_publicaciones').append("<h4>" + nm3 );	
			
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
				
			$("pre").remove();
				
			$.getJSON('http://localhost:8080/web/php',function(result)
			{
				$('#panel_publicaciones').append("<pre>" + "<a href='" + result ['name'] + "' target='_blank'>" + result ['name'] + "</a>" )
			});
								

			/*$.getJSON('select_click',function(result)
					{
						$('#panel_publicaciones').append("<pre>" + "<a href='" + result ['uri'] + "' target='_blank'>" + result ['name'] + "</a>" )
					});*/
					
		/*}else{
			formando2();
		}
		
		valorinstitucion();
		alert (uaslp);*/
	}
};	

function formando2(json) 
{ 
		vis.selectAll("path")
			.style("opacity", 1)
			.transition()
			.duration(1000)
			.style("opacity", 0)
			.remove();					
		d3.json("json/remeri2",formando);
};	

function colour(d) {
  return color((d.children ? d : d.parent).name);
}

// http://www.w3.org/WAI/ER/WD-AERT/#color-contrast
function brightness(rgb) {
  return rgb.r * .299 + rgb.g * .587 + rgb.b * .114;
}
