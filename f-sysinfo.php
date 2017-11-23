<?php
/*
Plugin Name: F-SysInfo
Plugin URI: https://fedirko.pro/
Description: Shows important system information about server configuration as nice Widget on WP dashboard.
Version: 1.0
Author: Serhii Fedirko
Author URI: https://fedirko.pro
License: GPL2
*/

//plugins prefixes for functions - fsi_ (f-sysinfo)

register_activation_hook( __FILE__, 'fsi_plugin_activation');
register_deactivation_hook( __FILE__, 'fsi_plugin_deactivation');
register_uninstall_hook( __FILE__, 'fsi_plugin_uninstall');

function fsi_plugin_activation(){
	//some actions when activate
}
function fsi_plugin_deactivation(){
	//some actions when activate
}
function fsi_plugin_uninstall(){
	//some actions when activate
}

function fsi_dashboard_widget(){
	echo '<b>Wordpress version:</b> ' . get_bloginfo('version') . '<br>';
	echo '<b>PHP version:</b> ' . phpversion() . '<br>';
	try{
		$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
		echo '<b>MySQL Server version:</b> ' . $dbc->server_info . '<br>';
	} catch (Throwable $e){
		echo $e;
	}
	echo '<b>OS family:</b> ' . PHP_OS . '<br>';
	$cmd = 'cat /etc/*-release';
	exec($cmd, $output);
	if (!empty($output) && is_array($output)){
		echo '<b>OS detailed:</b> ' . $output[0] . '<br>';
	}
	echo '<b>Server details:</b> ' . php_uname() . '<br>';
	echo '<b>Server Software:</b> ' .  $_SERVER['SERVER_SOFTWARE'] . '<br>';
	echo '<b>Operated by server user:</b> ' . get_current_user() . '<br>';
	echo '<hr>';
	echo '<b>Loaded PHP extensions (sorted):</b><br>';
	$all_ext = array_map('strtolower', get_loaded_extensions());
	asort($all_ext);
	echo '<table id="sys_info_table"><caption>click to show/hide</caption><tbody>';
	foreach ($all_ext as $extension){
		echo '<tr><td>' . $extension . '</td></tr>';
	}
	echo '</tbody></table>';
}

function fsi_add_dashboard_widgets() {
	wp_add_dashboard_widget('fsi_dashboard_widget', 'System Info', 'fsi_dashboard_widget');
}

add_action('wp_dashboard_setup', 'fsi_add_dashboard_widgets' );

add_action('admin_enqueue_scripts', 'fsi_styles_and_scripts');

function fsi_styles_and_scripts() {
	wp_enqueue_style('fsi_styles', plugins_url('/css/fsi_styles.css',__FILE__));
	wp_enqueue_script('fsi_scripts', plugins_url('/js/fsi_scripts.js', __FILE__), array( 'jquery' ), 1, true);
}