var minimo=1564 /*Año mínimo en el que empiezan las publicaciones*/
		maximo=2013; /*Año actual*/

$(function() {
	$( "#slider-range" ).slider({
		range: true,
		min: minimo,
		max: maximo,
		values: [ minimo, maximo, ],
		slide: function( event, ui ) {
			$( "#minrango" ).val( ui.values[ 0 ]);
			$( "#maxrango" ).val( ui.values[ 1 ]);
		}
	});
	
	$( "#minrango" ).val( $( "#slider-range" ).slider( "values", 0 ));
	$( "#maxrango" ).val( $( "#slider-range" ).slider( "values", 1 ));
	
	$("input.intervalo").change(function () {
		$minV = $("#minrango").val();
		$maxV = $("#maxrango").val();
		
		if ($maxV < $minV){ /* No permite que el usuario ingrese años que provoquen que los indicadores se traslpane*/
			$("#slider-range" ).slider({
				values: [$minV, $minV]							
			});
			$( "#maxrango" ).val( $minV);
		}
		else if ($minV<minimo) { /* No permite que el usuario ingrese un año menos al indicado en la línea de tiempo*/
			$("#slider-range" ).slider({
				values: [minimo, $maxV]
			});	
			$( "#minrango" ).val( minimo);
		}
		else if ($maxV>maximo) { /* No permite que el usuario ingrese un año mayor al indicado en la línea de tiempo*/
			$("#slider-range" ).slider({
				values: [$minV, maximo]
			});	
			$( "#maxrango" ).val(maximo);
		}
		else{
			$("#slider-range" ).slider({
				values: [$minV, $maxV]							
			});
		}
	});
});