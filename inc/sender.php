<?php

/** Main logic
 * @author SMS CLUB
 * @url https://github.com/smsclub/php/blob/master/json_sms/send_single.php
 * @copyright 2021
 */

defined( 'ABSPATH' ) || exit;

if( 'yes' === get_option('sb_sms_enable_plugin')){

	// Option 1 - after create new order
	if( 'yes' === get_option('sb_sms_new_order')){
		add_action( 'woocommerce_thankyou', 'sb_send_sms_new_order' );
	}
	// Option 2 - while change order status to complete
	if( 'yes' === get_option('sb_sms_status_completed')){
		add_action( 'woocommerce_order_status_changed', 'sb_send_sms_order_completed', 99, 4 );
	}
}

function sb_send_sms_new_order() {

	$args     = [
		'numberposts' => 1,
		'post_type'   => 'shop_order',
		'post_status' => 'wc-pending, wc-on-hold, wc-processing',
	];
	$post    = wp_get_recent_posts( $args );
	$order_id = $post[0]['ID'];

	$order = wc_get_order( $order_id );

	// Building message string
	$str = apply_filters( 'sb_sms_message_new_order', sprintf(__("Order №%s created successfully. We call you asap.", 'sbsmssender'), $order_id), $order);
	sb_sms_send($order->get_billing_phone(), $str);
}

function sb_send_sms_order_completed($id, $status_transition_from, $status_transition_to, $order){
	if('completed' === $status_transition_to){
		// Building message string
		$str = apply_filters( 'sb_sms_message_order_completed', sprintf(__("Order №%s completed.", 'sbsmssender'), $id), $order);
		sb_sms_send($order->get_billing_phone(), $str);
	}

}

function sb_sms_send($phone, $message){
	$token     = get_option( 'sb_sms_admin_tab_token' );
	$alfa_name = get_option( 'sb_sms_admin_tab_alfa_name' );
	$url       = 'https://im.smsclub.mobi/sms/send';

	$data = json_encode( [
		'phone'    => [ $phone ],
		'message'  => $message,
		'src_addr' => $alfa_name
	] );

	$headers = [
		'Authorization' => 'Bearer ' . $token,
		'Content-type'  => 'application/json'
	];

	$args = [
		'headers' => $headers,
		'body' => $data,
	];
	$response = wp_remote_post($url, $args);
}