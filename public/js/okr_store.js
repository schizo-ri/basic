storeOkr ();
var employee = $('#filter_okr_empl').val().toLowerCase();
var quarter = $('#filter_quarter').val().toLowerCase();
var status = $( '#filter_status' ).val();
function storeOkr () {
    console.log("storeOkr");
    console.log(open_element);
    $('.form_okr').on('submit',function(e) {
        e.preventDefault();
        url = $( this ).attr('action');
        form_data = $(this).serialize(); 
        url_load = location.href + '?status='+status;

        if( url.includes('key_result_tasks')) { 
            id = $('select[name=keyresult_id]').val();
        } else if (url.includes('key_results')) {
            id = $('select[name=okr_id]').val();
        }

        console.log( "id " + id );
        visible_element = $('.tabcontent:visible').attr('id');
       
        /* invisible_element = $('.tabcontent:hidden').attr('id'); */
        $.ajax({
            url: url,
            type: "post",
            data: form_data,
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $.modal.close();
                $('#loader').remove();
                $.get(url_load, function(data, status){
                    content =  $('#'+visible_element+' .section_okr>div',data );
                    $( '#'+visible_element+' .section_okr').html( content );  
                    
                    filterQuarterOpenElements();
                    
                    $.getScript('/../js/okr.js', function() {
                        open_element;
                    });
                });
            }, 
            error: function(xhr,textStatus,thrownError) {
                console.log("validate eror " + xhr + "\n" + textStatus + "\n" + thrownError);                            
            }
        });
    }); 
}
