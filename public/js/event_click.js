$('.dates>li').on('click',function(){
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
   //      $('.calendar .comming_agenda').height(placeholder_height + 60);
      $('.placeholder_cal >p').css('line-height',placeholder_height + 'px' );
    } else {
        $('.comming_agenda .placeholder').hide();
    }
});

$('.shortcuts_container .shortcut').on('click',function(){
    $('.icon_delete').toggle();
});

$('.shortcuts_container .new_open').on('click',function(){
    $('<div><div class="modal-header">Novi prečac</div><div class="modal-body" style="padding-top: 20px"><p>Da biste dodali prečac otvorite stranicu koju želite i u gornjem desnom kutu pronađite link za spremanje "Prečaca"</p><p>Ukoliko Prečac već postoji na stranici imate mogućnost promijeniti naslov prečaca</p></div></div>').modal();
});
$('.shortcuts_container .open_new_shortcut').on('click',function(){
    $('<div><div class="modal-header">Novi prečac</div><div class="modal-body" style="padding-top: 20px"><p>Da biste dodali prečac otvorite stranicu koju želite i u gornjem desnom kutu pronađite link za spremanje "Prečaca"</p><p>Ukoliko Prečac već postoji na stranici imate mogućnost promijeniti naslov prečaca</p></div></div>').modal();
});

 var shortcuts_container_width = $('.shortcuts_container').first().width();
var shortcut_box_width = shortcuts_container_width / 6;
/* $('.shortcut_box').width(shortcut_box_width-15); */

$('#right-button-scroll').on('click',function(event) {
    event.preventDefault();
    $('.shortcuts_container .profile_images').animate({
        scrollLeft: "+=127px"
    }, "slow");
    $('.profile_images .scroll_left').show();
});
$('#left-button-scroll').on('click',function(event) {
    event.preventDefault();
    $('.shortcuts_container .profile_images').animate({
        scrollLeft: "-=127px"
    }, "slow");
});