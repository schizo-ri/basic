/* variable*/	
    var user;
    var user_object;
    var user_list_date;
    var first_date;
    var user_designins;
    var date_on_list;
    var array_list;
    var background = 'inherit';
    var first_day;
    var last_day;
    var next_day; 
    var this_day; 
    var previous_day; 
    var day_before;
    var day_after;
    var first_week;
    var last_week;
    var last_index;
    var weekday = ["ned","pon","uto","sri","čet","pet","sub"];
    var row_index;
    var this_row;
    var i;
    var j;
    var change_id;
    var fst_day;
    var table_first_day;
    var today;
    var today_day;
    var num_of_days = 33;
    var day_of_week;
    var append ='';
    var row_name;
    var this_checkboxes1;
    var checkbox_text = '';
    var status = 1;
/* variable*/	
$(function() {
    users = JSON.parse($('.json_users').text());
    status = $('.status').text();
    console.log(status);
    $.each(users, function( index_user, user ) {
        today = new Date();
        today.setDate(today.getDate() - (today.getDay() + 6 ));
        
        append ='';
        $('.timeline tbody').append( '<tr class="user_days " id="user_'+ user.id +'_0"><td class="list_first_cell" >'+ user.first_name + ' ' + user.last_name +'</td></tr>');
        for (i = 0; i < 33; i++) {
            day_of_week = today.getDay();
            if (day_of_week >0 && day_of_week < 6 ) {
                fst_day = day_of_week == 1 ? ' week_first_Day' : '';
                today_day =  today.getFullYear() + '-' + ( '0'+ (today.getMonth()+1)).slice(-2) + '-' + (( '0'+ today.getDate()).slice(-2));
                append += '<td class="proj_user_color days '+ fst_day +'" id="' + user.id + '_' + today_day+'"></td>';
            }
            today.setDate(today.getDate() + 1);
        }
        $( "#user_"+user.id +'_0' ).append(append);
    });
    timeline_drow();
});

function timeline_drow () {    
    users = JSON.parse($('.json_users').text());
    $( ".timeline tbody tr" ).each(function( index ) {
        this_row = $(this);
        user = $(this).attr('id');
        user = user.replace("user_","");
        user = user.substring(0, user.lastIndexOf("_") );
        user_object = $.grep(users, function(e){ return e.id == user; })[0];
        user_designins = user_object.designins;
      /*   console.log(user_designins); */
        first_day = new Date($('.days_row th.day').first().attr('id'));
        
        table_first_day = $('.user_days').first().find('.proj_user_color').first().attr('id');
        table_first_day = table_first_day.slice(-10);
        if(user_designins.length > 0) {
            $.each(user_designins, function( index_designin, designin ) {
                if(designin.active == status) {
                    if( (new Date(designin.end) >= new Date(first_day))) {

                        next_day = new Date($('.days_row th.day').first().attr('id'));
                        user_list_date = designin.list_date;
                       
                        $.each(user_list_date, function( index_list, date ) {
                            if($( '.user_days#user_'+ user +'_'+designin.project_no).length == 0 ) {
                                $(this_row).after( '<tr class="user_days " id="user_'+ user +'_'+designin.project_no+'"></tr>' );
                                $( '.user_days#user_'+ user +'_'+designin.project_no).append('<td class="list_first_cell"><span hidden>'+ user_object.first_name + ' ' + user_object.last_name +'</span></td>');
                                
                                for (i = 0; i < 33; i++) {
                                    this_day = next_day.getFullYear() + '-' + ( '0'+ (next_day.getMonth()+1)).slice(-2) + '-' + (( '0'+ next_day.getDate()).slice(-2));
                                    fst_day = next_day.getDay() == 1 ? ' week_first_Day' : '';
                                    
                                    if( next_day.getDay() > 0 && next_day.getDay() < 6  ) {
                                        $('.user_days#user_'+ user +'_'+designin.project_no).append('<td class="proj_user_color days '+fst_day+'" id="'+ user +'_'+ this_day+'"></td>');
                                    }
                                    next_day = new Date(next_day.setDate(next_day.getDate() + 1));
                                }   
                            }
                         
                        });
                    }
                }
            });
        }
        $(this_row).remove()
        $("tr[id^='user_"+user_object.id+"']").first().find('.list_first_cell').text(user_object.first_name + ' ' + user_object.last_name );
    });
    $( ".timeline tbody tr" ).each(function( index ) {
        last_day = new Date($('.days_row th').last().attr('id'));
        last_week = parseInt($('.week_row .week').last().text())
        this_row = $(this);
        user = $(this).attr('id');
        user = user.replace("user_","");
        user = user.substring(0, user.lastIndexOf("_") );
        user_object = $.grep(users, function(e){ return e.id == user; })[0];
        user_designins = user_object.designins;
        
         if(user_designins.length > 0) {
            $.each(user_designins, function( index, designin ) {
                if(designin.active == status) {
                    user_list_date = designin.list_date;
                    span_append = '<span class="project_name '+ designin.project_no +'" ></span>';
                    $.each(user_list_date, function( index, date ) {
                        if( $('.proj_user_color#'+ user + '_' + date ).length == 0 ) {
                            delete user_list_date[index];
                        }
                    });
                    colspan = user_list_date.length;
                    $.each(user_list_date, function( index, date ) {
                        if( date != undefined) {
                           
                            first_date = user_list_date[0];
                            
                            if( $('.proj_user_color#'+ user + '_' + first_date ).length == 0 ) {
                                first_date = table_first_day;
                            } 
                            if( $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date ).length > 0  ) {
                                if( $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date  +'>span.'+designin.project_no).length == 0  ) {
                                    $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date).append(span_append);
                                    $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date +'>span.'+designin.project_no).css('background',user_object.color);
                                    $('#user_'+ user +'_'+ designin.project_no + ' span.'+designin.project_no).first().text( designin.project_no + ' ' + designin.name);
                                }
                            } else {
                                if( $('.user_days#user_'+ user +'_0' + ' td.proj_user_color#'+user+'_'+ date + ' span').length == 0 ) {
                                     $( '.user_days#user_'+ user +'_0' + ' td.proj_user_color#'+user+'_'+ date).append(span_append);
                                    $( '.user_days#user_'+ user +'_0' + ' td.proj_user_color#'+user+'_'+ date +'>span.'+designin.project_no).css('background',user_object.color);
                                    $( '.user_days#user_'+ user +'_0' + ' td.proj_user_color#'+user+'_'+ first_date+ ' span.'+designin.project_no).first().text( designin.project_no + ' ' +designin.name); 
                                }
                            }
                        }
                        $('.user_days#user_'+ user +'_0' + ' span.'+designin.project_no).each(function( index ) {
                            if( index == 0) {
                               //  $(this).parent().css('background',user_object.color); 
                                $(this).parent().attr('colspan', colspan);
                            } else {
                                $(this).parent().remove();
                            }
                        });
                        if($('.user_days#user_'+ user +'_'+ designin.project_no + ' span.'+designin.project_no).length > 0) {
                            
                            $('.user_days#user_'+ user +'_'+ designin.project_no + ' span.'+designin.project_no).each(function( index ) {
                                if( index == 0) {
                                //    $(this).parent().attr('colspan', colspan);
                                } else  {
                                    $(this).parent().css('background',user_object.color); 
                                    $(this).remove(); 
                                } 
                            });
                        } 
                    });
                }
            });
        } 
    });
}

$('.timeline .next_week').on('click',function(){
    last_day = new Date($('.days_row th').last().attr('id'));
    last_week = parseInt($('.week_row .week').last().text());
    last_week++;
    
    $('.week_row .week').first().remove();
    $( '.days_row>.day' ).each(function( index ) {
        if( index <= 4 ) {
            $( this ).remove();
        }
    });
    $( '.user_days' ).each(function( index ) {
        var this_row = this;
        $( this_row ).find('.proj_user_color').each(function( index ) {
            if(index <= 4) {
                $(this).remove();
            }
        }); 
    }); 
    $('.week_row').append('<th class="align_center week" colspan="5">'+last_week+'</th>');
    table_first_day = $('.user_days').first().find('.proj_user_color').first().attr('id');
    table_first_day = table_first_day.slice(-10);
    for (i = 0; i < 7; i++) {
        next_day = new Date(last_day.setDate(last_day.getDate() + 1));
        fst_day = next_day.getDay() == 1 ? ' week_first_Day' : '';
        day_after =  next_day.getFullYear() + '-' + ( '0'+ (next_day.getMonth()+1)).slice(-2) + '-' + (( '0'+ next_day.getDate()).slice(-2));
        if (next_day.getDay() > 0 && next_day.getDay() <= 5 ) {
            $('.days_row').append('<th class="day align_center'+fst_day+'" id="'+ day_after +'" class="align_center  ">'+next_day.getDate() + '.' + (next_day.getMonth()+1) + '.' +'<br>'+ weekday[next_day.getDay()] +'</th>');
        }
    }

    $('.timeline tbody').empty();
    
    $.each(users, function( index_user, user ) {
        today = new Date($('.days_row  th.day').first().attr('id'));
        append ='';
        $('.timeline tbody').append( '<tr class="user_days " id="user_'+ user.id +'_0"><td class="list_first_cell" >'+ user.first_name + ' ' + user.last_name +'</td></tr>');

        for (i = 0; i < 35; i++) {
            day_of_week = today.getDay();
            if (day_of_week >0 && day_of_week < 6 ) {
                fst_day = day_of_week == 1 ? ' week_first_Day' : '';
                today_day =  today.getFullYear() + '-' + ( '0'+ (today.getMonth()+1)).slice(-2) + '-' + (( '0'+ today.getDate()).slice(-2));
                append += '<td class="proj_user_color days '+ fst_day +'" id="' + user.id + '_' + today_day+'"></td>';
            }
            today.setDate(today.getDate() + 1);
        }
        $( "#user_"+user.id +'_0' ).append(append);
  
    });
    timeline_drow();
});

$('.timeline .previous_week').on('click',function(){
    first_day = new Date($('.days_row th.day').first().attr('id'));
    first_week = parseInt($('.week_row .week').first().text());
    first_week--;
    $('.week_row .week').last().remove();
    last_index = $( '.days_row>.day' ).length;
    $( '.days_row>.day' ).each(function( index ) {
        if( index >= (last_index-5) ) {
            $( this ).remove();
        } 
    });
    $( '.user_days' ).each(function( index ) {
        $( this ).find( '.proj_user_color' ).each(function( index, el ) {
            if( index >= (last_index-5) ) {
                el.remove();
            } 
        }); 
    }); 
    $( '<th class="align_center week" colspan="5">'+first_week+'</th>' ).insertAfter( $( ".week_first_cell" ) );
    
    for (i = 0; i < 7; i++) {
        previous_day = new Date(first_day.setDate(first_day.getDate() - 1));
        fst_day = previous_day.getDay() == 1 ? ' week_first_Day' : '';
        day_before = previous_day.getFullYear() + '-' + ( '0'+ (previous_day.getMonth()+1)).slice(-2) + '-' + (( '0'+ previous_day.getDate()).slice(-2)) ;
        if (previous_day.getDay() > 0 && previous_day.getDay() <= 5 ) {
            $('.days_row .day_first_cell').after('<th class="day align_center" id="'+ day_before +'" class="align_center">'+ previous_day.getDate() + '.' + (previous_day.getMonth()+1) + '.' + '<br>'+ weekday[previous_day.getDay()] +'</th>');

        }
    }
    $('.timeline tbody').empty();

    $.each(users, function( index_user, user ) {
        today = new Date($('.days_row  th.day').first().attr('id'));
        append ='';
        $('.timeline tbody').append( '<tr class="user_days " id="user_'+ user.id +'_0"><td class="list_first_cell" >'+ user.first_name + ' ' + user.last_name +'</td></tr>');
        for (i = 0; i < 35; i++) {
            day_of_week = today.getDay();
            if (day_of_week >0 && day_of_week < 6 ) {
                fst_day = day_of_week == 1 ? ' week_first_Day' : '';
                today_day =  today.getFullYear() + '-' + ( '0'+ (today.getMonth()+1)).slice(-2) + '-' + (( '0'+ today.getDate()).slice(-2));
                append += '<td class="proj_user_color days '+ fst_day +'" id="' + user.id + '_' + today_day+'"></td>';
            }
            today.setDate(today.getDate() + 1);
        }
        $( "#user_"+user.id +'_0' ).append(append);
  
    });
    timeline_drow();

});


$('.btn-delete.doc').on('click',function(e){
    e.preventDefault();
    var conf = confirm("Sigurno želiš obrisati dokumenat?");

    if (conf == true) {
        var file = $( this ).attr('title');
        $.ajax({
            method: "GET",
            url:  "delete_file",
            data: { file: file }
        })
        .done(function( msg ) {
            alert( "Dokumenat je obrisan");
            location.reload();
        }).fail(function() {
            alert( "Došlo je do problema, dokumenat nije obrisan" );
        });
    } else {
        $('.btn-delete').unbind();
    }
});

/* $('.file-upload').click(function(){
    var id = $( this ).attr('id');
    id = id.replace('upload_','');

    $('<form accept-charset="UTF-8" role="form" method="post" action="'+location.origin+'/designings/'+id+'" enctype="multipart/form-data"><div class="form-group"><label>Dodaj dokumenat</label><input type="file" name="fileToUpload" id="fileToUpload"></div><input type="hidden" name="file_up" value="1">{{ csrf_field() }} {{ method_field("PUT") }}<input class="btn btn-md btn-primary pull-right" type="submit" value="Spremi"></form>').appendTo('body').modal();
});
 */
$('.update_preparation_employee').on('submit',function(e){
    e.preventDefault();
    var form = $( this );
    var form_data = form.serialize(); 
    var id =  form.attr('id');
    url = form.attr('action');
    var url_update = location.origin + '/designings';   
/*     console.log(form_data);
    console.log(url); */
    $.ajax({
        url: url,
        type: "post",
        data: form_data,
        success: function( response ) {
           /*   $('<div><div class="modal-header"><span class="img-error">Poruka</span></div><div class="modal-body"><div class="alert alert-success alert-dismissable">Podaci su upisani</div></div></div>').appendTo('body').modal();  */
            $('.project_'+id).load(url_update + ' .project_'+id+' td');
            $('.timeline').load(url_update + ' .timeline .table-responsive');
            $( '.update_preparation_employee#' + id ).load(url_update + ' .update_preparation_employee#' + id + '>fieldset', function(){ 
                open_list();
            });
            $.getScript('/../restfulizer.js'); 
        }, 
        error: function(jqXhr, json, errorThrown) {
            $('<div><div class="modal-header"><span class="img-error">Poruka</span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><strong></strong>Problem kod spramanja podataka!</div></div></div>').appendTo('body').modal();
        
            $(".btn-submit").prop("disabled", false);
            console.log(jqXhr);
            if(jqXhr.responseJSON != undefined) {
                data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                'message':  jqXhr.responseJSON.message,
                'file':  jqXhr.responseJSON.file,
                'line':  jqXhr.responseJSON.line };
                $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + data_to_send.message + '</div></div></div>').appendTo('body').modal(); 

                $.ajax({
                    url: 'errorMessage',
                    type: "get",
                    data: data_to_send,
                    success: function( response ) {
                        console.log(response);
                    }, 
                    error: function(jqXhr, json, errorThrown) {
                        console.log(jqXhr.responseJSON); 
                        
                    }
                });
            }
        }
    });
    open_list();
});

open_list();

var body_height = $('body').height();
var position;
var checkbox_height;

function open_list () {
    var expanded = false;
    var expanded2 = false;

    $('.showCheckboxes').on('click',function(){
        this_checkboxes1 = $(this).next('.checkboxes1');
        checkbox_text = '';
        
        i = 0;
        j = $(this).find('.j').text();
        employee_id =  $(this).find('.employee_id').text();

        var checked = '';

        $.each(users, function( index_user, user ) {
            if(employee_id && employee_id ==user.id ) {
                checked =  "checked";
            }else {
                checked = '';
            }
    
            checkbox_text += '<label  class="col-12 float_left panel1" >';
            checkbox_text += '<input name="designer_id" type="radio" id="id_' + j + '_'+ i + '_'+ user.id + '" value="'+ user.id + '" ' +checked+' />';
            checkbox_text += '<label for="'+ 'id_' + j + '_'+ i + '_'+ user.id +'" >'+ user.first_name + ' ' + user.last_name +'</label>';
            /* {!! $employee && $employee->id == $user->id ? 'checked' : '' !!} */
            checkbox_text += '</label>';
       
            i++;
        });
   


        if( $(this_checkboxes1).is(":visible")) {
            expanded = true;
        } else {
            expanded = false;
        }
        $('.checkboxes1').hide();
    
        if (! expanded) {
            this_checkboxes1.prepend(checkbox_text);
            this_checkboxes1.show();
            expanded = true;
        } else {
           
        }
        if( expanded ) {
            $(document).on('mouseup',function(e) {
                var container = this_checkboxes1;
        
                // if the target of the click isn't the container nor a descendant of the container
                if (! container.is(e.target) && container.has(e.target).length === 0) 
                {
                    container.hide();
                    $(container).find('.panel1').remove();
                }
            });
        }

        position = this_checkboxes1.offset().top;
        checkbox_height = this_checkboxes1.height();
        if(position + checkbox_height > body_height) {
            this_checkboxes1.css({"bottom": '100%', "top": "auto"});
        } 
    });
    $('.collapsible').on('click',function(){
        var this_content = $(this).next('.content');
        
        if( $(this_content).is(":visible")) {
            expanded2 = true;
        } else {
            expanded2 = false;
        }
        this_content.hide();
    
        if (! expanded2) {
            this_content.show();
            expanded2 = true;
        } 
        if( expanded2 ) {
            $(document).on('mouseup',function(e) {
                var container = this_content;
        
                // if the target of the click isn't the container nor a descendant of the container
                if (! container.is(e.target) && container.has(e.target).length === 0) 
                {
                    container.hide();
                }
            });
        }
        position = this_content.offset().top;
        checkbox_height = this_content.height();
        if(position + checkbox_height > body_height) {
            this_content.css({"bottom": '100%', "top": "auto"});
        } 
    });
   
}


/* $('.timeline .next_week').on('click',function(){
    last_day = new Date($('.days_row th').last().attr('id'));
    last_week = parseInt($('.week_row .week').last().text())
    last_week++;
    $('.week_row .week').first().remove();
    $( '.days_row>.day' ).each(function( index ) {
        if( index <= 4 ) {
            $( this ).remove();
        }
    });
    $( '.user_days' ).each(function( index ) {
        var this_row = this;
        $( this_row ).find('.proj_user_color').each(function( index ) {
            if(index <= 4) {
                $(this).remove();
            }
        }); 
    }); 
    $('.week_row').append('<th class="align_center week" colspan="5">'+last_week+'</th>');
    table_first_day = $('.user_days').first().find('.proj_user_color').first().attr('id');
    table_first_day = table_first_day.slice(-10);
    for (i = 0; i < 7; i++) {
        next_day = new Date(last_day.setDate(last_day.getDate() + 1));
        fst_day = next_day.getDay() == 1 ? ' week_first_Day' : '';
        day_after =  next_day.getFullYear() + '-' + ( '0'+ (next_day.getMonth()+1)).slice(-2) + '-' + (( '0'+ next_day.getDate()).slice(-2));
        if (next_day.getDay() > 0 && next_day.getDay() <= 5 ) {
            $('.days_row').append('<th class="day align_center'+fst_day+'" id="'+ day_after +'" class="align_center  ">'+next_day.getDate() + '.' + (next_day.getMonth()+1) + '.' +'<br>'+ weekday[next_day.getDay()] +'</th>');
           

              $( ".timeline tbody tr" ).each(function( index ) {
                this_row = $(this);
                user = $(this).attr('id');
                user = user.replace("user_","");
                user = user.substring(0, user.lastIndexOf("_") );
                
                user_object = $.grep(users, function(e){ return e.id == user; })[0];
                user_designins = user_object.designins;

                $( this ).append('<td class="proj_user_color days '+fst_day+'" id="'+ user +'_' + day_after +'"></td>');
                 if(user_designins.length > 0) {
                    $.each(user_designins, function( row_index, designin ) {
                        user_list_date = designin.list_date;
                        span_append = '<span class="project_name '+ designin.project_no +'" ></span>';
                        if( $('#user_'+ user +'_'+ designin.project_no).length > 0 ) {
                            $.each(user_list_date, function( index, date ) {
                                first_date = user_list_date[0];
                               
                                if( $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date  +'>span.'+designin.project_no).length == 0  ) {
                                    $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date).append(span_append);
                                    $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date +'>span.'+designin.project_no).css('background',user_object.color);
                                    $(' span.'+designin.project_no).text('');
                                    $(' span.'+designin.project_no).first().text( designin.project_no + ' ' +designin.name);
                                    $(' span.'+designin.project_no).first().css('color','white');
                                }
                            });
                        } else {
                            $.each(user_list_date, function( index, date ) {
                            
                                if( $('.user_days#user_'+ user +'_0').find('td.proj_user_color#'+user+'_'+ date + ' span').length == 0  ) {
                                    $( '.user_days#user_'+ user +'_0' + ' td.proj_user_color#'+user+'_'+ date).append(span_append);
                                    $( '.user_days#user_'+ user +'_0' + ' td.proj_user_color#'+user+'_'+ date +'>span.'+designin.project_no).css('background',user_object.color);
                                    $(' span.'+designin.project_no).text('');
                                    $(' span.'+designin.project_no).first().text( designin.project_no + ' ' +designin.name);
                                    $(' span.'+designin.project_no).first().css('color','white');
                                }
                            });
                        }
                    });
                } 
            });  
            
        }
    }
});
 */
/* $('.timeline .previous_week').on('click',function(){
    first_day = new Date($('.days_row th.day').first().attr('id'));
    first_week = parseInt($('.week_row .week').first().text());
    first_week--;
    $('.week_row .week').last().remove();
    last_index = $( '.days_row>.day' ).length;
    $( '.days_row>.day' ).each(function( index ) {
        if( index >= (last_index-5) ) {
            $( this ).remove();
        } 
    });
    $( '.user_days' ).each(function( index ) {
        $( this ).find( '.proj_user_color' ).each(function( index, el ) {
            if( index >= (last_index-5) ) {
                el.remove();
            } 
        }); 
    }); 
    $( '<th class="align_center week" colspan="5">'+first_week+'</th>' ).insertAfter( $( ".week_first_cell" ) );
    
    for (i = 0; i < 7; i++) {
        previous_day = new Date(first_day.setDate(first_day.getDate() - 1));
        fst_day = previous_day.getDay() == 1 ? ' week_first_Day' : '';
        day_before = previous_day.getFullYear() + '-' + ( '0'+ (previous_day.getMonth()+1)).slice(-2) + '-' + (( '0'+ previous_day.getDate()).slice(-2)) ;
        if (previous_day.getDay() > 0 && previous_day.getDay() <= 5 ) {
            $('.days_row .day_first_cell').after('<th class="day align_center" id="'+ day_before +'" class="align_center">'+ previous_day.getDate() + '.' + (previous_day.getMonth()+1) + '.' + '<br>'+ weekday[previous_day.getDay()] +'</th>');

             $( ".timeline tbody tr" ).each(function( index ) {
                this_row = $(this);
                user = $(this).attr('id');
                user = user.replace("user_","");
                user = user.substring(0, user.lastIndexOf("_") );
                user_object = $.grep(users, function(e){ return e.id == user; })[0];
                user_designins = user_object.designins;
                
                $( this ).find('.list_first_cell').after('<td class="proj_user_color days '+fst_day+'" id="'+ user +'_' + day_before +'"></td>');
                table_first_day = $('.user_days').first().find('.proj_user_color').first().attr('id');
                table_first_day = table_first_day.slice(-10);
                if(user_designins.length > 0) {
                    $.each(user_designins, function( row_index, designin ) {
                        user_list_date = designin.list_date;
                        span_append = '<span class="project_name '+ designin.project_no +'" ></span>';
                        if( $('#user_'+ user +'_'+ designin.project_no).length > 0 ) {
                            $.each(user_list_date, function( index, date ) {
                                first_date = user_list_date[0];
                               
                                if( $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date  +'>span.'+designin.project_no).length == 0  ) {
                                    $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date).append(span_append);
                                    $('#user_'+ user +'_'+ designin.project_no + ' #'+user+'_'+ date +'>span.'+designin.project_no).css('background',user_object.color);
                                    $(' span.'+designin.project_no).text('');
                                    $(' span.'+designin.project_no).first().text( designin.project_no + ' ' +designin.name);
                                    $(' span.'+designin.project_no).first().css('color','white');
                                }
                            });
                        } else {
                            $.each(user_list_date, function( index, date ) {
                            
                                if( $('.user_days#user_'+ user +'_0').find('td.proj_user_color#'+user+'_'+ date + ' span').length == 0  ) {
                                    $( '.user_days#user_'+ user +'_0' + ' td.proj_user_color#'+user+'_'+ date).append(span_append);
                                    $( '.user_days#user_'+ user +'_0' + ' td.proj_user_color#'+user+'_'+ date +'>span.'+designin.project_no).css('background',user_object.color);
                                    $(' span.'+designin.project_no).text('');
                                    $(' span.'+designin.project_no).first().text( designin.project_no + ' ' +designin.name);
                                    $(' span.'+designin.project_no).first().css('color','white');
                                }
                            });
                        }
                    });
                }
            });
        }
    }
});

 */