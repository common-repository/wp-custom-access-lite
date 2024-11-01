<?php
namespace custom\access\lite;
defined('ABSPATH') or die('No script kiddies please!');


class errormessage_Seng { 
	/**
	* gets a message with a certain errorcode.
	* @param: $errorcode
	* @param: $functionname, function where the error occurred.
	**/
	public function get($errorcode,$ajaxCall=false) { 
                if(is_admin() && !$ajaxCall){
                    $action = 'admin_notices';
                }else{
                    $action = false;
                }
		switch ($errorcode) {
			case 1: //Query error
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_1', 20);
                                    return false;
                                }else{
                                    return __('error in the query, please notify the maker.');
                                }
				break;
			case 2: //Wrong credentials error in login
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_2', 20);
                                    return false;
                                }else{
                                    return __('Incorrect username or password.');
                                }
				break;
			case 3: //User already exists
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_3', 20);
                                    return false;
                                }else{
                                    return __('This username is already taken.');
                                }
				break;
			case 4: //passwords are not the same
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_4', 20);
                                    return false;
                                }else{
                                    return __('Passwords do not match.');
                                }
				break;
			case 5: //account not active
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_5', 20);
                                    return false;
                                }else{
                                    return __('Your account is not activated yet.');
                                }
				break;
			case 6: //Email failed to send
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_6', 20);
                                    return false;
                                }else{
                                    return __('Email failed to send.');
                                }
				break;
			case 7: //wrong email format
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_7', 20);
                                    return false;
                                }else{
                                    return __('No valid email given.');
                                }
				break;
                        case 8: //email already registered
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_8', 20);
                                    return false;
                                }else{
                                    return __('Email is already registered.');
                                }
                                break;
                        case 9: //level not in accepted parameter
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_9', 20);
                                    return false;
                                }else{
                                    return __('Level was not within accepted parameter.');
                                }
                                break;
                        case 10: //username contains not allowed characters
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_10', 20);
                                    return false;
                                }else{
                                    return __('Username contains not allowed characters.');
                                }
                                break;
			default:
                                if($action){
                                    add_action($action, 'custom\access\lite\SENG_error_thrown_Seng_error_00', 20);
                                    return false;
                                }else{
                                    return __("A general error occurred, please contact the site admin.");
                                }
				break;
		}
	} 

} 