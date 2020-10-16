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