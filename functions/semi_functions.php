<?php
namespace custom\access\lite;
defined('ABSPATH') or die('No script kiddies please!');

require_once( ABSPATH . WPINC . '/pluggable.php' );

class semi_functions_seng {

    /**
     * Send an email with correct headers.
     * */
    public function sendMail($to, $subject, $message) {
        $header_info = get_option('CUSTOM_ACCESS_LITE_email_options');
        $backup = get_option('admin_email');
        $headers = "";
        if (strLen($header_info['from']) > 1) {
            $headers .= "From: " . $header_info['from'] . " <" . $header_info['email'] . ">\r\n";
        }
        if (strLen($header_info['reply_to']) > 1) {
            $headers .= "Reply-To: " . $header_info['reply_to'] . "\r\n";
        }else{
            $headers .= "Reply-To: " . $backup . "\r\n";
        }
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\nContent-type: text/plain\r\n";
        if (wp_mail($to, $subject, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * createRandomID: Returns a hex string length 20 to use as session id or activation key.
     * */
    public function createRandomID() {
        $bytes = openssl_random_pseudo_bytes(20);
        return bin2hex($bytes);
    }

    /**
     * Check if password is strong enough and gives back error array
     * @return true if password is strong enough, false if not.
     * */
    public function checkPassword($pwd, &$errors) {
        $errors_init = $errors;

        if (strlen($pwd) < 6) {
            $errors[] = _("Password is too short!");
        }

        if (!preg_match("#[0-9]+#", $pwd)) {
            $errors[] = _("Password must include at least one number!");
        }

        if (!preg_match("#[a-zA-Z]+#", $pwd)) {
            $errors[] = _("Password must include at least one letter!");
        }
        return ($errors == $errors_init);
    }

    /**
     * Check if email is valid.
     * @return true if email is valid, otherwise false.
     * */
    public function checkEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL))
            return true;
        else
            return false;
    }

    /**
     * Retrieves the IP address of the client.
     * */
    public function getRealIpAddr() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ipaddress .= $_SERVER['HTTP_CLIENT_IP'] . ' - ';
        }
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipaddress .= $_SERVER['HTTP_X_FORWARDED_FOR']. ' - ';
        }
        if(isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ipaddress .= $_SERVER['HTTP_X_FORWARDED']. ' - ';
        }
        if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ipaddress .= $_SERVER['HTTP_FORWARDED_FOR']. ' - ';
        }
        if(isset($_SERVER['HTTP_FORWARDED'])) {
            $ipaddress .= $_SERVER['HTTP_FORWARDED']. ' - ';
        }
        if(isset($_SERVER['REMOTE_ADDR'])) {
            $ipaddress .= $_SERVER['REMOTE_ADDR']. ' - ';
        }

        if($ipaddress == '') {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    // When you need to hash a password, just feed it to the function
    // and it will return the hash which you can store in your database. 
    // The important thing here is that you don’t have to provide a salt 
    // value or a cost parameter. The new API will take care of all of 
    // that for you. And the salt is part of the hash, so you don’t 
    // have to store it separately.
    // 
    // Links:
    // Here is a imlementation for PHP 5.5 and older:
    public function create_password_hash($strPassword, $numAlgo = 1, $arrOptions = array()) {
        if (function_exists('password_hash')) {
            // php >= 5.5
            $hash = password_hash($strPassword, $numAlgo, $arrOptions);
        } else {
            $salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
            $salt = base64_encode($salt);
            $salt = str_replace('+', '.', $salt);
            $hash = crypt($strPassword, '$2y$10$' . $salt . '$');
        }
        return $hash;
    }

    public function verify_password_hash($strPassword, $strHash) {
        if (function_exists('password_verify')) {
            // php >= 5.5
            $boolReturn = password_verify($strPassword, $strHash);
        } else {
            $strHash2 = crypt($strPassword, $strHash);
            $boolReturn = $strHash == $strHash2;
        }
        return $boolReturn;
    }

    public function createRandomPass() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $capitals = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 7; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $randomString .= $numbers[rand(0, strlen($numbers) - 1)];
        $randomString .= $capitals[rand(0, strlen($capitals) - 1)];
        for ($i = 0; $i < 7; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function getInformationFromId($id){
        global $wpdb;
        $table_name = $wpdb->prefix.'custom_access_lite_accounts';
        return $wpdb->get_row($wpdb->prepare("SELECT id,username,email,fullname,level FROM $table_name WHERE id= %d", $id), ARRAY_A);
    }
    
    public function containsNotAllowedValues($string){
        return preg_match("/[^a-zA-Z0-9\!\?\|\+\-\_\%\$\# ]/", $string) !== 0;
    }
}
