<?php
    namespace custom\access\lite;
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
        
	include_once __DIR__ . '/../functions/semi_functions.php';
	/**
	van Stein en Groentjes - Log system v1.0
	This file is protected and copyright of van Stein en Groentjes V.O.F
	**/

	/*
	With this log system we track user activity on secured systems and errors that might happen
	There are 3 levels of logs.
	 - Information: Just information of the use of several systems
	 - Warning: Might be important events such as failed loggin attempts
	 - Errors: Real errors like mysql errors
	*/
class Seng_Logger{
	private $loglevel = "Information";
	private $fun; // functions object
	
	/*
	* Contructor. Use as $log = new sg_log("Website naam");
	*/
	public function __construct() 
	{
		$this->fun = new semi_functions_seng();
	}
	
	/*
	* Adds an information entry to the log database. Also logs the Ip adress of the user and the username if given.
	*/
	public function info($text, $username = "Unknown")
	{
            $this->loglevel = "Information";
            $this->wite_log($text, $username);
		
	}
	
        public function info_page($text, $username = "Unknown")
	{
            $this->loglevel = "Information";
            $this->wite_log($text, $username);
	}
        
	/*
	* Adds a warning to the log database. Also logs the Ip adress of the user and the username if given.
	*/
	public function warning($text, $username = "Unknown")
	{
            $this->loglevel = "Warning";
            $this->wite_log($text, $username);
	}
	
	/*
	* Adds an error to the log database. Also logs the Ip adress of the user and the username if given.
	*/
	public function error($text, $username = "Unknown")
	{
            $this->loglevel = "Error";
            $this->wite_log($text, $username);
	}
	
	/*
	* Private function to write the actual log to the database
	*/
	private function wite_log($text, $username){
		global $wpdb;
		$table_name = $wpdb->prefix.'custom_access_lite_logger';
                $pageid = get_the_ID();
                if(CUSTOM_ACCESS_LITE_LEVEL == 2 && $this->loglevel === "Information"){
                    return;
                }
                if(CUSTOM_ACCESS_LITE_LEVEL == 3 && $this->loglevel !== "Information"){
                    return;
                }
	
                $ip = $this->fun->getRealIpAddr();
                $array_to_save = ['username' => $username, 'description' => $text, 'ip' => $ip, 'level' => $this->loglevel, 'pageid' => $pageid];
                $array_to_save_types = ['%s', '%s', '%s', '%s', '%d'];
                $result = $wpdb->insert($table_name, $array_to_save, $array_to_save_types);
                if(!is_numeric($result)){
                        add_action( 'admin_notices', 'SENG_error_thrown_Seng_Logger' );
                }
	}
}