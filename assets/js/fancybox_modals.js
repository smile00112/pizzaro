jQuery(document).ready(function($) {
	$(document).on('click', '[data-fancy_modal]', function(event){
		event.preventDefault();
		var selector = $(this).data('fancy_modal');
		if(!!selector)
		$.fancybox.open({
			src  : '#'+selector,
			type : 'inline',
			opts : {
				afterShow : function( instance, current ) {
					console.info( 'done!' );
				}
			}
		});
	}); 
});

function show_modal($id){
	//if($('#'+$id).lenght)
		$.fancybox.open({
			src  : '#'+$id,
			type : 'inline',
			
			opts : {
				
				afterShow : function( instance, current ) {
					console.info( 'done!' );
				}
				,
				touch: false,
				autoFocus: true,
				smallBtn: true,
				hideScrollbar: true,
				backFocus: true,
				trapFocus: true,
				clickSlide: false,  
			},

			
		});
}
function close_modal($id){
	$.fancybox.close();
}