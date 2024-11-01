<?php
namespace custom\access\lite;
defined('ABSPATH') or die('No script kiddies please!');


class SenG_Create_Login_Class {
    
    public function __construct(){
        
    }
    
    public function createLoginPage($user){
        if(!$user->isloggedin()){
        ?>
        <div class="panel panel-default" >
            <div class="panel-heading">
                <div class="panel-title text-center"><?php _e('Login page'); ?></div>
            </div>     

            <div class="panel-body" >

                <form name="form" id="form" class="form-horizontal" enctype="multipart/form-data" method="POST">
                   <?php
                    if (function_exists('wp_nonce_field'))
                        wp_nonce_field('seng_user_side_try_login', 'seng_try_login_noncical');
                    ?>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="user" type="text" class="form-control" name="username" value="" placeholder="<?php _e('Username');?>">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="password" type="password" class="form-control" name="password" placeholder="<?php _e('Password');?>">
                        <span class="input-group-addon custom_access_lite-show_pass" style="cursor:pointer;"><i class="glyphicon glyphicon-eye-open"></i></span>
                    </div>
                    <?php
                        if(CUSTOM_ACCESS_LITE_USE_CAPTCHA){
                    ?>
                    <div class='col-md-5 column' style="margin-top:20px; margin-left:-15px;">
                        <div class="g-recaptcha" data-sitekey="<?php echo CUSTOM_ACCESS_LITE_PUBLIC; ?>"></div>
                    </div>   
                    <?php
                        }
                    ?>
                    <br/>
                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button type="submit" id="custom_access_lite_send_button" href="#" class="btn btn-primary pull-right" name="seng_logger_login"><i class="glyphicon glyphicon-log-in"></i> Login</button>                          
                        </div>
                    </div>

                </form>     

            </div>                     
        </div>
        <?php 
            if(CUSTOM_ACCESS_LITE_USE_CAPTCHA){
                ?>
                     <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                <?php
            }
        }else{
        ?>
            <div class="panel panel-success" >
                <div class="panel-heading">
                    <div class="panel-title text-center"><?php _e('Already logged in.'); ?></div>
                </div> 
                <div class="panel-body" >
                    <p>
                        <?php _e('You are already logged in. To log in with another account please logout first.'); 
                        /*Je bent al ingelogd. Deze website ondersteund meerdere keren inloggen niet. Als je in wilt loggen met een ander account op deze computer, log dan eerst uit. 
                            Dat kan door hieronder op de logoutknop te klikken. Ben je hier per ongeluk terecht gekomen, wees dan niet bang je kan gewoon in het menu naar een andere pagina gaan.*/?>
                    </p>
                    <form action="" method="post">
                        <input type="submit" class="btn btn-default pull-right" id="logout" name="seng_must_logout" value="<?php _e('Logout'); ?>" style="margin-top: 25px;">
                    </form>
                </div>
            </div>
            <?php
        }
    }
    
    public function loginSuccesfull(){
        ?>
        <div class="panel panel-success" >
            <div class="panel-heading">
                <div class="panel-title text-center"><?php _e('You are logged in.'); ?></div>
            </div> 
            <div class="panel-body" >
                <p>
                    <?php _e('You are logged in. To log in with another account please logout first.'); 
                    /*Je bent al ingelogd. Deze website ondersteund meerdere keren inloggen niet. Als je in wilt loggen met een ander account op deze computer, log dan eerst uit. 
                        Dat kan door hieronder op de logoutknop te klikken. Ben je hier per ongeluk terecht gekomen, wees dan niet bang je kan gewoon in het menu naar een andere pagina gaan.*/?>
                </p>
                <form action="" method="post">
                    <input type="submit" class="btn btn-default pull-right" id="logout" name="seng_must_logout" value="<?php _e('Logout'); ?>" style="margin-top: 25px;">
                </form>
            </div>
        </div>
        <?php
    }
    
    public function loginFailed($username){
        ?>
        <div class="panel panel-danger" >
            <div class="panel-heading">
                <div class="panel-title text-center"><?php _e('Wrong login credentials, please try again'); ?></div>
            </div>     

            <div class="panel-body" >

                <form name="form" id="form" class="form-horizontal" enctype="multipart/form-data" method="POST">
                   <?php
                    if (function_exists('wp_nonce_field'))
                        wp_nonce_field('seng_user_side_try_login', 'seng_try_login_noncical');
                    ?>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="user" type="text" class="form-control" name="username" value="<?php echo filter_var($username, FILTER_SANITIZE_SPECIAL_CHARS); ?>" placeholder="<?php _e('Username');?>">
                    </div>
                    <br/>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="password" type="password" class="form-control" name="password" placeholder="<?php _e('Password');?>">
                        <span class="input-group-addon custom_access_lite-show_pass" style="cursor:pointer;"><i class="glyphicon glyphicon-eye-open"></i></span>
                    </div>
                    <?php
                    if(CUSTOM_ACCESS_LITE_USE_CAPTCHA) {
                    ?>
                    <div class='col-md-5 column' style="margin-top:20px; margin-left:-15px;">
                        <div class="g-recaptcha" data-sitekey="<?php echo CUSTOM_ACCESS_LITE_PUBLIC; ?>"></div>
                    </div>
                    <?php
                    }
                    ?>
                    <br/>
                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button type="submit" id="custom_access_lite_send_button" href="#" class="btn btn-primary pull-right" name="seng_logger_login"><i class="glyphicon glyphicon-log-in"></i> Login</button>                          
                        </div>
                    </div>

                </form>     

            </div>                     
        </div>
        <?php 
            if(CUSTOM_ACCESS_LITE_USE_CAPTCHA){
                ?>
                     <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                <?php
            }
    }
}

