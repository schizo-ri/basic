storeOkr ();
var status = $( '#filter_status' ).length > 0 ? $( '#filter_status' ).val() : 'all';
var tim = $('#filter_okr_tim').length > 0 ? $('#filter_okr_tim').val() : 'all';
console.log("tim "+ tim);
console.log("status "+ status);

function storeOkr () {
    $('.form_okr').on('submit',function(e) {
        e.preventDefault();
        url = $( this ).attr('action');
        form_data = $(this).serialize(); 
       
        url_load = location.href + '?tim='+tim+'&status='+status;
        console.log(url);
        console.log(form_data);
        console.log(url_load);
        if( url.includes('key_result_tasks')) { 
            id = $('select[name=keyresult_id]').val();
        } else if (url.includes('key_results')) {
            id = $('select[name=okr_id]').val();
        }

        visible_element = $('.tabcontent:visible').attr('id');
        $.ajax({
            url: url,
            type: "post",
            data: form_data,
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response_id ) {
                $.modal.close();
                $('#loader').remove();
                $.get(url_load, function(data, status){
                    content =  $('#'+visible_element+' .section_okr>div',data );
                    $( '#'+visible_element+' .section_okr').html( content );
                   
                    console.log(open_element);
                    filterQuarterOpenElements(open_element);

                    try {
                        var targetEle = $('#' + response_id);
                        if(targetEle.length > 0 ) {
                            var container = $('.tabcontent');
                            var scrollTo = targetEle;
                      
                            var position = scrollTo.offset().top 
                                    - container.offset().top 
                                    + container.scrollTop();
                      
                            container.scrollTop(position);
                        }
                    } catch (error) {
                        
                    }
                   
                    $.getScript('/../js/okr1.js', function() {
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
