jQuery(document).ready(function($) {

	// slider f�r rating container
	$(".rating-container").slideDown(1500);

	
	
	
	$(".rating-image img").hover(function(e) {
		// Bildvergr��erung
		
		var target = $(e.target);
		var targetId = '#' + target.attr('id');
		
		//console.log ( targetId );	
		
		$( targetId ).css( { "width":"250px" } );
		$( targetId ).css( { "height":"auto" } );
		
		}, function(e) {
			// Bildverkleinerung
			var target = $(e.target);
			var targetId = '#' + target.attr('id')			
			$( targetId ).css( { "width":"150px" } );
			$( targetId ).css( { "height":"auto" } );	
			
			
		});


});