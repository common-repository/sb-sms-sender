<?php
/**
 * Plugin Name: SB SMS Sender
 * Description: Send SMS to client using SMS club
 * Version: 0.0.2
 * Author: SB-development
 * Author URI: https://sb-dev.com.ua/
 * Text Domain: sbsmssender
 * Domain Path: /lang
 * License: GPLv2
 */

defined('ABSPATH') || exit;

// strings for Plugin name/desc translation
__('SB SMS Sender');
__('Send SMS to client using SMS club');

add_action( 'plugins_loaded', function(){
	load_plugin_textdomain( 'sbsmssender', false, dirname( plugin_basename(__FILE__) ) . '/lang' );
} );

// Plugin version.
define( 'SBSMSSENDER_VERSION', '0.0.2' );

// Currently plugin name
define('SBSMSSENDER_NAME', __('SB SMS Sender','sbsmssender'));

// Plugin Folder Path.
define( 'SBSMSSENDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Plugin Folder url.
define( 'SBSMSSENDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Deactivate the plugin if PHP version is below the minimum required.
if (version_compare(PHP_VERSION, '7.2', '<')) {
	deactivate_plugins(plugin_basename(__FILE__));
	wp_die(sprintf(__('Sorry, but your version of PHP (%s) is not supported by %s. PHP 7.2 or greater is required. Plugin has been deactivated. We recommend contacting your hosting provider to have your PHP version upgraded. <a href="%s">Return to the Dashboard.</a>','sbsmssender'),
		PHP_VERSION, SBFORMS_NAME, admin_url()));
	exit();
}

// Deactivate the plugin if the WordPress version is below the minimum required.
global $wp_version;
if (version_compare($wp_version, '5.0', '<')) {
	deactivate_plugins(plugin_basename(__FILE__));
	wp_die(sprintf(__('Sorry, but your version of WordPress, %s, is not supported by %s. WP 5.0 or greater is required. Plugin has been deactivated. <a href="%s">Return to the Dashboard.</a>','sbsmssender'),
		$wp_version, SBFORMS_NAME, admin_url()));
	exit();
}

/**
 * Begins execution of the plugin.
 */

require_once plugin_dir_path( __FILE__ ) . 'inc/admin.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/sender.php';