$('.btn-delete.doc').click(function(e){
    e.preventDefault();
    var conf = confirm("Sigurno želiš obrisati dokumenat?");

    if (conf == true) {
        var file = $( this ).attr('title');
        console.log(file);
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

    console.log(id);
    $('<form accept-charset="UTF-8" role="form" method="post" action="'+location.origin+'/designings/'+id+'" enctype="multipart/form-data"><div class="form-group"><label>Dodaj dokumenat</label><input type="file" name="fileToUpload" id="fileToUpload"></div><input type="hidden" name="file_up" value="1">{{ csrf_field() }} {{ method_field("PUT") }}<input class="btn btn-md btn-primary pull-right" type="submit" value="Spremi"></form>').appendTo('body').modal();
});
 */
$('.update_preparation_employee').submit(function(e){
    e.preventDefault();
    var form = $( this );
    var form_data = form.serialize(); 
    var id =  form.attr('id');
    url = form.attr('action');
    var url_update = location.origin + '/designings';   
    console.log(form_data);
    console.log(url);
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

    $('.showCheckboxes').click(function(){
        var this_checkboxes1 = $(this).next('.checkboxes1');
        
        if( $(this_checkboxes1).is(":visible")) {
            expanded = true;
        } else {
            expanded = false;
        }
        $('.checkboxes1').hide();
    
        if (! expanded) {
            this_checkboxes1.show();
            expanded = true;
        } 
        if( expanded ) {
            $(document).mouseup(function(e) {
                var container = this_checkboxes1;
        
                // if the target of the click isn't the container nor a descendant of the container
                if (! container.is(e.target) && container.has(e.target).length === 0) 
                {
                    container.hide();
                }
            });
        }

        position = this_checkboxes1.offset().top;
        checkbox_height = this_checkboxes1.height();
        if(position + checkbox_height > body_height) {
            this_checkboxes1.css({"bottom": '100%', "top": "auto"});
        } 
    });
    $('.collapsible').click(function(){
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
            $(document).mouseup(function(e) {
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

var users = jQuery.parseJSON($('.json_users').text());
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
var change_id;
var fst_day;
var table_first_day;

$('.timeline .previous_week').click(function(){
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
        day_before = previous_day.getFullYear() + '-' + ( '0'+ (previous_day.getMonth()+1)).slice(-2) + '-' + (( '0'+ previous_day.getDate()).slice(-2)) ;
        if (previous_day.getDay() > 0 && previous_day.getDay() <= 5 ) {

            $('.days_row .day_first_cell').after('<th class="day align_center" id="'+ day_before +'" class="align_center">'+ previous_day.getDate() + '.' + (previous_day.getMonth()+1) + '.' + '<br>'+ weekday[previous_day.getDay()] +'</th>');

            $( ".timeline tbody tr" ).each(function( index ) {
                this_row = $(this);
                row_index = $(this).attr('id');
                row_index = row_index.substring(row_index.lastIndexOf('_') + 1); 
                
                user = $(this).attr('id');
                user = user.replace("user_","");
                user = user.substring(0, user.lastIndexOf("_") );
                user_object = $.grep(users, function(e){ return e.id == user; })[0];
                user_designins = user_object.designins;
                
                fst_day = previous_day.getDay() == 1 ? ' week_first_Day' : '';
                $( this ).find('.list_first_cell').after('<td class="proj_user_color days '+fst_day+'" id="'+ user +'_' + day_before +'_'+row_index+'"><span class="project_name"></span></td>'); //privremeni row_index = 0

                
                if(user_designins.length > 0) {
                    $.each(user_designins, function( index, value ) {
                        
                        user_list_date = this.list_date;
                        first_date = user_list_date[0];
                    
                        /*   if( $('.proj_user_color#'+ user + '_' + first_date +'_'+row_index).length == 0 ) {
                            
                            first_date = table_first_day;
                            
                            console.log("user_list_date "+user_list_date[0]);
                            console.log("first_date "+first_date);
                            console.log("---------------");
                        } 
                        */
                        var array_list = $.map(user_list_date, function(value, index){
                            return [value];
                        });
                        if(index == row_index) {
                            if(array_list.includes( day_before )) {
                                $('.user_days#user_'+ user+'_'+row_index+' .proj_user_color#'+ user + '_' + day_before +'_'+row_index).css("background-color", user_object.color);
                                $('.user_days#user_'+ user+'_'+row_index+' .proj_user_color').find('.project_name').text('');
                                $('.user_days#user_'+ user+'_'+row_index+' .proj_user_color#'+ user + '_' + first_date +'_'+row_index+  ' .project_name').text(this.project_no + ' ' + this.name);
                                if ($('.user_days#user_'+ user+'_'+row_index+' .proj_user_color#'+ user + '_' + first_date +'_'+row_index).length == 0) {
                                    $('.user_days#user_'+ user+'_'+row_index+' .proj_user_color').find('.project_name').text('');
                                    $('.user_days#user_'+ user+'_'+row_index+' .proj_user_color:first').find('.project_name').text(this.project_no + ' ' + this.name);
                                }
                            }
                        }
                    });
                }
            });
        }
    }
});
$('.timeline .next_week').click(function(){
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
    table_first_day = table_first_day.substring(0, table_first_day.lastIndexOf("_"));
    table_first_day = table_first_day.slice(-10);
    
    for (i = 0; i < 7; i++) {
        next_day = new Date(last_day.setDate(last_day.getDate() + 1));
        day_after =  next_day.getFullYear() + '-' + ( '0'+ (next_day.getMonth()+1)).slice(-2) + '-' + (( '0'+ next_day.getDate()).slice(-2));
        if (next_day.getDay() > 0 && next_day.getDay() <= 5 ) {
            $('.days_row').append('<th class="day align_center" id="'+ day_after +'" class="align_center  ">'+next_day.getDate() + '.' + (next_day.getMonth()+1) + '.' +'<br>'+ weekday[next_day.getDay()] +'</th>');

            $( ".timeline tbody tr" ).each(function( index ) {
                row_index = $(this).attr('id');
                row_index = row_index.substring(row_index.lastIndexOf('_') + 1); 

                this_row = $(this);
                user = $(this).attr('id');
                user = user.replace("user_","");
                user = user.substring(0, user.lastIndexOf("_") );
                user_object = $.grep(users, function(e){ return e.id == user; })[0];
                user_designins = user_object.designins;
                fst_day = next_day.getDay() == 1 ? ' week_first_Day' : '';
                $( this ).append('<td class="proj_user_color days '+fst_day+'" id="'+ user +'_' + day_after +'_'+row_index+'"><span class="project_name"></span></td>');

                if(user_designins.length > 0) {
                    $.each(user_designins, function( index, value ) {
                        
                        user_list_date = this.list_date;
                        first_date = user_list_date[0];

                        if( $('.proj_user_color#'+ user + '_' + first_date +'_'+row_index).length == 0 ) {
                            first_date = table_first_day;
                            
                        } 
                        var array_list = $.map(user_list_date, function(value, index){
                            return [value];
                        });
                        if(index == row_index) {
                            if(array_list.includes( day_after ) ){
                                $('.user_days#user_'+ user+'_'+row_index+' .proj_user_color#'+ user + '_' + day_after+'_'+row_index).css("background-color", user_object.color);
                                $('.user_days#user_'+ user+'_'+row_index+' .proj_user_color#'+ user + '_' + first_date +'_'+row_index+ ' .project_name').text(this.project_no + ' ' + this.name);
                            }
                            
                            if( first_date != user_list_date[0] ){
                                $('.user_days#user_'+ user+'_'+row_index+' .proj_user_color#'+ user + '_' + first_date +'_'+row_index+ ' .project_name').text(this.project_no + ' ' + this.name);
                            }
                        }
                    });
                }
            });
        }
    }

});
