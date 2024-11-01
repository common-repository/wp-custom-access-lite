<?php
namespace custom\access\lite;

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
/*
 * install plugin
 */
function SenG_install() {
    require_once(__DIR__ . '/../classes/installation.php');
    $installation = new Installation_SenG(CUSTOM_ACCESS_LITE_VERSION, home_url());
    if ($installation->getIfDone()) {
        update_option('CUSTOM_ACCESS_LITE_Logger_pages', ['loginpage' => '']);
        update_option('CUSTOM_ACCESS_LITE_opties_array', ['custom_access_lite_captcha_private' => '', 'custom_access_lite_captcha_public' => '', 'custom_access_lite_uselogger' => 1, 'custom_access_lite_levellogger' => 1, 'BCRYPT_COST' => 8, 'USE_COOKY' => 1], 'no');
        update_option('CUSTOM_ACCESS_LITE_email_options', ['from' => '', 'email' => '', 'reply_to' => ''], 'no');
    }
}
/*
 * deactivate function
 */
function SenG_un_install() {
    
}
/*
 * uninstall, deletes all databases and options
 */
function SenG_delete_all(){
    require_once(__DIR__ . '/../classes/seng_uninstall.php');
    $installation = new uninstall_SenG();
    if($installation->getIfDone()){
        return true;
    }
}
/*
 * create the menu in wordpress
 */
function logger_menu_admin() {
    add_menu_page('custom_access_lite', 'CA - Lite', 'activate_plugins', 'custom_access_lite', 'custom\access\lite\seng_logger_main_page', '', 65);
    add_submenu_page( 'custom_access_lite', 'Custom Access', 'Custom Access', 'activate_plugins', 'custom_access_lite', 'custom\access\lite\seng_logger_main_page' );
    add_submenu_page( 'custom_access_lite', 'add users', 'Add users', 'activate_plugins', 'ca-lite-add_users', 'custom\access\lite\seng_logger_admin_side' );
    add_submenu_page( 'custom_access_lite', 'set pages', 'Set pages', 'activate_plugins', 'ca-lite-set_pages', 'custom\access\lite\seng_logger_admin_side_set_pages');
    add_submenu_page( 'custom_access_lite', 'View Log', 'view logs', 'activate_plugins', 'ca-lite-log', 'custom\access\lite\seng_logger_admin_side_view_log' );
    add_submenu_page( 'custom_access_lite', 'CA - Options', 'options', 'activate_plugins', 'ca-lite-options', 'custom\access\lite\seng_logger_admin_side_options' );
}
/*
 * start session if not started, also starts seng_useraccount, and checks cookies
 */
function seng_start_session_because_needed() {
//    if(!session_id()) {
//        session_start();
//    }
    if(!is_admin()){
        $user = new Seng_UserAccount();
        $user->setCookies();
        if(isset($_POST['seng_must_logout'])){
            if($user->isLoggedIn()){
                $user->logout();
            }
        }
    }
}
/*
 * create main page admin-side
 */
function seng_logger_admin_side() {
    require_once(__DIR__ . '/../classes/admin_menu_logger.php');
    $pagina = new SenG_Admin_Menu_Logger();
    $pagina->createPagina();
}
/*
 * create option page admin-side
 */
function seng_logger_admin_side_options(){
    require_once(__DIR__ . '/../classes/admin_menu_logger.php');
    $pagina = new SenG_Admin_Menu_Logger();
    $pagina->createPaginaOptions();
}
/*
 * create log page admin-side
 */
function seng_logger_admin_side_view_log(){
    require_once(__DIR__ . '/../classes/admin_menu_logger.php');
    $pagina = new SenG_Admin_Menu_Logger();
    $pagina->createPaginaViewlogs();
}
/*
 * create pages page admin-side
 */
function seng_logger_admin_side_set_pages(){
    require_once(__DIR__ . '/../classes/admin_menu_logger.php');
    $pagina = new SenG_Admin_Menu_Logger();
    if(isset($_POST['seng_set_login_id'])){
        $pagina->changeOptionsPages($_POST);
    }
    $pagina->createPaginaSetPages();
}

function seng_logger_main_page(){
    require_once(__DIR__ . '/../classes/admin_menu_logger.php');
    $pagina = new SenG_Admin_Menu_Logger();
    $pagina->createPaginaMain();
}
/*
 * if this is seen, something goes wrong
 */
function seng_logger_slug() {
    echo 'argh';
}
/*
 * login function
 */
function seng_short_code_for_login(){
    global $SENG_LOGGER_LOGGEDIN;
    $user = new Seng_UserAccount();
    $option = get_option('CUSTOM_ACCESS_LITE_Logger_pages');
    if($option['loginpage'] == get_the_ID()){
        require_once(__DIR__ . '/../classes/seng_login_screen.php');
        $loginpage = new SenG_Create_Login_Class();
        if(isset($_POST['seng_logger_login'])){
            if($SENG_LOGGER_LOGGEDIN){
                return $loginpage->loginSuccesfull();
            }else{
                return $loginpage->loginFailed($_POST['username']);
            }
        }
        return $loginpage->createLoginPage($user);
    }
    echo '<div style="width:100%;height:50px;border-radius:15px;border:1px solid #4c0000; background-color:#e5e5ff; text-align:center; padding-top:13px;margin:10px;">' . __('You still need to add the id of the page in the set pages') . '</div>';
}
/*
 * show id of page
 */
function seng_short_code_get_page_id(){
    echo '<div style="width:100%;height:50px;border-radius:15px;border:1px solid #4c0000; background-color:#e5e5ff; text-align:center; padding-top:13px;margin:10px;">id = ' . get_the_ID() . '</div>';
}
/*
 * check if user is logged in, and if logged in, if his level is sufficient
 */
function seng_short_code_for_login_check($args){
    $user = new Seng_UserAccount();
    $option = get_option('CUSTOM_ACCESS_LITE_Logger_pages');
    if(!$user->isLoggedIn()){
        //user is already logged in, reroute to secure page.
        echo '<div style="width:100%;height:50px;border-radius:15px;border:1px solid #4c0000; background-color:#e5e5ff; text-align:center; padding-top:13px;margin:10px;color:red;">' . __('Not logged in!') . '</div>';
        echo '<script>window.location.href = "' . get_permalink($option['loginpage']) .'"</script>';
        wp_die();
    }else if($user->getLevel() < $args['level']){
        echo '<div style="width:100%;height:50px;border-radius:15px;border:1px solid #4c0000; background-color:#e5e5ff; text-align:center; padding-top:13px;margin:10px;color:red;">' . __('You don\'t have the needed clearence for this page') . '</div>';
        echo '<script>window.location.href = "' . get_home_url() .'"</script>';
        wp_die();
    }else{
        return;
    }
}
/*
 * logout function
 */
function seng_log_user_out(){
    require_once(__DIR__ . '/../classes/useraccount.php');
    $user = new Seng_UserAccount();
    if($user->isLoggedIn()){
        $user->logout();
    }
}
/*
 * adds necessary css and js to the specific pages
 */
function addbootstraptable($hook) {
    $arr = get_option('CUSTOM_ACCESS_LITE_Logger_pages');
    $url = plugins_url( '../', __FILE__ );
    if ($hook == 'toplevel_page_custom_access_lite' || $hook == 'ca-lite_page_ca-lite-add_users' || $hook == 'ca-lite_page_ca-lite-set_pages' || $hook == 'ca-lite_page_ca-lite-log' || $hook == 'ca-lite_page_ca-lite-options')  {
        wp_enqueue_script('bootstrap-min-js', $url . 'js/bootstrap.min.js', array('jquery'), NULL, true);
        wp_enqueue_script('bootstrap-table-min', $url . 'js/bootstrap-table.min.js', array('jquery'), NULL, true);
        wp_enqueue_script('bootstrap-table-export',$url . 'js/bootstrap-table-export.js' , array('jquery'), NULL, true);
        wp_enqueue_script('bootstrap-export', $url . 'js/tableExport.min.js', array('jquery'), NULL, true);
        wp_enqueue_script('SenG_logger_script', $url . 'js/SenG_logger_script.js', array('jquery'), NULL, true);
        wp_enqueue_style('bootstrap-min-css', $url . 'css/bootstrap.min.css');
        wp_enqueue_style('bootstrap-table-min-css', $url . 'css/bootstrap-table.min.css');
        wp_enqueue_style('SenG_logger_css', $url . 'css/SenG_logger_css.css');
    }
    if(in_array(get_the_ID(), $arr)){
        wp_enqueue_script('bootstrap-min-js', $url . 'js/bootstrap.min.js', array('jquery'), NULL, true);
        wp_enqueue_script('SenG_public_script', $url . 'js/SenG_public_script.js', array('jquery'), NULL, true);
        wp_enqueue_style('bootstrap-min-css', $url . 'css/bootstrap.min.css');
    }
    global $SENG_LOGGER_LOGGEDIN;
    $SENG_LOGGER_LOGGEDIN = false;
    if(isset($arr['loginpage']) && $arr['loginpage'] === get_the_ID()){
        //set the cookies here cause else the headers will already be send.
        $user = new Seng_UserAccount();
        if(isset($_POST['seng_logger_login'])){
             $SENG_LOGGER_LOGGEDIN = $user->loginPost($_POST)===true;
        }
        add_filter('the_content', 'custom\access\lite\seng_short_code_for_login');
    }
}
/*
 * handle all ajax calls
 */
function custom_access_lite_handle_ajax_calls(){
    if(!is_admin()){
        wp_die('Return to hell, you demon.');
    }
    require_once 'semi_functions.php';
    $_fun = new semi_functions_seng();
    // Handle request then generate response using WP_Ajax_Response
    $response = array();
    $response['error'] = '';
    $POST = $_POST['data'];
    if(isset($POST['name']) && isset($POST['value'])){
        $my_options = get_option('CUSTOM_ACCESS_LITE_opties_array');
        if(htmlspecialchars($POST['name']) === 'global'){
            /*
             * Set global options
             */
            if(isset($POST['value']['captcha_private']) && isset($POST['value']['captcha_public'])){
                if(htmlspecialchars($POST['value']['captcha_private']) != $my_options['custom_access_lite_captcha_private']){
                    $my_options['custom_access_lite_captcha_private'] = htmlspecialchars($POST['value']['captcha_private']);
                }
                if(htmlspecialchars($POST['value']['captcha_public']) != $my_options['custom_access_lite_captcha_public']){
                    $my_options['custom_access_lite_captcha_public'] = htmlspecialchars($POST['value']['captcha_public']);
                }
            }
            if(isset($POST['value']['use_logger'])){
                if(htmlspecialchars($POST['value']['use_logger']) === 'true' && $my_options['custom_access_lite_uselogger'] != 1){
                    $my_options['custom_access_lite_uselogger'] = 1; 
                }else if(htmlspecialchars($POST['value']['use_logger']) === 'false' && $my_options['custom_access_lite_uselogger'] != 0){
                    $my_options['custom_access_lite_uselogger'] = 0; 
                }
                if(htmlspecialchars($POST['value']['use_logger']) === 'true'){
                    if(intval($POST['value']['level_logger']) > 0 && $my_options['level_logger'] !== intval($POST['value']['level_logger'])){
                        $my_options['custom_access_lite_levellogger'] = intval($POST['value']['level_logger']); 
                    }
                }else{
                    $my_options['custom_access_lite_levellogger'] = 0; 
                }
            }
            
//            if(isset($POST['value']['allow_cookie'])){
//                if(htmlspecialchars($POST['value']['allow_cookie']) === 'true' && $my_options['USE_COOKY'] != 1){
//                    $my_options['USE_COOKY'] = 1; 
//                }else if(htmlspecialchars($POST['value']['allow_cookie']) === 'false' && $my_options['USE_COOKY'] != 0){
//                    $my_options['USE_COOKY'] = 0; 
//                }
//            }
            update_option('CUSTOM_ACCESS_LITE_opties_array', $my_options);
            $response['succes'] = 'succes';
        }else if(htmlspecialchars($POST['name']) === 'security'){
            /*
             * set security options
             */
            $my_options = get_option('CUSTOM_ACCESS_LITE_opties_array');
            $bcrypt_preliminary = htmlspecialchars ($POST['value']['seng_Bcrypt']);
            $response['value'] = __('value to be set = ') . $bcrypt_preliminary;
            if($bcrypt_preliminary == $my_options['BCRYPT_COST']){
                $response['error'] = __('same value.');
            }else{
                if(is_numeric($bcrypt_preliminary) && $bcrypt_preliminary > 7){
                    $my_options['BCRYPT_COST'] = intVal($bcrypt_preliminary);
                    update_option('CUSTOM_ACCESS_LITE_opties_array', $my_options);
                    $response['succes'] = 'succes';
                }else{
                    $repsonse['error'] = __('value is not a number or higher then 7');
                }
            }
        }else if(htmlspecialchars($POST['name']) === 'emailoptions'){
            /*
             * set email options
             */
            $my_options = get_option('CUSTOM_ACCESS_LITE_email_options');
            $emailfromname = htmlspecialchars($POST['value']['fromname']);
            $emailfromemail = htmlspecialchars($POST['value']['fromemail']);
            $emailreplyto = htmlspecialchars($POST['value']['replyto']);
            $something_is_updated = false;
            if(strlen($emailfromname) > 2 && $emailfromname != $my_options['from']){
                $my_options['from'] = $emailfromname;
                $something_is_updated = true;
            }
            if(seng_check_validity_email($emailfromemail) && $emailfromemail != $my_options['email']){
                $my_options['email'] = $emailfromemail;
                $something_is_updated = true;
            }
            if(seng_check_validity_email($emailreplyto) && $emailreplyto != $my_options['reply_to']){
                $my_options['reply_to'] = $emailreplyto;
                $something_is_updated = true;
            }
            if($something_is_updated){
                update_option('CUSTOM_ACCESS_LITE_email_options', $my_options);
                $response['succes'] = 'succes';
            }else{
                $response['error'] = __('nothing updated, either nothing changed or emails not valid.');
            }
//        }else if(htmlspecialchars($POST['name']) === 'seng_test'){
//            /*
//             * test earlier set smtp information, if it fails, reset option info to nothing
//             */
//            $response['succes'] = 'succes';
        }else if(htmlspecialchars($POST['name']) === 'get_info'){
            /*
             * get user information in admin screen
             */
            $response['datap'] = gettype($POST['value']['id']) . ' ' . $POST['values']['id'];
            $id = intval($POST['value']['id']);
            $response['datah'] = gettype($id) . ' ' . $id;
            if(!is_numeric($id) || $id <= 0){
                $response['error'] = __('id is not a number. If the error persists, contact maker.');
            }else{
                if($userdata = $_fun->getInformationFromId($id)){
                    $response['success'] = 'success';
                    $response['user'] = $userdata;
                }else{
                    $response['error'] = __('id does not exist in database. If the error persists, contact maker.');
                }
            }
        }else if(htmlspecialchars($POST['name']) === 'handle_userinfo'){
            /*
             * update user
             */
            $toUpdate = new Seng_UserAccount();
            $ret = $toUpdate->saveUser($POST['value'], true);
            if($ret === true){
                $response['success'] = 'success';
            }else{
                $response['error'] = $ret;
            }
        }else if(htmlspecialchars($POST['name']) === 'delete_user_info'){
            /*
             * delete user
             */
            $toUpdate = new Seng_UserAccount();
            $ret = $toUpdate->delete_user(htmlspecialchars($POST['value']['id']));
            if($ret === true){
                $response['success'] = 'success';
            }else{
                $response['error'] = $ret;
            }
        }else if(htmlspecialchars($POST['name']) === 'send_new_pw'){
            /*
             * send new pw
             */
            $toUpdate = new Seng_UserAccount();
            $ret = $toUpdate->sendResetPassword(intval(htmlspecialchars($POST['value']['id'])), true);
            if($ret === true){
                $response['success'] = 'success';
            }else{
                $response['error'] = $ret;
            }
        } else if (htmlspecialchars($POST['name']) === 'update_table') {
            if (current_user_can('administrator')) {
                $temp = seng_function_to_update_log(intval($POST['value']['step']));
                if ($temp['error'] === '') {
                    $response['success'] = 'success';
                } else {
                    $response['error'] = $temp['error'];
                }
            } else {
                $response['error'] = __('You have no power here!');
            }
        }
    }else{
        $response['error'] = __('What are you trying to do?');
    }
    echo json_encode($response);
    // Don't forget to stop execution afterward.
    wp_die();
}

function seng_function_to_update_log($int) {
    GLOBAL $wpdb;
    $table_name = $wpdb->prefix . 'custom_access_lite_logger';
    $response = array();
    $response['error'] = '';
    if (current_user_can('administrator')) {
        if ($int === 1) {
            $result = $wpdb->delete($table_name, array('level' => 'Information'), array('%s'));
            if ($result !== false) {
                $response['succes'] = 'succes';
            }
        } elseif ($int === 2) {
            $result = $wpdb->delete($table_name, array('level' => 'Warning'), array('%s'));
            if ($result !== false) {
                $response['succes'] = 'succes';
            }
        } elseif ($int === 3) {
            $result = $wpdb->query("TRUNCATE TABLE $table_name");
            if ($result !== false) {
                $response['succes'] = 'succes';
            }
        } else {
            $response['error'] = __('Stop trying to ruin it!');
        }
        if (!array_key_exists('succes', $response)) {
            $response['error'] = __('An database error was encountered!');
        }
    } else {
        $response['error'] = __('You have no power here!');
    }
    return $response;
}

/*
 * set all defined options
 * @returns boolean
 */
function seng_zet_defined() {
    $options = get_option('CUSTOM_ACCESS_LITE_opties_array');
    if(is_array($options) && count($options) > 1){
        ($options['custom_access_lite_uselogger'] > 0) ? define('CUSTOM_ACCESS_LITE_USE_LOGGER', true) : define('CUSTOM_ACCESS_LITE_USE_LOGGER', false);
        (array_key_exists('custom_access_lite_levellogger', $options)) ? define('CUSTOM_ACCESS_LITE_LEVEL', $options['custom_access_lite_levellogger']) : define('CUSTOM_ACCESS_LITE_LEVEL', 0);
        if (strLen($options['custom_access_lite_captcha_private']) > 8 && strLen($options['custom_access_lite_captcha_public']) > 8) {
            define('CUSTOM_ACCESS_LITE_USE_CAPTCHA', true);
            define('CUSTOM_ACCESS_LITE_PRIVATE', $options['custom_access_lite_captcha_private']);
            define('CUSTOM_ACCESS_LITE_PUBLIC', $options['custom_access_lite_captcha_public']);
        } else {
            define('CUSTOM_ACCESS_LITE_USE_CAPTCHA', false);
            define('CUSTOM_ACCESS_LITE_PRIVATE', '');
            define('CUSTOM_ACCESS_LITE_PUBLIC', '');
        }
        is_numeric($options['BCRYPT_COST']) ? define("CUSTOM_ACCESS_LITE_BCRYPT_COST", $options['BCRYPT_COST']) : define("CUSTOM_ACCESS_LITE_BCRYPT_COST", 8);
        $options['USE_COOKY'] = 1 ? define("CUSTOM_ACCESS_LITE_USE_COOKY", true) : define("CUSTOM_ACCESS_LITE_USE_COOKY", false);
        define ('CUSTOM_ACCESS_LITE_USE_IP', true);
        return true;
    }
    return false;
}
    /**
     * Check if email is valid.
     * @return true if email is valid, otherwise false.
     * */
function seng_check_validity_email($email){
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
        return true;
    else
        return false;
}
//errors, thousands of them

function SENG_error_thrown_Seng_Logger() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Error in the logger, if this error persists, contact the creater.'); ?></p>
    </div>
    <?php
}

function SENG_added_user() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Succesfully added the new user.'); ?></p>
    </div>
    <?php
}

function SENG_set_new_pages() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Succesfully updated the pages.'); ?></p>
    </div>
    <?php
}

function SENG_send_login_email_to_new_user() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Succesfully send email to the new user with log information.'); ?></p>
    </div>
    <?php
}

function SENG_send_activation_email_to_new_user() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Succesfully send email to the new user with activation information.'); ?></p>
    </div>
    <?php
}

function SENG_error_thrown_Seng_error_1() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('error in the query, please notify the maker.'); ?></p>
    </div>
    <?php
}

function SENG_error_thrown_Seng_error_2() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Incorrect username or password.'); ?></p>
    </div>
    <?php
}

function SENG_error_thrown_Seng_error_3() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('This username is already taken.'); ?></p>
    </div>
    <?php
}

function SENG_error_thrown_Seng_error_4() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Passwords do not match.'); ?></p>
    </div>
    <?php
}

function SENG_error_thrown_Seng_error_5() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Your account is not activated yet.'); ?></p>
    </div>
    <?php
}

function SENG_error_thrown_Seng_error_6() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Email failed to send.'); ?></p>
    </div>
    <?php
}

function SENG_error_thrown_Seng_error_7() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('No valid email given.'); ?></p>
    </div>
    <?php
}

function SENG_error_thrown_Seng_error_8() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Email is already registered.'); ?></p>
    </div>
    <?php
}
function SENG_error_thrown_Seng_error_9() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Level was not within accepted parameter.'); ?></p>
    </div>
    <?php
}
function SENG_error_thrown_Seng_error_10() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('Username contains not allowed characters.'); ?></p>
    </div>
    <?php
}
function SENG_error_thrown_Seng_error_00() {
    ?>
    <div class="notice notice-error is-dismissible">
        <p><?php _e('A general error occurred, please contact the site admin.'); ?></p>
    </div>
    <?php
}
