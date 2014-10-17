$(document).ready(function(){



/*************Horizontal Slider****************/
/*************Horizontal Slider****************/



//===== NORMAL SLIDER =====//
//===== NORMAL SLIDER =====//

    $("#slider").slider({ 
	range: 'min',
	value: 50
	});
	
	
	
	
//===== RANGE SLIDER =====//
//===== RANGE SLIDER =====//

	$("#slider-range").slider({
			value:100,
			range: 'min',
			min: 0,
			max: 500,
			step: 50,
			slide: function( event, ui ) {
				$( "#slider-range-amount" ).val( "$" + ui.value );
			}
		});
		$( "#slider-range-amount" ).val( "$" + $( "#slider-range" ).slider( "value" ) );
		
		
	
//===== TWO RANGE SLIDER =====//
//===== TWO RANGE SLIDER =====//	
	
	$( "#slider-two-range" ).slider({
			range: true,
			min: 0,
			max: 500,
			values: [ 75, 300 ],
			slide: function( event, ui ) {
				$( "#slider-two-range-amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
			}
		});
		$( "#slider-two-range-amount" ).val( "$" + $( "#slider-two-range" ).slider( "values", 0 ) +
			" - $" + $( "#slider-range" ).slider( "values", 1 ) );
		
		
		

//===== MAX RANGE SLIDER =====//
//===== MAX RANGE SLIDER =====//

	$( "#slider-range-max" ).slider({
			range: "max",
			min: 1,
			max: 10,
			value: 2,
			slide: function( event, ui ) {
				$( "#slider-range-max-amount" ).val( ui.value );
			}
		});
		$( "#slider-range-max-amount" ).val( $( "#slider-range-max" ).slider( "value" ) );	
		
		
		







/*************Vertical Slider****************/
/*************Vertical Slider****************/

      $( "#slider-vertical-1" ).slider({
			orientation: "vertical",
			range: "max",
			min: 0,
			max: 100,
			value: 53,
			slide: function( event, ui ) {
				$( "#slider-vertical-1-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-1-amount" ).val( "%" + $( "#slider-vertical-1" ).slider( "value" ) );

   	
	  $( "#slider-vertical-2" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 20,
			slide: function( event, ui ) {
				$( "#slider-vertical-2-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-2-amount" ).val( "%" + $( "#slider-vertical-2" ).slider( "value" ) );
			
			
			
	 $( "#slider-vertical-3" ).slider({
			orientation: "vertical",
			range: "max",
			min: 0,
			max: 100,
			value: 30,
			step: 10,
			slide: function( event, ui ) {
				$( "#slider-vertical-3-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-3-amount" ).val( "%" + $( "#slider-vertical-3" ).slider( "value" ) );
			
			
    $( "#slider-vertical-4" ).slider({
			orientation: "vertical",
			range: "max",
			min: 0,
			max: 100,
			value: 70,
			step: 20,
			slide: function( event, ui ) {
				$( "#slider-vertical-4-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-4-amount" ).val( "%" + $( "#slider-vertical-4" ).slider( "value" ) );
			
			
	
	$( "#slider-vertical-5" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 34,
			step: 7,
			slide: function( event, ui ) {
				$( "#slider-vertical-5-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-5-amount" ).val( "%" + $( "#slider-vertical-5" ).slider( "value" ) );
			
			
	$( "#slider-vertical-6" ).slider({
			orientation: "vertical",
			range: "max",
			min: 0,
			max: 100,
			value: 80,
			step: 20,
			slide: function( event, ui ) {
				$( "#slider-vertical-6-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-6-amount" ).val( "%" + $( "#slider-vertical-6" ).slider( "value" ) );
			
			
	$( "#slider-vertical-7" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 70,
			step: 20,
			slide: function( event, ui ) {
				$( "#slider-vertical-7-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-7-amount" ).val( "%" + $( "#slider-vertical-7" ).slider( "value" ) );
			
			
			
	$( "#slider-vertical-8" ).slider({
			orientation: "vertical",
			range: "max",
			min: 0,
			max: 100,
			value: 50,
			step: 50,
			slide: function( event, ui ) {
				$( "#slider-vertical-8-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-8-amount" ).val( "%" + $( "#slider-vertical-8" ).slider( "value" ) );
			
			
			
	$( "#slider-vertical-9" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 39,
			step: 10,
			slide: function( event, ui ) {
				$( "#slider-vertical-9-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-9-amount" ).val( "%" + $( "#slider-vertical-9" ).slider( "value" ) );
			
			
			
	$( "#slider-vertical-10" ).slider({
			orientation: "vertical",
			range: "max",
			min: 0,
			max: 100,
			value: 72,
			step: 5,
			slide: function( event, ui ) {
				$( "#slider-vertical-10-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-10-amount" ).val( "%" + $( "#slider-vertical-10" ).slider( "value" ) );
			
			
	
	$( "#slider-vertical-11" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 37,
			step: 1,
			slide: function( event, ui ) {
				$( "#slider-vertical-11-amount" ).val( "%" + ui.value );
			  }
		  });
	    	$( "#slider-vertical-11-amount" ).val( "%" + $( "#slider-vertical-11" ).slider( "value" ) );
			
			
			
			
			
});