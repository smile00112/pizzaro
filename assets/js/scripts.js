( function( $ ) {
	'use strict';

    /*===================================================================================*/
    /*  Set Height of Products li
    /*===================================================================================*/

    // these are (ruh-roh) globals. You could wrap in an
    // immediately-Invoked Function Expression (IIFE) if you wanted to...
    var currentTallest = 0,
        currentRowStart = 0,
        rowDivs = new Array();

    function setConformingHeight(el, newHeight) {
        // set the height to something new, but remember the original height in case things change
        el.data("originalHeight", (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight")));
        el.height(newHeight);
    }

    function getOriginalHeight(el) {
        // if the height has changed, send the originalHeight
        return (el.data("originalHeight") == undefined) ? (el.height()) : (el.data("originalHeight"));
    }

    function columnConform() {

        $( '.product-outer' ).each( function() {
            var $this = $(this);
            if ( ! ( $this.parents('.list-view').length || $this.parents('.list-no-image-view').length ) && ( $( window ).width() > 1199 ) ) {
                if ( 0 != $this.height() ) {
                    $this.height( $this.height() );
                }
            }
        });

    }

    function excerptReadmore() {
        if( pizzaro_options.enable_excerpt_readmore == '1' ) {
            $('.products .product div[itemprop="description"] > p').readmore( pizzaro_options.excerpt_readmore_data );
            $('.products .product div.woocommerce-product-details__short-description > p').readmore( pizzaro_options.excerpt_readmore_data );
        }
    }

    $( window ).on( 'resize', function() {
        columnConform();
        excerptReadmore();
		changecityphone();
		changecityphonefooter();
    });

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        columnConform();
        excerptReadmore();
    });

    $('.kc_tabs > .kc_wrapper').each( function( index ){
        var $_this = $(this),
            tab_group = $_this.parent('.kc_tabs.group'),
            tab_event = ('yes' === tab_group.data('open-on-mouseover')) ? 'mouseover' : 'click',
            effect_option = ('yes' === tab_group.data('effect-option')) ? true : false,
            active_section = parseInt( tab_group.data('tab-active') )-1;
        $( this ).find('>.ui-tabs-nav>li').on( tab_event, function(e){
            columnConform();
            excerptReadmore();
        });
    });

    /**
     * Block UI Defaults
     */
    if( typeof $.blockUI !== "undefined" ) {
        $.blockUI.defaults.message                      = null;
        $.blockUI.defaults.overlayCSS.background        = '#fff url(' + pizzaro_options.ajax_loader_url + ') no-repeat center';
        $.blockUI.defaults.overlayCSS.backgroundSize    = '16px 16px';
        $.blockUI.defaults.overlayCSS.opacity           = 0.6;
    }

    /*===================================================================================*/
    /*  Add to Cart animation
    /*===================================================================================*/


    $( 'body' ).on( 'adding_to_cart', function( e, $btn, data){
        $btn.closest( '.product' ).block();
    });

    $( 'body' ).on( 'added_to_cart', function(){
        $( '.product' ).unblock();
    });

   


    /*===================================================================================*/
    /*  FOOTER MAP COLLAPSE
    /*===================================================================================*/
    
    $('#footer-map-collapse').on('shown.bs.collapse', function () {

        var panel = $(this);

        $('html, body').animate({
            scrollTop: panel.offset().top
        }, 500);

    });

    $( window ).load( function() {
        
        // Adjust Product Item heights
        columnConform();

        $("#city-phone-numbers").trigger('change');

        /*===================================================================================*/
        /*  Custom Scrollbar Script
        /*===================================================================================*/
        if( typeof mCustomScrollbar !== "undefined" ) {
            $( '.pizzaro-sidebar-header .site-header' ).mCustomScrollbar();
        }

    });
	    $("#city-phone-numbers").change(function(){
//changecityphone();
		
    });
	
function changecityphone() {

 if ($(window).width() < 767 ) {
 $("#city-phone-number-label").text(function() {
  $(this).replaceWith(

    '<span id="city-phone-number-label" class="phone-number"><a href="tel:' + $("#city-phone-numbers").val() + '">' + $("#city-phone-numbers").val() + "</a></span>"
  );
});
 
	var vibermobnumber = $("#city-phone-numbers").val().replace(/[^0-9]/g, '');	
//$('#messicons').html('<a href="https://wa.me/' + $("#city-phone-numbers").val() + '" class="whatsup"></a><a href="' + $("#city-phone-numbers").val() + '" class="telegram"></a><a href="viber://chat?number=' + vibermobnumber + '" class="viber"></a>');
} 
else {
 var selectedKey = $("#city-phone-numbers").val();
        $('#city-phone-number-label').html( selectedKey );	
		
		//$('#messicons').html('<a href="https://wa.me/' + $("#city-phone-numbers").val() + '" class="whatsup"></a><a href="' + $("#city-phone-numbers").val() + '" class="telegram"></a><a href="viber://chat?number=' + $("#city-phone-numbers").val() + '" class="viber"></a>');
}


}


function changecityphonefooter() {
 if ($(window).width() < 767 ) {
 $(".footer-contact-info .address li:nth-child(2) .address-text").text(function() {
  $(this).replaceWith(

    '<a href="tel:' + $("#city-phone-numbers").val() + '">' + $("#city-phone-numbers").val() + "</a>"
  );
});
 var vibermobnumber = $("#city-phone-numbers").val().replace(/[^0-9]/g, '');	
	//$('#messicons2').html('<a href="https://wa.me/' + $("#city-phone-numbers").val() + '" class="whatsup"></a><a href="' + $("#city-phone-numbers").val() + '" class="telegram"></a><a href="viber://chat?number=' + vibermobnumber + '" class="viber"></a>');		

} else {
	//$('#messicons2').html('<a href="https://wa.me/' + $("#city-phone-numbers").val() + '" class="whatsup"></a><a href="' + $("#city-phone-numbers").val() + '" class="telegram"></a><a href="viber://chat?number=' + $("#city-phone-numbers").val() + '" class="viber"></a>');	
}
	
	

}
    $( document ).ready( function() {
		 /*===================================================================================*/
    /*  Header Phone Numbers Display
    /*===================================================================================*/

changecityphonefooter();
changecityphone();	
	 
	
        // Adjust Product Item heights
        // columnConform();

        // Excerpt read more toggle
        excerptReadmore();

        /*===================================================================================*/
        /*  Animate on scroll into view
        /*===================================================================================*/
        $( '.animate-in-view' ).each( function() {
            var $this = $(this), animation = $this.data( 'animation' );
            var waypoint_animate = new Waypoint({
                element: $this,
                handler: function(e) {
                    $this.addClass( $this.data( 'animation' ) + ' animated' );
                },
                offset: '90%'
            });
        });

        /*===================================================================================*/
        /*  STICKY NAVIGATION
        /*===================================================================================*/

        // If we're in hand-held navigation...
        if( pizzaro_options.enable_sticky_header == '1' ) {
            var sidebar_header = false;
            if ( $( window ).width() > 768 && $('body').hasClass('pizzaro-sidebar-header') ) {
                sidebar_header = true;
            }
            
            if( !sidebar_header && $( "#page" ).find( 'header' ).hasClass( "site-header" ) ) {
                var sticky_header = new Waypoint.Sticky({
                    element: $('header.site-header > .site-header-wrap')[0]
                });
            }
        }

    });
$( window ).load( function() {

		$( '#site-navigation2 .phm-close' ).on( 'click', function() {
			$( '#site-navigation2 .menu-toggle' ).trigger( 'click' );
		} );

		$( document ).click( function( event ) {
			var menuContainer = $( '#site-navigation2 .main-navigation' );

			if ( $( '#site-navigation2 .main-navigation' ).hasClass( 'toggled' ) ) {
				if ( ! menuContainer.is( event.target ) && 0 === menuContainer.has( event.target ).length ) {
					$( '#site-navigation2 .menu-toggle' ).trigger( 'click' );
				}
			}
		} );

    

      /* устанавливаем то же колличество и для допов */
      // var $product_quantity_contener = $(this).parents('.product-quantity');
      // if($product_quantity_contener.data('is_parent') == 'true'){
      //   console.log('is_parent=true')
      //     var $prod_key = $product_quantity_contener.data('product_key');
      //     $('[data-qty_parent="'+$prod_key+'"]').find('input[type="number"]').val(9);
      // }else{
      //   console.log('is_parent', $product_quantity_contener.data('is_parent'))
      // }

	} );
	
	 
 
	  //$("#billing_address_2_field").detach().appendTo("#billing_homeaddress");
//$("#billing_gatetimecheckout_field").detach().appendTo("#billing_homeaddress");
 
	
} )( jQuery );

( function() {
	var container, button, menu;

	container = document.getElementById( 'site-navigation2' );
	if ( ! container ) {
		return;
	}

	button = container.getElementsByTagName( 'button' )[0];
	if ( 'undefined' === typeof button ) {
		return;
	}

	menu = container.getElementsByTagName( 'ul' )[0];

	// Hide menu toggle button if menu is empty and return early.
	if ( 'undefined' === typeof menu ) {
		button.style.display = 'none';
		return;
	}

	menu.setAttribute( 'aria-expanded', 'false' );

	if ( -1 === menu.className.indexOf( 'nav-menu' ) ) {
		menu.className += ' nav-menu';
	}

	button.onclick = function() {
		if ( -1 !== container.className.indexOf( 'toggled' ) ) {
			container.className = container.className.replace( ' toggled', '' );
			button.setAttribute( 'aria-expanded', 'false' );
			menu.setAttribute( 'aria-expanded', 'false' );
		} else {
			container.className += ' toggled';
			button.setAttribute( 'aria-expanded', 'true' );
			menu.setAttribute( 'aria-expanded', 'true' );
		}
	};
} )();






  