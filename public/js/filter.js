$( document ).ready(function() {  
	$('#mySearch').keyup(function() {
        var trazi = $( this ).val().toLowerCase();
     
        $('.employee').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
        });
	
    });	
    
    $('.clearable__clear').click(function(){
        $('#mySearch').val('');
        $('.employee').show();
    });
});

function mySearchTable() {
    $("#mySearchTbl").keyup(function() {
        var value = $(this).val().toLowerCase();
        
        $("#index_table tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $('.clearable__clear').click(function(){
        $('#mySearchTbl').val('');
        $('#index_table tbody tr').show();
    });
}
var trazi_status;
var text;


function mySearch_preparation() {
    
    $('#mySearch_preparation').keyup(function() {
        text = $('#mySearch_preparation').parent().siblings('.show_inactive').text();
        console.log(text);
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
    
    $('.clearable__clear').click(function(){
        $('#mySearch_preparation').val('');
        $('.row_preparation_text' + trazi_status).show();
 //       $('.form_preparation').hide();
    });
}

function mySearchList () {
    $('#mySearchList').keyup(function() {
        var trazi = $( this ).val().toLowerCase();

        $('.row_preparation_text').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
        });
    });
    $('.clearable__clear').click(function(){
        $('#mySearchList').val('');
        $('.row_preparation_text').show();
    });
    
}