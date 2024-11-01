<?php
namespace custom\access\lite;
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

include_once 'errormessage.php';
include_once __DIR__ . '/../functions/semi_functions.php';

if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
    include_once 'sg-logger.php';
}

class Seng_BindParam {
    private $values = array(), $types = '';

    public function add($type, &$value) {
        $this->values[] = $value;
        $this->types .= $type;
    }

    public function get() {
        return array_merge(array($this->types), $this->values);
    }

}


class Seng_UserAccount {
    private $USER;
    private $err; // error message object
    private $fun; // functions object
    private $bcryptOptions;
    private $log;
    private $loggedIn = false;
    

    /**
     * Constructor: Creates database connection and prepares login if cookies are set.
     * */
    function __construct() {
        $this->err = new errormessage_Seng();
        $this->fun = new semi_functions_seng();
        if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
            $this->log = new Seng_Logger();
        }

        $this->bcryptOptions = array('cost' => CUSTOM_ACCESS_LITE_BCRYPT_COST);
        if(!is_admin()){
            $this->restoreSession(); //login user if session is set.
        }
    }

    /*     * ***********************************
      SESSION SET AND RESET
     * ************************************ */

    /**
     * Restore Session to log in user that already has cookies or session set.
     * */
    private function restoreSession() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_access_lite_session';
        $table_name2 = $wpdb->prefix . 'custom_access_lite_accounts';

        if (session_id() && isset($_SESSION['SENG_LOGGER_USER_ID'])) {
            $userid = $_SESSION['SENG_LOGGER_USER_ID'];
            $temp = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2 WHERE id = %d LIMIT 1", $userid), ARRAY_A);
            if (count($temp) > 0) {
                $this->USER = $temp[0];
                $this->loggedIn = true;
            } else {
                return $this->err->get(1, "userAccount:restoreSession");
            }
        }
        if (CUSTOM_ACCESS_LITE_USE_COOKY && isset($_COOKIE["SENG_LOGGER_USER_NAME"]) && isset($_COOKIE["SENG_LOGGER_USER_SESSION"])) {
            $username = $_COOKIE["SENG_LOGGER_USER_NAME"];
            $usersession = $_COOKIE["SENG_LOGGER_USER_SESSION"];
            $tempStop = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2 WHERE username = %s LIMIT 1", $username), ARRAY_A);
            if (count($tempStop) > 0) {
                $USERtemp = $tempStop[0];
                $userid = $USERtemp['id'];
                if ($sessionResult = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE userid= %d AND sessioncode= %s", array($userid, $usersession)), ARRAY_A)) {
                    if (!empty($sessionResult)) {
                        //user has valid session cookie. So lets log him in.
                        $this->USER = $USERtemp;
                        $this->loggedIn = true;
                        $this->setSession();
                    } else {
                        //user has no valid session cookie.
                        //Destroy cookies.
                        $this->eatCookies();
                    }
                }
            } else {
                return $this->err->get(1, "userAccount:restoreSession_2");
            }
        }
    }

    /**
     * Sets the session for $USER
     * */
    private function setSession() {
        if(session_id()){
            $_SESSION['SENG_LOGGER_USER_ID'] = $this->USER['id'];
            $_SESSION['SENG_LOGGER_USER_TYPE'] = $this->USER['level'];
            if ($this->USER['fullname'] != "") {
                $_SESSION['SENG_LOGGER_USER_NAME'] = $this->USER['fullname'];
            } else {
                $_SESSION['SENG_LOGGER_USER_NAME'] = $this->USER['username'];
            }
        }
    }

    /**
     * Destroys current session.
     * */
    private function deleteSession() {
        if(session_id()){
            unset($_SESSION['SENG_LOGGER_USER_ID']);
            unset($_SESSION['SENG_LOGGER_USER_TYPE']);
            unset($_SESSION['SENG_LOGGER_USER_NAME']);
        }
    }

    /**
     * Creates a new session for current user.
     * */
    private function createSession() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_access_lite_session';
        $table_name2 = $wpdb->prefix . 'custom_access_lite_accounts';
        $newsessionid = $this->fun->createRandomID();
        $username = $this->USER['username'];
        $userid = $this->USER['id'];
        $this->USER['sessionid'] = $newsessionid;
        $ip = "";
        if (CUSTOM_ACCESS_LITE_USE_IP) {
            $ip = $this->fun->getRealIpAddr();
        }
        if ($result = $wpdb->update($table_name2, array('sessionid' => $newsessionid), array('username' => $username), array('%s'), array('%s'))) {
            if ((is_int($result) && $result <= 0) || !is_int($result)){
                return $result;
            }
        }
        $result2 = $wpdb->insert($table_name, array('userid' => $userid,'sessioncode' => $newsessionid, 'ip' => $ip), array('%d', '%s', '%s'));
        if (!is_int($result2) || (is_int($result2) && $result2 <= 0) ){
            return false;
        }
        $this->setCookies();
        $this->setSession();
        return true;
    }

    /*     * ***********************************
      COOKIE SET AND RESET
     * ************************************ */

    /**
     * Sets the cookies for $USER (yum yum)
     * */
    public function setCookies() {
        if (CUSTOM_ACCESS_LITE_USE_COOKY) {
            setcookie("SENG_LOGGER_USER_NAME", $this->USER['username'], time() + 36000, '/');  /* expire in 10 hour */
            setcookie("SENG_LOGGER_USER_SESSION", $this->USER['sessionid'], time() + 36000, '/');  /* expire in 10 hour */
        }
        
    }

    /**
     * Destroys current cookies.
     * */
    private function eatCookies() {
        setcookie("SENG_LOGGER_USER_NAME", "", time() - 3600);
        setcookie("SENG_LOGGER_USER_SESSION", "", time() - 3600);
    }

    /*     * ***********************************
      USER LOGIN
     * ************************************ */

    /**
     * Lets a user login into the system.
     * @param $username, unique username
     * @param $password, user password
     * @param $captcha, [Optional] code from image
     * @return true if user is succesfully logged in, string with error otherwise.
     * */
    public function login($username, $password, $captcha = "") {
        global $wpdb;
        $ps = "";
        $username = htmlspecialchars($username);
        $table_name = $wpdb->prefix . 'custom_access_lite_session';
        $table_name2 = $wpdb->prefix . 'custom_access_lite_accounts';
        $tempTarget = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2 WHERE username= %s LIMIT 1", $username), ARRAY_A);
        if (count($tempTarget) > 0) {
            $this->USER = $tempTarget[0];
            $ps = $this->USER['ps'];
        } else {
            if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
                $this->log->warning("Incorrect password or username", $username);
            }
            return $this->err->get(2);
        }
        if ($this->fun->verify_password_hash($password, $ps)) {
            if (CUSTOM_ACCESS_LITE_USE_CAPTCHA) {
                if($captcha['success'] == false){
                    $err = "Security code incorrect";
                    $this->log->warning("LOGIN with incorrect captcha", $username);
                    return $err;
                }
            }

            if ($this->USER['active'] == 0) {
                return $this->err->get(5);
            }
            
            if ($this->createSession()) {
                $this->loggedIn = true;
                if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
                    $this->log->info("User logged in", $username);
                }
                return true;
            } else {
                return $this->err->get(1, "userAccount:login_2");
            }
        } else {
            if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
                $this->log->warning("Incorrect password or username", $username);
            }
            return $this->err->get(2);
        }
    }

    /**
     * Lets a user login into the system with a post form.
     * @param $POST, array of post values, should contain username and password.
     * */
    public function loginPost($POST) {
        if(CUSTOM_ACCESS_LITE_USE_CAPTCHA){
            if(isset($_POST['g-recaptcha-response'])){
                $captcha = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . CUSTOM_ACCESS_LITE_PRIVATE . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
            }else{
                $captcha = array("success" => false);
            }
            return $this->login(filter_var($POST['username'], FILTER_SANITIZE_SPECIAL_CHARS), $POST['password'], $captcha);
        }else{
            return $this->login(filter_var($POST['username'], FILTER_SANITIZE_SPECIAL_CHARS), $POST['password']);
        }
    }

    /*     * ***********************************
      USER REGISTER
     * ************************************ */

    /**
     * Lets a user register into the system.
     * @param $username, unique username
     * @param $password, user password
     * @param $password2, user password retyped
     * @param $email, email for recovery etc
     * @param $level [optional] inlog level
     * @param $captcha, [Optional] code from image
     * @return true if user is succesfully registered, string with error otherwise.
     * */
    public function register($username, $password, $password2, $email, $level=0, $POST, $captcha = "") {
        global $wpdb;
        $table_name = $wpdb->prefix.'custom_access_lite_accounts';
        //sanity checks
        if (!$this->fun->checkEmail($email)) {
            return $this->err->get(7);
        }
        $password_errors = array();
        if (!$this->fun->checkPassword($password, $password_errors)) {
            return $password_errors[0];
        }
        if (CUSTOM_ACCESS_LITE_USE_CAPTCHA && !is_admin()) {
            if($captcha['success'] == false){
                $err = "Security code incorrect";
                $this->log->warning("REGISTER with incorrect captcha", $username);
                return $err;
            }
        }
        if ($wpdb->get_row( $wpdb->prepare("SELECT id FROM " . $table_name . " WHERE username = %s AND active = 1;", $username)) !== null) {
            return $this->err->get(3);
        }
        if ($password != $password2) {
            return $this->err->get(4);
        }
        if($level > 9 || $level < 0 || !is_numeric($level)){
            return $this->err->get(9);
        }
        $hash = $this->fun->create_password_hash($password, 1, $this->bcryptOptions);
        $array_to_save = ['username' => $username, 'email' => $email, 'fullname' => $POST['fullname'], 'level' => $level, 'ps' => $hash];
        $array_to_save_types = ['%s', '%s', '%s', '%d', '%s'];
        $array_to_save['active'] = 1;
        $array_to_save_types[] = '%d';
        
        $result = $wpdb->insert($table_name, $array_to_save, $array_to_save_types);
        if (is_numeric($result)) {
            if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
                $this->log->info("User registered", $username);
            }
            $homeurl = get_option('home');
            //send email with first time login details
            $body2 = "Dear $username.\n\n".
                    "An account has been created for you on " . $homeurl . ".\n" .
                    "You can log in with the following information: \n" .
                    "username:" . $username . "\n" . 
                    "password:" . $password . "\n\n" . 

                    "Should you encounter some problems, don't hesitate to contact us.\n\n" .

                    "Yours truly,\n" . 
                    get_bloginfo(`name`) . "\n";
            if (!$this->fun->sendMail($email, "Gegevens website.", $body2)){
                return $this->err->get(6);
            }else{
                add_action( 'admin_notices', 'custom\access\lite\SENG_send_login_email_to_new_user' );
            }
            if (!is_admin()) {
                return $this->login($username, $password);
            }
            return true;
        } else {
            return $this->err->get(1, "userAccount:register");
        }
    }

//register

    /**
     * Lets a user register into the system with a post form.
     * @param $POST, array of post values, should contain at least username, password, password2 and email.
     * */
    public function registerPost($POST) {
        $username = filter_var($POST['username'], FILTER_SANITIZE_SPECIAL_CHARS);
        $password = $password2 = $this->fun->createRandomPass();
        $email = filter_var($POST['email'], FILTER_SANITIZE_EMAIL);
        $level = filter_var($POST['level'], FILTER_SANITIZE_NUMBER_INT);
        unset($POST['username']);
        unset($POST['level']);
        unset($POST['password']);
        unset($POST['password2']);
        unset($POST['email']);
        if(CUSTOM_ACCESS_LITE_USE_CAPTCHA && !is_admin()){
            if(isset($_POST['g-recaptcha-response'])){
                $captcha = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . CUSTOM_ACCESS_LITE_PRIVATE . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
            }else{
                $captcha = array("success" => false);
            }
        }else{
                $captcha = false;
        }
        return $this->register($username, $password, $password2, $email, $level, $POST, $captcha);
    }

    /**
     * Activate account
     * @return boolean, true if activated, false if error.
     * */
    public function activate($token, $email) {
        global $wpdb;
        $table_name = $wpdb->prefix.'custom_access_lite_accounts';
        if ($userResult = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE email = %s AND rand = %s", array($email, $token)), ARRAY_A)) {
            if (!empty($userResult)) {
                if ($userResult = $wpdb->update($table_name, array('active' => 1), array('email' => $email, 'rand' => $token), array('%d'), array('%s', '%s'))) {
                    if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
                        $this->log->info("User activated", $email);
                    }
                    return true;
                }
            }
        }
        if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
            $this->log->warning("User failed to activate", $email);
        }
        return false;
    }

    /*     * ***********************************
      USER LOGOUT
     * ************************************ */

    /**
     * Logout user and reset session.
     * */
    public function logout() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_access_lite_session';
        $userid = $this->USER['id'];
        $sessionid = $this->USER['sessionid'];
        $this->deleteSession();
        $this->eatCookies();
        $this->loggedIn = false;
        $wpdb->delete($table_name, array('userid' => $userid, 'sessioncode' => $sessionid), array('%s', '%s'));
        return true;
    }

    /*     * ***********************************
      GET FUNCTIONS
     * ************************************ */

    /**
     * check if user is loggedin.
     * @return boolean
     * */
    public function isLoggedIn() {
        if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
            $this->log->info("user visited page", $this->USER['fullname']);
        }
        return $this->loggedIn;
    }

    /**
     * check if user is admin.
     * @return boolean
     * */
    public function getLevel() {
        return $this->USER['level'];
    }

    /**
     * gets current user
     * @return array with user data
     * */
    public function getUser() {
        return $this->USER;
    }

    /*     * ***********************************
      CHANGE USER
     * ************************************ */

    /**
     * gets current user
     * @param array with new user data.
     * @return array with user data
     * */
    public function saveUser($NEWUSER, $ajaxCall = false) {
        global $wpdb;
        $table_name = $wpdb->prefix.'custom_access_lite_accounts';
        $allowed = array("fullname", "username", "level", "email", "password");
        $updatevalues = array();
        foreach ($NEWUSER as $key => $value) {
            if (in_array($key, $allowed)) {
                if ($key == "email" && !$this->fun->checkEmail($value)) {
                    return $this->err->get(7, $ajaxCall);
                }else{
                    $updatevalues[$key] = filter_var(trim($value),FILTER_SANITIZE_EMAIL);
                }
                $passworderrors = array();
                if ($key == "password" && !$this->fun->checkPassword($value, $passworderrors)) {
                    return $this->err->get(11, $ajaxCall);
                }else{
                    $updatevalues[$key] = $value;
                }
                if($key == 'level' && is_numeric($value) && ($value > 9 || $value < 0)){
                    return $this->err->get(9, $ajaxCall);
                }else{
                    $updatevalues[$key] = intval($value);
                }
                if($key == 'username' && $this->fun->containsNotAllowedValues($value)){
                    return $this->err->get(10, $ajaxCall);
                }else{
                    $updatevalues[$key] = $value;
                }
            }
        }
        if(!is_admin()){$id = $this->USER['id'];}else{ $id = $NEWUSER['id'];}
        $result = $wpdb->update($table_name, $updatevalues, array('id' => $id));
        if ($result) {
            return true;
        } else {
            return $this->err->get(1, "userAccount:saveUser");
        }
    }

    /*     * ***********************************
      PASSWORD RESET
     * ************************************ */
    /**
     * checks captcha if needed
     * @param username
     * @param $_POST 
     * @param random hash from email
     * @return resetPassword()
     * */
    public function resetPasswordPre($username, $POST, $rand) {
        if(CUSTOM_ACCESS_LITE_USE_CAPTCHA){
            if(isset($_POST['g-recaptcha-response'])){
                $captcha = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . CUSTOM_ACCESS_LITE_PRIVATE . "&response=" . $_POST['g-recaptcha-response'] . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);
            }else{
                $captcha = array("success" => false);
            }
        }else{
            $captcha = false;
        }
        return $this->resetPassword($username, htmlspecialchars($POST['password']), htmlspecialchars($POST['password']), $rand, $captcha);
    }
    /**
     * Sends password reset email
     * @param username
     * @param password
     * @param password2 must be same as password
     * @param random hash from email
     * */
    public function resetPassword($username, $password, $password2, $rand, $captcha = false) {
        global $wpdb;
        $table_name = $wpdb->prefix.'custom_access_lite_accounts';
        if (CUSTOM_ACCESS_LITE_USE_CAPTCHA) {
            if($captcha === false || $captcha['success'] == false){
                $err = "Security code incorrect";
                $this->log->warning("REGISTER with incorrect captcha", $username);
                return $err;
            }
        }
        $password_errors = array();
        if (!$this->fun->checkPassword($password, $password_errors)) {
            return $password_errors[0];
        }
        $token = $rand;
        if ($password != $password2) {
            return $this->err->get(4);
        }
        if ($userResult = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE username = %s AND rand= %s LIMIT 1", array($username, $rand) ), ARRAY_A)) {
            if (!empty($userResult)) {
                $password = $this->fun->create_password_hash($password, 1, $this->bcryptOptions);
                if ($wpdb->update($table_name, array('active' => 1, 'rand' => '', 'ps' => $password), array('username'=> $username), array('%d', '%s', '%s'), array('%s'))) {
                    if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
                        $this->log->info("User activated", $username);
                    }
                    return true;
                } else {
                    return "update went wrong";
                }
            }
        }
        if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
            $this->log->warning("User failed to reset password", $username);
        }
        return "something went wrong.";
    }

    /**
     * Check if user is allowed to reset.
     * @param POST values from link in email
     * */
    public function checkPasswordReset($email, $token) {
        global $wpdb;
        $table_name = $wpdb->prefix.'custom_access_lite_accounts';
        if ($userResult = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE email= %s AND rand = %s", array($email, $token)),ARRAY_A)) {
            if (!empty($userResult)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Sends password reset email
     * @param username
     * */
    public function sendResetPassword($userid, $isAjax = false) {
        if(is_admin()){
            global $wpdb;
            $table_name = $wpdb->prefix.'custom_access_lite_accounts';
            $option_page = get_option('CUSTOM_ACCESS_LITE_Logger_pages');
            if(array_key_exists('activationpage', $option_page)){
                if ($userr = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id= %d LIMIT 1", $userid), ARRAY_A)[0]) {
                    if (!empty($userr)) {
                        $randToken = $this->fun->createRandomID();
                        $activation_link = add_query_arg( array( 'token' => $randToken, 'em' => $userr['email'], 'us' => $userr['username'] ), home_url() . '?p=' . $option_page['activationpage']);
                        $body = "Dear " . $userr['fullname'] . "\n\n" .
                                "To reset your password please follow this link:\n" .
                                $activation_link . "\n" .
                                "\n" ;
                        if ($wpdb->update($table_name, array('rand' => $randToken), array('id' => $userid), array('%s'), array('%s'))) {
                            if (!$this->fun->sendMail($userr['email'], "Password reset.", $body)) {
                                return $this->err->get(6, $isAjax);
                            }
                            return true;
                        }
                    }
                }
                if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
                    $this->log->warning("someone tried to change password from id ", $userid);
                }
                return __("User does not exist.");
            }else{
                if ($userr = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE id= %d LIMIT 1", $userid), ARRAY_A)[0]) {
                    if (!empty($userr)) {
                        $password = $this->fun->createRandomPass();
                        $hash = $this->fun->create_password_hash($password, 1, $this->bcryptOptions);
                        $homeurl = get_option('home');
                        $body = "Dear " . $userr['fullname'] . "\n\n" .
                                "Your password has been reset for our site, " . $homeurl . ".\n" .
                                "Your new password is:\n" .
                                $password . "\n" .
                                "\n" .
                                "Should you encounter some problems, don't hesitate to contact us.\n\n" .

                                "Yours truly,\n" . 
                                get_bloginfo(`name`) . "\n";
                        if ($wpdb->update($table_name, array('ps' => $hash), array('id' => $userid), array('%s'), array('%s'))) {
                            if (!$this->fun->sendMail($userr['email'], "Password reset.", $body)) {
                                return $this->err->get(6, $isAjax);
                            }
                            return true;
                        }
                    }
                }
                if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
                    $this->log->warning("someone tried to change password from id ", $userid);
                }
                return __("User does not exist.");
            }
        }
        if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
            $this->log->warning("someone tried to change password from id ", $userid);
        }
        return "something went wrong.";
    }
    
    /*
     * checks the information
     * @param rand hash from email
     * @param email
     * @param username
     * @return boolean
     */
    public function resetPwCheck($token, $email, $user){
        global $wpdb;
        $table_name = $wpdb->prefix.'custom_access_lite_accounts';
        if($userr = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE username= %s AND rand= %s AND email= %s LIMIT 1", $user, $token, $email ), ARRAY_A)){
            if(!empty($userr)){
                return true;
            }
        }
        return false;
    }
    /*
     * delete user by id
     * @param id
     * @return true/string error
     */
    public function delete_user($id){
        if(is_numeric($id) && $id>0){
            global $wpdb;
            $table_name = $wpdb->prefix.'custom_access_lite_accounts';
            if($wpdb->update($table_name, array('active' => 0), array('id' => $id), array('%d'), array('%d'))){
                return true;
            }else{
                return $this->err->get(1);
            }
        }else{
            return $this->err->get(00, 'no id.');
        }
    }
    
    function refValues($arr) {
        if (strnatcmp(phpversion(), '5.3') >= 0) { //Reference is required for PHP 5.3+
            $refs = array();
            foreach ($arr as $key => $value)
                $refs[$key] = &$arr[$key];
            return $refs;
        }
        return $arr;
    }
    
    /*
     * log error
     * @param errortekst
     * @param username
     */
    public function send_error($error, $user = ''){
        if($user === ''){
            if(array_key_exists('username',$this->USER)){
                $user = $this->USER['username'];
            }
        }
        if (CUSTOM_ACCESS_LITE_USE_LOGGER) {
            $this->log->warning($error, $user);
        }
        return true;
    }

}