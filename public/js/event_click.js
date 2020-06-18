/* $('.dates>li').click(function(){
    $('.dates>li').removeClass('active_date');
    $( this ).addClass('active_date');
    var this_li = $(this).attr('id');
    if(this_li) {
        var this_id = this_li.replace("li-",""); // selektirani datum
        $( ".comming_agenda > .agenda" ).each( (index, element) => {
            $(element).addClass('display_none');
            $(element).removeClass('show_agenda');
            if($(element).attr('id') == this_id ) {
                $(element).removeClass('display_none');
                $(element).addClass('show_agenda');
            }
        });
    }
    if(! $('.comming_agenda .agenda.show_agenda').length) {
        $('.comming_agenda .placeholder').show();
        var placeholder_height =  $('.placeholder img').height();
         $('.calendar .comming_agenda').height(placeholder_height + 60);
      $('.placeholder_cal >p').css('line-height',placeholder_height + 'px' );
    } else {
        $('.comming_agenda .placeholder').hide();
    }
});
 */