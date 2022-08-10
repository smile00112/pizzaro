<?php

//Регестрируем точки выдачи
function wps_store_post_type() {
    $labels = array(
        'name'                  => _x( 'Точки выдачи', 'Post Type General Name', 'wc-pickup-store' ),
        'singular_name'         => _x( 'Точка', 'Post Type Singular Name', 'wc-pickup-store' ),
        'menu_name'             => __( 'Точки', 'wc-pickup-store' ),
        'name_admin_bar'        => __( 'Точка', 'wc-pickup-store' ),
        'archives'              => __( 'Архив', 'wc-pickup-store' ),
        'attributes'            => __( 'Атрибуты', 'wc-pickup-store' ),

        'all_items'             => __( 'Все точки', 'wc-pickup-store' ),
        'add_new_item'          => __( 'Добавить новую', 'wc-pickup-store' ),
        'add_new'               => __( 'Добавить новую', 'wc-pickup-store' ),
        'new_item'              => __( 'Новая точка', 'wc-pickup-store' ),
        'edit_item'             => __( 'Редактировать', 'wc-pickup-store' ),
        'update_item'           => __( 'Обновить', 'wc-pickup-store' ),

    );
    $args = array(
        'label'                 => __( 'Точка', 'wc-pickup-store' ),
        'description'           => __( 'Точки', 'wc-pickup-store' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields', ),
        'taxonomies'            => array(),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-store',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
        'rewrite' => array(
            'slug' => 'pickup',
        )
    );
    register_post_type( 'pickuppoint', $args );
}
add_action('init', 'wps_store_post_type');

// Custom function that handle your settings
// Функция принимающая параметры точек выдачи
function carrier_settings(){
    // параметры по умолчанию
    $pick_locationsposts = get_posts( array(
        'numberposts' => 10,
        'fields' => 'ids',
        'post_type'   => 'pickuppoint'
    ) );

    /* если одна точка, пункт по умолчанию не добавляем */
    // if(count($pick_locationsposts) > 1)
    //     $pick_locations = array( "Выберите точку самовывоза");
    $pick_locations = [];
    foreach( $pick_locationsposts as $index=>$post ){ //echo get_post_field( 'post_title', $post );
        setup_postdata($post);
//$slug = get_post_field( 'post_name', $post );
        $slug = get_post_field( 'post_title', $post );
        $pick_locations[$index+1] = $slug;
    }

    wp_reset_postdata(); // сброс
    return array(
        'targeted_methods' => array('local_pickup:4'), // Your targeted shipping method(s) in this array
        'field_id'         => 'carrier_name', // Field Id
        'field_type'       => 'select', // Field type
        'field_label'      => '', // Leave empty value if the first option has a text (see below).
        'label_name'       => __("Точка самовывоза","woocommerce"), // for validation and as meta key for orders
        'field_options'    => $pick_locations
    );
}


// Display the custom checkout field
// Отображение своего checkout поля
add_action( 'woocommerce_after_shipping_rate', 'carrier_company_custom_select_field', 20, 2 );
function carrier_company_custom_select_field( $method, $index ) {
    extract( carrier_settings() ); // Load settings and convert them in variables

    $chosen  = WC()->session->get('chosen_shipping_methods'); // The chosen methods
    $value   = WC()->session->get($field_id);
    $value   = WC()->session->__isset($field_id) ? $value : WC()->checkout->get_value('_'.$field_id);
    $options = array(); // Initializing

	
	//if(in_array($method->id, $targeted_methods)) echo $chosen ;
	//var_dump($targeted_methods);
	//echo '<br>|'. $method->id;

    if( ! empty($chosen) && $method->id === $chosen[$index] && in_array($method->id, $targeted_methods)  ) { //echo 'constr select';
        //( count($field_options) <=2 ) ? $style = 'style="display:none"' : $style = '';
        echo '<div class="custom-carrier" '.$style.'>';

        // Loop through field otions to add the correct keys
        foreach( $field_options as $key => $option_value ) {
            $option_key = $key == 0 ? '' : $key;
            $options[$option_key] = $option_value;
        }

        woocommerce_form_field( $field_id, array(
            'type'     => $field_type,
            'label'    => 'Точка самовывоза', // Not required if the first option has a text.
            'class'    => array('form-row-wide ' . $field_id . '-' . $field_type ),
            'required' => true,
            'options'  => $options,
        ), $value );

        echo '</div>';
    }
}





//Check the need for the code below

// jQuery code (client side) - Ajax sender
add_action( 'wp_footer', 'carrier_company_script_js' );
function carrier_company_script_js() { 
    // Only cart & checkout pages
    if( is_cart() || ( is_checkout() && ! is_wc_endpoint_url() ) ):

        // Load settings and convert them in variables
        extract( carrier_settings() );

        $js_variable = is_cart() ? 'wc_cart_params' : 'wc_checkout_params';

        // jQuery Ajax code
        ?>
        <script type="text/javascript">
            jQuery( function($){ console.log('js tochka');
                if (typeof <?php echo $js_variable; ?> === 'undefined')
                    return false;

                $(document.body).on( 'change', 'select#<?php echo $field_id; ?>', function(){ 
                    console.log('on change');
                    var value = $(this).val();
                    $.ajax({
                        type: 'POST',
                        url: <?php echo $js_variable; ?>.ajax_url,
                        data: {
                            'action': 'carrier_name',
                            'value': value
                        },
                        success: function (result) {
                            console.log(result); // Only for testing (to be removed)
                        }
                    });
                });


                /* если элемент 1, активируем его */
                // var select_selector = '#carrier_name_field select[name="carrier_name"]';
                // var options = $(select_selector+' option');
                // var value = $(select_selector+' option:first').val();
                // if( options.size() == 1){
                //     $.ajax({
                //         type: 'POST',
                //         url: <?php echo $js_variable; ?>.ajax_url,
                //         data: {
                //             'action': 'carrier_name',
                //             'value': value
                //         },
                //         success: function (result) {
                //             console.log('!!!!activate!!!!',value); // Only for testing (to be removed)
                //         }
                //     });
                // }



            });
        </script>
    <?php
    endif;
}

// The Wordpress Ajax PHP receiver
add_action( 'wp_ajax_carrier_name', 'set_carrier_company_name' );
add_action( 'wp_ajax_nopriv_carrier_name', 'set_carrier_company_name' );
function set_carrier_company_name() {
    if ( isset($_POST['value']) ){
        // Load settings and convert them in variables
        extract( carrier_settings() );

        if( empty($_POST['value']) ) {
            $value = 0;
            $label = 'Empty';
        } else {
            $value = $label = esc_attr( $_POST['value'] );
        }

        // Update session variable
        WC()->session->set( $field_id, $value );

        // Send back the data to javascript (json encoded)
        echo $label . ' | ' . $field_options[$value];
        die();
    }
}


// Conditional function for validation
function has_carrier_field(){
    $settings = carrier_settings();
    return array_intersect(WC()->session->get( 'chosen_shipping_methods' ), $settings['targeted_methods']);
}

// Validate the custom selection field
add_action('woocommerce_checkout_process', 'carrier_company_checkout_validation');
function carrier_company_checkout_validation() {
    // Load settings and convert them in variables
    extract( carrier_settings() );

    if( has_carrier_field() && isset( $_POST[$field_id] ) && empty( $_POST[$field_id] ) )
        wc_add_notice(
            sprintf( __("Выберите %s это обязательно для самовывоза.","woocommerce"),
                '<strong>' . $label_name . '</strong>'
            ), "error" );
}


// Save custom field as order meta data
add_action( 'woocommerce_checkout_create_order', 'save_carrier_company_as_order_meta', 30, 1 );
function save_carrier_company_as_order_meta( $order ) {
    // Load settings and convert them in variables
    extract( carrier_settings() );

    if( has_carrier_field() && isset( $_POST[$field_id] ) && ! empty( $_POST[$field_id] ) ) {
        $order->update_meta_data( '_'.$field_id, $field_options[esc_attr($_POST[$field_id])] );
		//$order->add_meta_data( $key, $value, true );
        WC()->session->__unset( $field_id ); // remove session variable
    }
}



// Display custom field in admin order pages
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'admin_order_display_carrier_company', 30, 1 );
function admin_order_display_carrier_company( $order ) {
    // Load settings and convert them in variables
    extract( carrier_settings() );

    $carrier = $order->get_meta( '_'.$field_id ); // Get carrier company

    if( ! empty($carrier) ) {
        // Display
        echo '<p><strong>' . $label_name . '</strong>: ' . $carrier . '</p>';
    }
}

// Display carrier company after shipping line everywhere (orders and emails)
add_filter( 'woocommerce_get_order_item_totals', 'display_carrier_company_on_order_item_totals', 1000, 3 );
function display_carrier_company_on_order_item_totals( $total_rows, $order, $tax_display ){
    // Load settings and convert them in variables
    extract( carrier_settings() );

    $carrier = $order->get_meta( '_'.$field_id ); // Get carrier company

    if( ! empty($carrier) ) {
        $new_total_rows = [];

        // Loop through order total rows
        foreach( $total_rows as $key => $values ) {
            $new_total_rows[$key] = $values;

            // Inserting the carrier company under shipping method
            if( $key === 'shipping' ) {
                $new_total_rows[$field_id] = array(
                    'label' => $label_name,
                    'value' => $carrier,
                );
            }
        }
        return $new_total_rows;
    }
    return $total_rows;
}
