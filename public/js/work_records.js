var month;
var url;
var table = $('table.display');
$('.second_view_header .change_month').on('change',function() {
    if($(this).val() != undefined) {
        date = $(this).val();
        url = location.href + '?date='+date;
        console.log(url);
        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
              /*   $('tbody').load(url + " tbody th"); */
               
                $('.main_work_records').load(url + " .main_work_records .second_view",function(){
                    $('#loader').remove();
                    $( ".td_izostanak:not(:empty)" ).each(function( index ) {
                        $( this ).addClass('abs_'+  $.trim($( this ).text()));
                       
                    });
                    /* $.getScript('/../js/datatables.js'); */
                    $('.show_button').on('click',function () {
                        $('.dt-buttons').toggle();		
                    })
                    $('.change_month').find('option[value="'+date+'"]').attr('selected',true);
                });
            },
            error: function(jqXhr, json, errorThrown) {
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };
                $.ajax({
                    url: 'errorMessage',
                    type: "get",
                    data: data_to_send,
                    success: function( response ) {
                        $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                    }, 
                    error: function(jqXhr, json, errorThrown) {
                        console.log(jqXhr.responseJSON); 
                    }
                });
            }
        });
    }
});	

$(function() {
    $( ".td_izostanak:not(:empty)" ).each(function( index ) {
        $( this ).addClass('abs_'+  $.trim($( this ).text()));
    });
});