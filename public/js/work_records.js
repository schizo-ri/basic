if( $('.work_record_header').length > 0) {
    
    $( ".td_izostanak:contains('GO')" ).each(function( index ) {
        $( this ).addClass('abs_GO');
    });
    $( ".td_izostanak:contains('BOL')" ).each(function( index ) {
        $( this ).addClass('abs_BOL');
    });
   /*  $('.export_file>a').on('click',function(e){
        e.preventDefault();
        console.log('export_file');

        url = $(this).attr('href');
        console.log(url),
        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $( 'body' ).load( location.href , function() {
                    $('#loader').remove();
                    alert(response);
                });
            }, 
            error: function(xhr,textStatus,thrownError) {
                console.log("validate eror " + xhr + "\n" + textStatus + "\n" + thrownError);                            
            }
        });
    }); */
}

