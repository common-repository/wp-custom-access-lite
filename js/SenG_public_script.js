jQuery(document).ready(function($){
    $(".custom_access_lite-show_pass").on("click", function(){
       var showPassClass = $(this).find("i");
       if(showPassClass.hasClass("glyphicon-eye-open")){
           showPassClass.removeClass("glyphicon-eye-open").addClass("glyphicon-eye-close");
           $("#password").attr("type", "text");
       } else {
           showPassClass.removeClass("glyphicon-eye-close").addClass("glyphicon-eye-open");
           $("#password").attr("type", "password");
       }
    });
});