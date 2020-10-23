$(function(){
    $.getScript( '/../js/filter_table.js');
});

$(function() {
    $( ".td_izostanak:contains('GO')" ).each(function( index ) {
        $( this ).addClass('abs_GO');
    });
    $( ".td_izostanak:contains('BOL')" ).each(function( index ) {
        $( this ).addClass('abs_BOL');
    });
});
