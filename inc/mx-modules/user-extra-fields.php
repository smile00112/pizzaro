<?php




//вывод дополнительных полей в админке Профиль пользователя
add_action( 'show_user_profile', 'extra_profile_fields', 10 );
add_action( 'edit_user_profile', 'extra_profile_fields', 10 );
function extra_profile_fields( $user ) { 
	$db_user_sex = get_user_meta( $user->ID, 'user_sex', true ); //пол пользователя
	$db_user_promo = get_user_meta( $user->ID, 'user_on_promo', true ); //участие в акциях
	$list = get_field('preferences_list', 'option') ;
	$user_preferences = get_user_meta( $user->ID, 'preferences', true ); //предпочтения
	$user_preferences = json_decode($user_preferences, true);
	$user_rate_order = round(get_user_meta( $user->ID, 'user_rate_order', true ), 1);
?>
   
    <h3><?php _e('Дополнительная информация'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="user-extra-sex">Пол</label></th>
            <td>
            <!--<input type="text" name="user-extra-sex" id="user-extra-sex" value="" class="regular-text" /><br />
            <span class="description">М/Ж</span>-->
			<select name="user-extra-sex" id="user-extra-sex">
				<option <?php if($db_user_sex == '' || empty($db_user_sex) || $db_user_sex == '0') echo 'selected="selected"'; ?> value="0">Выбор</option>
				<option <?php if($db_user_sex == 'male') echo 'selected="selected"'; ?> value="male">М</option>
				<option <?php if($db_user_sex == 'female') echo 'selected="selected"'; ?> value="female">Ж</option>
			</select>
            </td>
			
        </tr>
		<tr>
			<th><label for="user-extra-birth">Дата рождения</label></th>
			<td><input type="text" name="user-extra-birth" id="user-extra-birth" value="<?php echo esc_attr( get_user_meta( $user->ID, 'user_birth', true ) ); ?>" class="regular-text" /></td><br />
		</tr>
		<tr>
			<th><label for="user-on-promo">Учасите в акциях</label></th>
			<td><input type="checkbox" <?php if($db_user_promo=='true') echo 'checked'; ?> name="user-on-promo" id="user-on-promo" value="<?php if($db_user_promo=='true') echo 'true'; else echo 'false'; ?>" class="regular-text" /></td><br />
		</tr>
		<tr>
			<th><label for="user-rate-order">Средняя оценка заказов</label></th>
			<td><input type="text" name="user-rate-order" id="user-rate-order" value="<?php echo $user_rate_order; ?>" class="regular-text" /></td><br />
		</tr>
		<tr>
			<th><label for="user-rate-order">Предпочтения</label></th>
			<td>
<?
		echo '<ul class="preferences-list">';
		foreach( $list as $l ){
			$checked = in_array($l['code'], $user_preferences) ? 'checked="true"' : '';
			echo '<li><input type="checkbox" name="user-extra-preference[]" id="pref'.$l['code'].'" '.$checked.' value="'.$l['code'].'"/><label for="pref'.$l['code'].'">'.$l['text'].'</label></li>';

		}
		echo '</ul>';
?>

			</td><br />
		</tr>	

    </table>
<?php

}

//сохранение информации
function save_extra_profile_fields( $user_id ) {

    if ( !current_user_can( 'edit_user', $user_id ) )
        return false;

    update_usermeta( $user_id, 'user_sex', $_POST['user-extra-sex'] );
	update_usermeta( $user_id, 'user_birth', $_POST['user-extra-birth'] );
	update_usermeta( $user_id, 'preferences', json_encode($_POST['user-extra-preference'], JSON_UNESCAPED_UNICODE));

	if (isset($_POST['user-on-promo']) && $_POST['user-on-promo'] == 'true'){
		update_usermeta( $user_id, 'user_on_promo', 'true' );
		//debug_to_file('user in sale: '.$_POST['user-on-promo']);
	}
	else { //debug_to_file('user in sale: '.$_POST['user-on-promo']);
		update_usermeta( $user_id, 'user_on_promo', 'false' );
	}


}

add_action( 'personal_options_update', 'save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'save_extra_profile_fields' );


/////добавление колонки в таблицу списка пользователей

add_action('manage_users_columns', 'register_custom_user_column');
add_action('manage_users_custom_column', 'register_custom_user_column_view', 10, 3);
function register_custom_user_column($columns) {
    $columns['user_rate_order'] = 'Ср. оценка заказов';
    return $columns;
}

function register_custom_user_column_view($value, $column_name, $user_id) {
    $user_info = get_userdata( $user_id );
	$user_rate_order = round(get_user_meta( $user_id, 'user_rate_order', true ), 1);
	$user_rate_order_int = round(get_user_meta( $user_id, 'user_rate_order', true ));
    if($column_name == 'user_rate_order'){
		if($user_rate_order_int == 5) return '<span class="rate-green">' . $user_rate_order . '</span>';
		if($user_rate_order_int == 4) return '<span class="rate-blue">' . $user_rate_order . '</span>';
		if($user_rate_order_int == 3) return '<span class="rate-orange">' . $user_rate_order . '</span>';
		if($user_rate_order_int == 2) return '<span class="rate-red">' . $user_rate_order . '</span>';
		if($user_rate_order_int == 1) return '<span class="rate-red">' . $user_rate_order . '</span>';
		else return '<small>(<em>нет оценки</em>)</small>';
	}
    //return '<small>(<em>no value</em>)</small>';

}

?>
