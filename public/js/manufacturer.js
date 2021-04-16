
submit_manufacturer();
delete_manufacturer();

function submit_manufacturer() {
    $('form.manufacturer_store').on('submit',function(e){
        e.preventDefault();
        var form = $('form.manufacturer_store');
        var form_data = form.serialize(); 
        var url = $(form).attr('action');
    
        $.ajax({
            url: url,
            type: "post",
            data: form_data,
            success: function( response ) {
                $.modal.close();
                $('.manufacturers_body').load(location.origin + '/manufacturers .manufacturers_body table', function(){
                    submit_manufacturer();
                    delete_manufacturer();
                    $.getScript( '/../restfulizer.js');
                });
            }, 
            error: function(jqXhr, json, errorThrown) {
                $(".btn-submit").prop("disabled", false);
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };
    
                console.log(data_to_send); 
           
                if(url.includes("users") && errorThrown == 'Unprocessable Entity' ) {
                    alert(email_unique);
                }  else {
                    $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + "Podaci nisu spremljeni, došlo je do greške: " + data_to_send.message + '</div></div></div>').appendTo('body').modal();
                }
            }
        });
    });
}

function delete_manufacturer() {
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        if (! confirm("Sigurno želiš obrisati proizvođača?")) {
            return false;
        } else {
            
            url_delete = $( this ).attr('href');
            url_load = location.origin + '/manufacturers';
            token = $( this ).attr('data-token');
            
            $.ajaxSetup({
                headers: {
                    '_token': token
                }
            });
            $.ajax({
                url: url_delete,
                type: 'POST',
                data: {_method: 'delete', _token :token},
                beforeSend: function(){
                    $('body').prepend('<div id="loader"></div>');
                },
                success: function(result) {
                    $('.manufacturers_body tbody').load(url_load + " tbody>tr",function(){
                        $('#loader').remove();
                        submit_manufacturer();
                        delete_manufacturer();
                        $.getScript( '/../restfulizer.js');
                    });
                },
                error: function(jqXhr, json, errorThrown) {
                    console.log(jqXhr.responseJSON.message);
                }
            });
        }
    });
}