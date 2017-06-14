jQuery(document).ready(function(){

   jQuery(".weddingmodal a").click(function(){

          jQuery(".modal").css("display", "block");
         var imageSource =  jQuery(this).parent().prev().attr('src');

        jQuery(".modal img").attr('src', imageSource) ;

        return false;
   });

   jQuery(".close").click(function(){
    jQuery(".modal").css("display", "none");

   });


});