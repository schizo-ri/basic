var data_to_send=[];

/* modal checkbox */
/* $("#mySearch1").keyup( function() {
    var value = $(this).val().toLowerCase();
    $(".panel1").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
}); */

$('#mySearch_preparation').on('keyup',function() {
    var trazi = $( this ).val().toLowerCase();
    $('.open_project').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
    });
    $('.row_preparation_text').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
    });
    $('.form_preparation:visible').filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(trazi) > -1)
    });
});	
$('.clearable__clear').click(function(){
    $('#mySearch_preparation').val('');
    $('.open_project').show();

});
$('.select_employee').change(function(){
    var find = $( this ).val().toLowerCase();
  
    if(find == 'all' ) {
        find = '';
    } 
    console.log( find );
    if(find == ''){
        $('.open_project').show();
    } else {
        $('.open_project').filter(function() {
            $( this ).toggle( $( this ).find('.zaduzi_text').text().toLowerCase().indexOf(find) > -1);
        });
    }
});
/* $('.table_preparations .tbody').width($('.table_preparations .thead').width()); */

$('.update_preparation_employee').submit(function(e){
    e.preventDefault();
    var form = $( this );
    var form_data = form.serialize(); 
    var id =  form.attr('id');
    url = form.attr('action');
    var url_update = location.origin + '/preparations';   
    console.log(form_data);
    $.ajax({
        url: url,
        type: "post",
        data: form_data,
        success: function( response ) {
           /*  $('<div><div class="modal-header"><span class="img-error">Poruka</span></div><div class="modal-body"><div class="alert alert-success alert-dismissable">Podaci su upisani</div></div></div>').appendTo('body').modal();  */
            $( '.update_preparation_employee#' + id ).load(url_update + ' .update_preparation_employee#' + id + '>fieldset', function(){ 
                open_list();

            });
            $.getScript('/../restfulizer.js'); 
        }, 
        error: function(jqXhr, json, errorThrown) {
            $('<div><div class="modal-header"><span class="img-error">Poruka</span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><strong></strong>Problem kod spramanja podataka!</div></div></div>').appendTo('body').modal();
          
            $(".btn-submit").prop("disabled", false);
             console.log(jqXhr);
             if(jqXhr.responseJSON != undefined) {
                data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                'message':  jqXhr.responseJSON.message,
                'file':  jqXhr.responseJSON.file,
                'line':  jqXhr.responseJSON.line };
                $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + data_to_send.message + '</div></div></div>').appendTo('body').modal(); 

                $.ajax({
                    url: 'errorMessage',
                    type: "get",
                    data: data_to_send,
                    success: function( response ) {
                        console.log(response);
                    }, 
                    error: function(jqXhr, json, errorThrown) {
                        console.log(jqXhr.responseJSON); 
                        
                    }
                });
            }
        }
    });
    open_list();
});

open_list();

var body_height = $('body').height();
var position;
var checkbox_height;
function open_list () {
    var expanded = false;

    $('.showCheckboxes').click(function(){
        var this_checkboxes1 = $(this).next('.checkboxes1');
        
        if( $(this_checkboxes1).is(":visible")) {
            expanded = true;
        } else {
            expanded = false;
        }
        $('.checkboxes1').hide();
    
        if (! expanded) {
            this_checkboxes1.show();
            expanded = true;
        } 
        if( expanded ) {
            $(document).mouseup(function(e) {
                var container = $(".checkboxes1");
        
                // if the target of the click isn't the container nor a descendant of the container
                if (! container.is(e.target) && container.has(e.target).length === 0) 
                {
                    container.hide();
                }
            });
        }
        position = this_checkboxes1.offset().top;
        checkbox_height = this_checkboxes1.height();
        if(position + checkbox_height + 100 > body_height) {
            this_checkboxes1.css({"bottom": '100%', "top": "auto"});
        } 
    });

}