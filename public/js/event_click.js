$('.dates>li').click(function(){
    $('.dates>li').removeClass('active_date');
    $( this ).addClass('active_date');
    var this_li = $(this).attr('id');
    if(this_li) {
        var this_id = this_li.replace("li-",""); // selektirani datum
        
        $( ".comming_agenda > .agenda" ).each( (index, element) => {
            $(element).addClass('display_none');
            if($(element).attr('id') == this_id ) {
                $(element).removeClass('display_none');
            }
    
        });
    }
});
