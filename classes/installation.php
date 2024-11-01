<?php
namespace custom\access\lite;
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


class Installation_SenG {

    private $_done = false;

    public function __construct($version, $website) {
        $installed_version = get_option('CUSTOM_ACCESS_LITE_VERSION');
        if (!$installed_version) {
            if ($this->installDataBases()) {
                add_option('CUSTOM_ACCESS_LITE_VERSION', $version, '', 'no');
                $this->_done = true;
            }
        } else if ($installed_version != $version) {
            if ($this->checkIfUpdateNeeded($installed_version, $version) === 'updated') {
                update_option('CUSTOM_ACCESS_LITE_VERSION', $version);
                $this->_done = true;
            }
        } else {
            $this->_done = true;
        }
    }

    public function getIfDone() {
        return $this->_done;
    }

    private function installDataBases() {
        global $wpdb;
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'custom_access_lite_session';
        $table_name2 = $wpdb->prefix . 'custom_access_lite_accounts';
        $table_name3 = $wpdb->prefix . 'custom_access_lite_logger';
        $table_1 = false;
        $table_2 = false;
        $table_3 = false;
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            //Table does not exits
            $sql = "CREATE TABLE $table_name (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  userid int(11) NOT NULL,
			  sessioncode varchar(128) NOT NULL,
			  created timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  ip varchar(128) NOT NULL,
			  PRIMARY KEY  (id)
			) $charset_collate;";

            dbDelta($sql);
            $table_1 = true;
        }
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name2'") != $table_name2) {
            //Table does not exits
            $sql = "CREATE TABLE $table_name2 (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  username varchar(128) NOT NULL,
			  email varchar(128) NOT NULL,
			  fullname varchar(128) NOT NULL,
			  level tinyint(3) DEFAULT 0,
			  sessionid varchar(64) NOT NULL,
			  ps varchar(128) NOT NULL,
			  rand varchar(128) NOT NULL,
			  joined timestamp DEFAULT CURRENT_TIMESTAMP,
			  active int(11) DEFAULT 0 NOT NULL,
			  PRIMARY KEY  (id)
			) $charset_collate;";

            dbDelta($sql);
            $table_2 = true;
        }
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name3'") != $table_name3) {
            //Table does not exits
            $sql = "CREATE TABLE $table_name3 (
			  id int(11) NOT NULL AUTO_INCREMENT,
			  username varchar(128) DEFAULT NULL,
			  description varchar(128) DEFAULT NULL,
			  ip varchar(128) DEFAULT NULL,
			  level varchar(16) DEFAULT NULL,
                          pageid int(11) DEFAULT NULL,
			  timestamp timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			  PRIMARY KEY  (id)
			) $charset_collate;";

            dbDelta($sql);
            $table_3 = true;
        }
        if ($table_1 && $table_2 && $table_3) {
            return true;
        } else {
            return $this->checkDataBases($table_1, $table_2, $table_3);
        }
    }

    private function checkDataBases($table_1, $table_2, $table_3) {
        return true;
    }

    private function checkIfUpdateNeeded($int_ver, $ver) {
        return 'updated';
    }

}
