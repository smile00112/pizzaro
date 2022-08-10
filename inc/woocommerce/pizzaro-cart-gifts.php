<?php
$giftedidS = get_gift_sells();
?>
<? if(count($giftedidS)){?>
<tr class="aditprod"> <td style="width:100%;border-bottom:none;"  colspan="6" class="aditional_products ">
<div class="aditional_products_item title " style="
font-style: normal;
font-weight: normal;
font-size: 24px;
line-height: 130%;
color: #000000;">Дополнительно</div>
			<?php
			$cart_subtotalll = 0;
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				// When free productis is cart
				if ( !array_key_exists($cart_item['data']->get_id(),$giftedidS )) {
					$cart_subtotalll += $cart_item['line_total'] + $cart_item['line_tax'];
				}
			}
	
			
			
			$giftedidS = get_gift_sells();
			//echo $cart_subtotalll;
			//var_dump($giftedidS);
			
			foreach($giftedidS as $key => $val ) { 
			$dopproduct = wc_get_product( $key);
				$price = array_column($val, 'order_value');

			array_multisort($price, SORT_DESC, $val);
			$dopproduct = wc_get_product( $key);	
				
				//echo $dopproduct;
				
				$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $dopproduct->get_image() );
				
				
				 $class = implode( ' ', array_filter( array(
            'button',
            'product_type_' . $dopproduct->get_type(),
            $dopproduct->is_purchasable() && $dopproduct->is_in_stock() ? 'add_to_cart_button' : '',
            $dopproduct->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
        ) ) );

        // Adding embeding <form> tag and the quantity field
        $htmlqty = sprintf( '%s',
           
            woocommerce_quantity_input( array(), $dopproduct, false )
         
           
        );
		
		//Колличество
		$targeted_id = $dopproduct->get_id();
		// Get quantities for each item in cart (array of product id / quantity pairs)
		$quantities = WC()->cart->get_cart_item_quantities(); 

		// Displaying the quantity if targeted product is in cart
		if( isset($quantities[$targeted_id]) && $quantities[$targeted_id] > 0 ) {
		   $quantity = $quantities[$targeted_id];

		} else {
			$quantity = 0;
		}
		
		
		 $htmladdtocart = sprintf( '<a class="dopi_plus">+</a><a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" id="%s" data-product_sku="%s" class="%s" style="display:none!important">+</a>',
          
           
            esc_url( $dopproduct->add_to_cart_url() ),
            esc_attr( isset( $quantity ) ? $quantity+1 : 1 ),
            esc_attr( $dopproduct->get_id() ),
            esc_attr( $dopproduct->get_id() ),
            esc_attr( $dopproduct->get_sku() ),
            esc_attr( isset( $class ) ? $class : 'button' )
        
        );
				?>
				

				<div style="" class="aditional_products_item baur_table">
				<div class="aditional_products_itemimg"><?php echo $thumbnail; ?><div class="aditional_products_itemname"><?php echo $dopproduct->get_title(); ?></div></div>
				
				<div class="wrapels">
				
				
				<div class="aditional_products_itemqty">
				<div class="qidop-container">
		
		<div class="quantitydop buttons_added">
		<a class="dopi_minus">-</a><a href="<?=esc_url( $dopproduct->add_to_cart_url() )?>" data-quantity="<?echo (isset( $quantity ) ? $quantity-1 : 0)?>" class="minus add_to_cart_button ajax_add_to_cart" data-productid="<?php echo $dopproduct->get_id(); ?>" style="display:none!important;">-</a>
		<span class="kolichestvo"><?php // Set here your product ID (or variation ID)
$targeted_id = $dopproduct->get_id();

// Get quantities for each item in cart (array of product id / quantity pairs)
$quantities = WC()->cart->get_cart_item_quantities(); 

// Displaying the quantity if targeted product is in cart
if( isset($quantities[$targeted_id]) && $quantities[$targeted_id] > 0 ) {
   echo $quantities[$targeted_id];

} else {
	echo '0';
}?></span>
			<?php echo $htmladdtocart; ?>
			</div>
		<?php if( isset($quantities[$targeted_id]) && $quantities[$targeted_id] > 0 ) {
  
   
   echo '
   <style>
   #cart_itemid'.$targeted_id.'{display:none;}
   </style>
   ';
}?></div>
		
	</div>
	
			
	
	
				<div class="aditional_products_itemprice">
				<?php 
				$addedprice = false;
				
				foreach($val as  $qtyallow) {
						if( isset($quantities[$targeted_id]) && $quantities[$targeted_id] > 0 ) {
					if( $cart_subtotalll   >= $qtyallow['order_value'] && $quantities[$targeted_id] <= $qtyallow['quantity'] ) {
						
						
						
						 echo '<span class="woocommerce-Price-amount amount"><bdi>0&nbsp;<span class="woocommerce-Price-currencySymbol">₽</span></bdi></span>';
						
						$addedprice = true;
						break;
					} elseif ($cart_subtotalll   >= $qtyallow['order_value'] && $quantities[$targeted_id] >= $qtyallow['quantity'] ) {
						$valuewill = $dopproduct->get_price() * ($quantities[$targeted_id] - $qtyallow['quantity']);
						 echo '+ <span class="woocommerce-Price-amount amount"><bdi>'.$valuewill.'&nbsp;<span class="woocommerce-Price-currencySymbol">₽</span></bdi></span>';
					 $addedprice = true;
					 break;
					}
						}
				
				}
				
				if(!$addedprice){
					 echo '+ '.$dopproduct->get_price_html(); 
				}
				
				
				 ?>
                </div>
                <!-- </div> -->
				<div class="aditional_products_itemaud baur_desctop_vid">
				    <?php 
				        $ctr = 1;
				        foreach($val as  $qtyallow) {
					        if( $cart_subtotalll   >= $qtyallow['order_value'] ) {
                                echo '<span class="allowedfree">До '.$qtyallow['quantity'].' шт бесплатно</span>';
                                break;
					        }
				        }
				    ?>
				</div>
            </div>
            <div class="aditional_products_itemaud baur_momile_vid">
                <?php 
                    $ctr = 1;
                    foreach($val as  $qtyallow) {
                        if( $cart_subtotalll   >= $qtyallow['order_value'] ) {
                            echo '<span class="allowedfree">До '.$qtyallow['quantity'].' шт бесплатно</span>';
                            break;
                        }
                    }
                ?>
            </div>
		</div>
		<?php	}
			?>
			
			 <script type='text/javascript'>
        jQuery(function($){
			var cart_update_timeout = 0
			$(document.body).on( "click", ".dopi_minus", function(e) {
				e.preventDefault();
				var $el = $(this);
				var q = $(this).parent().find('.kolichestvo').text()*1-1;
				if(q<0) q=0;
				$(this).parent().find('.kolichestvo').text(q)
				    clearTimeout(cart_update_timeout);
					cart_update_timeout = setTimeout(function() {
						var q =  $el.parent().find('.kolichestvo').text()*1;
						console.warn(q)
						$el.next().attr('data-quantity', q).click();
					}, 600);
			});
			$(document.body).on( "click", ".dopi_plus", function(e) {
				e.preventDefault();
				var $el = $(this);
				var q = $(this).parent().find('.kolichestvo').text()*1+1;
				$(this).parent().find('.kolichestvo').text(q)
				clearTimeout(cart_update_timeout);
				cart_update_timeout = setTimeout(function() {
					var q =  $el.parent().find('.kolichestvo').text()*1;
					console.warn(q)
					$el.next().attr('data-quantity', q).click();
					//$el.next().click();
				}, 600);
					
			});
            // Update data-quantity
	
			$(document.body).on( "click", ".quantitydop .minus", function(e) {	
			 e.preventDefault();

				var qtyspan = jQuery( this ).closest( ".aditional_products_item" ).find( ".kolichestvo" );
				
				var profid = jQuery( this ).attr( "data-productid" );
				var currval = qtyspan.html();
				currval = (currval*1)
				if (currval >= 1) {
					//var currproductblock = jQuery( 'body').find( "#cart_itemid"+profid );
					//var prodqutyinput = currproductblock.find('input.qty');
					console.log('[data-product_key="'+profid+'"]');
					var proddelete = jQuery( 'body').find('a.remove[data-product_id="'+profid+'"]').parents('.cart_item').find('a.remove')
					var prodqutyinput = proddelete.parents('.cart_item').find( 'input.qty' );
					prodqutyinput.val(currval).trigger("change");
				
				} else if(currval == 0){
					//var currproductblock = jQuery( 'body').find( "#cart_itemid"+profid );
					//var proddelete = currproductblock.find('a.remove');
					var proddelete = jQuery( 'body').find('a.remove[data-product_id="'+profid+'"]').parents('.cart_item').find('a.remove')
					proddelete.trigger("click");
				}
			
			});
			
			

        });
    </script>
		<style>
		.dopi_minus, .dopi_plus{
			cursor: pointer;
			

            -ms-user-select: none;

            -moz-user-select: none;

            -khtml-user-select: none;

            -webkit-user-select: none;
		}
		
		
		      .aditprod {
}

.aditprod td {
}

.wrapels {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 50%;
}

.fee {
    display: none;
}

.allowedfree {
    /* font-family: Inter; */
    font-style: normal;
    font-weight: bold;
    font-size: 14px;
    line-height: 130%;
    /* or 18px */
    color: #FFFFFF;
    background: #EF762C;
    padding: 10px 25px;
    background: url(/wp-content/themes/pizzaro/assets/images/Union.svg) no-repeat;
    background-size: contain;
}

.aditional_products_item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}

.aditional_products_itemimg {
    min-width: 50%;
    width: 50%;
    display: flex;
    align-items: center;
    flex: 1 1 auto;
}

.aditional_products_itemimg img {
    width: 40px;
    margin-right: 20px;
}

.aditional_products_itemname {
    /* font-family: Inter; */
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 130%;
    /* identical to box height, or 26px */
    color: #343941;
}

.quantitydop {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
    padding: 8px 20px;
    width: 149px;
    background: #FFFFFF;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.07);
    border-radius: 50px;
}

.quantitydop a {
    line-height: 1;
    display: inline-block!important;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-weight: 700;
    color: #EF762C;
    background: #fff;
    border-color: #fff;
    float: none;
    min-height: initial;
    min-width: initial;
    max-height: initial;
    max-width: initial;
    vertical-align: middle;
    font-size: 16px;
    letter-spacing: 0;
    border-style: solid;
    border-width: 1px;
    transition: none;
    border: none;
    vertical-align: middle;
    text-align: center;
    line-height: inherit;
    background: #FFFFFF;
}
.quantitydop a svg{display:none!important}
.quantitydop a:hover {
    color: #EF762C;
    border: none;
    background: #FFFFFF;
}

.aditional_products_itemqty .qidop-container .qib-container {
    background: #FFFFFF;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.07);
    border-radius: 50px;
    width: fit-content;
    overflow: hidden;
}

.aditional_products_itemqty .qidop-container .quantity {
    /* font-family: Inter; */
    font-style: normal;
    font-weight: bold;
    font-size: 16px;
    line-height: 130%;
    /* identical to box height, or 21px */
    color: #292929;
    /* Inside Auto Layout */
    flex: none;
    order: 1;
    flex-grow: 0;
    margin: 20px 0px;
}

.aditional_products_itemqty label {
    display: none!important
}

.aditional_products_itemqty input {
    padding: 0;
    max-width: auto;
}

.aditional_products_itemprice {
    flex: 1 1 auto;
    /* font-family: Inter; */
    font-style: normal;
    font-weight: bold;
    font-size: 16px;
    line-height: 130%;
    /* identical to box height, or 21px */
    color: #292929;
    margin-left:20px;
}

.aditional_products_itemaud {
    position: relative;
    flex: 1 1 auto;
}

.aditional_products_itemaud a {
    /* font-family: Inter; */
    font-style: normal;
    font-weight: 500;
    font-size: 20px;
    line-height: 130%;
    padding: 5px 10px;
    position: absolute;
    top: 0;
    left: 10px;
    background-color: #EA5C2C;
}

.aditional_products_itemaud {
    flex: 1 1 auto;
    min-width: 30%;
}

.quantitydop .add_to_cart_button::before {
    /* font-family: font-pizzaro; */
    content: "";
    font-size: 1.16em;
    line-height: 0;
    margin-right: .4em;
    vertical-align: middle;
    font-weight: 600;
    display: none!important;
}

@media (max-width: 999px) {
    .aditprod {
    }

    .aditprod td {
    }

    .wrapels {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
    }

    .quantitydop {
    }

    .quantitydop a {
    }

    .quantitydop .add_to_cart_button {
    }

    .aditional_products_itemimg {
    width: 100%;
    padding-bottom: 20px;
    }

    .aditional_products_item {
    flex-direction: column;
    padding-bottom: 30px;
    margin-bottom: 30px;
    border-bottom: 1px solid #000;
    }

    .aditional_products_itemaud {
    flex: 1 1 auto;
    }

    .aditional_products_itemprice {
    }

    .aditional_products_itemqty .qidop-container .qib-container {
    }
}

@media (max-width: 480px) {
    .aditprod {
    }

    .aditprod td {
    }

    .wrapels {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        flex-direction: column;
    }

    .quantitydop {
    }

    .quantitydop a {
    }

    .quantitydop .add_to_cart_button {
    }

    .aditional_products_itemimg {
    text-align: center;
    }

    .aditional_products_item {
    }

    .aditional_products_itemaud {
    margin-top: 20px;
    }

    .aditional_products_itemprice {
    margin-top: 20px;
    }

    .aditional_products_itemqty .qidop-container .qib-container {
    }
    
    .woocommerce table.cart td.product-quantity .qib-container:not(#qib_id):not(#qib_id) {
    
    border: none;
    box-shadow: none;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
                                                               
.qib-button:not(#qib_id):not(#qib_id) {
   
    float: none;
    margin: 0 auto;
}
table.cart tbody tr td {
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

}

</style>		

</td>
</tr>
<?}?>