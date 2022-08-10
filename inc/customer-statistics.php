<?php

$start = microtime( true );

$report = array();
$report[ 'category_ids' ] = array();
$report[ 'order' ] = array();
$report[ 'order_completed' ] = 0;

$args = array(
	'numberposts' => -1,
	'customer_id' => $customer_id
);

global $wpdb;

$Customer = $wpdb->get_row( "SELECT * FROM `wp_wc_customer_lookup` WHERE `user_id` = $customer_id", ARRAY_A ); // профиль покупателя в woo 
$last_active = ( $Customer ) ? $Customer[ 'date_last_active' ] : null;
$last_active_text = ( $last_active ) ? wp_date( 'j M Y H:i:s', strtotime( $last_active ) ) : 'Активность отсутствует';

$orders = wc_get_orders( $args ); // все заказы пользователя
if ( !$orders ) {
	echo '<p class="notice notice-warning notice-large">Пользователь ничего не заказывал. <br> Последняя активность: ' . $last_active_text . ' </p>';
	die();
}

$order_statuses = wc_get_order_statuses();

foreach ( $orders as $order ) { // перебор заказов
	$order_status = $order->get_status(); // статус заказа
	$order_source = $order->get_meta( 'order_meta_source' ); // источник заказа
	$order_total = $order->get_total(); // сумма заказа
	$items = $order->get_items(); // позиции в заказе (товары)


	$report[ 'order' ][ 'status' ][ $order_status ][ 'total' ][] = $order_total;
	
	$report[ 'source' ][] = $order_source;

	if ( $order_status == 'completed' ) { // если статус заказа completed
		foreach ( $items as $item_id => $item ) {
			$product_id = $item->get_product_id(); // id товара
			if ( $product_id ) { // если товар существует
				$product_quantity = $item->get_quantity(); // получаем количество товара в заказе
				for ( $i = 1; $i <= $product_quantity; $i++ ) {
					$report[ 'product_ids' ][] = $product_id; // добавляем в массив с заказанными товарами столько раз сколько товара в заказе
				}
			}
		}
		$report[ 'order' ][ 'totals' ][] = $order_total; //  сумма заказов
		$report[ 'order_completed' ]++;

	} // END if(order_status
}

$totals = (int) array_sum( $report[ 'order' ][ 'totals' ] ) ;

$prods = array_count_values( $report[ 'product_ids' ] ); // считаем сколько каких id товаров в заказах 
arsort( $prods ); // сортируем по убыванию заказанных товаров


$prods_html = '';
foreach ( $prods as $product_id => $product_count ) { // перебираем заказанные товары
	$product = wc_get_product( $product_id ); // получаем товар по id
	$product_name = $product->get_title(); // название товара
	//	$product_permalink = $product->get_permalink(); // ссылка на товар
	$category_ids = $product->get_category_ids(); // id категорий товара
	for ( $i = 1; $i <= $product_count; $i++ ) {
		$report[ 'category_ids' ] = array_merge( $report[ 'category_ids' ], $category_ids ); // добавляем категории товара в массив
	}
	$prods_html .= "<tr>
      <th scope=\"row\" class=\"woocommerce-table__item is-left-aligned\">$product_name</th>
      <td class=\"woocommerce-table__item\">$product_count</td>
    </tr>";
}

$cats = array_count_values( $report[ 'category_ids' ] ); // считаем сколько каких id категорий в заказах 
arsort( $cats ); // сортируем по убыванию

$cats_html = '';
foreach ( $cats as $category_id => $category_count ) {
	$category_name = get_the_category_by_ID( $category_id );
	$cats_html .= "<tr>
      <th scope=\"row\" class=\"woocommerce-table__item is-left-aligned\">$category_name</th>
      <td class=\"woocommerce-table__item\">$category_count</td>
    </tr>";
}

$status_li ='';
foreach ( $report[ 'order' ][ 'status' ] as $status => $status_arr ) {
$status_li .= "<li><strong>".$order_statuses['wc-'.$status ]."</strong> - ".count($status_arr[ 'total' ])."</li>";
}

$source_li ='';
$source = array_count_values($report[ 'source' ]);
foreach($source as $sk=>$sv){
$ist = (trim($sk)) ? $sk : 'Не указан';
$source_li	.= "<li><strong>$ist</strong> - $sv</li>";
}

?>

<div class="woocommerce-dashboard__columns" style="display: flex">
	<div class="card card33">
<h2>Оформлено заказов</h2>
<p class="css-1ahfdc3-Text e15wbhsk0"><strong>Всего</strong> - <?php echo count($orders) ; ?></p>
<ul><?php echo $status_li; ?></ul>
	<hr>
<strong>Источники</strong>
<ul><?php echo $source_li; ?></ul>
</div>
	
<div class="card card33">
<h2>Сумма покупок</h2>
<p class="css-1ahfdc3-Text e15wbhsk0"><?php echo $totals; ?>&nbsp;₽</p>
</div>
<div class="card card33">
<h2>Куплено товаров</h2>
<p class="css-1ahfdc3-Text e15wbhsk0"><?php echo count($report[ 'product_ids' ]); ?>&nbsp;шт.</p>
</div>

</div>
<p class="notice notice-info notice-title notice-large">Последняя активность пользователя: <?php echo $last_active_text; ?></p>

<?php if($prods_html): ?>
<div class="woocommerce-dashboard__columns" style="display: flex">
	<div class="woocommerce-card woocommerce-table woocommerce-analytics__card woocommerce-leaderboard">
		<div class="woocommerce-card__header">
			<div class="woocommerce-card__title-wrapper">
				<h2 class="woocommerce-card__title woocommerce-card__header-item">Купленные товары</h2>
			</div>
		</div>
		<div class="woocommerce-card__body">
			<div class="woocommerce-table__table" aria-hidden="false" aria-labelledby="caption-2" role="group">
				<table>
					<caption id="caption-2" class="woocommerce-table__caption screen-reader-text">Товары</caption>
					<tbody>
						<tr>
							<th role="columnheader" scope="col" class="woocommerce-table__header is-left-aligned"><span aria-hidden="false">Товар</span>
							</th>
							<th role="columnheader" scope="col" class="woocommerce-table__header"><span aria-hidden="false">Кол-во</span>
							</th>
							</th>
						</tr>
						<?php echo $prods_html;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="woocommerce-card woocommerce-table woocommerce-analytics__card woocommerce-leaderboard">
		<div class="woocommerce-card__header">
			<div class="woocommerce-card__title-wrapper">
				<h2 class="woocommerce-card__title woocommerce-card__header-item">Категории товаров</h2>
			</div>
		</div>
		<div class="woocommerce-card__body">
			<div class="woocommerce-table__table" aria-hidden="false" aria-labelledby="caption-2" role="group">
				<table>
					<caption id="caption-2" class="woocommerce-table__caption screen-reader-text">Категории</caption>
					<tbody>
						<tr>
							<th role="columnheader" scope="col" class="woocommerce-table__header is-left-aligned"><span aria-hidden="false">Категория</span>
							</th>
							<th role="columnheader" scope="col" class="woocommerce-table__header"><span aria-hidden="false">Кол-во</span>
							</th>
							</th>
						</tr>
						<?php echo $cats_html;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
<hr>
<style>
.woocommerce-card.woocommerce-table.woocommerce-analytics__card.woocommerce-leaderboard {
		min-width: calc(49% - 15px);
		display: inline-block;
		margin-right: 10px;
	}
.card.card33 {
    min-width: calc(33% - 10px);
    margin-right: 5px;
}
</style>