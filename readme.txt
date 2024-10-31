=== SB SMS Sender ===
Contributors: Max Shostak
Tags: sms, sender, send sms, sms notifications, woocommerce, club
Requires at least: 5.0
Tested up to: 5.7
Stable tag: 0.0.2
Requires PHP: 7.2
WC requires at least: 4.0
WC tested up to: 5.1.0
License: GPLv3

Send SMS to client using SMS club.

== Description ==

Send SMS to client using SMS club.

= Features =
* Simple to use
* Send SMS after creating new order
* Send SMS after when order status changed to completed
* Required using of 3d party service [SMS club](https://smsclub.mobi/)
* Support Ukrainian and russian languages
* Required Woocommerce

== Screenshots ==

1. General tab plugin features
2. Cabinet tab user credentials


== Installation ==

1. Upload `sb-sms-sende` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Register on [SMS club](https://smsclub.mobi/)
4. Setup login, token, alfa name on Cabinet tab
5. Enable/Disable sending sms on order creation and order is completed

== Frequently Asked Questions ==

= How much does it cost? =

1 sms cost as ordinary sms typical to your location
[Here](https://smsclub.mobi/pricing) you can check the actual pricing

= How can I change message of sms? =

Add this filter to your theme's `functions.php` file:

`
/**
 * Set SMS message content
 * for new order
 */
add_filter( 'sb_sms_message_new_order', 'child_sms_message_new_order', 10, 2);
function child_sms_message_new_order($message, $order){

	$message = "Your new sms message for new_order";

	return $message;
}

/**
 * Set SMS message content
 * for order_completed
 */
add_filter( 'sb_sms_message_order_completed', 'child_sms_message_order_completed', 10, 2);
function child_sms_message_order_completed($message, $order){
	$message = "Your new sms message for order_completed";

	return $message;
}
`
== Changelog ==

= 0.0.2 =
* Initial release