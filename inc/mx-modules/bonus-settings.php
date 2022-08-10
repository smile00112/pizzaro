<?php
////Фильтр для события Обновление acf поля, обновление настроек бонусов
add_filter('acf/update_value', 'upd_bonus_settings', 10, 3);
function upd_bonus_settings($value, $post_id, $field){
	
	
	if( $field['name'] == 'bon_use_with_promo' ){
		if(in_array($value, ['no', 'yes']))
			update_option('rs_disable_point_if_coupon',$value);
	}
	
	if( $field['name'] == 'bon_use_with_sale' ){
		if(in_array($value, ['no', 'yes']))
			update_option('rs_point_not_award_when_sale_price',$value);
	}

	if( $field['name'] == 'perc_for_purch1' ){ // настройка бонусов - процент начисления за покупки
		$perc_for_purch = $_POST['acf']['field_607edabe3a5e3'];
		//debug_to_file('upd_acf perc_for_purch'.$new_value);
		update_option('rs_global_reward_percent',$perc_for_purch);
	}
	
	if( $field['name'] == 'bon_for_reg' ){ // кол-во бонусов за регистрацию
		$bon_for_reg = $_POST['acf']['field_6049f2f95a91f'];
		//debug_to_file('upd_acf perc_for_purch'.$new_value);
		update_option('rs_reward_signup',$bon_for_reg);
	}
	
	if( $field['name'] == 'level_up_group' ){
		$group_bon = $_POST['acf']['field_607edb6a3362d'];

		$new_ser_lev = [];
		$cur_time = time();
		foreach($group_bon as $lev){
			//debug_to_file($cur_time);
			$lev_name = '';
			$lev_bon = '';
			$lev_perc = '';
			$lev_type = '';
			foreach($lev as $k=>$v){
				if($k == 'field_607edb9b3362e') $lev_name = $v; // название уровня
				if($k == 'field_60800b8eef594') $lev_type = $v; // тип вычисления
				if($k == 'field_607ef202f17f1') $lev_bon = $v; // значение
				if($k == 'field_607ef259f17f2') $lev_perc = $v; // процент
				
			}
			if($lev_type == 'numberorder') $lev_type = '1';
			else if($lev_type == 'totalmoney') $lev_type = '2';
				else if($lev_type == 'maunthtotalmoney') $lev_type = '3';
			
			//debug_to_file($lev_type); debug_to_file('---');
			$lev_perc = intval($lev_perc) * 100; //$lev_perc = $lev_perc.'';
			$new_ser_lev[$cur_time]['name'] =  $lev_name;
			$new_ser_lev[$cur_time]['type'] =  $lev_type;
			$new_ser_lev[$cur_time]['value'] =   $lev_bon;
			$new_ser_lev[$cur_time]['percentage']  =  $lev_perc;
			$cur_time++;
			//debug_to_file($new_ser_lev);			
		}
		
		//debug_to_file($new_ser_lev);
		update_option('rewards_dynamic_rule_purchase_history',$new_ser_lev);
			
		$lev_status = $_POST['acf']['field_6081590e1cde6']; // статусы начисления бонусов
		//debug_to_file($lev_status);
		$status_arr = [];
		if(empty($lev_status)){ //если статусы не заданы, по-умолчанию
			array_push($status_arr, 'completed');
		}
		else{
			foreach($lev_status as $status_id){
				$status_name = get_post_field( 'post_name', $status_id );
				array_push($status_arr, $status_name);
			}
		}
		//debug_to_file($status_arr);
		
		update_option('rs_earning_percentage_order_status_control',$status_arr); //обновление поля статусов в плагине в бд
		update_option('rs_product_purchase_history_range','2'); //тип учёта(2 - после достижения цели, 1 - до достижения цели)
		update_option('rs_enable_user_purchase_history_based_reward_points','yes'); //активация функционала уровней в плагине
		
		$bonus_day_nulled = $_POST['acf']['field_608168d425ab8']; // через сколько дней сгорают бонусы
		update_option('rs_point_to_be_expire',$bonus_day_nulled);
		update_option('rs_point_expiry_activated','yes'); //активация сгорания
		
	}
	
	return $value;
}