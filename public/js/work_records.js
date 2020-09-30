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
$( ".first_view_header .change_month" ).on('change',function() {
    if($(this).val() != undefined) {
        month = $(this).val().toLowerCase();
        var url = location.origin + '/work_records'+ '?date='+month;
        $.ajax({
            type: "GET",
            date: { 'date': month },
            url: url, 
            success: function(response) {
                $('tbody').load(url + " tbody tr");
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
$( ".second_view_header .change_month" ).on('change',function() {
    if($(this).val() != undefined) {
        month = $(this).val().toLowerCase();
        var url = location.origin + '/work_records_table'+ '?date='+month;
        $.ajax({
            type: "GET",
            date: { 'date': month },
            url: url, 
            success: function(response) {
                $('.main_work_records').load(url + " .main_work_records .second_view",function(){
                    $( ".td_izostanak:contains('GO')" ).each(function( index ) {
                        $( this ).addClass('abs_GO');
                    });
                    $( ".td_izostanak:contains('BOL')" ).each(function( index ) {
                        $( this ).addClass('abs_BOL');
                    });
                    $('table').show();
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
    $( ".td_izostanak:contains('GO')" ).each(function( index ) {
        $( this ).addClass('abs_GO');
    });
    $( ".td_izostanak:contains('BOL')" ).each(function( index ) {
        $( this ).addClass('abs_BOL');
    });
});
$( ".change_employee_work" ).on('change',function() {
    var value = $(this).val().toLowerCase();
    console.log(value);
    
    $("tbody tr").filter(function() {
        //$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        $(this).toggle($(this).hasClass(value));
    });
    if(value == '') {
        $("tbody tr").show();
    }
});