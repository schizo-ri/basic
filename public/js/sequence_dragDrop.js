var sequence_id;
var order;
var i;

$(  "#sortable" ).sortable({
    stop: function( event, ui ) {
        var sequences = [];
        var sequences_id = [];
        sequences = event.target.children;
        
        order = 0;
        for (i = 0; i < sequences.length; i++) {
            sequence_id = sequences[ i ].id;
            if (sequence_id != '') {
                order++;
                console.log(sequences[i]);
                console.log($(sequences[i]).find('.emails_order_no .order_no'));
                $(sequences[i]).find('.emails_order_no .order_no').text(i+1);
             //  $( this +'.emails_order_no .order_no').text(order);
                sequences_id.push(sequence_id);
            } 
        }
        
        var url = location.origin + "/setOrder";

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            url: url, 
            data: {'sequences_id': sequences_id},
            success: function(response) {
                $('.section_emails .emails').load(location.href + ' .section_emails .emails .emails_email_body')
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
