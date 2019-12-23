$.getScript( '/../js/filter_table.js');    

$('.more').click(function(){
    $( this ).siblings('.role').toggle();
    $( this ).hide();
    $( this ).siblings('.hide').show();
});
$('.hide').click(function(){
    $( this ).siblings('.role').hide();
    $( this ).siblings('.role._0').show();
    $( this ).siblings('.role._1').show();

    $( this ).siblings('.more').show();
    $( this ).hide();
});

$('.change_view').click(function(){
    $('.index_table_filter label #mySearchTbl').attr('id','mySearchElement');
    $('.index_table_filter label #mySearchElement').attr('onkeyup','mySearchElement()');

    $( ".change_view" ).toggle();
    $( ".change_view2" ).toggle();
    if($('.second_view').is(':visible')) {
        $('.second_view').css('display:none');
    } else {
        $('.second_view').css('display:flex');
    }
//	$('.second_view').toggle();		
    $('.table-responsive').toggle();		
});
$( ".change_view2" ).click(function() {
    $('.index_table_filter label #mySearchElement').attr('id','mySearchTbl');
    $('.index_table_filter label #mySearchTbl').attr('onkeyup','mySearchTable()');
    $( ".change_view" ).toggle();
    $( ".change_view2" ).toggle();
    if($('.second_view').is(':hidden')) {
        $('.second_view').css('display:flex');
    } else {
        $('.second_view').css('display:none');
    }
    $('.table-responsive').toggle();
});