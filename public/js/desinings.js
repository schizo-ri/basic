$('.btn-delete.doc').click(function(e){
    e.preventDefault();
    var conf = confirm("Sigurno želiš obrisati dokumenat?");

    if (conf == true) {
        var file = $( this ).attr('title');
        console.log(file);
        $.ajax({
            method: "GET",
            url:  "delete_file",
            data: { file: file }
        })
        .done(function( msg ) {
            alert( "Dokumenat je obrisan");
            location.reload();
        }).fail(function() {
            alert( "Došlo je do problema, dokumenat nije obrisan" );
        });
    } else {
        $('.btn-delete').unbind();
    }
});

$('.update_preparation_employee').submit(function(e){
e.preventDefault();
var form = $( this );
var form_data = form.serialize(); 
var id =  form.attr('id');
url = form.attr('action');
var url_update = location.origin + '/designings';   
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

function open_list () {
    var expanded = false;
    var expanded2 = false;

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
                var container = this_checkboxes1;
        
                // if the target of the click isn't the container nor a descendant of the container
                if (! container.is(e.target) && container.has(e.target).length === 0) 
                {
                    container.hide();
                }
            });
        }
    });
    $('.collapsible').click(function(){
        var this_content = $(this).next('.content');
        
        if( $(this_content).is(":visible")) {
            expanded2 = true;
        } else {
            expanded2 = false;
        }
        this_content.hide();
    
        if (! expanded2) {
            this_content.show();
            expanded2 = true;
        } 
        if( expanded2 ) {
            $(document).mouseup(function(e) {
                var container = this_content;
        
                // if the target of the click isn't the container nor a descendant of the container
                if (! container.is(e.target) && container.has(e.target).length === 0) 
                {
                    container.hide();
                }
            });
        }
        console.log("expanded " + expanded);
    });
}