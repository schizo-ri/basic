var locale = $('.locale').text();
console.log("validate");
if(locale == 'hr') {
    validate_text = "Obavezno polje";
} else if( locale = 'en') {
    validate_text = "Required field";            
} else {
    validate_text = "Obavezno polje";
}   

$('.btn-submit').click(function(event){
    
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
   
    var validate = false;
    
    $( "textarea" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 ) {
                if( !$( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
            }
        }
    });

    $( "input" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {      
        
            if( $(this).val().length == 0 || $(this).val() == '') {
             //   console.log("input" + $(this).val() ); 
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
            }
        }
    });

    $( "select" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val() == null || $(this).val() == '' || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
            }
        }
    });
console.log(validate);
    if(validate == false) {
        event.preventDefault();
    } else {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: url,
            type: "POST",
            data: form_data,
            success: function( response ) {
                $.modal.close();
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