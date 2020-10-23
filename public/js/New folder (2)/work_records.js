/* $('.change_view').click(function(){
		
    $( ".change_view" ).toggle();
    $( ".change_view2" ).toggle();

    $('.second_view').css('display','block');
    $('main>.table-responsive').toggle();		
});
$( ".change_view2" ).click(function() {

    $( ".change_view" ).toggle();
    $( ".change_view2" ).toggle();
    
    $('.second_view').css('display','none');
    $('main>.table-responsive').toggle();
}); */

$(function(){
    $.getScript( '/../js/filter_table.js');
/*     $.getScript( '/../restfulizer.js'); */
});
var month;
var is_visible;
    var not_visible;
    $(function() {
    $( ".td_izostanak:contains('GO')" ).each(function( index ) {
        $( this ).addClass('abs_GO');
    });
    $( ".td_izostanak:contains('BOL')" ).each(function( index ) {
        $( this ).addClass('abs_BOL');
    });
});