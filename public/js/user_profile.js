$( function () {
    var div_width = $( '.profile_images').width();
    var all_width = 0;

    $( ".profile_images > .profile_img" ).each( (index, element) => {
        all_width += $(element).width();
    });
    if(all_width > div_width ) {
        $('.profile_images .scroll_right').show();
    }

    $('#right-button').click(function() {
        event.preventDefault();
        $('.profile_images').animate({
            scrollLeft: "+=200px"
        }, "slow");
        $('.profile_images .scroll_left').show();
        
    });

    $('#left-button').click(function() {
        event.preventDefault();
        $('.profile_images').animate({
            scrollLeft: "-=115px"
        }, "slow");
        if($('.profile_images').scrollLeft() < 115 ) {
            $('.profile_images .scroll_left').hide();
        } else {
            $('.profile_images .scroll_left').show();
        }
    });
    
});