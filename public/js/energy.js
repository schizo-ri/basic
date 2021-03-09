if( $('.energy_consumptions').length >0) {
    var location_id;
    var energy_id;
    var counter = null;
    var counter1 = null;
    var counter2 = null;

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
            countSource(location_id, energy_id);
        }
    });

    function countSource (location_id, energy_id) {
        url = location.origin + '/lastCounter/'+location_id+'/'+energy_id;
        console.log(url);
        $.ajax({
            url: url,
            type: "get",
            success: function( data ) {
                console.log(data);
                counter1 = data.counter[1];
                counter2 = data.counter[2];
                var no_counter = data.no_counter;
              
                console.log(counter1);
                console.log(counter2);
                console.log(no_counter);
               
                if( counter1 != null) {
                    $('.last_counter span').text( counter1 );
                } 
                if( no_counter > 1 ) {
                    $('.hidden_counter').show();
                    $('.last_counter2 span').text( counter2 );
                    $('input[name=counter2]').attr('disabled',false);
                } else {
                    $('.hidden_counter').hide();
                    $('input[name=counter2]').attr('disabled',true);
                    $('.last_counter2 span').text('');
                }
            },
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr);
                console.log(json);
                console.log(errorThrown);
            }
        });
    }

    function countSource_last (location_id, energy_id, date) {
        url = location.origin + '/lastCounter_Skip/'+location_id+'/'+energy_id+'/'+date;
        $.ajax({
            url: url,
            type: "get",
            success: function( data ) {
                console.log(data);
                counter1 = data[1];
                counter2 = data[2];
                if( counter1 != null) {
                    $('.last_counter span').text( counter1 );
                    $('#result').text( $('input[name=counter').val() - counter1 );
                } 
                if( counter2 != null) {
                    $('.last_counter span').text( counter1 );
                    $('#result2').text( $('input[name=counter').val() - counter2 );
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
    $( "input[name=counter2]" ).on('keyup',function() {
        counter2 = $(this).val();
        last_counter2 = $('.last_counter2 span').text();
        console.log(counter2);
        console.log(last_counter2);

        if( counter2 && last_counter2 ) {
            $( '#result2' ).text( counter2 - last_counter2);
            if( (counter2 - last_counter2) <0 ) {
                $( '#result2' ).css('color','red');
            } else {
                $( '#result2' ).css('color','inherit');
            }
        }
    });

    if( $('.energy_consumptions.edit').length >0) {
        location_id = $("select[name=location_id]").val();
        energy_id = $("select[name=energy_id]").val();
        date = $("input[type=date]").val();

        countSource_last(location_id, energy_id, date);
    }
}