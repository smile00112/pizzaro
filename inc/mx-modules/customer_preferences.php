<?php
$preference_status = get_field('preferences_status', 'option');
if($preference_status == 1){

	add_filter( 'woocommerce_account_menu_items', 'truemisha_log_preferences_link', 25 );
	function truemisha_log_preferences_link( $menu_links ){
		$new_list = [];
		foreach($menu_links as $code=>&$l){
			if($code =='bookings'){
				$new_list[ 'log-preferences' ] = 'Предпочтения';
			}else{
				$new_list[$code]=$l;
			}
		}
		return $new_list;
	
	}

	add_action( 'init', 'truemisha_add_endpoint', 25 );
	function truemisha_add_endpoint() {
	
		add_rewrite_endpoint( 'log-preferences', EP_PAGES );
	
	}
	
	add_action( 'woocommerce_account_log-preferences_endpoint', 'truemisha_content', 25 );
	function truemisha_content() {

		$user_preferences = get_user_preferences();
		$list = get_field('preferences_list', 'option') ;

		echo '<h3>Выберите Ваши предпочтения в продуктах</h3>';
		echo '<form method="POST" id="preferences-form" action="" onsubmit="save_preferences(this);return false;">
		<input type="hidden" name="action" value="save_preferences">
		';
		echo '<ul class="preferences-list">';
		foreach( $list as $l ){
			$checked = in_array($l['code'], $user_preferences['preferences']) ? 'checked' : '';
			echo '<li><input type="checkbox" name="preference[]" id="pref'.$l['code'].'" '.$checked.' value="'.$l['code'].'"/><label for="pref'.$l['code'].'">'.$l['text'].'</label></li>';

		}
		echo '</ul>
		<h4>Комментарий</h4>
		<textarea name="preferences_comment" placeholder="Укажите то, что не попало в наш список">'.$user_preferences['comment'].'</textarea>
	    <button type="submit" class="add-address-btn">Сохранить</button>
		</form>';

	}

	add_action( 'wp_footer', 'customer_preferences_script_js' );
	function customer_preferences_script_js() {
	
			?>
			<script type="text/javascript">
				function save_preferences(form){
					var f = $('#preferences-form').serialize();
					var submit_btn = $('#preferences-form button');
					var $data = {
						action : 'save_preferences',
						form: f
					};
					
					//save_preferences
					$.ajax({
						type : 'POST',
						url : '/wp-admin/admin-ajax.php',
						async: true,
						data : f,
						dataType: 'html',
						beforeSend: function (xhr) {
							submit_btn.text('Сохраняю...')
							//preloader.style = 'display:block';
						},
						complete: function() {
							submit_btn.text('Сохранено')
							//preloader.style = 'display:none';
						},
						success: function (data) {
							//console.log(data);
							
							//  if(confirm('обновить?'))
							//	location.reload();
							//localStorage.removeItem('address_mode');
						},
					});
				}
			</script>

			<style>
			body.woocommerce-account ul li.woocommerce-MyAccount-navigation-link--log-preferences a:before{
				content: "\f0f6";
			}
			.preferences-list{
				width: 200px;
				margin: 0;
				margin-top: 30px;
			}
			.preferences-list li{
				list-style: none;
				border-bottom: 1px solid #0000001c;
				line-height: 20px;
				cursor: pointer;
			}
			.preferences-list li label{
				margin-left: 15px;
				cursor: pointer;
			}
			</style>
			
			<?
	
	}

	add_action( 'wp_ajax_nopriv_save_preferences', 'save_preferences_data' );
	add_action( 'wp_ajax_save_preferences', 'save_preferences_data' );
	function save_preferences_data(){
		$user_id = get_current_user_id();
		if(!$user_id) return false;

		//print_r($_POST['preference']);
		if(!empty($_POST['preference'])){
		   // update_user_meta($user_id, 'preferences', json_encode($_POST['preference'], JSON_UNESCAPED_UNICODE));
		   // update_user_meta($user_id, 'preferences_comment', $_POST['preferences_comment']);
		}

		return false;
	}

	function get_user_preferences($user_id = 0){
		if(empty($user_id))
		    $user_id = get_current_user_id();
		if(!$user_id) return false;
	    $preferences = get_user_meta($user_id, 'preferences', true);
	    $preferences_comment = get_user_meta($user_id, 'preferences_comment', true);
        $data['preferences']= json_decode($preferences, true);
		$data['comment'] = $preferences_comment;

		return $data;
	}

	function get_user_preferences_text($user_id = 0){
		if(empty($user_id))
		    $user_id = get_current_user_id();
		if(!$user_id) return false;
		$list = get_field('preferences_list', 'option');
		$list_new = [];
		$user_preferences = get_user_preferences($user_id);
		$comment = $user_preferences['comment'];
		foreach( $list as $l ){
			$checked = in_array($l['code'], $user_preferences['preferences']) ? true : false;	
			if($checked)
			$list_new[]=$l['text'];
		}
		$user_preferences = implode(', ', $list_new);
		if(!empty($comment))
		    $user_preferences.=' - '.$comment;

		return $user_preferences;
	}	

		
	// В заказе выводим область с предпочтениями клиента
	add_action( 'add_meta_boxes', 'add_meta_box_user_preferences' );
	function add_meta_box_user_preferences(){
		add_meta_box( 'preferences_box', __('Предпочтения клиента','woocommerce'), 'mv_add_other_user_preferences', 'shop_order', 'side', 'core' );
	}
	function mv_add_other_user_preferences()
	{
		global $post;
			$user_id = get_post_meta( $post->ID, '_customer_user', true );
			if(!$user_id) return false;

			$list = get_field('preferences_list', 'option') ;
			$user_preferences = get_user_preferences( $user_id ); //предпочтения

			echo '<ul class="preferences-list">';
			foreach( $list as $l ){
				if( in_array($l['code'], $user_preferences['preferences']) ){
					echo '<li>-<label for="pref'.$l['code'].'">'.$l['text'].'</label></li>';
				}
			}
			echo '</ul>';
			echo 'Комментарий:';
			echo '<textarea name="preferences_comment">'.$user_preferences['comment'].'</textarea>';
	}

	/* API */
	add_action( 'rest_api_init', function () {
		register_rest_route('systeminfo/v1', '/user_preferences/(?P<user_id>\d+)', array(
			'methods' => 'GET',
			'callback' => 'api_user_preferences',
		));
	});
	function api_user_preferences(WP_REST_Request $request) {
		$params = $request->get_params();
		
		if(!empty(intval($params['user_id']))){
			$user_id = intval($params['user_id']);
			$list = get_field('preferences_list', 'option');
			$list_new = [];
			$user_preferences = get_user_preferences($user_id);
			foreach( $list as $l ){
				$checked = in_array($l['code'], $user_preferences['preferences']) ? true : false;	
				$list_new[]=[
					'code' => $l['code'],
					'text' => $l['text'],
					'checked' => $checked,
				];
			}
			$user_preferences['preferences'] = $list_new;
			return $user_preferences;
		}
		return false;
	}
	add_action( 'rest_api_init', function () {
		register_rest_route('systeminfo/v1', '/user_preferences/(?P<user_id>\d+)', array(
			'methods' => 'POST',
			'callback' => 'api_save_user_preferences',
		));
	});
	function api_save_user_preferences(WP_REST_Request $request) {
		$params = $request->get_params();


		
		$user_id = $params['user_id'];
		if(!$user_id) return false;

		$preferences = [];
		if( count($params['preferences'] ) )
			foreach( $params['preferences'] as $pref){
				if($pref['checked'] == 1){
					$preferences[]=$pref['code'];
				}
			}

		if(!empty($preferences)){
		    update_user_meta($user_id, 'preferences', json_encode($preferences, JSON_UNESCAPED_UNICODE));
		    update_user_meta($user_id, 'preferences_comment', $params['comment']);

			return true;
		}

		return false;
	}		


}

?>
