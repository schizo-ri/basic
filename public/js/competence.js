if( $('.competence_table').length > 0 ) {
    var mouse_is_inside = false;
    var form = $('form.form_evaluation');
    var url;
    var ev_id;
    var rating_id;
    var comment;
    var value;
    var container;
    var rating = 0;
    var rating2 = 0;
    var all_rating = 0;
    var all_rating2 = 0;
    var total_rating = 0;
    var total_group = 0;
    var element_id;
    var coefficient;
    var q_rating;

    $('.show_button_upload').on('click', function(){
        element_id = $(this).attr('id');
        console.log("show_button_upload");
        $('.upload_file.'+element_id).modal();
        $('.upload_file.'+element_id).show();
    });

    $('.rating_radio.evaluate_manager>input').on('click',function(){
        mouse_is_inside = true;
        ev_id = $(this).attr('title');
        container = null;
        rating_id = $(this).val();
        comment = null;
        submit_form (ev_id,rating_id, comment);
    });

    $('.rating_radio.evaluate_user>input').on('click',function() {
        coefficient = parseFloat( $('#coefficient').text());
       
        total_rating = 0;
        total_group = 0;
        $( ".rating_radio.evaluate_user input:checked" ).each(function( index, element ) {
            q_rating = parseFloat($( element ).siblings('span.span_question_rating').text());
            console.log($(element).next('label.label_rating').text());
            rating = parseFloat($( element ).next('label.label_rating').text()) * coefficient * q_rating;
            console.log(rating);
            if($.isNumeric( rating ) ) {
                total_rating+=rating;
            }

            if( $(element).is(':visible')) {
                total_group +=  rating; 
            }
        });

        $('.rating_all span').text(total_rating.toFixed(2));
        $('.mySlides:visible .rating_group span').text(total_group.toFixed(2))
    });
    
    $(".evaluation_comment").on('change',function(){
        container = $( this );
        mouse_is_inside = true;
        ev_id = $(this).attr('title');
        comment = $(this).val();
        rating_id = null;
        $(document).on('click',function(e) {
            if(container && !container.is(e.target) && mouse_is_inside == true ) {
                submit_form (ev_id,rating_id, comment);
            }
        });
    });
    
    function submit_form (ev_id, rating_id, comment) {
        /* form_data = form.serialize();  */
        if(rating_id) {
            url = $( form ).attr('action') + '?rating_id='+rating_id+'&id='+ev_id;
        }
        if( comment ) {
            url = $( form ).attr('action') + '?comment='+comment+'&id='+ev_id;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });  
        $.ajax({
            url: url,
            type: "post",
            success: function( response ) {
                console.log(response);
                mouse_is_inside = false;
            }, 
            error: function(jqXhr, json, errorThrown) {
                $(".btn-submit").prop("disabled", false);
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };

                console.log(data_to_send); 
        
                $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + "Podaci nisu spremljeni, došlo je do greške: " + data_to_send.message + '</div></div></div>').appendTo('body').modal();
            }
        });
    }

    $('.filter_evaluation').on('change',function(){
        $('.total_rating').remove();
        var element = $(this);
        value = $(this).val().toLowerCase();
        id = $(this).find('option:selected').attr('data-id');
        console.log(value);
        console.log(id);
        if( value == 'all') {
            $(".tr_questions").hide();
            $(".tr_evaluation").hide();

        } else {
            $(".tr_questions").show();
            $(".tr_evaluation").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
            all_rating = 0;
            all_rating2 = 0;
            $( ".rating_empl:visible" ).each(function( index ) {
                rating =  parseFloat($( this ).text());
                all_rating+=rating;
            });
            $( ".edit_evaluation_id:visible input:checked" ).each(function( index ) {
                rating2 = parseFloat($( this ).siblings('label').text());
                if($.isNumeric( rating2 ) ) {
                    all_rating2+=rating2;
                }
            });
            $( element ).parent().after('<p class="total_rating">Ukupna bodovi: '+all_rating.toFixed(2)+'</p>');
            $('.form_recommendation').find('input#employee_id').val(id);
            $('.form_recommendation').show();
        }
    });

    $('.prev').on('click',function(){
        $('.slideshow-container form').animate({ scrollTop: 0 }, "slow");
        return false;
    });
    $('.next').on('click',function(){
        $('.slideshow-container form').animate({ scrollTop: 0 }, "slow");
        return false;
    });
}