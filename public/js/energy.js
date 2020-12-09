if( $('.energy_consumptions').length >0) {
    var location_id;
    var energy_id;
    var counter = null;

    $( "select[name=location_id]" ).on('change',function() {
        location_id = $(this).val();
        energy_id = $("select[name=energy_id]").val();
        
        if( location_id != null && energy_id != null ) {
            console.log(location_id);
            console.log(energy_id);
            countSource(location_id, energy_id);
        }
    });
    $( "select[name=energy_id]" ).on('change',function() {
        energy_id = $(this).val();
        location_id = $("select[name=location_id]").val();

        if( location_id != null && energy_id != null ) {
            console.log(location_id);
            console.log(energy_id);
            countSource(location_id, energy_id);
        }
    });

    function countSource (location_id, energy_id) {
        url = location.origin + '/lastCounter/'+location_id+'/'+energy_id;
        console.log(url);
        $.ajax({
            url: url,
            type: "get",
            success: function( counter ) {
                counter = counter;
                if( counter != null) {
                    $('.last_counter span').text( counter );
                } 
            },
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr);
                console.log(json);
                console.log(errorThrown);
            }
        });
    }
    function countSource_last (location_id, energy_id) {
        url = location.origin + '/lastCounter_Skip/'+location_id+'/'+energy_id;
        console.log(url);
        $.ajax({
            url: url,
            type: "get",
            success: function( counter ) {
                counter = counter;
                if( counter != null) {
                    $('.last_counter span').text( counter );
                    $('#result').text( $('input[name=counter').val() - counter );
                } 
            },
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr);
                console.log(json);
                console.log(errorThrown);
            }
        });
    }
    $( "input[name=counter]" ).on('keyup',function() {
        counter = $(this).val();
        last_counter = $('.last_counter span').text();
        console.log(counter);
        console.log(last_counter);

        if( counter && last_counter ) {
            $( '#result' ).text( counter - last_counter);
            if( (counter - last_counter) <0 ) {
                $( '#result' ).css('color','red');
            } else {
                $( '#result' ).css('color','inherit');
            }
        }
    });

    if( $('.energy_consumptions.edit').length >0) {
        location_id = $("select[name=location_id]").val();
        energy_id = $("select[name=energy_id]").val();

        countSource_last(location_id, energy_id);

    }
}