<?php
add_action('woocommerce_order_status_completed', 'check_events');
//if($_GET['trt']) check_events(30068);
function check_events($order_id = 0) {
	// ini_set('display_errors', 'On'); //отключение ошибок на фронте
	// ini_set('log_errors', 'On'); //запись ошибок в логи
	global $wpdb ;

	$auto_events_status = get_field('auto_events_status', 'option');
	if($order_id){
		if($auto_events_status){
			$auto_events_all = get_field('auto_events', 'option');
			$order = new WC_Order($order_id);
			$user = $order->get_user();
			
			foreach($auto_events_all as $auto_event){
				if($auto_event['status'] == 1){
					$event_aproved_log = [];
					foreach($auto_event['activate_conditions'] as $condition){
						$time = 'NOW()';
						$event_aproved = false;
						

						//print_r($condition);
						switch($condition['condition']){
							/* смотрим условия на отложенное уведомление и увеличиваем время срабатывания  */
							//DATE_ADD(p.post_date, INTERVAL pm.meta_value DAY)
							case 'time_from_last_order':
								//$newdate = date('Y-m-d H:i:s', ( strtotime($order->get_date_created()) + $condition['conditions_value_last_order_time']*60*60 ));
								// $newdate = date('Y-m-d H:i:s', ( strtotime($order->get_date_created()) + $condition['conditions_value_last_order_time']*60*60 ));
								// $time = "'".$newdate."'";
								// $event_aproved = true;
							break;

							case 'total_order':
								$needed_total = $condition['order_total'];
								$order_data = $order->get_data();
								if( $order_data['total'] >= $needed_total )
									$event_aproved = true;
							break;	

							case 'orders_count_is':
								/* Считаем заказы */
								$args = array(
									'customer_id' => $user->ID,
									'status'=> array( 'wc-completed' ),
								);
								$orders = wc_get_orders( $args );
								if( count( $orders ) == $condition['orders_count'] ){
									$event_aproved = true;
								}
							break;

							case 'customer_used_coupon':
								/* Смотрим купоны */
								foreach( $order->get_coupon_codes() as $coupon_code ){
									//echo $coupon_code;
									$coupon_obj = new WC_Coupon($coupon_code);
									$coupon_id = $coupon_obj->get_id();
									if( $condition['order_coupon'] == $coupon_id ){
										
										$event_aproved = true;
										break;
									}
								}

							break;	

							case 'customer_buy_product':
								/* Смотрим купленные товары */
								$needed_products = $condition['order_products'];
								$products_categories = [];
								foreach ($order->get_items() as $item) {

									if(in_array($item["product_id"], $needed_products)){
										$event_aproved = true;

									}

									$product_cats = get_the_terms($item["product_id"], 'product_cat');
									foreach	($product_cats as $cat){
										$products_categories[]=$cat->term_id;
									}
									
								}
								//print_r($products_categories);
							break;

							case 'customer_buy_from_category':
								/* Смотрим купленные товары в определённой категории*/
								$needed_categories = $condition['order_category'];
								$products_categories = [];
								foreach ($order->get_items() as $item) {

									$product_cats = get_the_terms($item["product_id"], 'product_cat');
									foreach	($product_cats as $cat){
										$products_categories[]=$cat->term_id;
									}
									
								}

								//Сравнивам массивы с нужными категриями и имеющимися в заказе и если есть хоть одно совпадаение, то проверка прошла
								if(count(array_intersect($needed_categories, $products_categories)))
									$event_aproved = true;

							break;
							
							case 'customer_used_bonuses':
								/* Смотрим потраченные бонусы*/
								$needed_redeemed = $condition['order_bonuses'];
								$already_has = get_user_meta( $user->ID, 'bonus_event_'.$auto_event['event_code'], true );
								
								// $Points        = $PointsData->total_available_points() ;
								// $Points2        = $PointsData->total_earned_points() ;
								// $Points3        = $PointsData->total_redeemed_points() ;	
								if(!$already_has){
									$PointsData = new RS_Points_Data( $user->ID );							
									$total_redeemed = $PointsData->total_redeemed_points() ;
									if( $needed_redeemed <= $total_redeemed ){
										$event_aproved = true;
										update_user_meta( $user->ID, 'bonus_event_'.$auto_event['event_code'], 1 );
									}
								}
									
							break;

							case 'time_from_last_app_visiting':
								/* Время последней активности*/

							/*
							TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(p.post_date, INTERVAL pm.meta_value DAY) ) 

							p.post_type ='shop_order' AND (p.post_status !='wc-cancelled' AND p.post_status!='wc-pending') 	AND ( TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(p.post_date, INTERVAL pm.meta_value DAY) ) > 0 AND TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(p.post_date, INTERVAL pm.meta_value DAY) ) < 24) AND HOUR(NOW()) = 12  
							*/		
								//p.post_type ='shop_order' AND (p.post_status !='wc-cancelled' AND p.post_status!='wc-pending') 	AND ( TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(p.post_date, INTERVAL pm.meta_value DAY) ) > -24 AND TIMESTAMPDIFF(HOUR, NOW(), DATE_ADD(p.post_date, INTERVAL pm.meta_value DAY) ) < 24) AND HOUR(NOW()) = 12 
								//echo '<pre>';  

							break;

							
						}

						$event_aproved_log[]=$event_aproved;

					}	

						
						//print_r($event_aproved_log);
						$event_aproved = true;
						foreach($event_aproved_log as $l)
							$event_aproved = $event_aproved && $l;

						if($event_aproved){
							echo "INSERT INTO auto_events SET code='".$auto_event['event_code']."', order_id='".$order_id."', user_id= 0, time='.$time.', status= 1 _____";
							$wpdb->query( "INSERT INTO auto_events SET code='".$auto_event['event_code']."', order_id='".$order_id."', user_id= 0, time=$time, status= 1, date_added=NOW()" );

						}
				}
			}
		}
	}else{
		//Если без order_id, то смотрим "истёкшие события"
		//echo 'NO order_id';

		$auto_events_all = get_field('auto_events', 'option');

		foreach($auto_events_all as $auto_event){
			if($auto_event['status'] == 1){
				$event_aproved_log = [];
				foreach($auto_event['activate_conditions'] as $condition){
					$time = 'NOW()';
					$event_aproved = false;
					//print_r($condition);
					switch($condition['condition']){
						/* смотрим условия на отложенное уведомление и увеличиваем время срабатывания  */
						//DATE_ADD(p.post_date, INTERVAL pm.meta_value DAY)
						case 'time_from_last_order':
							
							$sql ="SELECT um.user_id as user, um.meta_key, um.meta_value, TIMESTAMPDIFF(HOUR,  DATE_FORMAT(FROM_UNIXTIME(um.meta_value), '%Y-%m-%d %H:%i:%s'), NOW() ) FROM ".$wpdb->prefix."usermeta um WHERE um.meta_key ='wc_last_active' AND  TIMESTAMPDIFF(HOUR,  DATE_FORMAT(FROM_UNIXTIME(um.meta_value), '%Y-%m-%d %H:%i:%s'), NOW() ) BETWEEN ".$condition['conditions_value_last_order_time']." AND ".($condition['conditions_value_last_order_time']+24)." AND ( HOUR(NOW()) = 17 AND MINUTE(NOW()) = 51) AND user_id != 1";
							$Data = $wpdb->get_results( $sql , ARRAY_A );

							foreach ( $Data as $key => $eacharray ) {
								$wpdb->query( "INSERT INTO auto_events SET code='".$auto_event['event_code']."', order_id= 0, user_id=".$eacharray['user'].", time=$time, status= 1, date_added=NOW()" );
								//echo "INSERT INTO auto_events SET code='".$auto_event['event_code']."', order_id= 0, user_id=".$eacharray['user'].", time=$time, status= 1, date_added=NOW()";
							}

						break;
					}
				}
			}
		}

	}
}

function activate_prersonal_coupon($coupon_id, $user_id, $is_coupon_time_limit = true, $coupon_days_limit = 0, $coupon_hours_limit = 0){

	$avaible_users = get_field('usage_avaible_users', $coupon_id );

	//$avaible_users =  get_post_meta($coupon_id, 'usage_avaible_users', true  );
	$individual_coupon = get_post_meta($coupon_id, 'individual_coupon', true );	
	$user_search = array_search($user_id, array_column($avaible_users, 'user'));
	$timezone = wp_timezone_string();
	$tz = new DateTimeZone($timezone);

	if($is_coupon_time_limit){
		$date = new DateTime("now", $tz);
		if($coupon_days_limit)
			$date->modify( '+'.$coupon_days_limit.' day' );

		if($coupon_hours_limit)
			$date->modify( '+'.$coupon_hours_limit.' hour' );
			
		//$stop_date->format('Y-m-d H:i:s');
		$coupon_time_limit = $date->format('Y-m-d H:i:s');
	}else{
		$date = new DateTime("now", $tz);
		$date->modify( '+1 year' );
		$coupon_time_limit = $date->format('Y-m-d H:i:s');
	}

	if(empty($avaible_users)) $avaible_users = [];

	if($individual_coupon ){
		if($user_search == false){
			$avaible_users[]= [ 'user' => $user_id,  'use_until' => $coupon_time_limit ];
			//	$avaible_users = update_post_meta($coupon_id, 'usage_avaible_users', $avaible_users);
		}else{
			$avaible_users[$user_search] = [ 'user' => $user_id,  'use_until' => $coupon_time_limit ];
		}
	}

	update_field('usage_avaible_users', $avaible_users, $coupon_id);
}