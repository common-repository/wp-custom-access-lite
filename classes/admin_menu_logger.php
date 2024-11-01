<?php
namespace custom\access\lite;
defined('ABSPATH') or die('No script kiddies please!');

class SenG_Admin_Menu_Logger {
    private $_updated = false;
    public function __contsruct() {
        
    }
    
    public function createPaginaMain(){
        if (!current_user_can('activate_plugins')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        ?>
        <!-- banner S&G -->
        <div class="custom_access_lite-over_banner">
            <div class="custom_access_lite-banner">
                <div class="custom_access_lite-inner_banner">
                    <div class="banner-content">
                       <div class="container-fluid">
                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                               <div class="row">
                                   <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-12 col-xs-12">
                                       <img class="custom_access_lite-logo_img" src="<?php echo plugins_url('../logo.png', __FILE__);?>" alt="Logo S&G"/>
                                   </div>
                                   <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
                                       <h3 class="custom_access_lite-plugin_title_header"><?php _e("WP - Custom Access Lite")?></h3>
                                       <h4 class="custom_access_lite-subtitle_header"><?php echo _e("By van Stein & Groentjes"); ?></h4>
                                   </div>
                               </div>
                           </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end banner S&G -->
        <div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 column">
                    <!-- introduction -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel panel-default seng-same-height">
                            <div class="panel-heading"><?php _e('Welcome to S&G login system with logger') ?></div>
                            <div class="panel-body">
                                <div class="custom_access_lite-css_panel_text">
                                    <p>
                                        <?php _e('This is a plugin to easily create a login page and hide posts for visitors who aren\'t logged in. We strive to make the use of our plugin as easy and intu&iuml;tive as possible.'); ?>
                                    </p>
                                    <p>
                                        <?php _e('Through a few easy steps you will be able to use our plugin. Beside this we try to give as much possibilities as possible to make sure you are content with our plugin.'); ?>
                                    </p>
                                    <p>
                                        <?php _e('Our main purpose is ofcourse security. Through our logger you will be able to see who did what. Also attempts to do actions against pages affiliated to this plugin will be logged.'); ?>
                                    </p>
                                    <p>
                                        <?php _e('Several features will unfortunately only be available for paying users. Thank you for downloading our plugin and we would love to hear feedback, good and bad.'); ?>
                                    </p>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#custom_access_lite-installation_modal">
                                        <?php echo _e("Click here for the installation information"); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end introduction -->
                    <!-- installation info -->
                    <div class="modal fade" id="custom_access_lite-installation_modal" role="dialog" aria-labelledby="installation_label">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="installation_label"><?php echo _e("Starting to use the plugin")?></h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        <?php _e('Step 1: specify what page you wish to be the login page. This is explained'); ?> <a href="<?php echo admin_url( 'admin.php?page=ca-lite-set_pages'); ?>"><?php _e('here'); ?></a>
                                        <!--<?php _e('or click'); ?> <a href="youtube.com/url/to/watch/for/info"><?php _e('here'); ?></a>
                                        <?php _e('to view a video how to.'); ?>-->
                                    </p>
                                    <p>
                                        <?php _e('Step 2. simply add our shorthand to the page you wish to hide behind the login system. generate the code '); ?>
                                        <a data-toggle="modal" data-target="#custom_access_lite_help_information" style="cursor:pointer;"><?php _e('here'); ?></a>
                                        <!--<?php _e('or click'); ?> <a href="youtube.com/url/to/watch/for/info"><?php _e('here'); ?></a>
                                        <?php _e('to view a video how to.'); ?>-->
                                    </p>
                                    <p>
                                        <?php _e('That is all that is needed! Just add the people you want to give acces to these pages'); ?>
                                        <a href="<?php echo admin_url( 'admin.php?page=seng_add_users'); ?>"><?php _e('here'); ?></a>
                                    </p>
                                    <p>
                                        <?php _e('Don\'t forget to check out the options '); ?>
                                        <a href="<?php echo admin_url( 'admin.php?page=ca-lite-options'); ?>"><?php _e('here'); ?></a>
                                        <!--
                                        <?php _e('or check out '); ?>
                                        <a href="youtube.com/url/to/watch/for/info"><?php _e('our video'); ?></a>
                                        <?php _e('about what you can do with this plugin.'); ?>-->
                                    </p>
                                    <p>
                                        <?php _e('You can also watch a video explaining how to use the app, '); ?>
                                        <a href="<?php echo admin_url( 'https://www.youtube.com/watch?v=3A_PgyAvJUQ'); ?>"><?php _e('here'); ?></a>
                                    </p>
                                    <p>
                                        <?php _e('And if you are happy, sad, want more or anything,'); ?>
                                        <a href="vansteinengroentjes.nl"><?php _e('let us know!'); ?></a>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close'); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end installation info -->
                </div>
            </div>
        </div>
        <div class="col-lg-10 col-lg-offset-1 col-md-10 col-md-offset-1 column">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default seng-same-height">
                    <div class="panel-heading"><?php _e('You can us the following shortcodes') ?></div>
                    <div class="panel-body">
                        <div class="custom_access_lite-css_panel_text">
                            <p>hide the content behind the login: 
                                <b style="padding-left:24px;">[CA_lite_loggedin level=?]</b>
                            </p>
                            <p> <?php _e('*Where the question mark is the required level to see the content of the page'); ?> </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="custom_access_lite_help_information" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="javascript:custom_access_lite_closemodal()">&times;</button>
                        <h4 class="modal-title"><?php _e('Create shorthand'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="custom_access_lite_level_shorthand"><?php _e('level needed:'); ?></label><br />
                            <div class="controls">
                                <input id="custom_access_lite_level_shorthand" name="custom_access_lite_level_shorthand" type="number" min="1" max="9" placeholder="<?php _e('Enter desired level'); ?>" class="form-control" required="">
                            </div>
                        </div>
                        <button type="button" class="btn btn-default" onclick="javascript:custom_access_lite_createShorthand()"><?php _e('Create shorthand'); ?></button>
                        <hr class="custom_access_lite_hidden_shorthand">
                        <div class="custom_access_lite_hidden_shorthand">
                            <p> <?php _e('your shorthand is:'); ?> </p>
                            <h4 class="custom_access_lite_target_shorthand">
                            </h4>
                        </div>
                        <div class="seng_hidden_error">
                            <h4 class="error">
                            </h4>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="javascript:custom_access_lite_closemodal()"><?php _e('Close'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function createPagina($added = false, $values = array()) {
        require_once 'seng_gebruiker.php';
        if (!current_user_can('activate_plugins')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_access_lite_accounts';
        $results = $wpdb->get_results('SELECT * FROM ' . $table_name . ' WHERE active = 1', ARRAY_A);
        $array_gebruikers = array();
        foreach ($results AS $result) {
            $array_gebruikers[] = new Seng_Gebruiker($result);
        }
        ?>
        <div class="custom_access_lite-over_banner">
            <div class="custom_access_lite-banner">
                <div class="custom_access_lite-inner_banner">
                    <div class="banner-content">
                        <div class="container-fluid">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-2 col-xs-2">
                                        <img class="custom_access_lite-logo_img" src="<?php echo plugins_url('../logo.png', __FILE__);?>" alt="Logo S&G"/>
                                    </div>
                                    <div class="col-lg-6 col-md-8 col-sm-10 col-xs-10">
                                        <h3 class="custom_access_lite-plugin_title_header"><?php _e("WP - Custom Access Lite")?></h3>
                                        <h4 class="custom_access_lite-subtitle_header"><?php echo _e("User Management"); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 column">
                    <div class="col-md-12 col-sm-12">
                        <div class="row">
                            <button class="btn btn-primary custom_access_lite-new_user_btn" type="button" data-toggle="modal" data-target="#custom_access_lite-register_modal"><?php _e("Register user");?></button>
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <p class="small"><?php echo _e("Edit or delete existing ones")?></p>
                                    <i class="glyphicon glyphicon-question-sign custom_access_lite-user_info_btn" data-toggle="modal" data-target="#custom_access_lite_help_information"></i>
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table id="exporttable"
                                               data-show-export="true"
                                               data-exportDataType="all"
                                               data-search="true"
                                               class="table table-striped persoontable table-hover"
                                               data-toggle="table"
                                               data-show-columns="true"
                                               data-pagination="true"
                                               data-export-types="['excel','json', 'csv']">
                                            <thead>
                                            <tr>
                                                <th data-visible="false" title="<?php _e('id'); ?>"><?php _e('id'); ?></th>
                                                <th data-sortable="true" title="<?php _e('username'); ?>"><?php _e('Username'); ?></th>
                                                <th data-sortable="true" title="<?php _e('email'); ?>"><?php _e('email'); ?></th>
                                                <th data-sortable="true" title="<?php _e('joined'); ?>"><?php _e('joined'); ?></th>
                                                <th data-class="custom_access_lite_center" class="custom_access_lite_center" data-sortable="true" title="<?php _e('level'); ?>"><?php _e('level'); ?></th>
                                                <th data-visible="false" title="<?php _e('full name'); ?>"><?php _e('full name'); ?></th>
                                                <th data-class="custom_access_lite_center" class="custom_access_lite_center" data-tableexport-display="none" title="<?php _e("Edit"); ?>"><?php _e("Edit"); ?></th>
                                                <th data-class="custom_access_lite_center" class="custom_access_lite_center" data-tableexport-display="none" title="<?php _e("Delete"); ?>"><?php _e("Delete"); ?></th>
                                            </tr>
                                            </thead>
                                            <tbody class="searchable">
                                            <?php
                                            foreach ($array_gebruikers AS $gebruiker) {

                                                echo    '<tr class="custom_access_lite_class_voor_id_' . htmlspecialchars($gebruiker->getId()) . '">' .
                                                    '<td data-field="idtb" class="seng_class_voor_id">' . htmlspecialchars($gebruiker->getId()) . '</td>' .
                                                    '<td data-field="usernametb" class="custom_access_lite_class_voor_username">' . htmlspecialchars($gebruiker->getName()) . '</td>' .
                                                    '<td data-field="emailtb" class="custom_access_lite_class_voor_email">' . htmlspecialchars($gebruiker->getEmail()) . '</td>' .
                                                    '<td>' . htmlspecialchars($gebruiker->getJoined()) . '</td>' .
                                                    '<td class="custom_access_lite_center custom_access_lite_class_voor_level" data-field="leveltb">' . htmlspecialchars($gebruiker->getLevel()) . '</td>' .
                                                    '<td data-field="fullnametb" class="seng_class_voor_fullname">' . htmlspecialchars($gebruiker->getFullName()) . '</td>' .
                                                    "<td data-tableexport-display=\"none\">" .
                                                    "<button type='button' onClick='javascript:custom_access_lite_get_info_to_edit(" . htmlspecialchars($gebruiker->getId()) . ")' class='btn btn-primary'><i class='glyphicon glyphicon-pencil'></i></button>" .
                                                    "</td>" .
                                                    "<td data-tableexport-display=\"none\"><button type='button' class='btn btn-danger custom_access_lite_delete' data-id-target='" . htmlspecialchars($gebruiker->getId()) . "' data-confirm='" . __('Are you sure to delete this item?') . "'><i class='glyphicon glyphicon-trash'></i></button></td>".
                                                    '</tr>';
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="custom_access_lite-register_modal" role="dialog" aria-labelledby="custom_access_lite-register_modal_label">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="custom_access_lite-register_modal_label"><?php echo _e("Register a user")?></h4>
                                    </div>
                                    <div class="modal-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <?php
                                            if (function_exists('wp_nonce_field'))
                                                wp_nonce_field('seng_admin_side_add_person', 'seng_noncical_field');
                                            ?>
                                            <div class="row">
                                                <div class='col-md-12 column'>
                                                    <!-- Text input-->
                                                    <div class="control-group">
                                                        <div class="form-group">
                                                            <label class="control-label" for="username"><?php _e('Username:'); ?></label><br />
                                                            <div class="controls">
                                                                <input id="username" name="username" type="text" placeholder="<?php _e('John Doe'); ?>" class="form-control" required="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label" for="email"><?php _e('Email:'); ?></label><br />
                                                            <div class="controls">
                                                                <input id="email" name="email" type="text" placeholder="a@b.com" class="form-control" required="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label" for="fullname"><?php _e('Full name:'); ?></label><br />
                                                            <div class="controls">
                                                                <input id="fullname" name="fullname" type="text" placeholder="<?php _e('Jane Doe'); ?>" class="form-control" required="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label" for="level"><?php _e('Acces level:'); ?></label><br />
                                                            <div class="controls">
                                                                <input id="level" name="level" type="number" min="0" max="9" placeholder="0" class="form-control" required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <button class="btn btn-primary" type="submit" name="register_admin" value="Register"><i class="fa fa-user-plus" aria-hidden="true"></i><?php _e('Register'); ?> </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- advanced modal -->
        <div id="custom_access_lite_options" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="javascript:custom_access_lite_closemodal()">&times;</button>
                        <h4 class="modal-title"><?php _e('Edit user'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label" for="custom_access_lite_edit_username"><?php _e('Username:'); ?><i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="<?php _e('change username of this user.'); ?>"></i></label>
                                    <div class="controls">
                                        <input id="custom_access_lite_edit_username" name="custom_access_lite_edit_username" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label" for="custom_access_lite_edit_email"><?php _e('Email:'); ?><i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="<?php _e('change username of this user.'); ?>"></i></label>
                                    <div class="controls">
                                        <input id="custom_access_lite_edit_email" name="custom_access_lite_edit_email" type="email" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label" for="custom_access_lite_edit_full"><?php _e('Full name:'); ?><i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="<?php _e('change username of this user.'); ?>"></i></label>
                                    <input id="custom_access_lite_edit_full" name="custom_access_lite_edit_full" type="text" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label" for="custom_access_lite_edit_level"><?php _e('Acces level:'); ?><i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="<?php _e('change username of this user.'); ?>"></i></label>
                                    <div class="controls">
                                        <input id="custom_access_lite_edit_level" name="custom_access_lite_edit_level" type="number" min="0" max="9" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="form-group">
                                    <input id="custom_access_lite_edit_id" name="custom_access_lite_edit_id" type="text" class="hidden form-control"/>
                                    <div class="custom_access_lite-float_button_div">
                                        <button type="button" class="btn btn-primary custom_access_lite-modal_button_float_right" onclick="javascript:custom_access_lite_checkValues_user()"><?php _e('Save'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary custom_access_lite-modal_button_float_left" onclick="javascript:custom_access_lite_send_new_mail_pw()"><?php _e('send new password'); ?></button>
                        <button type="button" class="btn btn-default" onclick="javascript:custom_access_lite_closemodal()"><?php _e('Close'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <div id="custom_access_lite_help_information" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="javascript:custom_access_lite_closemodal()">&times;</button>
                        <h4 class="modal-title"><?php _e('Help information.'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            <?php _e('To add the log system to specific pages is really easy. Just add the following to a page: [CA_lite_loggedin level=1]. With this code, everyone with clearence level 1 or higher will be able to see it.'); ?>
                        </p>
                        <p>
                            <?php _e('if you wish to change the clearence level, just change the level=1 to for example level=5(fullcode is [CA_lite_loggedin level=5]), to allow only people with clearence level 5 or higher.'); ?>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="javascript:custom_access_lite_closemodal()"><?php _e('Close'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var seng_error_messages = {
                'id_not_a_number'       :   '<?php _e('id is not a number. If the error persists, contact maker.'); ?>',
                'id_does_not_exist'     :   '<?php _e('id does not exist in database. If the error persists, contact maker.'); ?>',
                'updating_went_wrong'   :   '<?php _e('updating went wrong, please try again. If the error persists, contact maker.'); ?>',
                'deleting_went_wrong'   :   '<?php _e('deleting went wrong, please try again. If the error persists, contact maker.'); ?>'
            };
        </script>
        <?php
    }

    public function createPaginaOptions() {
        if (!current_user_can('activate_plugins')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $options = get_option('CUSTOM_ACCESS_LITE_opties_array');
        $header_info = get_option('CUSTOM_ACCESS_LITE_email_options');
        if(isset($options['use_smtp_set'])){
            if($options['use_smtp_set'] == ''){
                $smtp_settings = [];
            }else{
                $smtp_settings = $options['use_smtp_set'];
            }
        }
        ?>
        <div class="custom_access_lite-over_banner">
            <div class="custom_access_lite-banner">
                <div class="custom_access_lite-inner_banner">
                    <div class="banner-content">
                        <div class="container-fluid">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-2 col-xs-2">
                                        <img class="custom_access_lite-logo_img" src="<?php echo plugins_url('../logo.png', __FILE__);?>" alt="Logo S&G"/>
                                    </div>
                                    <div class="col-lg-6 col-md-8 col-sm-10 col-xs-10">
                                        <h3 class="custom_access_lite-plugin_title_header"><?php _e("WP - Custom Access Lite")?></h3>
                                        <h4 class="custom_access_lite-subtitle_header"><?php _e("Options")?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content col-md-12">
            <div class="row">
                <div class="col-md-10 col-md-offset-1 column">
                    <div class="row">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <?php
                            if (function_exists('wp_nonce_field'))
                                wp_nonce_field('seng_admin_side_change_options', 'seng_will_specify_options');
                            ?>
                            <div class="row">
                                <div class='col-md-12 column'>
                                    <!-- Text input-->
                                    <div class="row control-group">
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="panel panel-primary custom_access_lite-min-height">
                                                <div class="panel-heading"><?php _e('Add recaptcha.(Recommended)') ?> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="<?php _e('sign up at https://www.google.com/recaptcha/admin and request your recaptcha for this site.'); ?>"></i></div>
                                                <div class="panel-body">
                                                    <div class="row">
                                                        <label class="control-label col-md-12" for="custom_access_lite_captcha_private" style="margin-top:20px;"><?php _e('Recaptcha private'); ?></label>
                                                        <div class="controls col-md-12">
                                                            <input id="" name="custom_access_lite_captcha_private" type="text" placeholder="captcha private here." class="form-control" <?php if(strLen($options['custom_access_lite_captcha_private']) > 4) echo 'value="' . $options['custom_access_lite_captcha_private'] . '"'; ?>>
                                                        </div>
                                                        <label class="control-label col-md-12" for="custom_access_lite_captcha_public" style="margin-top:20px;"><?php _e('Recaptcha public'); ?></label>
                                                        <div class="controls col-md-12">
                                                            <input id="" name="custom_access_lite_captcha_public" type="text" placeholder="captcha public here." class="form-control" <?php if(strLen($options['custom_access_lite_captcha_public']) > 4) echo 'value="' . $options['custom_access_lite_captcha_public'] . '"'; ?>>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                            <div class="panel panel-primary custom_access_lite-min-height">
                                                <div class="panel-heading"><?php _e('Security options') ?></div>
                                                <div class="panel-body custom_access_lite-panel-body-has-button">
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <div class="checkbox checkbox-primary checkbox-inline" style="margin-left:25px;">
                                                                <input name="custom_access_lite_uselogger" value="1" type="checkbox" id="custom_access_lite_uselogger" class="styled" <?php if($options['custom_access_lite_uselogger'] > 0) echo 'checked'; ?>>
                                                                <label class="control-label" for="custom_access_lite_uselogger">
                                                                    <?php _e('Safe log in the database?'); ?> <i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="<?php _e('saves several actions into database log, to be reviewed later.'); ?>"></i>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-12" <?php if($options['custom_access_lite_uselogger'] <= 0){echo 'style="display:none;"';} ?>>
                                                            <div class="">
                                                                <label class="custom_access_lite_control-label" for="level_logger">
                                                                    <?php _e('What to save in log?'); ?>
                                                                </label>
                                                                <select name="level_logger" id="custom_access_lite_level_logger">
                                                                    <option value="1" <?php if(array_key_exists('custom_access_lite_levellogger', $options) && $options['custom_access_lite_levellogger'] == 1){echo "selected";}?>><?php _e('everything'); ?></option>
                                                                    <option value="2" <?php if(array_key_exists('custom_access_lite_levellogger', $options) && $options['custom_access_lite_levellogger'] == 2){echo "selected";}?>><?php _e('just warnings and errors'); ?></option>
                                                                    <option value="3" <?php if(array_key_exists('custom_access_lite_levellogger', $options) && $options['custom_access_lite_levellogger'] == 3){echo "selected";}?>><?php _e('just information'); ?></option>
                                                                    if(array_key_exists('level_logger', $options))
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <button class="btn btn-primary custom_access_lite-bottom-right custom_access_lite_options_buttons" data-toggle="modal" data-target="#custom_access_lite_options">
                                                            <div class="">
                                                                <i class='glyphicon glyphicon-wrench'></i>
                                                                <?php _e('advanced'); ?>
                                                            </div>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="button" name="seng_accept_changes"  onclick="javascript:custom_access_lite_checkMainChanges()"><i class="fa fa-user-plus" aria-hidden="true"></i> <?php _e('save'); ?> </button>
                                </div>   
                            </div>								
                        </form> 
                    </div>
                </div>
            </div>
        </div>
          <!-- advanced modal -->
        <div id="custom_access_lite_options" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="javascript:custom_access_lite_closemodal()">&times;</button>
                        <h4 class="modal-title"><?php _e('Advanced security options'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="control-label" for="custom_access_lite_bcrypt_cost"><?php _e('Bcrypt cost'); ?><i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="<?php _e('Higher cost is more secure but also takes more time to compute.'); ?>"></i></label>
                            <div class="controls">
                                <input id="custom_access_lite_bcrypt_cost" name="custom_access_lite_bcrypt_cost" type="number" min="8" max="256" placeholder="0" class="form-control" value="<?php echo $options['BCRYPT_COST']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-primary center" onclick="javascript:custom_access_lite_checkValues('security')"><?php _e('Save'); ?></button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" onclick="javascript:custom_access_lite_closemodal()"><?php _e('Close'); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function createPaginaViewlogs(){
        if (!current_user_can('activate_plugins')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $options = get_option('CUSTOM_ACCESS_LITE_opties_array');
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_access_lite_logger';
        $results = $wpdb->get_results('SELECT * FROM ' . $table_name . ' ORDER BY `timestamp` DESC', ARRAY_A);
        $array_gebruikers = array();
        ?>
        <div class="custom_access_lite-over_banner">
            <div class="custom_access_lite-banner">
                <div class="custom_access_lite-inner_banner">
                    <div class="banner-content">
                        <div class="container-fluid">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-2 col-xs-2">
                                        <img class="custom_access_lite-logo_img" src="<?php echo plugins_url('../logo.png', __FILE__);?>" alt="Logo S&G"/>
                                    </div>
                                    <div class="col-lg-6 col-md-8 col-sm-10 col-xs-10">
                                        <h3 class="custom_access_lite-plugin_title_header"><?php $options['custom_access_lite_uselogger'] == 0 ? _e("Logger is disabled and won\'t save data.") : _e("WP - Custom Access Lite")?></h3>
                                        <h4 class="custom_access_lite-subtitle_header"><?php $options['custom_access_lite_uselogger'] == 0 ? "" : _e("Log"); ?></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content col-md-10 col-sm-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo _e("Log record");?></h3>
                </div>
                <div class="panel-body">
                    <div class="col-sm-12 column">
                        <div class="table-responsive">
                            <table id="exporttable"
                                   data-show-export="true"
                                   data-exportDataType="all"
                                   data-search="true"
                                   class="table table-striped persoontable table-hover"
                                   data-toggle="table"
                                   data-show-columns="true"
                                   data-pagination="true"
                                   data-export-types="['excel','json', 'csv']">
                                <thead>
                                <tr>
                                    <th data-sortable="true" title="<?php _e('user'); ?>"><?php _e('Username'); ?></th>
                                    <th data-sortable="true" title="<?php _e('description'); ?>"><?php _e('Description'); ?></th>
                                    <th data-sortable="true" title="<?php _e('page id'); ?>"><?php _e('Page id'); ?></th>
                                    <th data-sortable="true" title="<?php _e('ip'); ?>"><?php _e('IP'); ?></th>
                                    <th data-sortable="true" title="<?php _e('type'); ?>"><?php _e('Type'); ?></th>
                                    <th data-sortable="true" title="<?php _e('date'); ?>"><?php _e('Date'); ?></th>
                                </tr>
                                </thead>
                                <tbody class="searchable">
                                <?php
                                foreach ($results AS $result) {
                                    echo '<tr><td>' . $result['username'] . '</td>';
                                    echo '<td>' . $result['description'] . '</td>';
                                    echo '<td class="custom_access_lite_center">' . $result['pageid'] . '</td>';
                                    echo '<td>' . $result['ip'] . '</td>';
                                    echo '<td>' . $result['level'] . '</td>';
                                    echo '<td>' . $result['timestamp'] . '</td></tr>';
                                }
                                ?>
                                </tbody>
                            </table>

                        </div>
                        <div class="row">
                            <div class='col-sm-4'>
                                <button class='btn btn-primary seng-btn-log' id='custom_access_lite_empty_table_1' alt='delete information type entries' data-confirm='<?php _e('Are you sure you wish to delete all information type entries from the database?'); ?>'><?php _e('remove all informaton');?></button>
                            </div>
                            <div class='col-sm-4'>
                                <button class='btn btn-primary seng-btn-log' id='custom_access_lite_empty_table_2' alt='delete warning type entries' data-confirm='<?php _e('Are you sure you wish to delete all warning type entries from the database?'); ?>'><?php _e('remove all warnings');?></button>
                            </div>
                            <div class='col-sm-4'>
                                <button class='btn btn-primary seng-btn-log' id='custom_access_lite_empty_table_3' alt='delete all entries' data-confirm='<?php _e('Are you sure you wish to delete all entries from the database?'); ?>'><?php _e('remove all');?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

            </div>
        </div>
        <?php
    }
    
    public function createPaginaSetPages(){
        if (!current_user_can('activate_plugins')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $options = get_option('CUSTOM_ACCESS_LITE_Logger_pages');
        if($options['loginpage'] == '' || !is_numeric($options['loginpage'])){
            ?>
            <div class="custom_access_lite-over_banner">
                <div class="custom_access_lite-banner">
                    <div class="custom_access_lite-inner_banner">
                        <div class="banner-content">
                            <div class="container-fluid">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-2 col-xs-2">
                                            <img class="custom_access_lite-logo_img" src="<?php echo plugins_url('../logo.png', __FILE__);?>" alt="Logo S&G"/>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-10">
                                            <h3 class="custom_access_lite-plugin_title_header"><?php _e("WP - Custom Access Lite")?></h3>
                                            <h4 class="custom_access_lite-subtitle_header"><?php _e("Pages")?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content col-md-12">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1 column">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <div class="panel-title"><?php echo _e("Set your login page");?></div>
                                        <i class="glyphicon glyphicon-question-sign custom_access_lite_pages_info_btn" data-toggle="modal" data-target="#pages_modal"></i>
                                    </div>
                                    <div class="panel-body">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <?php
                                            if (function_exists('wp_nonce_field'))
                                                wp_nonce_field('seng_admin_side_add_login', 'seng_add_login_noncical');
                                            ?>
                                            <div class="form-group">
                                                <label class="control-label" for="seng_id_login_page"><?php _e('Id login page:'); ?></label>
                                                <?php wp_dropdown_pages(array('name' => 'seng_id_login_page', 'class' => 'form-control')); ?>
                                            </div>
                                            <div class="form-group">
                                                <button class="btn btn-primary" type="submit" name="seng_set_login_id"><i class="fa fa-user-plus" aria-hidden="true"></i> <?php _e('Save'); ?> </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" role="dialog" id="pages_modal" aria-labelledby="pages_modal_label">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h3 id="pages_modal_label" class="modal-title"><?php echo _e("Pages - Explanation");?></h3>
                            </div>
                            <div class="modal-body">
                                <p>
                                    <?php _e('Several things are needed to make this login with logger plugin to work. Firstly we need you to create a standard login page. It is very easy:'); ?>
                                </p>
                                <ol>
                                    <li><?php _e('create a page with the name you wish.'); ?></li>
                                    <li><?php _e('place the shorthand: [CA_lite_login_page] in the page.(optional)'); ?></li>
                                    <li><?php _e('Select the page in the dropdown.'); ?></li>
                                </ol>
                                <p>
                                    <?php _e('alternitavely add [CA_lite_get_id_page] to the page, save, go to the page, copy the number and fill it here in. Don\'t forget to remove it.'); ?>
                                </p>
                                <p>
                                    <?php _e('if you are wondering why we want the id of the page, it is because we wish to have as little impact on the speed of you\'re site, so we want to only load our css and js when we need to.'); ?>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php     
        }else{
            ?>
            <?php if($this->_updated){ ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Succesfully updated the pages.'); ?></p>
                </div>
            <?php } ?>
            <div class="custom_access_lite-over_banner">
                <div class="custom_access_lite-banner">
                    <div class="custom_access_lite-inner_banner">
                        <div class="banner-content">
                            <div class="container-fluid">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-lg-offset-2 col-md-2 col-md-offset-2 col-sm-2 col-xs-2">
                                            <img class="custom_access_lite-logo_img" src="<?php echo plugins_url('../logo.png', __FILE__);?>" alt="Logo S&G"/>
                                        </div>
                                        <div class="col-lg-6 col-md-8 col-sm-10 col-xs-10">
                                            <h3 class="custom_access_lite-plugin_title_header"><?php _e("WP - Custom Access Lite")?></h3>
                                            <h4 class="custom_access_lite-subtitle_header"><?php _e("Pages")?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content col-md-12">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <div class="panel-title"><?php echo _e("Set your login page or activation page");?></div>
                                    <i class="glyphicon glyphicon-question-sign custom_access_lite_pages_info_btn" data-toggle="modal" data-target="#pages_activation_modal"></i>
                                </div>
                                <div class="panel-body">
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <?php
                                        if (function_exists('wp_nonce_field'))
                                            wp_nonce_field('seng_admin_side_add_login', 'seng_add_login_noncical');
                                        ?>
                                        <div class="form-group">
                                            <label class="control-label" for="seng_id_login_page"><?php _e('Id login page:'); ?></label>
                                            <?php wp_dropdown_pages(array('name' => 'seng_id_login_page', 'class' => 'form-control', 'selected' => $options['loginpage'])); ?>
                                        </div>
                                        <!--<div class="form-group">
                                            <label class="control-label" for="seng_id_activate_page"><?php _e('Activation page:'); ?></label>
                                            <?php
                                           // if(array_key_exists('activationpage', $options)){ $selected = $options['activationpage']; }else{ $selected = 0;}
                                           // wp_dropdown_pages(array('name' => 'seng_id_activate_page', 'class' => 'form-control', 'selected' => $selected, 'show_option_none' => __('none selected')));
                                            ?>
                                        </div>-->
                                        <div class="form-group">
                                            <button class="btn btn-primary" type="submit" name="seng_set_login_id"><i class="fa fa-user-plus" aria-hidden="true"></i> <?php _e('Save'); ?> </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="pages_activation_modal" aria-labelledby="pages_activation_label" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h3 id="pages_activation_label" class="modal-title"><?php echo _e("Pages - Information");?></h3>
                            </div>
                            <div class="modal-body">
                                <p>
                                    <?php _e('if you are wondering why we want the id of the page, it is because we wish to have as little impact on the speed of you\'re site, so we want to only load our css and js when we need to.'); ?>
                                </p>
                                <p>
                                    <?php _e('you can find the id number of a page in the url when editing the page, the number after "post="'); ?>
                                </p>
                                <p>
                                    <?php _e('alternitavely add: "[CA_lite_get_id_page]" to the page, save, go to the page, copy the number and fill it here in. Don\'t forget to remove it.'); ?>
                                </p>
                                <p>
                                    <?php _e("To add the login form to your page, add the shorthand \"[CA_lite_login_page]\" to your page."); ?>
                                </p>
                                <p>
                                    <?php _e('if activation page isn\'t set, activation cannot be activated. Don\'t forget to add [shorthand_for_seng_activation] to the page.'); ?>
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          <?php
        }
    }
    
    public function changeOptionsPages($POST){
        if (!current_user_can('activate_plugins')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        $pages = array();
        $pages['loginpage'] = intval(htmlspecialchars($POST['seng_id_login_page']));
        if ( function_exists('wp_nonce_field') ) 
            check_admin_referer( 'seng_admin_side_add_login', 'seng_add_login_noncical');
//        if(isset($POST['seng_id_activate_page'])){
//            if(intval(htmlspecialchars($POST['seng_id_activate_page'])) > 0){
//                $pages['activationpage'] = intval(htmlspecialchars($POST['seng_id_activate_page']));
//            }
//        }
        if(isset($POST['seng_ids_other_pages'])){
            foreach($POST['seng_ids_other_pages'] AS $single){
                $pages[] = intval(htmlspecialchars($single));
            }
        }
        update_option('CUSTOM_ACCESS_LITE_Logger_pages', $pages);
        $this->_updated = true;
        return;
    }
}
