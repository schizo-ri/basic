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
            error: function(xhr,textStatus,thrownError) {
                console.log(" error " + xhr + "\n" + textStatus + "\n" + thrownError);  
            }
        });
    }
});
