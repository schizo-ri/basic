if( $('.competence_table').length > 0 ) {
    var mouse_is_inside = false;
    var form = $('form.form_evaluation');
    var url;
    var url_recommodation;
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
    console.log("competence");

    collapse_tr ();
    submit_evaluation ( );
    storeEvaluation();
    submit_form ();

    $('.show_button_upload').on('click', function(){
        element_id = $(this).attr('id');
        console.log("show_button_upload");
        $('.upload_file.'+element_id).modal();
        $('.upload_file.'+element_id).show();
    });
   
    function storeEvaluation() {
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
        
       
    }
   
    function collapse_tr () {
        $('.tr_group').on('click',function(){
            var id = $( this ).attr('data-id');
    
            $('.tr_questions[data-id="'+id+'"]').toggle();
            if( $('.tr_questions[data-id="'+id+'"]:visible').length == 0 ) {
                $('.tr_evaluation[data-id="'+id+'"]').hide();
                $('.arrow_collapse').find('i , svg').remove();
                $('.arrow_collapse').prepend('<i class="fas fa-angle-down"></i>');
            
            } else {
                $( this ).find('.arrow_collapse').find('i , svg').remove();
                $( this ).find('.arrow_collapse').prepend('<i class="fas fa-angle-up"></i>');
            }
        });
        $('.tr_questions').on('click',function(){
            var id = $( this ).attr('data-question');
            $('.tr_evaluation[data-question="'+id+'"]').toggle();
            if( $('.tr_evaluation[data-question="'+id+'"]:visible').length == 0 ) {
                $( this ).find('.td_question').find('.arrow_collapse').find('i , svg').remove();
                $( this ).find('.td_question').find('.arrow_collapse').prepend('<i class="fas fa-angle-down"></i>');
            } else {
                $( this ).find('.td_question').find('.arrow_collapse').find('i , svg').remove();
                $( this ).find('.td_question').find('.arrow_collapse').prepend('<i class="fas fa-angle-up"></i>');
            }
        });
    }

    function submit_evaluation ( ) {
        $('.form_recommendation').on('submit',function(e){
            e.preventDefault();
           
            form = $('form.form_recommendation');
            form_data = form.serialize(); 
            url =  $( form ).attr('action') + '?employee_id='+id;
    
            $.ajax({
                url: url,
                type: "post",
                data: form_data,
                success: function( recomendations ) {
                    console.log(recomendations);
                    text = '';
                    $.each(recomendations, function( index, recomendation ) {
                       text += '<p>'+ recomendation.comment + '<small>'+recomendation.target_date+'</small>'+ '</p>';
                    });
                    $('.recomendation div').append(text);
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
        });
    }

    function submit_form () {
        $('.rating_radio.evaluate_manager>input').on('click',function(){
            mouse_is_inside = true;
            ev_id = $(this).attr('title');
            container = null;
            rating_id = $(this).val();
            comment = null;
            form = $('form.form_evaluation');
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
        });

        $(".evaluation_comment").on('change',function(){
            console.log("evaluation_comment");
            
            container = $( this );
            mouse_is_inside = true;
            ev_id = $(this).attr('title');
            comment = $(this).val();
            rating_id = null;
            console.log(ev_id);
            console.log(comment);
            $(document).on('click',function(e) {
                if(container && !container.is(e.target) && mouse_is_inside == true ) {
                    console.log("submit textarea");
                    form = $('form.form_evaluation');
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
            });
        });

    }

    $('.filter_evaluation').on('change',function(){
        $('.total_rating').remove();
        var element = $(this);
        value = $(this).val().toLowerCase();
        id = $(this).find('option:selected').attr('data-id');
        console.log(value);
        console.log(id);

        url = location.href + '?employee_id='+id;
        console.log(url);
        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $('.recomendation').load( url + ' .recomendation>div');
                $('tbody').load( url + ' tbody>tr', function(){
                    collapse_tr ();
                    submit_form ();

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
                        });;
                        $( element ).parent().after('<p class="total_rating">Ukupna bodovi: '+all_rating.toFixed(2)+'</p>');
                        $('.form_recommendation').find('input#employee_id').val(id);
                        $('.form_recommendation').show()
                    }
                    $('#loader').remove();
                });
               
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
      /*   url_recommodation = location.origin + '/getRecommendations?employee_id='+id;
        $.ajax({
            url: url_recommodation,
            type: "get",
            success: function( recomendations ) {
                console.log(recomendations);
                text = '';
                $.each(recomendations, function( index, recomendation ) {
                date = recomendation.target_date.split("-");
                
                text += '<p>'+ recomendation.comment;
                if (recomendation.mentor_name != '') {
                    text +=  " | Dodjeljen mentor: " + recomendation.mentor_name;
                }
                text += '<small>'+date[2]+"."+date[1]+"."+date[0]+'.</small>'+ '</p>';
                });
                $('.recomendation div').append(text);
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
        }); */
        

        /* if( value == 'all') {
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
            });;
            $( element ).parent().after('<p class="total_rating">Ukupna bodovi: '+all_rating.toFixed(2)+'</p>');
            $('.form_recommendation').find('input#employee_id').val(id);
            $('.form_recommendation').show()
        } */
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