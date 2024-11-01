<?php

/*
  Plugin Name: WP - Custom Access Lite
  Plugin URI:  https://vansteinengroentjes.nl/
  Description: Plugin that enables you to manage access to your pages without the wordpress login system.
  Version:     1.0.0
  Author:      Van Stein en Groentjes, Jeroen Carpentier
  Author URI:  https://vansteinengroentjes.nl
  License:     LGPLv3
  License URI: https://www.gnu.org/licenses/lgpl-3.0.html
  Text Domain: wporg
  Domain Path: /languages
 */
/**
  WP - Custom Access Lite
  This file is protected and copyright of van Stein en Groentjes V.O.F
 * */
defined('ABSPATH') or die('No script kiddies please!');
/*
 *  functions needed for wordpress
 */
require_once('functions/prime_functions.php');

/*
 * 	version
 */
define('CUSTOM_ACCESS_LITE_VERSION', '1.0.0');
/*
 * activation / deactivation hooks and defining the things to define
 */
$continue = true;
if (is_admin()) {
    //create db's for logger
    register_activation_hook(__FILE__, 'custom\access\lite\SenG_install');

    //deactivate the plugin
    register_deactivation_hook(__FILE__, 'custom\access\lite\SenG_un_install');

    //delete the plugin
    register_uninstall_hook(__FILE__, 'custom\access\lite\SenG_delete_all');
    
    if (!custom\access\lite\seng_zet_defined()) {
        $continue = false;
    }
} else {
    if (!custom\access\lite\seng_zet_defined()) {
        $continue=false;
    }
}
/*
 *  
 */
if($continue){
    require_once('classes/useraccount.php');
    if (is_admin()) {
        //add js/css
        add_action('admin_enqueue_scripts', 'custom\access\lite\addbootstraptable');

        if (isset($_POST['register_admin'])) {
            if ( function_exists('wp_nonce_field') ) 
                check_admin_referer( 'seng_admin_side_add_person', 'seng_noncical_field');
            //add thingy
            
            $user = new custom\access\lite\Seng_UserAccount();
            $temp = $user->registerPost($_POST);
            if ($temp === true) {
                add_action('admin_notices', 'custom\access\lite\SENG_added_user', 20);
            }
        }
        //ajax handler
        add_action( 'wp_ajax_custom_access_lite_handle_ajax', 'custom\access\lite\custom_access_lite_handle_ajax_calls' );

        //create admin menu
        add_action('admin_menu', 'custom\access\lite\logger_menu_admin');
    }
    //add js/css
    add_action('wp_enqueue_scripts', 'custom\access\lite\addbootstraptable');
    //create session if it doesnt exist
    add_action('init', 'custom\access\lite\seng_start_session_because_needed', 1);
    //create phpmailer with smtp gegevens indien beschikbaar
//    add_action( 'phpmailer_init', 'custom\access\lite\seng_smtp_user_info_init' );
    
    //shortcodes
    add_shortcode('CA_lite_get_id_page', 'custom\access\lite\seng_short_code_get_page_id');	
    add_shortcode('CA_lite_login_page', 'custom\access\lite\seng_short_code_for_login');
    add_shortcode('CA_lite_loggedin', 'custom\access\lite\seng_short_code_for_login_check');
}
