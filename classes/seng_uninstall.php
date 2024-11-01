<?php
namespace custom\access\lite;
defined('ABSPATH') or die('No script kiddies please!');

class uninstall_SenG {

    private $_done = false;
            
    public function __construct() {
        if(!is_admin() || !current_user_can('activate_plugins')){
            if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
                $this->log = new Seng_Logger();
                $this->log->warning("Someone attemted to uninstall the plugin", 'unknown');
            }
            wp_die(__('Begone you evil man'));
        }
        $this->delete_dbs()  ;
        $this->remove_options();
        $this->setDone();
    }

    public function getIfDone() {
        return $this->_done;
    }

    private function setDone() {
        $this->_done = true;
    }
    
    private function delete_dbs(){
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_access_lite_session';
        $table_name2 = $wpdb->prefix . 'custom_access_lite_accounts';
        $table_name3 = $wpdb->prefix . 'custom_access_lite_logger';
        $optie1 = $wpdb->query( "DROP TABLE IF EXISTS $table_name" );
        $optie2 = $wpdb->query( "DROP TABLE IF EXISTS $table_name2" );
        $optie3 = $wpdb->query( "DROP TABLE IF EXISTS $table_name3" );
        delete_option("my_plugin_db_version");
    }
    
    private function remove_options(){
        $optie1 = delete_option( 'CUSTOM_ACCESS_LITE_VERSION' );
        $optie3 = delete_option( 'CUSTOM_ACCESS_LITE_email_options' );
        $optie4 = delete_option( 'CUSTOM_ACCESS_LITE_opties_array' );
        $optie5 = delete_option( 'CUSTOM_ACCESS_LITE_Logger_pages' );
        if(in_array(false, array($optie1,$optie3,$optie4,$optie5))){
            return false;
        }else{
            return true;
        }
    }
}