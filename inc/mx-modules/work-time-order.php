<?php

////связь настроек Часы доставки с плагином Date Time Picker
//оформление заказа через сайт, при выборе времени доставки, открывается календарь, там должен быть выбор времени, в диапазоне работы доставки

add_filter('acf/update_value', 'set_calendar_worktime', 10, 3);
function set_calendar_worktime($value, $post_id, $field){
	if( $field['name'] == 'opening_hours' ){
		$dtrpckr_time_arr = get_option('dtpicker_advanced'); //debug_to_file($dtrpckr_time_arr);
	
		$work_time = get_worktime();
		
		foreach($work_time->week as $day => $time){
			 //debug_to_file($time);
				$from = $time->from;
				$to = $time->to; debug_to_file($from.' '.$to);
				$str_time = $from;
				$next_time = strtotime($from)+30*60;
				while($next_time < strtotime($to)+30*60){
					$str_time .= ',';
					$str_time .= date('H:i', $next_time);
					$next_time = $next_time+30*60;
				} 
				
				if($day == 1){ $dtrpckr_time_arr['monday_times'] = $str_time; }
				if($day == 2){ $dtrpckr_time_arr['tuesday_times'] = $str_time; }
				if($day == 3){ $dtrpckr_time_arr['wednesday_times'] = $str_time; }
				if($day == 4){ $dtrpckr_time_arr['thursday_times'] = $str_time; }
				if($day == 5){ $dtrpckr_time_arr['friday_times'] = $str_time; }
				if($day == 6){ $dtrpckr_time_arr['saturday_times'] = $str_time; }
				if($day == 7){ $dtrpckr_time_arr['sunday_times'] = $str_time; }
		}
		update_option('dtpicker_advanced', $dtrpckr_time_arr);
		debug_to_file($dtrpckr_time_arr);
	}
	return $value;
}

function get_worktime(){
	$url_domain = 'https://'.$_SERVER['SERVER_NAME']; //debug_to_file($url_domain);
	$url_api = '/wp-json/wc/v3/general-info';
		
	$work_time = curl_init($url_domain.$url_api);
		
	curl_setopt($work_time, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json'));
	curl_setopt($work_time, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($work_time, CURLOPT_RETURNTRANSFER, 1);
		
	$result = curl_exec ( $work_time ); //debug_to_file($result);
		
	curl_close($work_time);
	
	$arr = json_decode($result); //debug_to_file($arr->week);
	
	return $arr;
}