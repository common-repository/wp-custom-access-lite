jQuery(document).ready(function ($) {
    if ($(".glyphicon-info-sign").length > 0) {
        custom_access_lite_activateTooltip(".glyphicon-info-sign");
    }

    if ($(".button_tooltip").length > 0) {
        custom_access_lite_activateTooltip(".button_tooltip");
    }
    
    if ($('#custom_access_lite_uselogger').length > 0){
        if($('#custom_access_lite_uselogger').is(':checked')){
            $('#custom_access_lite_level_logger').parent().parent().show();
        }
        $('#custom_access_lite_uselogger').change(function(){
            if($('#custom_access_lite_uselogger').is(':checked')){
                $('#custom_access_lite_level_logger').parent().parent().show();
            }else{
                $('#custom_access_lite_level_logger').parent().parent().hide();
            }
        });
    }
    
    if($('.custom_access_lite_delete').length > 0){
        custom_access_lite_deletelinks();
    }
    
    if($('#custom_access_lite_accept_cookies').length > 0){
        $('#custom_access_lite_send_button').disable(true);
        $('#custom_access_lite_accept_cookies').on('change', function(){
           if($(this).is(':checked')){
               $('#custom_access_lite_send_button').disable(false);
           }else{
               $('#custom_access_lite_send_button').disable(true);
           }
        });
    }
    
    if($('.custom_access_lite_copy_to_clipboard').length > 0){
        $('.custom_access_lite_copy_to_clipboard').on('click', function(){
            custom_access_lite_copy_stuff();
        });
    }
    
    if($('.custom_access_lite_options_buttons').length > 0){
        $('.custom_access_lite_options_buttons').on('click', function(){
            event.preventDefault();
        });
    }
    
    if($('#custom_access_lite_empty_table_1').length > 0){
        $('#custom_access_lite_empty_table_1').on('click', function(){
            if(event.preventDefault){
                event.preventDefault();
            }else{
                event.returnValue = false;
            }
            var choice = confirm(this.getAttribute('data-confirm'));
            if (choice) {
                custom_access_lite_send_confirm_info(1);
            }
        });
    }
    if($('#custom_access_lite_empty_table_2').length > 0){
        $('#custom_access_lite_empty_table_2').on('click', function(){
            if(event.preventDefault){
                event.preventDefault();
            }else{
                event.returnValue = false;
            }
            var choice = confirm(this.getAttribute('data-confirm'));
            if (choice) {
                custom_access_lite_send_confirm_info(2);
            }
        });
    }
    if($('#custom_access_lite_empty_table_3').length > 0){
        $('#custom_access_lite_empty_table_3').on('click', function(){
            if(event.preventDefault){
                event.preventDefault();
            }else{
                event.returnValue = false;
            }
            var choice = confirm(this.getAttribute('data-confirm'));
            if (choice) {
                custom_access_lite_send_confirm_info(3);
            }
        });
    }
});

function custom_access_lite_send_confirm_info(id) {
    if (!custom_access_lite_js_isnumeric(id)) {
        return custom_access_lite_add_error('error...');
    }
    var value = {'step': id};
    var data = {'name': 'update_table', 'value': value};
    //jQuery('body').addClass('wp-ca-lite-loading');
    jQuery.post(
        ajaxurl,
        {
            'action': 'custom_access_lite_handle_ajax',
            'data': data
        },
        function (response) {
            //jQuery('body').removeClass('wp-ca-lite-loading');
            response = JSON.parse(response);
            if (response.success == 'success') {
                location.reload();
            } else {
                custom_access_lite_add_error(response.error);
            }
        }
    );
}

jQuery.fn.extend({
    disable: function(state) {
        return this.each(function() {
            this.disabled = state;
        });
    }
});

function custom_access_lite_closemodal() {
    if (jQuery('#custom_access_lite_options').length > 0 && jQuery('#custom_access_lite_options').hasClass('in')) {
        jQuery('#custom_access_lite_options').modal('hide');
    }
    if (jQuery('#custom_access_lite_help_information').length > 0 && jQuery('#custom_access_lite_help_information').hasClass('in')) {
        jQuery('#custom_access_lite_help_information').modal('hide');
    }
    return;
}
/*
 * activate tooltips
 */
function custom_access_lite_activateTooltip(target) {
    jQuery(target).each(function () {
        jQuery(this).tooltip({
            animation: true
        });
    });
}
/*
 *  check Values
 */

function custom_access_lite_checkValues(string){
    var ajaxcall = false;
    if(string === 'security'){
        var seng_Bcrypt = jQuery('#custom_access_lite_bcrypt_cost').val();
        var value = {'seng_Bcrypt' :  seng_Bcrypt};
        var data = {'name' : string, 'value' : value};
        if(custom_access_lite_js_isnumeric(seng_Bcrypt)){
            ajaxcall = true;
        }
    }/*else if(string === 'emailoptions'){
        var value = {
                    'fromname' : jQuery('#seng_email_from').val(),
                    'fromemail': jQuery('#seng_email_email').val(),
                    'replyto'  : jQuery('#seng_email_reply_to').val()
                    };
        var data = {'name' : string, 'value' : value};
        if(value['fromname'].length > 1 || value['fromemail'].length > 6 ||  value['replyto'].length > 6){
            ajaxcall = true;
        }
    }*/
    if(ajaxcall){
        //jQuery('body').addClass('wp-ca-lite-loading');
        jQuery.post(
            ajaxurl, 
            {
                'action': 'custom_access_lite_handle_ajax',
                'data':   data
            }, 
            function(response){
                //jQuery('body').removeClass('wp-ca-lite-loading');
                response = JSON.parse(response);
                if(response['succes'] === 'succes' && string === 'security'){
                    custom_access_lite_closemodal()
                    custom_access_lite_add_succes('success');
                }else if(response['succes'] === 'succes'){
                    custom_access_lite_test_email();
                }else{
                    custom_access_lite_add_error(response['error']);
                }
            }
        );
    }else{
        //<@TODO> create error handler
    }
}

function custom_access_lite_test_email(){
    //jQuery('body').addClass('wp-ca-lite-loading');
    jQuery.post(
        ajaxurl, 
        {
            'action': 'custom_access_lite_handle_ajax',
            'data':   {'name': 'seng_test', 'value': ''}
        }, 
        function(response){
            //jQuery('body').removeClass('wp-ca-lite-loading');
            custom_access_lite_closemodal();
            response = JSON.parse(response);
            if(response['succes'] === 'succes'){
                custom_access_lite_add_succes('success');
            }else{
                custom_access_lite_add_error(response['error']);
            }
        }
    );
} 

function custom_access_lite_checkMainChanges(){
    var captcha_private = ''; var captcha_public = ''; var use_logger = true; var allow_cookie = true; var level_logger = 1;
    if(jQuery('input[name="custom_access_lite_captcha_private"]').val().length > 5 && jQuery('input[name="custom_access_lite_captcha_public"]').val().length > 5){
        captcha_private = jQuery('input[name="custom_access_lite_captcha_private"]').val();
        captcha_public = jQuery('input[name="custom_access_lite_captcha_public"]').val();
    }
    use_logger = jQuery('input[name="custom_access_lite_uselogger"]').is(':checked');
    level_logger = jQuery('select[name="level_logger"]').val();
    allow_cookie = true;//jQuery('input[name="seng_use_cooky"]').is(':checked');
    var value =  {
                    'captcha_private' : captcha_private, 
                    'captcha_public' : captcha_public, 
                    'use_logger' : use_logger,
                    'level_logger' : level_logger,
                    'allow_cookie' : allow_cookie
                };
    var data =  {'name' : 'global', 'value' : value };
    //jQuery('body').addClass('wp-ca-lite-loading');
    jQuery.post(
            ajaxurl, 
            {
                'action': 'custom_access_lite_handle_ajax',
                'data':   data
            }, 
            function(response){
                //jQuery('body').removeClass('wp-ca-lite-loading');
                custom_access_lite_closemodal();
                response = JSON.parse(response);
                if(response['succes'] === 'succes'){
                    custom_access_lite_add_succes('success');
                }else{
                    custom_access_lite_add_error(response['error']);
                }
            }
        );
}

function custom_access_lite_js_isnumeric(obj){
    return !jQuery.isArray( obj ) && (obj - parseFloat( obj ) + 1) >= 0;
}

function custom_access_lite_get_info_to_edit(id){
    if(!custom_access_lite_js_isnumeric(id)){
        return custom_access_lite_add_error(seng_error_messages['id_not_a_number']);
    }
    var value = {'id' :  id};
    var data = {'name' : 'get_info', 'value' : value};
    //jQuery('body').addClass('wp-ca-lite-loading');
    jQuery.post(
            ajaxurl, 
            {
                'action': 'custom_access_lite_handle_ajax',
                'data':   data
            },
            function(response){
                //jQuery('body').removeClass('wp-ca-lite-loading');
                response = JSON.parse(response);
                if(response.success === 'success'){
                    jQuery('input[name="custom_access_lite_edit_username"]').val(response.user['username']);
                    jQuery('input[name="custom_access_lite_edit_email"]').val(response.user['email']);
                    jQuery('input[name="custom_access_lite_edit_full"]').val(response.user['fullname']);
                    jQuery('input[name="custom_access_lite_edit_level"]').val(response.user['level']);
                    jQuery('input[name="custom_access_lite_edit_id"]').val(response.user['id']);
                    jQuery('#custom_access_lite_options').modal('show');
                }else{
                    custom_access_lite_add_error(response.error);
                }
            }
        );
}

function custom_access_lite_checkValues_user(){
    if(jQuery('#custom_access_lite_error_message_to_remove').length > 0)
        jQuery('#custom_access_lite_error_message_to_remove').remove();
    var username = jQuery('input[name="custom_access_lite_edit_username"]').val();
    var email = jQuery('input[name="custom_access_lite_edit_email"]').val();
    var fullname = jQuery('input[name="custom_access_lite_edit_full"]').val();
    var level = jQuery('input[name="custom_access_lite_edit_level"]').val();
    var id = jQuery('input[name="custom_access_lite_edit_id"]').val();
    if(parseInt(level) < 1 || parseInt(level) > 9){
        jQuery('input[name="custom_access_lite_edit_level"]').toggleClass('custom_access_lite_has_error');
    }else{
        if(jQuery('input[name="custom_access_lite_edit_level"]').hasClass('custom_access_lite_has_error')){
            jQuery('input[name="custom_access_lite_edit_level"]').toggleClass('custom_access_lite_has_error');
        }
    }
    if(username.length < 1 || username.length > 50){
        jQuery('input[name="custom_access_lite_edit_username"]').toggleClass('custom_access_lite_has_error');
    }else{
        if(jQuery('input[name="custom_access_lite_edit_username"]').hasClass('custom_access_lite_has_error')){
            jQuery('input[name="custom_access_lite_edit_username"]').toggleClass('custom_access_lite_has_error');
        }
    }
    var value = {'id' :  id, 'username' : username, 'email' : email, 'fullname' : fullname, 'level' : level};
    var data = {'name' : 'handle_userinfo', 'value' : value};
    //jQuery('body').addClass('wp-ca-lite-loading');
    jQuery.post(
            ajaxurl, 
            {
                'action': 'custom_access_lite_handle_ajax',
                'data':   data
            }, 
            function(response){
                //jQuery('body').removeClass('wp-ca-lite-loading');
                response = JSON.parse(response);
                if(response.success == 'success'){
                    custom_access_lite_closemodal();
                    var target = jQuery('.custom_access_lite_class_voor_id_' + id);
                    target.find('.custom_access_lite_class_voor_username').text(username);
                    target.find('.custom_access_lite_class_voor_email').text(email);
                    target.find('.custom_access_lite_class_voor_level').text(level);
                }else{
                    custom_access_lite_add_error_modal(response.error);
                }
            }
        );
}

function custom_access_lite_add_error_modal(string){
    if(jQuery('#custom_access_lite_error_message_to_remove').length > 0)
        jQuery('#custom_access_lite_error_message_to_remove').remove();
    jQuery('.modal.in .modal-body').prepend('<div id="custom_access_lite_error_message_to_remove" style="width:100%;height:50px;border-radius:15px;border:1px solid #4c0000; background-color:#e5e5ff; text-align:center; padding-top:13px;margin:10px 0;color:red;">' + string + '</div>');
    return;
}
function custom_access_lite_add_error(string){
    if(jQuery('#custom_access_lite_error_message_to_remove').length > 0)
        jQuery('#custom_access_lite_error_message_to_remove').remove();
    jQuery('.content.col-md-12').prepend('<div id="custom_access_lite_error_message_to_remove" style="width:100%;height:50px;border-radius:15px;border:1px solid #4c0000; background-color:#e5e5ff; text-align:center; padding-top:13px;margin:10px;color:red;">' + string + '</div>');
    return;
}

function custom_access_lite_add_succes(string){
    if(jQuery('#custom_access_lite_error_message_to_remove').length > 0)
        jQuery('#custom_access_lite_error_message_to_remove').remove();
    jQuery('.content.col-md-12').prepend('<div id="custom_access_lite_error_message_to_remove" style="width:100%;height:50px;border-radius:15px;border:1px solid #4c0000; background-color:#e5e5ff; text-align:center; padding-top:13px;margin:10px;color:green;">' + string + '</div>');
    return;
}

function custom_access_lite_deletelinks(){
    var deleteLinks = document.querySelectorAll('.custom_access_lite_delete');
    for (var i = 0; i < deleteLinks.length; i++) {
        deleteLinks[i].addEventListener('click', function (event) {
            event.preventDefault();
            var choice = confirm(this.getAttribute('data-confirm'));
            if (choice) {
                custom_access_lite_get_ready_to_delete(this.getAttribute('data-id-target'));
            }
        });
    }
}

function custom_access_lite_get_ready_to_delete(id){
    var value = {'id' :  id};
    var data = {'name' : 'delete_user_info', 'value' : value};
    //jQuery('body').addClass('wp-ca-lite-loading');
    jQuery.post(
        ajaxurl, 
        {
            'action': 'custom_access_lite_handle_ajax',
            'data':   data
        }, 
        function(response){
            //jQuery('body').removeClass('wp-ca-lite-loading');
            response = JSON.parse(response);
            if(response.success == 'success'){
                jQuery('.custom_access_lite_class_voor_id_' + id).remove();
            }else{
                custom_access_lite_add_error(response.error);
            }
        }
    );
}

function custom_access_lite_send_new_mail_pw(){
    var id = jQuery('#custom_access_lite_edit_id').val();
    //jQuery('body').addClass('wp-ca-lite-loading');
    jQuery.post(
        ajaxurl, 
        {
            'action': 'custom_access_lite_handle_ajax',
            'data':   {'name': 'send_new_pw', 'value': id}
        }, 
        function(response){
            //jQuery('body').removeClass('wp-ca-lite-loading');
            response = JSON.parse(response);
            if(response.success == 'success'){
                custom_access_lite_add_succes('success');
                custom_access_lite_closemodal();
            }else{
                custom_access_lite_add_error_modal(response.error);
            }
        }
    );
}

function custom_access_lite_createShorthand(){
    var level = parseInt(jQuery('#custom_access_lite_level_shorthand').val());
    if(level > 0 && level <10){
        jQuery('.custom_access_lite_hidden_shorthand').show();
        jQuery('.custom_access_lite_target_shorthand').text('[CA_lite_loggedin level=' + level + ']');
    }
}

function custom_access_lite_copy_stuff(){
    var emailLink = document.querySelector('.custom_access_lite_target_shorthand');  
    var range = document.createRange();  
    range.selectNode(emailLink);  
    window.getSelection().addRange(range);
    try {  
        // Now that we've selected the anchor text, execute the copy command  
        var successful = document.execCommand('copy');  
        var msg = successful ? 'successful' : 'unsuccessful';  
    } catch(err) {  

    }

    // Remove the selections - NOTE: Should use
    // removeRange(range) when it is supported  
    window.getSelection().removeAllRanges();  
}