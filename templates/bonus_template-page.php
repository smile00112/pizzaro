<?php
/*
* Template Name: Бонусная система
*/

get_header();

//echo $_GET['user_id'];

$img_path = get_template_directory_uri().'/assets/images';

$user_id = intval($_GET['user_id']); //echo $cur_user_id;

$fake_num_before = '000 000 '; //прставка перед id пользователя, для вида
//echo '$user_id = '.$user_id;
$bon_lev = 1; //уровень бонусов
$bon_balance = intval(get_user_bonus_page($user_id)); //баланс бонусов пользователя
$spent_sum_all = intval(wc_get_customer_total_spent( $user_id )); //пользователь потратил денег
$spent_sum_last_mounth = intval(get_user_total_spent_for_last_mounth( intval($user_id) ));

$bon_perc = 10; //пройдено "пути" до следующего уровня(прогресс-бар). задаём старт для визуального отображения

$levels_data = get_bonus_level_page();//debug_to_file($levels); //данные уровней бонусов(номер, необходимая сумма до след. уровня)
$levels = $levels_data['values'];
$type = $levels_data['types'][1]; //Смотрим тип подсчета у первого и считаем, что у остальных

$bonus_history = get_user_bonus_history( intval($user_id) );


if($type!=3){
	$spent_sum = $spent_sum_all;
}else{
	$spent_sum = $spent_sum_last_mounth;
}
$next_lev_num = 1;  //номер следующего уровня
$next_lev_sum = 0;  //общая сумма, чтоб перейти на след. уровень
$next_lev_flag = 0; //флаг для фиксации суммы след. уровня


foreach($levels as $lev_num => $lev_sum){ //debug_to_file($lev_sum .' -| ');

	if($lev_sum > $spent_sum && $next_lev_flag == 0){ //если сумма уровня больше, чем пользователь потратил, тогда устанавливаем как цель суммы(прогресс-бар)
			$next_lev_num = $lev_num;
			$next_lev_sum = $lev_sum;
			$next_lev_flag = 1;
		}
		if($lev_sum < $spent_sum )$bon_lev = $lev_num; //текущий уровень

}

if($next_lev_sum != 0) $bon_perc = round($spent_sum / $next_lev_sum * 100);
else if($next_lev_sum == 0) {
	$next_lev_sum = $spent_sum;
	$bon_perc = 100;
}
if($bon_perc < 10) $bon_perc = 10; //чтоб визуального было частично заполнено

$levels_info = get_field('level_up_group', 'option');



function get_user_bonus_page2($user_id){ // информациионные поля бонусов
    
    $ar_all_fields = [];
	$user_id = (int) $request['user_id'];
	$percent_max = get_field('bon_percent_max', 'option');
	
	global $wpdb ;
	$table = $wpdb->prefix . "rspointexpiry" ;
	$result = $wpdb->get_results( "SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid = $user_id" , ARRAY_A ) ;
    $points_balance = round_off_type( number_format( ( int ) $result[0]["availablepoints"] , 2 , '.' , '' ) );
	
    //$points = (int) get_field('android_ver', 'option'); 

	$ar_all_fields += ['user_id' => $user_id];
	$ar_all_fields += ['points_balance' => $points_balance];
    $ar_all_fields += ['percent_max' => intval($percent_max)];		
            

    return $ar_all_fields; 
}

function get_user_total_spent_for_last_mounth( $user_id ) {
	global $wpdb ;
	$OrderIds = $wpdb->get_results(  $wpdb->prepare( "SELECT posts.ID
		FROM $wpdb->posts as posts
		LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
		WHERE   meta.meta_key       = '_customer_user'
		AND     posts.post_type     IN ('" . implode( "','" , wc_get_order_types( 'order-count' ) ) . "')
		AND     posts.post_status   IN ('" . implode( "','" , array_keys( wc_get_order_statuses() ) ) . "')
		AND     meta_value          = %d
		AND		posts.post_date > LAST_DAY(DATE_SUB(CURDATE(), INTERVAL 2 MONTH))
		AND		posts.post_date < DATE_ADD(LAST_DAY(CURDATE() - INTERVAL 1 MONTH), INTERVAL 1 DAY) 
	" , $user_id ) , ARRAY_A );

	foreach ( $OrderIds as $Ids ) {
		$Total[] = get_post_meta( $Ids[ 'ID' ] , '_order_total' , true ) ;
	}

	return  array_sum( $Total ) ;
	

}

function get_user_bonus_history( $user_id ) {
	global $wpdb ;
	$bonus_history = [];
	//SUM(earnedpoints) as sum_earnedpoints, SUM(redeempoints) as sum_redeempoints, 
	// GROUP BY orderid
	// MAP
	$bonus_history_t = $wpdb->get_results(  $wpdb->prepare( "
		SELECT rs.*
		FROM `wp_rsrecordpoints` as rs
		WHERE `userid` = %d
		
		ORDER BY id DESC
		LIMIT 1500
		
	" , $user_id ) , ARRAY_A );

	$bonus_history_mod = [];
	$orders_map = [];
	foreach($bonus_history_t as $index=>$bh){
		$order_id = $bh['orderid'];
		if(!$bh['orderid']) $order_id = $bh['checkpoints'].'_'.$bh['id'];
		if(empty($bonus_history_mod[$order_id])){
			$bonus_history_mod[$order_id] = $bh;
		}else{
			$bonus_history_mod[$order_id]['earnedpoints']+=$bh['earnedpoints'];
			$bonus_history_mod[$order_id]['redeempoints']+=$bh['redeempoints'];
		}
	}
// echo '<pre>';
// 	//print_R($bonus_history_t);
// 	print_R($bonus_history_mod);
// 	exit;
	$bonus_history_t = array_values($bonus_history_mod);
	foreach($bonus_history_t as $bh){
		$date = date('Y-m-d', $bh['earneddate']);
		$bonus_history[$date][]=$bh;
	}

	return $bonus_history;

}
function rus__date( $date ){
	$d = strtotime( $date );
	$months = array( 1 => 'Января' , 'Февраля' , 'Март' , 'Апреля' , 'Мая' , 'Июня' , 'Июля' , 'Августа' , 'Сентября' , 'Октября' , 'Ноября' , 'Декабря' );
	return date( date( 'd' , $d).' ' . $months[date( 'n' , $d)] .' '.  date( 'Y' , $d) ); 
}
?>
<style>
#bon-info-modal {
    /* width: 310px;
    height: 100%;
	min-height: 600px;
    z-index: 999999;
    box-shadow: 0px 8px 35px rgb(0 0 0 / 5%);
    border-radius: 20px;
    padding: 20px;
    background-color: #fff;
    position: absolute;
    top: 0;
    display: none; */
}
</style>
<div id="bonus-syst-block">
	<div class="bon-batchcode"><img src="<?php echo $img_path.'/bon-batchcode.png'; ?>" alt=""></div>
	<div class="user-code"><?php echo $fake_num_before.$user_id ?></div>
	<div class="for-hr"><hr class="after-user-code"></div>
	<div class="bon-info" style="<? if(count($levels) > 1) echo 'height: 155px;'; else echo 'height: 43px;';?>">
		<? if(count($levels) > 1){?>
		<div class="bn-i left">У Вас<br><span class="bon-num-lev"><?php echo $bon_lev; ?></span><span class="text"> Уровень</span></div>
		<?}?>
		<div class="bn-i right">У Вас бонусов<br><span class="bon-num-lev"><?php echo $bon_balance; ?> ₽</span></div>	
		<? if(count($levels) > 1){?>
		<div class="bon-progress" data-perc="<?php echo $bon_perc; ?>">
			<div class="bon-bar"><span class="bon-start"><?php echo $spent_sum; ?> ₽</span></div>
			<span class="bon-target"><?php echo $next_lev_sum; ?> ₽</span>
		</div>
		<?}?>
		<div class="info-bottom">
			<? if(count($levels) > 1){?>
				<div class="text">Осталось купить на <?php echo $next_lev_sum-$spent_sum; ?> ₽,<br> чтобы получить <?php echo $bon_lev+1 ?> уровень</div>
			<?}?>
			<div class="bon-but-modal">?</div>
		</div>
			<br>	
		<div id="bon-info-modal">
			<div class="close"></div>
			<div class="title">Правила бонусной системы</div>
				<?php
					foreach($levels_info as $levels_info_item){
				?>
				<div class="item">
					<div class="sub-title"><?php echo $levels_info_item['level_info_title']; ?></div>
					<div class="text"><?php echo $levels_info_item['level_info_text']; ?></div>
				</div>
					<?php } ?>
				<!--<div class="item">
					<div class="sub-title">Уровень 2</div>
					<div class="text">Значимость этих проблем настолько очевидна, что начало повседневной работы по формированию позиции играет важную роль в формировании направлений прогрессивного развития.</div>
				</div>
				<div class="item">
					<div class="sub-title">Уровень 3</div>
					<div class="text">Информация является предварительной, исходя из истории учтенных заказов на сегодняшний день.</div>
				</div>-->
				<div class="info-bottom">
					<? if(count($levels) > 1){?>
						<div class="text">Осталось купить на <?php echo $next_lev_sum-$spent_sum; ?> ₽,<br> чтобы получить <?php echo $bon_lev+1 ?> уровень</div>
					<?}?>
				<div class="bon-but-modal">?</div>
				</div>
		</div>

	</div>
	<div class="bonus-history history_close">
			<div class="bonus-history-list-header  "><div>История начислений</div> <div class="list-opened-icon"></div></div>
			<div class="bonus-history-list">
					<?

						$old_date = '';
						foreach($bonus_history as $d => $b){
							//$ds = (string)date('d-m-Y', $d);
							$date1 = new DateTime("now");
							$date2 = new DateTime($d);
							$interval = $date1->diff($date2);
							if( $interval->d == 0)  $d = 'Сегодня';
							else
							if( $interval->d == 1 ){
								$d = 'Вчера, '. rus__date( $d );
							}else{
								$d = rus__date(  $d  );
							}
							//if(strtotime($now) == strtotime($ds)) $d = 'Сегодня';

							?>
							<div class="item-wrapper">
								<div class="bonus-history-item-date-wrapper"><div class="bonus-history-item-date"><?=$d?></div></div>
							<?
							foreach($b as $bh){
								$sum_earnedpoints = intval( $bh['earnedpoints'] ); //sum_redeempoints
								$sum_redeempoints = intval( $bh['redeempoints'] ); //sum_redeempoints
								if(!$sum_redeempoints) $count = '+'.$sum_earnedpoints;
								else $count = '-'.$sum_redeempoints;
								$info = $bh['checkpoints'];
								$order_id = $bh['orderid'];
								$date = date('Y-m-d', $bh['earneddate']);
								switch ($info) {
									case 'PPRP':
										$info = 'бонусов за заказ №'.$order_id;
										break;
									
									case 'RP':
										$info = 'потрачено на заказ №'.$order_id;
										break;
										
									case 'SLRRP':
										$info = 'бонусов на заказ реферала';
										break;

									case 'AUEVADDP':
										$info = 'бонусов выполненное условие';
										break;

									case 'MAP':
										$info = 'бонусов выполненное условие';
										break;

									/*ручное зачисление */
									case 'MAURP':	
										$info = $bh['reasonindetail'];
										$count = '+'.$sum_earnedpoints;		
									break;																		
									/*ручное списание */
									case 'MRURP':
										$info = $bh['reasonindetail'];
										$count = '-'.$sum_earnedpoints;
										break;		
																		
									default:
										$info = '???';

								}
								?>
									<div class="bonus-history-item"><div class="bonus-count"><?=$count;?></div> <div class="bonus-info"><?=$info;?></div></div>
								
								<?

							}
							?>
								</div>
							<?
						}
						
					?>
			</div>

		</div>
</div>

<style>
		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ExtraBold.eot');
			src: local('Gilroy ExtraBold'), local('Gilroy-ExtraBold'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ExtraBold.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ExtraBold.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ExtraBold.ttf') format('truetype');
			font-weight: 800;
			font-style: normal;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Heavy.eot');
			src: local('Gilroy Heavy'), local('Gilroy-Heavy'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Heavy.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Heavy.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Heavy.ttf') format('truetype');
			font-weight: 900;
			font-style: normal;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-LightItalic.eot');
			src: local('Gilroy Light Italic'), local('Gilroy-LightItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-LightItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-LightItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-LightItalic.ttf') format('truetype');
			font-weight: 300;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ThinItalic.eot');
			src: local('Gilroy Thin Italic'), local('Gilroy-ThinItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ThinItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ThinItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ThinItalic.ttf') format('truetype');
			font-weight: 100;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-BlackItalic.eot');
			src: local('Gilroy Black Italic'), local('Gilroy-BlackItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-BlackItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-BlackItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-BlackItalic.ttf') format('truetype');
			font-weight: 900;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-BoldItalic.eot');
			src: local('Gilroy Bold Italic'), local('Gilroy-BoldItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-BoldItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-BoldItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-BoldItalic.ttf') format('truetype');
			font-weight: bold;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-SemiBold.eot');
			src: local('Gilroy SemiBold'), local('Gilroy-SemiBold'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-SemiBold.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-SemiBold.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-SemiBold.ttf') format('truetype');
			font-weight: 600;
			font-style: normal;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-UltraLightItalic.eot');
			src: local('Gilroy UltraLight Italic'), local('Gilroy-UltraLightItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-UltraLightItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-UltraLightItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-UltraLightItalic.ttf') format('truetype');
			font-weight: 200;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-SemiBoldItalic.eot');
			src: local('Gilroy SemiBold Italic'), local('Gilroy-SemiBoldItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-SemiBoldItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-SemiBoldItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-SemiBoldItalic.ttf') format('truetype');
			font-weight: 600;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Light.eot');
			src: local('Gilroy Light'), local('Gilroy-Light'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Light.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Light.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Light.ttf') format('truetype');
			font-weight: 300;
			font-style: normal;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-MediumItalic.eot');
			src: local('Gilroy Medium Italic'), local('Gilroy-MediumItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-MediumItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-MediumItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-MediumItalic.ttf') format('truetype');
			font-weight: 500;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ExtraBoldItalic.eot');
			src: local('Gilroy ExtraBold Italic'), local('Gilroy-ExtraBoldItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ExtraBoldItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ExtraBoldItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-ExtraBoldItalic.ttf') format('truetype');
			font-weight: 800;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Regular.eot');
			src: local('Gilroy Regular'), local('Gilroy-Regular'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Regular.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Regular.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Regular.ttf') format('truetype');
			font-weight: normal;
			font-style: normal;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-HeavyItalic.eot');
			src: local('Gilroy Heavy Italic'), local('Gilroy-HeavyItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-HeavyItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-HeavyItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-HeavyItalic.ttf') format('truetype');
			font-weight: 900;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Medium.eot');
			src: local('Gilroy Medium'), local('Gilroy-Medium'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Medium.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Medium.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Medium.ttf') format('truetype');
			font-weight: 500;
			font-style: normal;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-RegularItalic.eot');
			src: local('Gilroy Regular Italic'), local('Gilroy-RegularItalic'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-RegularItalic.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-RegularItalic.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-RegularItalic.ttf') format('truetype');
			font-weight: normal;
			font-style: italic;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-UltraLight.eot');
			src: local('Gilroy UltraLight'), local('Gilroy-UltraLight'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-UltraLight.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-UltraLight.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-UltraLight.ttf') format('truetype');
			font-weight: 200;
			font-style: normal;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Bold.eot');
			src: local('Gilroy Bold'), local('Gilroy-Bold'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Bold.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Bold.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Bold.ttf') format('truetype');
			font-weight: bold;
			font-style: normal;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Thin.eot');
			src: local('Gilroy Thin'), local('Gilroy-Thin'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Thin.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Thin.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Thin.ttf') format('truetype');
			font-weight: 100;
			font-style: normal;
		}

		@font-face {
			font-family: 'Gilroy';
			src: url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Black.eot');
			src: local('Gilroy Black'), local('Gilroy-Black'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Black.eot?#iefix') format('embedded-opentype'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Black.woff') format('woff'),
				url('/wp-content/themes/pizzaro/assets/fonts/gilroy/Gilroy-Black.ttf') format('truetype');
			font-weight: 900;
			font-style: normal;
		}

</style>
<style>

	* {
		font-family: 'Gilroy'!important;
	}
	.bonus-history-list{
		display: none;
	}
	.bonus-history-list .item-wrapper:nth-child(2n+1){
		transform: translateX(150%);
		animation: ani 0.6s forwards;
	}
	.bonus-history-list .item-wrapper:nth-child(2n){
		transform: translateX(-150%);
		animation: ani2 1.2s forwards;
	}	
	.history_open .bonus-history-list{
		display: flex;
		align-items: flex-start;
		flex-direction: column;
		flex-wrap: nowrap;
		align-content: center;
		padding: 0 20px;
	}
	@keyframes ani {
		0% {transform: translateX(150%);}
		100% {transform: translateX(0);}
	}
	@keyframes ani2 {
		0% {transform: translateX(-150%);}
		100% {transform: translateX(0);}
	}
	.bonus-history{
		display: flex;
		flex-direction: column;
		flex-wrap: nowrap;
		margin-top: 30px;
		overflow-y: hidden;
	}
	.bonus-history-list-header{
		display: flex;
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
		padding: 17px 20px 18px 20px;
		cursor: pointer;
		border-top: 1px solid #EAEAEA;
		overflow-y: hidden;
	}
	.bonus-history-list-header div{
		font-family: 'Gilroy'!important;
		font-style: normal;
		font-weight: 700;
		font-size: 16px;
		line-height: 130%;
		color: #262626;
	}
	.history_close .list-opened-icon{
		background: url(/wp-content/themes/pizzaro/assets/images/icons/strelka.svg) no-repeat center;
		/* background-size: cover; */
		height: 10px;
		width: 10px;
		transform: rotate(180deg);
    	transform-origin: 50% 50%;
	}
	.history_open .list-opened-icon{
		background: url(/wp-content/themes/pizzaro/assets/images/icons/strelka.svg) no-repeat center;
		/* background-size: cover; */
		height: 10px;
		width: 10px;
	}
	
	.bonus-history-item{
		display: flex;
		align-items: center;
		flex-direction: row;
		gap: 10px;
		padding: 16px 0;
		width: 100%;
		justify-content: flex-start;
		border-bottom: 1px solid #EAEAEA;
	}
	.bonus-history-item:last-child{
		border-bottom: none;
	}
	.bonus-history-item .bonus-count{
		font-family: 'Gilroy'!important;
		font-style: normal;
		font-weight: 700;
		font-size: 16px;
		line-height: 130%;
		color: #8650CA;
	}
	.bonus-history-item .bonus-info{
		font-family: 'Gilroy'!important;
		font-style: normal;
		font-weight: 400;
		font-size: 14px;
		line-height: 150%;
		color: #262626;
	}
	.bonus-history-item-date-wrapper{
		display: flex;
		flex-direction: row;
		justify-content: center;
		align-items: center;
		padding: 10px;
		gap: 10px;

		width: 100%;
		height: 34px;


		position: relative;
	}
	.bonus-history-item-date{
		position: absolute;
		background: #F7F7F7;
		border-width: 1px 0px;
		border-style: solid;
		border-color: #EEEEEE;
		width: 115%;
		text-align: center;
		
	}
	.item-wrapper{
		width: 100%;
	}
	#bon-info-modal{
		z-index: 2;
		left: 0;
	}
</style>
<?php

//баланс бонусов пользователя  mages/icons/strelka.svgi
function get_user_bonus_page($user_id){
	global $wpdb;
	$table = $wpdb->prefix . "rspointexpiry" ;
	$result = $wpdb->get_results( "SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid = $user_id" , ARRAY_A ) ;
    $points_balance = round_off_type( number_format( ( int ) $result[0]["availablepoints"] , 2 , '.' , '' ) );
	
	return $points_balance;
}

//получение по каждому уровню пороговой суммы
function get_bonus_level_page(){

	$bon_lev_ser = get_option('rewards_dynamic_rule_purchase_history'); //debug_to_file($bon_lev_ser);
	$bon_lev_arr = ['values'=>[], 'types'=>[]];
	$i = 1;
	foreach($bon_lev_ser as $level){
		$bon_lev_arr['values'][$i] = $level['value'];
		$bon_lev_arr['types'][$i] = $level['type'];
		$i++;
	}
	//debug_to_file($bon_lev_arr);
	
	return $bon_lev_arr;
}

//add_action( 'wp_footer', 'get_levels_info_page' );
//получение информации настроек по каждому уровню бонусов
/*function get_levels_info_page(){
	$levels_info = get_field('level_up_group', 'option'); debug_to_file($levels_info);
	
	return $levels_info;
}*/


get_footer( $footer_style );