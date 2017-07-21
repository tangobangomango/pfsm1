

jQuery(document).ready(function( $ ) {
	/*
	
	$(".tdg-real-pull-quotes").each(function() {
		var div = $(".tdg-real-pull-quotes");
		
			
			($this).closest('p').prev().before(div);
		
	});
	

	*/
	/*
	var quote = $(".tdg-real-pull-quotes:eq(0)");
	console.log(quote);
	quote.closest('p').prev().before(quote);
	*/
	var quotes = $(".tdg-real-pull-quotes"); 
	for(i = 0; i < quotes.length; i++) {
	var quote = quotes.eq(i); 
	console.log(quote);
    quote.closest('p').prev().before(quote);
  	}

	
});