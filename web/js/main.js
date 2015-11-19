$(document).ready(function () {

    $( ".alert" ).on( "click", function() {
        $(this).fadeToggle("slow");
    });


    $(".socialImg").on( "mouseenter", function(){
        $( this ).stop().animate({
            marginTop:"0px"
        }, 500 );
    });

    $(".socialImg").on( "mouseout", function(){
        $( this ).stop().animate({
            marginTop:"10px"
        }, 500 );
    });

});