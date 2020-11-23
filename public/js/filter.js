$(function() { 
	$('#mySearch').on('keyup',function() {
        var trazi = $( this ).val().toLowerCase();
     
        $('.employee').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
        });
	
    });	
    
    $('.clearable__clear').on('click',function(){
        $('#mySearch').val('');
        $('.employee').show();
    });

    $('.filter_designer').on('change',function() {
		var user =  $(this).val().toLowerCase();
		console.log(user);
		if(user == 'all' ) {
			user = '';
		} 
	
		if(user == ''){
			$('tbody tr').show();
		} else {
			$('.designers_list tbody tr').filter(function() {
				$(this).toggle($(this).find('.designer_select').text().toLowerCase().indexOf(user) > -1);
            });
            $('.timeline tbody tr').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(user) > -1);
			});
		}

    });	
    

});
function mySearchTable() {
    $("#mySearchTbl").on('keyup',function() {
        var value = $(this).val().toLowerCase();
        $("table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $('.clearable__clear').on('click',function(){
        $('#mySearchTbl').val('');
        $('table tbody tr').show();
    });
}

var trazi_status;
var text;

function mySearch_preparation() {

    $('#mySearch_preparation').on('keyup',function() {
        text = $('.show_inactive').text();
        var trazi = $( this ).val().toLowerCase();
        if(text == 'Prikaži neaktivne') {
            trazi_status = '.active';
        } else {
            trazi_status = '.inactive';
        }

        $('.row_preparation_text' + trazi_status).filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
        });
        $('.form_preparation:visible').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
        });
    });	
    
    $('.clearable__clear').on('click',function(){
        $('#mySearch_preparation').val('');
        $('.row_preparation_text' + trazi_status).show();
    //       $('.form_preparation').hide();
    });
}

function mySearchList () {
    $('#mySearchList').on('keyup',function() {
        var trazi = $( this ).val().toLowerCase();

        $('.row_preparation_text').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
        });
    });
    $('.clearable__clear').on('click',function(){
        $('#mySearchList').val('');
        $('.row_preparation_text').show();
    });
    
}