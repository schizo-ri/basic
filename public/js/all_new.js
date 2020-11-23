$(function(){
	if($('.all_absences #index_table').length > 0) {
		var d = new Date();
		var ova_godina = d.getFullYear();
		var prosla_godina = ova_godina - 1;
		var year = '';
		
		if($(".all_absences").length == 0) {
			init_absence_table ();
		}
		function init_absence_table () {
			jQuery.extend( jQuery.fn.dataTableExt.oSort, {
				"date-eu-pre": function ( date ) {
					date = date.replace(" ", "");
						
					if ( ! date ) {
						return 0;
					}
				
					var year;
					var eu_date = date.split(/[\.\-\/]/);
					/*year (optional)*/
					if ( eu_date[2] ) {
						year = eu_date[2];
					}
					else {
						year = 0;
					}
				
					/*month*/
					var month = eu_date[1];
					try {
						
						if ( month.length == 1 ) {
							month = 0+month;
						}
				
						/*day*/
						var day = eu_date[0];
						if ( day.length == 1 ) {
							day = 0+day;
						}
				
						return (year + month + day) * 1;
					} catch (error) {
						target = null;
					}
				},
				
				"date-eu-asc": function ( a, b ) {
					return ((a < b) ? -1 : ((a > b) ? 1 : 0));
				},
				
				"date-eu-desc": function ( a, b ) {
					return ((a < b) ? 1 : ((a > b) ? -1 : 0));
				}
			} );
			var admin = $('#user_admin').text();
			if (admin == 'true') {
				order = [ 1, "desc" ];
				targets = [1,3,4];
			} else {
				order = [ 2, "desc" ];
				targets = [0,2,3];
			}
			$('.all_absences #index_table').DataTable( {
				"order": [order],
				"fixedHeader": true,
				"columnDefs": [ {
					"targets"  :targets,
					"type": 'date-eu'
				}],
				dom: 'Bfrtip',
				"paging": false,
				buttons: [
					'copyHtml5',
					{
						extend: 'print',
						customize: function ( win ) {
							$(win.document.body).find('h1').addClass('title_print');
							$(win.document.body).find('table').addClass('table_print');
							$(win.document.body).find('table tr td').addClass('row_print');
							$(win.document.body).addClass('body_print');
							$(win.document.body).find('table tr th').addClass('hrow_print');
							$(win.document.body).find('table tr th:last-child').addClass('not_print');
							$(win.document.body).find('table tr td:last-child').addClass('not_print');
						}
					},
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4',
						exportOptions: {
							columns: 'th:not(.not-export-column)',
							rows: ':visible'
						}
					},
					{
						extend: 'excelHtml5',
						autoFilter: true,
						exportOptions: {
							columns: 'th:not(.not-export-column)',
							rows: ':visible'
						},
						customize: function( xlsx ) {
							var sheet = xlsx.xl.worksheets['sheet1.xml'];
						/* 	$('row c', sheet).attr( 's', '25' );  borders */
							$('row:first c', sheet).attr( 's', '27' );
						}	
					}
				]
			} );
		}
		
		$('#year_vacation').on('change',function(){
			year = $(this).val();
			
			$('.info_abs>p>.go').hide();
			$('.info_abs>p>.go.go_'+year).show();
			$('#mySearchTbl').val("");
	
			var url = location.href + '?year='+year;
			console.log(url);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});     
			$.ajax({
				url: url,
				type: "GET",
				success: function( response ) {
				
					$('table').load(url + ' table',function(){
						
						$.getScript( '/../restfulizer.js');
					});
				}, 
				error: function(jqXhr, json, errorThrown) {
					var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
										'message':  jqXhr.responseJSON.message,
										'file':  jqXhr.responseJSON.file,
										'line':  jqXhr.responseJSON.line };
	
					$.ajax({
						url: 'errorMessage',
						type: "get",
						data: data_to_send,
						success: function( response ) {
							$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
						}, 
						error: function(jqXhr, json, errorThrown) {
							console.log(jqXhr.responseJSON); 
							
						}
					});
				}
			});
		});
		$('#year_sick').on('change',function(){
			year = $(this).val();
			$('.info_abs>p>.bol').hide();
			$('.info_abs>p>.bol.bol_'+year).show();
	
			var url = location.href + '?year='+year+'&type=BOL';
			console.log(url);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});     
			$.ajax({
				url: url,
				type: "GET",
				success: function( response ) {
					console.log(response);
					$('table').load(url + ' table',function(){
						
						$.getScript( '/../restfulizer.js');
					});
				}, 
				error: function(jqXhr, json, errorThrown) {
					var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
										'message':  jqXhr.responseJSON.message,
										'file':  jqXhr.responseJSON.file,
										'line':  jqXhr.responseJSON.line };
	
					$.ajax({
						url: 'errorMessage',
						type: "get",
						data: data_to_send,
						success: function( response ) {
							$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
						}, 
						error: function(jqXhr, json, errorThrown) {
							console.log(jqXhr.responseJSON); 
						}
					});
				}
			});
		});
		$( "#request_type" ).on('change',function() {
			if($(this).val() == 'IZL') {
				$('.form-group.time').show();
				$('.form-group.date2').hide();
				var start_date = $( "#start_date" ).val();
				var end_date = $( "#end_date" );
				end_date.val(start_date);
			} else {
				$('.form-group.time').hide();
				$('.form-group.date2').show();
			}
		});
		$( "#start_date" ).on('change',function() {
			var start_date = $( this ).val();
			var end_date = $( "#end_date" );
			end_date.val(start_date);
		});
	}
	$('.all_absences #index_table_filter').prepend('<a class="add_new" href="'+ location.origin + '/absences/create ' +'" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>'+ 'Novi zahtjev'+'</a>');
	if($(".all_absences #index_table_filter .show_button").length == 0) {
		$('.all_absences #index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
	}
	$('.all_absences #index_table_filter').show(); 
});

$('.select_filter.sort').on('change',function () {
    $('main.main_ads').load($(this).val() + ' main.main_ads article');
});

var body_width = $('body').width();
if(body_width > 450) {
    var all_height = [];
    $('.noticeboard_notice_body.panel .ad_content').each(function(){
        all_height.push($(this).height());
    });
    all_height.sort(function(a, b) {
        return b-a;
    });
    var max_height = all_height[0];
    $('.noticeboard_notice_body.panel .ad_content').height(max_height);
    
}
$('.benefit_body').first().show();

$('.benefit_title').on('click',function(){
	$('.benefit_title').removeClass('active');
	var id = $(this).attr('id');
	console.log(id);
	$('.benefit_body').hide();
	$('.benefit_body#_'+id).show();
	$(this).addClass('active');
});
var main_benefits_height = $('.main_benefits').height();
var main_benefits_head_height = $('.main_benefits_head').height()+40;
var benefits_scroll = $('.benefits_scroll').height();
var body_width = $('body').width();
var div_width = $( '.main_benefits_head').width();
var all_width = 0;

$('.benefit_title').first().addClass('active');

if(body_width > 450) {
	
	$('.main_benefits_body').height(main_benefits_height-main_benefits_head_height-benefits_scroll);
	$( ".main_benefits_head > .benefit_title" ).each( (index, element) => {
		all_width += 203;
	});
	if((all_width - 30) > (div_width +1 )) {
		$('.main_benefits .scroll_right').show();
	}
} else {
	$( ".main_benefits_head > .benefit_title" ).each( (index, element) => {
		all_width += 110;
	});
	if((all_width - 10) > (div_width +1 )) {
		$('.main_benefits .scroll_right').show();
	}
}
	
$('#right-button').on('click',function(event) {
	event.preventDefault();
	$('.main_benefits_head').animate({
		scrollLeft: "+=203px"
	}, "slow");
	$('.main_benefits .scroll_left').show();
	
});

$('#left-button').on('click',function(event) {
	event.preventDefault();
	$('.main_benefits_head').animate({
		scrollLeft: "-=203px"
	}, "slow");
	if($('.main_benefits_head').scrollLeft() < 203 ) {
		$('.main_benefits .scroll_left').hide();
	} else {
		$('.main_benefits .scroll_left').show();
	}
});

$( window ).on('resize',function() {
	$( ".main_benefits_head > .benefit_title" ).each( (index, element) => {
		all_width += 203;
	});
	
	if((all_width - 30) > (div_width +1 )) {
		$('.main_benefits .scroll_right').show();
	} else {
		$('.main_benefits .scroll_right').hide();
	}
});
// kalendar dashboard
$( function () {
    $('.dates li').first().addClass('active_date');

    var div_width = $( '.dates').width();
    var all_width = 69;
    var dates = $('.box-content').find('.dates');
    var day_of_week = new Array("SUN","MON","TUE","WED","THU","FRI","SAT");
    var monthNames = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    var today = new Date();
    var date_today = today.getFullYear() + '-' +  ('0' + (today.getMonth() +1) ).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    var broj_dana = div_width / all_width;

    dates.append('<li id="li-' + date_today + '" class="active_date"><span class="month">' + monthNames[today.getMonth()] +  '</span><span class="day">' + today.getDate() +  '</span><span class="week_day">' + day_of_week[today.getDay()]  +  '</span><span class="display_none YYYY_mm">' + today.getFullYear()  + '-' + + ('0' + (today.getMonth()+1)).slice(-2)+ '</span></li>');

    for(i=0; i<broj_dana-1; i++) {
        var date_plus1 = new Date(today.setDate(today.getDate() +1));
        var date_new = date_plus1.getFullYear() + '-' +  ('0' + (date_plus1.getMonth() +1) ).slice(-2) + '-' + ('0' + date_plus1.getDate()).slice(-2);
        dates.append('<li id="li-' + date_new + '" class=""><span class="month">' + monthNames[date_plus1.getMonth()] +  '</span><span class="day">' + date_plus1.getDate() +  '</span><span class="week_day">' + day_of_week[date_plus1.getDay()]  +  '</span><span class="display_none YYYY_mm">' + today.getFullYear()  + '-' + + ('0' + (today.getMonth()+1)).slice(-2)+ '</span></li>');
    }

    $( window ).on('resize',function() {
        var div_width = $( '.dates').width();
        var broj_dana = div_width / all_width;
        
        for(i=0; i<broj_dana; i++) {
            var date_plus1 = new Date(today.setDate(today.getDate() +1));
    
            var date_new = date_plus1.getFullYear() + '-' +  ('0' + (date_plus1.getMonth() +1) ).slice(-2) + '-' + ('0' + date_plus1.getDate()).slice(-2);
    
            dates.append('<li id="li-' + date_new + '" class=""><span class="month">' + monthNames[date_plus1.getMonth()] +  '</span><span class="day">' + date_plus1.getDate() +  '</span><span class="week_day">' + day_of_week[date_plus1.getDay()]  +  '</span><span class="display_none YYYY_mm">' + today.getFullYear()  + '-' + + ('0' + (today.getMonth()+1)).slice(-2)+ '</span></li>');
        }

    });
   
    //prikaz evenata za selektirani dan
    $('.dates li').on('click',function(){
        var active_li =  $(this).attr('id');
        var active_date = active_li.replace('li-','');
        var this_li = $(this).attr('id');
        var url = location.origin + '/dashboard?active_date='+active_date;
       
        $('.dates>li').removeClass('active_date');
        $( this ).addClass('active_date');

        $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});   
        $.ajax({
			url: url,
			type: "GET",
			success: function( response ) {
                $('.comming_agenda').load(url + ' .comming_agenda>a, .comming_agenda>h3, .comming_agenda .all_agenda',function(){
                    if( $( '.comming_agenda .all_agenda .agenda').length == 0 ) {
                       $('.comming_agenda .placeholder').show();
                    };
                   
                    if(this_li) {
                        var this_id = this_li.replace("li-",""); // selektirani datum
                        $( ".comming_agenda > .agenda" ).each( (index, element) => {
                            $(element).addClass('display_none');
                            $(element).removeClass('show_agenda');
                            if($(element).attr('id') == this_id ) {
                                $(element).removeClass('display_none');
                                $(element).addClass('show_agenda');
                            }
                        });
                    }
                    if(! $('.comming_agenda .agenda.show_agenda').length) {
                        $('.comming_agenda .placeholder').show();
                        var placeholder_height =  $('.placeholder img').height();
                   //      $('.calendar .comming_agenda').height(placeholder_height + 60);
                      $('.placeholder_cal >p').css('line-height',placeholder_height + 'px' );
                    } else {
                        $('.comming_agenda .placeholder').hide();
                    }
                });
			}, 
			error: function(jqXhr, json, errorThrown) {
				var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };

				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
						
					}
				});
			}
        });
    });

    setHeight();
    function setHeight() {
        if($('body').width() > 1000) {
            var div_height = $('section.calendar>div').height();
            var calendar_height = $('section.calendar #calendar').height();
           
           // $('.comming_agenda').height(div_height - calendar_height -30);
        }
    }
    
    if(! $('.comming_agenda .all_agenda.show_agenda').length) {
        var calendar_height = $('section.calendar>div').height() - $('section.calendar #calendar').height() -40;
        $('.comming_agenda .placeholder').show();
        var placeholder_height =  $('.placeholder img').height();
        /* /* $('.calendar .comming_agenda').height(calendar_height ); */
        //   $('.placeholder_cal >p').css('line-height',placeholder_height + 'px' ); */
    } else {
        $('.comming_agenda .placeholder').hide();
    }
    
    $('#left-button').on('click',function() {
        var active_li = $('.dates').find('li.active_date');

        var first_li = $(dates).find('li').first();
        var day = first_li.find('.day').text();
        var month = first_li.find('span.YYYY_mm').text().slice(5,7);
        var year = first_li.find('span.YYYY_mm').text().slice(0,4);
        var currentDate = new Date(year + '-' + month + '-' + day);
        var date_prev = new Date(currentDate.setDate(currentDate.getDate() -1));

        var date = date_prev.getFullYear() + '-' +  ('0' + (date_prev.getMonth() +1) ).slice(-2) + '-' + ('0' + date_prev.getDate()).slice(-2);

        if($('.dates').scrollLeft() == 0) {
            dates.prepend('<li id="li-' + date + '" class=""><span class="month">' + monthNames[date_prev.getMonth()] +  '</span><span class="day">' + date_prev.getDate() +  '</span><span class="week_day">' + day_of_week[date_prev.getDay()]  +  '</span><span class="display_none YYYY_mm">' + currentDate.getFullYear()  + '-' + + ('0' + (currentDate.getMonth()+1)).slice(-2)+ '</span></li>');
        }
        $('.dates').animate({
            scrollLeft: "-=69"
        }, "slow");
        var previous_li = active_li.prev();
        previous_li.addClass('active_date');
        active_li.removeClass('active_date');
        
        previous_li.trigger('click');
        setHeight();
        $.getScript( '/../js/event_click.js');
    });

    $('#right-button').on('click',function() {
        
        var active_li = $('.dates').find('li.active_date');

        var last_li = $(dates).find('li').last();
        var day_last = last_li.find('.day').text();
        var month = last_li.find('span.YYYY_mm').text().slice(5,7);
        var year = last_li.find('span.YYYY_mm').text().slice(0,4);

        var lastDate = new Date(year + '-' + month + '-' + day_last);
        var date_next = new Date(lastDate.setDate(lastDate.getDate() +1));

        var next_date = date_next.getFullYear() + '-' + ('0' + (date_next.getMonth() +1) ).slice(-2) + '-' + ('0' + date_next.getDate()).slice(-2);
  
        $('.dates').animate({
            scrollLeft: "+=69"
        }, "slow");
        var count_li = 0;
        $( ".dates > li" ).each(function (index, element) {
            all_width += 69;
            count_li++;
        });
        if(((count_li * 69) - (div_width + 69)) < $('.dates').scrollLeft() ){
            dates.append('<li id="li-' + next_date + '" class=""><span class="month">' + monthNames[date_next.getMonth()] +  '</span><span class="day">' + date_next.getDate() +  '</span><span class="week_day">' + day_of_week[date_next.getDay()] +  '</span><span class="display_none YYYY_mm">' + date_next.getFullYear()  + '-' + + ('0' + (date_next.getMonth()+1)).slice(-2)+ '</span></li>');
        }
        var next_li = active_li.next();

        next_li.addClass('active_date');
        active_li.removeClass('active_date');
        next_li.trigger('click');        
        setHeight();
        $.getScript( '/../js/event_click.js');
    });
});
$('.campaign_show').on('click',function(e){
    window.history.replaceState({}, document.title, $(this).attr('href') ); 
 /*   location = $(this).attr('href'); */
    e.preventDefault();
    var page = $(this).attr('href');
    $('.index_main').load( page + ' .index_main > section', function() {
        $.getScript('/../js/jquery-ui.js');
        $.getScript( '/../js/sequence_dragDrop.js');
        $.getScript( '/../restfulizer.js');
       /*  $('.collapsible').click(function(event){
            $(this).siblings().toggle();
        }); */
       /*  $('.link_back').click(function(e){
            e.preventDefault();
            $('.campaigns_button').click();
           
        });  */
           /* Radi!!! Load back sa sekvence na kampanje*/
           $('.main_noticeboard .header_document .link_back').on('click', function(e){
            e.preventDefault();
            var url = location['origin'] +'/campaigns';
            
            $('.container').load( url + ' .container > div', function() {
               
                $.getScript( '/../js/datatables.js');
                $.getScript( '/../js/filter_table.js');                    
                $.getScript( '/../restfulizer.js');
                $.getScript( '/../js/event.js');
                $.getScript( '/../js/campaign.js');
                /* $('.collapsible').click(function(event){        
                    $(this).siblings().toggle();
                }); */
                
            });
            window.history.pushState( location.href, 'Title',  url);

         }); 
        $('.campaign_mail').on('click', function(){
            $.modal.defaults = {
                closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
                escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
                clickClose: false,       // Allows the user to close the modal by clicking the overlay
                closeText: 'Close',     // Text content for the close <a> tag.
                closeClass: '',         // Add additional class(es) to the close <a> tag.
                showClose: true,        // Shows a (X) icon/link in the top-right corner
                modalClass: "modal campaign_mail",    // CSS class added to the element being displayed in the modal.
                // HTML appended to the default spinner during AJAX requests.
                spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
            
                showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
                fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
                fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
            };
        });
    });
});
$('.period').on('change',function(){
    if($(this).val() == 'customized') {
        $('#period .period').hide();
        $('#period .period').removeAttr('required');
        $('#interval').show();
        $('input.input_interval').prop( "required" );
    }
});

$('.label_custom_interal').click(function(){
    $('#period .period').hide();
    $('#period .period').removeAttr('required');
    $('#interval').show();
    $('input.input_interval').prop( "required" );
});

$('.label_period').on('click',function(){
    $('#period .period').show();
    $('#period .period').prop('required');
    $('#interval').hide();
    $('input.input_interval').removeAttr( "required" );
});

var form_sequence_height = $('.form_sequence').height();
var header_campaign_height = $('.header_campaign').height();
if($('body').width() > 760) {
    $('.main_campaign').height(form_sequence_height-header_campaign_height);
}

var url = $('form.form_sequence').attr('action');
var html; 
var design; 

try {
    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        id: 'editor-container',
        projectId: 4885,
        displayMode: 'email'
    })
    
    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            /* json = data.design; */ // design json
            html = data.html; // design html
            design = data.design;
         /*    $('#text_html').text(html);
            $('#text_json').text(JSON.stringify(design)); */
          /*   console.log(html);
            console.log(JSON.stringify(design)); */
        })
    })	
} catch (error) {
    
}		

$('.form_sequence.create .btn-submit').on('click',function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('.form_sequence')[0];

    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text_html', html );

    $(".btn-submit").prop("disabled", true);   // disabled the submit button
    
    var form_data_array = $('.form_sequence').serializeArray();

    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    if( html == undefined  || JSON.stringify(design) == undefined ) {
        validate.push("block");
    } else {
        validate.push(true);
    }
 
    if(validate.includes("block") ) {
        e.preventDefault();
        alert("Nisu uneseni svi parametri, nemoguće spremiti sekvencu");
     } else {    
        $(".btn-submit").prop("disabled", false);
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                $(".btn-submit").prop("disabled", false);
            },
            error: function(jqXhr, json, errorThrown) {
				var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };

				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
						
					}
				});
			}
        });
     }  
});

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});

var dataArrTemplates;
var htmlTemplates;
var designTemplates;
var temp;

if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
    /* console.log(dataArrTemplates); */
    
    $.each(dataArrTemplates, function(i, item) {

        var title = item.title;
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});
$('.period').on('change',function(){
    if($(this).val() == 'customized') {
        $('#period .period').hide();
        $('#period .period').removeAttr('required');
        $('#interval').show();
        $('input.input_interval').prop( "required" );
    }
});

$('.label_custom_interal').click(function(){
    $('#period .period').hide();
    $('#period .period').removeAttr('required');
    $('#interval').show();
    $('input.input_interval').prop( "required" );
});
$('.label_period').click(function(){
    $('#period .period').show();
    $('#period .period').prop('required');
    $('#interval').hide();
    $('input.input_interval').removeAttr( "required" );
});
var form_sequence_height = $('.form_sequence').height();
var header_campaign_height = $('.header_campaign').height();

$('.main_campaign').height(form_sequence_height-header_campaign_height);

try {
    var design = JSON.parse( $('.dataArr').text()); // template JSON */
    var html = $('.dataArrHtml').text();
    var form_data = $('.form_sequence').serialize();
    var url = $('form.form_sequence').attr('action');
    var data_new = {};
    var json = '';
    var html = '';
    var id = $('#id').val();

    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        id: 'editor-container',
        projectId: 4885,
        displayMode: 'email'
    })
    unlayer.loadDesign(design);
    
    
    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
    
        /* 	$('#text_html').text( html.replace(/\n\s+|\n/g, ""));
            $('#text_json').text(JSON.stringify(json)); */
        })
    
    })
} catch (error) {
    
}

$('.form_sequence.edit .btn-submit').on('click',function(e) {
    var validate = [];
	e.preventDefault();
	form_data = $('.form_sequence').serialize();
    form_data_array = $('.form_sequence').serializeArray();
    data_new = form_data;
    var form = $('.form_sequence')[0];
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json', JSON.stringify(design) );
    data.append('text_html', html );
    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {
            validate.push("block");
        } else {
            validate.push(true);
        }
      });

    console.log(form_data_array);
    console.log(validate); 

    if(validate.includes("block") ) {
        e.preventDefault();
      
        alert("Nisu uneseni svi parametri, nemoguće spremiti sekvencu");
        
     } else {    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });     
    
        $.ajax({
            type: "post",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                console.log("SUCCESS : ", form_data_array);
                $(".btn-submit").prop("disabled", false);
            },
            error: function(jqXhr, json, errorThrown) {
				var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };

				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
						
					}
				});
			}
        });
     }  
});

$('.btn-back').on('click',function(e){
  /*   e.preventDefault();

    url = location.origin + '/dashboard';
    console.log("url "+ url);
    console.log("referrer "+ document.referrer);
    window.location = url;
    console.log(" window.location = url");
 */
  /*   window.location = location.origin + "/campaign_sequences/" + campaign_id; */
});

var dataArrTemplates;
var htmlTemplates;
var designTemplates;

if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
    
    $.each(dataArrTemplates, function(i, item) {
       /*  html = dataArrTemplates[i].text;  */
        var title = dataArrTemplates[i].title; 
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});


var ctx = $('#myChart');
var dataArr = $('.dataArr').text();   

try {
    if(ctx.length >0) {
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pročitano', 'Nepročitano'],
                datasets: [{
                    label: '# of Votes',
                    data: dataArr,
                    backgroundColor: [
                        'rgba(21, 148, 240, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderColor: [
                        'rgba(21, 148, 240, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'rgb(255, 99, 132)'
                    }
                },
                tooltips: {
                    enabled: false,
                }
            }
        });
    }
} catch (error) {
    
}


var ctx1 = $('#myChart1');
try {
    if(ctx1.length > 0) {
        var myChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Pročitano', 'Nepročitano'],
                datasets: [{
                    label: '# of Votes',
                    data: [25, 75],
                    backgroundColor: [
                        'rgba(7, 30, 87, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderColor: [
                        'rgba(7, 30, 87, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'rgb(255, 99, 132)'
                    }
                },
                tooltips: {
                    enabled: false,
                }
            }
        });
    }
    
} catch (error) {
    
}


var ctx2 = $('#myChart2');
try {
    if(ctx2.length>0) {
        var myChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Pročitano', 'Nepročitano'],
                datasets: [{
                    label: '# of Votes',
                    data: [25, 75],
                    backgroundColor: [
                        'rgba(234, 148, 19, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderColor: [
                        'rgba(234, 148, 19, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'rgb(255, 99, 132)'
                    }
                },
                tooltips: {
                    enabled: false,
                }
            }
        });
    }
    
} catch (error) {
    
}

var ctx3 = $('#myChart3');
try {
    if(ctx3.length >0) {
        var myChart = new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['Pročitano', 'Nepročitano'],
                datasets: [{
                    label: '# of Votes',
                    data: [25, 75],
                    backgroundColor: [
                        'rgba(19, 234, 144, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderColor: [
                        'rgba(19, 234, 144, 1)',
                        'rgba(241, 241, 241, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false,
                    labels: {
                        fontColor: 'rgb(255, 99, 132)'
                    }
                },
                tooltips: {
                    enabled: false,
                }
            }
        });
    }
    
} catch (error) {
    
}


$(function() { 
    $('.collapsible').on('click',function(event){ 
       if($(this).siblings().is(":visible")){ 
            $(this).siblings().css('display','none');
        } else {
            $(this).siblings().css('display','inline-block');
        }
     
    });
    $('.index_page table.dataTable .content').on('mouseleave',function(){
        $(this).hide();
    });

    $('.modal.modal_questionnaire .question .content').on('mouseleave',function(){
        $(this).hide();
    });
    $('.modal.modal_questionnaire .category .content').on('mouseleave',function(){
        $(this).hide();
    });

    $('ul.admin_pages li >span.arrow_down ').on('click',function(event){ 
        if($(this).parent().siblings('.car_links').is(":visible")){ 
            $(this).parent().siblings('.car_links').css('display','none');
         } else {
            $(this).parent().siblings('.car_links').css('display','block');
         }
     });
});
$( function () {
	if( $('table.display').not('.evidention_employee table.display', '.all_absences #index_table').length >0 ) {
		var url = location.href;
		var wrap_col;
		if( url.includes('loccos/')) {
			var wrap_col = "H";
			
		}
		if( url.includes('vehical_services')) {
			var wrap_col = "F";
			
		}
		var kolona = 0;
		var sort = 'asc';
		if ($('#index_table').hasClass('sort_1_asc')) {
			kolona = 1;
			sort = 'asc';
		}
		if ($('#index_table').hasClass('sort_1_desc')) {
			kolona = 1;
			sort = 'desc';
		}	
		if ($('#index_table').hasClass('sort_2_desc')) {
			kolona = 2;
			sort = 'desc';
		}	
		var th_length = $('table.display thead th').not('.not-export-column');
		var target = [];
		var widths = [];
		$(th_length).each(function(index){
			if($(this).hasClass("sort_date") ) {
				target.push(index);
			}
		});
		try {
			jQuery.extend( jQuery.fn.dataTableExt.oSort, {
				"date-eu-pre": function ( date ) {
					date = date.replace(" ", "");
					
					if ( ! date ) {
						return 0;
					}
			 
					var year;
					var eu_date = date.split(/[\.\-\/]/);
					/*year (optional)*/
					if ( eu_date[2] ) {
						year = eu_date[2];
					}
					else {
						year = 0;
					}
			 
					/*month*/
					var month = eu_date[1];
						if (month != undefined &&  month.length == 1 ) {
							month = 0+month;
						}
				
						/*day*/
						var day = eu_date[0];
						if ( day.length == 1 ) {
							day = 0+day;
						}
				
						return (year + month + day) * 1;
				
				},
			 
				"date-eu-asc": function ( a, b ) {
					return ((a < b) ? -1 : ((a > b) ? 1 : 0));
				},
			 
				"date-eu-desc": function ( a, b ) {
					return ((a < b) ? 1 : ((a > b) ? -1 : 0));
				}
			} );
		} catch (error) {
		/* 	target = null; */
		}
		
		if($('table.display').length >0) {
			var table = $('table.display').not('.evidention_employee table.display', '.all_absences #index_table').DataTable( {
				"language": {
					"search": "",
					"searchPlaceholder": "Search"
				},
				"lengthMenu": [ 10, 25, 50, 75, 100 ],
				"pageLength": 50,
				"paging": false,
				"searching": true,
				"ordering": true,
				"order": [ kolona, sort ],
				"info":     true,
				"bDestroy": true,
				"lengthChange": true,
				"fixedHeader": false,
				"colReorder": true,
				"columnDefs": [ {
					"targets"  :target,
					"type": 'date-eu'
				}],
				stateSave: true,
				dom: 'Bfrtip',
				buttons: [
				/* 	'copyHtml5',
					{
						extend: 'print',
						exportOptions: {
							columns: 'th:not(.not-export-column)',
							rows: ':visible'
						},
						customize: function ( win ) {
							$(win.document.body).find('h1').addClass('title_print');
							$(win.document.body).find('table').addClass('table_print');
							$(win.document.body).find('table tr td').addClass('row_print');
							$(win.document.body).addClass('body_print');
							$(win.document.body).find('table tr th').addClass('hrow_print');
							$(win.document.body).find('table tr th:last-child').addClass('not_print');
							$(win.document.body).find('table tr td:last-child').addClass('not_print');
						
							var last = null;
							var current = null;
							var bod = [];
			
							var css = '@page { size: landscape; }',
								head = win.document.head || win.document.getElementsByTagName('head')[0],
								style = win.document.createElement('style');
			
							style.type = 'text/css';
							style.media = 'print';
			
							if (style.styleSheet)
							{
							style.styleSheet.cssText = css;
							}
							else
							{
							style.appendChild(win.document.createTextNode(css));
							}
			
							head.appendChild(style);
						
						}
					}, */
					{
						extend: 'pdfHtml5',
						orientation: 'landscape',
						pageSize: 'A4',
						download: 'open',
						exportOptions: {
							columns: 'th:not(.not-export-column)',
							rows: ':visible'
						},
						customize: function( doc ) {
							doc.defaultStyle.fontSize = 8;
							var count_col = table.columns(':not(.not-export-column)').count();
							 	console.log(doc); 
							/* var width = (100/count_col) + '%';
							for (let index = 0; index < th_length.length; index++) {
								widths.push(width);
							} 
							doc.content[1].table.widths = widths; */
							doc.styles.tableHeader = {
								color: 'black',
								background: 'grey',
								alignment: 'center',
							}
							doc['footer']=(function(page, pages) {
								return {
								columns: [
									'Broj strana',
									{
										alignment: 'right',
										text: [
											{ text: page.toString(), italics: true },
											' of ',
											{ text: pages.toString(), italics: true }
										]
									}
								],
								margin: [30, 10]
								}
							});
							doc.styles = {
								table: {
									fontSize: 8,
								},
								subheader: {
									fontSize: 8,
									bold: true,
									color: 'black'
								},
								tableHeader: {
									bold: true,
									fontSize: 8,
									color: 'black'
								},
								lastLine: {
									bold: true,
									fontSize: 8,
									color: 'blue'
								},
								defaultStyle: {
									fontSize: 8,
									color: 'black'
								}
							}
							
							var objLayout = {};
							objLayout['hLineWidth'] = function(i) { return .8; };
							objLayout['vLineWidth'] = function(i) { return .5; };
							objLayout['hLineColor'] = function(i) { return '#aaa'; };
							objLayout['vLineColor'] = function(i) { return '#aaa'; };
							/* objLayout['paddingLeft'] = function(i) { return 8; };
							objLayout['paddingRight'] = function(i) { return 8; }; */
							doc.content[1].layout = objLayout;
						}
					},
					{
						extend: 'excelHtml5',
						autoFilter: true,
						exportOptions: {
							columns: 'th:not(.not-export-column)',
							rows: ':visible'
						},
						customize: function( xlsx ) {
							var sheet = xlsx.xl.worksheets['sheet1.xml'];
							$('row:first c', sheet).attr( 's', '2' );
						/* 	console.log(xlsx); */
							var pageSet = sheet.createElement("pageSetup");
							sheet.childNodes["0"].appendChild(pageSet);
							var pageSetup = sheet.getElementsByTagName("pageSetup")[0];
							pageSetup.setAttribute("paperSize", "8");
							pageSetup.setAttribute("orientation", "landscape");
							pageSetup.setAttribute("r:id", "rId1"); 
							
							var sheet1 = xlsx.xl['styles.xml'];
							var tagName = sheet1.getElementsByTagName('sz');
							for (i = 0; i < tagName.length; i++) {
								tagName[i].setAttribute("val", "9")
							}
							$('row c', sheet).each(function() {
								$(this).attr('s', '25');
							});
							var col = $('col', sheet);
							//set the column width otherwise it will be the length of the line without the newlines
							//$(col[1]).attr('width', 50);
							$('row c[r^="'+wrap_col+'"]', sheet).each(function() {
								if ($('is t', this).text()) {
									//wrap text
									$(this).attr('s', ['55']);
								}
							});
						}	
					}
				]
			});
		}
		if($(".index_table_filter .show_button").length == 0) {
			$('.index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
		}
	
		$('.show_button').on('click',function () {
			$('.index_page .dt-buttons').toggle();		
		})
		$('table.display').show();
	}
});

$( function () {
	if( $('.evidention_employee').length > 0) {
		if ( ! $.fn.DataTable.isDataTable( '.evidention_employee table.display' ) ) {
			var table = $('.evidention_employee table.display').DataTable( {
				language: {
					"search": "",
					"searchPlaceholder": "Search"
				},
				pageLength: 50,
				paging: false,
				searching: false,
				ordering: false,
				order: [],
				info:     true,
				bDestroy: false,
				lengthChange: true,
				fixedHeader: true,
				colReorder: true,
				responsive: true,
				columnDefs: [ {
					"targets"  : 'no-sort',
					"orderable": false,
					"order": []
				}],
				stateSave: true,
				dom: 'Bfrtip',
				buttons: [
					/* 'copyHtml5',
					{
						extend: 'print',
						orientation: 'landscape',
						pageSize: 'A3',
						exportOptions: {
							columns: 'th:not(.not-export-column)',
							rows: ':visible',
							orientation: 'landscape',
							pageSize: 'A3',
						},
						customize: function ( win ) {
							$(win.document.body).find('h1').addClass('title_print');
							$(win.document.body).find('table').addClass('table_print');
							$(win.document.body).find('table tr td').addClass('row_print');
							$(win.document.body).addClass('body_print');
							$(win.document.body).find('table tr th').addClass('hrow_print');
							$(win.document.body).find('table tr th:last-child').addClass('not_print');
							$(win.document.body).find('table tr td:last-child').addClass('not_print');
						}
					}, */
					{
						extend: 'pdfHtml5',
						footer: true,
						orientation: 'landscape',
						pageSize: 'A3',
						defaultStyle: {
							fontSize: 6,
							color: 'black'
						},
						exportOptions: {
							columns: 'th:not(.not-export-column)',
							rows: ':visible'
						},
						customize: function( doc ) {
						
							/*	var count_col = table.columns(':not(.not-export-column)').count();
								var width = (100/count_col) + '%';
								doc.content[1].table.widths = [width,width,width,width,width]
							} */
							doc.styles.tableHeader = {
								color: 'black',
								background: 'lightgrey',
								alignment: 'center'
							}
							doc.defaultStyle = {
								fontSize: 8
							}
							doc.styles = {
								subheader: {
									fontSize: 10,
									bold: true,
									color: 'black'
								},
								tableHeader: {
									bold: true,
									fontSize: 8,
									color: 'black'
								},
								lastLine: {
									bold: true,
									fontSize: 10,
									color: 'blue'
								},
								defaultStyle: {
									fontSize: 8,
									color: 'black'
								}
							}
							var rowCount = doc.content[1].table.body.length;
							
							for (i = 1; i < rowCount; i++) {
								var columnsCount = doc.content[1].table.body[i].length;
								var align;
								
								for (j = 1; j < columnsCount; j++) {
									if(j == 0 || j == columnsCount-1) {
										align = 'right';
									} else if (j == 1 ) {
										align = 'left';
									} else {
										align = 'center';
									}
									
									doc.content[1].table.body[i][j].alignment = align;
									if( i == 3 || i == 8 || i == 15 || i == 17) {
										doc.content[1].table.body[i][j].fillColor = 'lightgrey';
									}
									if( i == 24 ) {
										doc.content[1].table.body[i][j].bold = true;
										doc.content[1].table.body[i][j].fontSize = 10;
										doc.content[1].table.body[i][j].fillColor = 'lightgrey';
									}
								}
							}
							var objLayout = {};
							objLayout['hLineWidth'] = function(i) { return .8; };
							objLayout['vLineWidth'] = function(i) { return .5; };
							objLayout['hLineColor'] = function(i) { return '#aaa'; };
							objLayout['vLineColor'] = function(i) { return '#aaa'; };
							/* objLayout['paddingLeft'] = function(i) { return 8; };
							objLayout['paddingRight'] = function(i) { return 8; }; */
							doc.content[1].layout = objLayout;
						},
					},
					{
						extend: 'excelHtml5',
						autoFilter: false,
						footer: true,
						createEmptyCells: true, 
						orientation: 'landscape',
						pageSize: 'A2',
						exportOptions: {
							columns: 'th:not(.not-export-column)',
							rows: ':visible',
							
						},
					
						customize: function( xlsx ) {
							var sheet = xlsx.xl.worksheets['sheet1.xml'];
							$('row:first c', sheet).attr( 's', '2' );
						
							var pageSet = sheet.createElement("pageSetup");
							sheet.childNodes["0"].appendChild(pageSet);
							var seiteneinstellung = sheet.getElementsByTagName("pageSetup")[0];
							seiteneinstellung.setAttribute("paperSize", "8");
							seiteneinstellung.setAttribute("orientation", "landscape");
							seiteneinstellung.setAttribute("r:id", "rId1"); 
							$('row c', sheet).each(function() {
								$(this).attr('s', '25');
							});
							var col = $('col', sheet);
							//set the column width otherwise it will be the length of the line without the newlines
							
							$(col[1]).attr('width', 50);
							$('row c[r^="B"]', sheet).each(function() {
								if ($('is t', this).text()) {
									//wrap text
									$(this).attr('s', '55');
								}
							});
							var sheet = xlsx.xl['styles.xml'];
							var tagName = sheet.getElementsByTagName('sz');
							for (i = 0; i < tagName.length; i++) {
							tagName[i].setAttribute("val", "8")
							}
						}	
					}
				]
			});
		}
		$('.show_button').on('click',function () {
			$('.index_page .dt-buttons').toggle();		
		})
	}
});

$( function () {

    var body_width = $('body').width();
    var div_width = $( '.preview_doc').width();
    var all_width = 115;
   
    $( ".preview_doc > .thumbnail" ).each( (index, element) => {
        all_width += 115;
    });

    if(all_width > div_width ) {
        $('.preview_doc .scroll_right').show();
    }
    /* $('.collapsible').click(function(event){ 
        $(this).siblings().toggle();
    }); */
    $('#right-button').on('click',function(event) {
        event.preventDefault();
        $('.preview_doc').animate({
            scrollLeft: "+=115px"
        }, "slow");
        $('.preview_doc .scroll_left').show();
        
    });

    $('.thumbnail').each(function(){
        var src = $(this).attr('title');
    //	$( this ).find('.ajax-content').load(src);
    });

    $('#left-button').on('click',function(event) {
        event.preventDefault();
        $('.preview_doc').animate({
            scrollLeft: "-=115px"
        }, "slow");
        if($('.preview_doc').scrollLeft() < 115 ) {
            $('.preview_doc .scroll_left').hide();
        } else {
            $('.preview_doc .scroll_left').show();
        }
    });
    
    var documents_height = $('.all_documents').height();
    var filter_height = $('.dataTables_filter').height();
    var table_height = documents_height - filter_height;
    var body_height;
//    $('.display.table.dataTable').height(table_height);

    var index_height = $('.index_main.main_documents').height();
	var header_height = $('.page-header.header_document').height();
    
    if(body_width<768) {
	 //   $('.all_documents').css('height','auto');
    } 

	$('.show').on('click',function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').show();
        
        index_height = $('.index_main.main_documents').height();
        header_height = $('.page-header.header_document').height();
        body_height = index_height - header_height - 60;
        $('.all_documents').height(body_height);
    });
    
    $('.hide').on('click',function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').hide();
        index_height = $('.index_main.main_documents').height();
        header_height = $('.page-header.header_document').height();
        body_height = index_height - header_height - 60;
        $('.all_documents').height(body_height);
    });
    
    $('.button_nav').css({
       /*  'background': '#051847',
        'color': '#ffffff' */
    });
    
    $( '.doc_button' ).css({
       /*  'background': '#0A2A79',
        'color': '#ccc' */
    });
    
    $(function() {
		 $('#index_table').css('height','fit-content');
    });

});
	$('.efc_show').on('click',function(){
       // $('.efc').css('visibility','initial');
       $('.salery_hidden').hide();
       $('.salery_show').toggle();
       $('.salery_show').css('display','block');
        $('.efc_show').hide();
        $('.efc_hide').show();
    });
    $('.efc_hide').on('click',function(){
       // $('.efc').css('visibility','hidden');
       $('.salery_hidden').toggle();
       $('.salery_hidden').css('display','block');
       $('.salery_show').toggle();
        $('.efc_show').show();
        $('.efc_hide').hide();
    });
$(function() {
    var url_basic = location.origin + '/events';
    var calendar_main_height;
    var calendar_aside_height;
    var body_width = $('body').width();
    var view;
    var data1;
    if( $('.dataArr').text()) {
        var data1 = JSON.parse( $('.dataArr').text());
    }
    /*
    var data1 = [];
    for (i = 0; i < data.length; i++) { 
        var txt = '{"name": "' + data[i].name + '","date":"' + data[i].date + '"}'
        data1.push(JSON.parse(txt));
    }
    */
   if( $('.calender_view').length >0) {
        $('.calender_view').pignoseCalendar({
        multiple: false,
        week: 1,
       
        init: function(contex) {
            calendar_aside_height = $('.calendar_aside').height();
            calendar_main_height = $('.calendar_main').height();
            if($('body').width() > 450) {
                $('.index_aside .day_events').height(calendar_aside_height -calendar_main_height - 110 );   
            } else {
                $('.index_aside .day_events').height(calendar_aside_height -calendar_main_height - 60 );
            }
                   
        },
        scheduleOptions: {
            colors: {
                event: '#1390EA',
                task: '#eb0e0e',
                birthday: '#EA9413',
                GO: '#13EA90',
                IZL: '#13EA90',
                BOL: '#13EA90',
                locco: '#009933',
            }
        },
        schedules: data1,
            select: function(date, schedules, context) {
                /**
                 * @params this Element
                 * @params event MouseEvent
                 * @params context PignoseCalendarContext
                 * @returns void
                 */
                var $this = $(this); // This is clicked button Element.
                if(date[0] != null && date[0] != 'undefined') {
                    if(date[0]['_i'] != 'undefined' && date[0]['_i'] != null) {
                        var day = date[0]['_i'].split('-')[2];
                        var month = date[0]['_i'].split('-')[1]; // (from 0 to 11)
                        var year = date[0]['_i'].split('-')[0];
                        var datum = year + '-' + month + '-' + day;
                        view = $('.change_view_calendar').val();
                        var url = url_basic + '?dan=' + datum;
                      
                        get_url(url, datum);

                        if(body_width < 768) {
                            $('.index_main.index_event').modal();
                        }  
                    }
                }
                
            },
            prev: function(info, context) {
                // This is clicked arrow button element.
                var $this = $(this);

                // `info` parameter gives useful information of current date.
                var type = info.type; // it will be `prev`.
                var year = info.year; // current year (number type), ex: 2020
                var month = info.month; // current month (number type), ex: 2
                var day = info.day; // current day (number type), ex: 22
               
                // You can get target element in `context` variable.
                var element = context.element;

                // You can also get calendar element, It is calendar view DOM.
                var calendar = context.calendar;
               
                var prevDate = new Date(year + '-' + month + '-' + day);
                var month_before = prevDate.getMonth()+1; 
                var searchDate = year + '-' + ('0' + (month_before) ).slice(-2) + '-' + ('0' + (day)).slice(-2);
                
               /*  $('.pignose-calendar-unit-date').find('[data-date="' + searchDate + '"] > a' ).click(); */

                var url = url_basic + '?dan=' + searchDate;
               
                get_url(url, searchDate);

            },
            next: function(info, context) {
                /**
                 * @params context PignoseCalendarPageInfo
                 * @params context PignoseCalendarContext
                 * @returns void
                 */

                // This is clicked arrow button element.
                var $this = $(this);

                // `info` parameter gives useful information of current date.
                var type = info.type; // it will be `next`.
                var year = info.year; // current year (number type), ex: 2017
                var month = info.month; // current month (number type), ex: 6
                var day = info.day; // current day (number type), ex: 22
               
                // You can get target element in `context` variable.
                var element = context.element;

                // You can also get calendar element, It is calendar view DOM.
                var calendar = context.calendar;

                var currentDate = new Date(year + '-' + month + '-' + day);
                var month_after = currentDate.getMonth() +1; 
                var searchDate = year + '-' + ('0' + (month_after) ).slice(-2) + '-' + ('0' + (day)).slice(-2);                
               
                var url = url_basic + '?dan=' + searchDate;

                get_url(url, searchDate);
            }   
        });
   }
    
   $('.index_aside .day_events').show();

    function get_url(url, datum ) {
        $.get(url, { dan: datum }, function(data, status){
            var content =  $('.day_events>div',data ).get(0).outerHTML;
            $( ".day_events" ).html( content );
            $('.index_aside .day_events').show();
            var content_2 = $('.index_event>section',data ).get(0).outerHTML;
            $( ".index_event" ).html( content_2 );
            /* var content_3 = $('.calender_view>.pignose-calendar ',data ).get(0).outerHTML;
            $( ".calender_view" ).html( content_3 );  */
            $('.main_calendar_month tbody td').on('click',function(){
                var date = $(this).attr('data-date');
                $('.pignose-calendar-body').find('[data-date="' + date + '"] > a' ).trigger("click");
            });
        
            $( ".change_employee" ).on('change',function() {
                var value = $(this).val().toLowerCase();
                $(".show_event").filter(function() {
                    //$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    $(this).toggle($(this).hasClass(value));
                });
                $(".month_event").filter(function() {
                    //$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    $(this).toggle($(this).hasClass(value));
                });
                if(value == '') {
                    $(".show_event").show();
                    $(".month_event").show();
                }
            });

            $( ".change_car" ).on('change',function() {
                var value = $(this).val().toLowerCase();
                console.log(value);
                $(".show_locco").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    
                });
                if(value == '') {
                    $(".show_locco").show();
                }
            });
            
            $( ".change_view_calendar" ).on('change',function() {
                view = $( this ).val();
                if(view == 'day') {
                    $('.main_calendar_day').show();
                    $('.main_calendar_week').hide();
                    $('.main_calendar_month').hide();
                    $('.main_calendar_list').hide();
                    $('button.show_locco').show();
                    var scroll = $('.hour_val.position_8').position().top;
                    $('.main_calendar_day').scrollTop(scroll);
                } 
                if(view == 'week') {
                    $('.main_calendar_day').hide();
                    $('.main_calendar_week').show();
                    $('.main_calendar_month').hide();
                    $('.main_calendar_list').hide();
                    $('button.show_locco').show();
                    var scroll = $('.main_calendar_week tr.position_8').position().top;
                    $('.main_calendar_week').scrollTop(scroll);
                } 
                if(view == 'list') {
                        $('.main_calendar_list').show();
                        $('.main_calendar_day').hide();
                        $('.main_calendar_week').hide();
                        $('.main_calendar_month').hide();
                        $('.change_car').hide();
                        $('button.show_locco').hide();
                    } 
                if(view == 'month') {
                        $('.main_calendar_day').hide();
                        $('.main_calendar_week').hide();
                        $('.main_calendar_month').show();
                        $('.main_calendar_list').hide();
                        $('button.show_locco').show();

                }
            });
            
            $('button.show_loccos').on('click',function(e){
                e.preventDefault();
                $('.main_calendar td>a').toggle();
                $('.main_calendar .show_event').toggle();
                $('.main_calendar .show_locco ').toggle();
                $('.change_employee').toggle();
                $('.change_car').toggle();
                console.log("show_loccos");
        
            });
            
            var position_selected_day = $('.selected_day').position().top;
            $('.main_calendar_month').scrollTop(position_selected_day);
        
            select_view();
        });
    }
    function select_view() {
        if(view == 'day') {
            $('.change_view_calendar').val('day') ;
            $('.main_calendar').hide();
            $('.main_calendar_day').show();
            
        } else if(view == 'month') {
            $('.change_view_calendar').val('month') ;                                
            $('.main_calendar').hide();
            $('.main_calendar_month').show();
           
        } else if(view == 'week') {
            $('.change_view_calendar').val('week') ;
            $('.main_calendar').hide();
            $('.main_calendar_week').show();
        } else if(view == 'list') {
            $('.change_view_calendar').val('list') ;
            $('.main_calendar').hide();
            $('.main_calendar_week').show();
        }
    }
});
$('.shortcuts_container .shortcut').on('click',function(){
    $('.icon_delete').toggle();
    if($('.shortcuts_container .profile_images .profile_img.new_open').length ==0) {
        $('.shortcuts_container .profile_images').append('<span class="profile_img new_open"><span><span><i class="fas fa-plus"></i></span><span class="shortcut">Dodaj prečac</span></span></span>');
        $('.shortcuts_container .new_open').on('click',function(){
            $('<div><div class="modal-header">Novi prečac</div><div class="modal-body" style="padding-top: 20px"><p>Da biste dodali prečac otvorite stranicu koju želite i u gornjem desnom kutu pronađite link za spremanje "Prečaca"</p><p>Ukoliko Prečac već postoji na stranici imate mogućnost promijeniti naslov prečaca</p></div></div>').modal();
        });
    } else {
        $('.shortcuts_container .profile_images .profile_img.new_open').remove();
    }
    
});

$('.shortcuts_container .open_new_shortcut').on('click',function(){
    $('<div><div class="modal-header">Novi prečac</div><div class="modal-body" style="padding-top: 20px"><p>Da biste dodali prečac otvorite stranicu koju želite i u gornjem desnom kutu pronađite link za spremanje "Prečaca"</p><p>Ukoliko Prečac već postoji na stranici imate mogućnost promijeniti naslov prečaca</p></div></div>').modal();
});

 var shortcuts_container_width = $('.shortcuts_container').first().width();
var shortcut_box_width = shortcuts_container_width / 6;
/* $('.shortcut_box').width(shortcut_box_width-15); */

$('#right-button-scroll').on('click',function(event) {
    event.preventDefault();
    $('.shortcuts_container .profile_images').animate({
        scrollLeft: "+=127px"
    }, "slow");
    $('.profile_images .scroll_left').show();
});
$('#left-button-scroll').on('click',function(event) {
    event.preventDefault();
    $('.shortcuts_container .profile_images').animate({
        scrollLeft: "-=127px"
    }, "slow");
});
$('.event_show .type_event').on('click',function(){
    $('.event_hidden').show();
    $('#event_type').val('event');
    $('.event_show').hide();
});
$('.event_show .type_task').on('click',function(){
    $('.event_hidden').show();
    $('#event_type').val('task');
    $('.event_show').hide();
});
$('.event_show .type_other').on('click',function(){
    $('.event_hidden').show();
    $('#event_type').val('other');
    $('.event_show').hide();
});
$( document ).ready(function(){
	$("#mySearch").keyup( function() {
		var value = $(this).val().toLowerCase();
		$(".panel").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	$("#mySearch1").keyup( function() {
		var value = $(this).val().toLowerCase();
		$(".panel1").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
	$("#mySearch_noticeboard").keyup( function() {
		var value = $(this).val().toLowerCase();
		$(".panel").parent().filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
});


$(function() { // filter knowledge base
	var value = null;
	var date = null;
	var employee_id = null;
	var url;
	var data_to_send;

	$('#filter').on('change',function() {
		value = $('#filter').val().toLowerCase();
		if(value == "all"){
			$('.panel').show();
		} else {
			$('.panel').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
			});
		}
	});	
	
	$( ".first_view_header .change_employee_work" ).on('change',function() {
		employee_id =  $(this).val().toLowerCase();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}
		date =  $('.first_view_header .change_month').val();
		
		url =  location.href + '?date='+date+'&employee_id='+employee_id;

		console.log(date);
		console.log(employee_id);
		console.log(url);
		$.ajax({
			url: url,
			type: "get",
			success: function( response ) {
				$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
					$.getScript('/../restfulizer.js');
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
				});
			},
			error: function(jqXhr, json, errorThrown) {
				data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };
				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
					}
				});
			}
		});
	});
	
	$( ".second_view_header .change_employee_work" ).on('change',function() {
		employee_id =  $(this).val().toLowerCase();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}
		date =  $('.second_view_header .change_month').val();
		
		url =  location.href + '?date='+date+'&employee_id='+employee_id;

		console.log(date);
		console.log(employee_id);
		console.log(url);
		$.ajax({
			url: url,
			type: "get",
			success: function( response ) {
				console.log(response);
				$('.main_work_records').load(url + " .main_work_records .second_view",function(){
					$( ".td_izostanak:contains('GO')" ).each(function( index ) {
						$( this ).addClass('abs_GO');
					});
					$( ".td_izostanak:contains('BOL')" ).each(function( index ) {
						$( this ).addClass('abs_BOL');
					});
					$('table').show();

					$.getScript('/../restfulizer.js');
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
				});
			},
			error: function(jqXhr, json, errorThrown) {
				data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };
				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
					}
				});
			}
		});
	});
	
	$( ".first_view_header .change_month" ).on('change',function() {
		date =  $(this).val().toLowerCase();
		employee_id =  $('.first_view_header .change_employee_work').val();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}

		url =  location.href + '?date='+date+'&employee_id='+employee_id;

		console.log(date);
		console.log(employee_id);
		console.log(url);
		$.ajax({
			url: url,
			type: "get",
			success: function( response ) {
				$( '#admin_page >main' ).load(url + ' #admin_page >main .table-responsive',function(){
					$.getScript('/../restfulizer.js');
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
				});
			},
			error: function(jqXhr, json, errorThrown) {
				data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };
				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
					}
				});
			}
		});
		/* if($(this).val() != undefined) {
			date = $(this).val().toLowerCase();
			url = location.origin + '/work_records'+ '?date='+date;
			$.ajax({
				type: "GET",
				date: { 'date': date },
				url: url, 
				success: function(response) {
					$('tbody').load(url + " tbody tr");
				},
				error: function(jqXhr, json, errorThrown) {
					data_to_send = { 'exception':  jqXhr.responseJSON.exception,
										'message':  jqXhr.responseJSON.message,
										'file':  jqXhr.responseJSON.file,
										'line':  jqXhr.responseJSON.line };
					$.ajax({
						url: 'errorMessage',
						type: "get",
						data: data_to_send,
						success: function( response ) {
							$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
						}, 
						error: function(jqXhr, json, errorThrown) {
							console.log(jqXhr.responseJSON); 
						}
					});
				}
			}); 
		} */
	});

	$( ".second_view_header .change_month" ).on('change',function() {
		date =  $(this).val().toLowerCase();
		employee_id =  $('.second_view_header .change_employee_work').val();
		if(employee_id != '' && employee_id != null) {
			employee_id = employee_id.replace("empl_","");
		}

		url =  location.href + '?date='+date+'&employee_id='+employee_id;

		$.ajax({
			url: url,
			type: "get",
			success: function( response ) {
				$('.main_work_records').load(url + " .main_work_records .second_view",function(){
					$( ".td_izostanak:contains('GO')" ).each(function( index ) {
						$( this ).addClass('abs_GO');
					});
					$( ".td_izostanak:contains('BOL')" ).each(function( index ) {
						$( this ).addClass('abs_BOL');
					});
					$('table').show();

					$.getScript('/../restfulizer.js');
					/* $.getScript('/../js/datatables.js'); */
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
				});
			},
			error: function(jqXhr, json, errorThrown) {
				var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };
				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
					}
				});
			}
		});
	});

	$('.filter_fuels').on('change',function() {
		var date =  $('#filter_month').val().toLowerCase();
		var car =  $('#filter_car').val().toLowerCase();
		if(date == 'all' ) {
			date = '';
		} 
		if(car == 'all') {
			car = '';
		}
	
		if(date == '' && car == ''){
			$('tbody tr').show();
		} else {
			$('tbody tr').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(date) > -1 && $(this).text().toLowerCase().indexOf(car) > -1);
			});
		}
	});	
	
	$('.filter_loccos').on('change',function() {
		var date =  $('#filter_month').val().toLowerCase();
		console.log(date);

		$.ajax({
			url: location.href + '?date='+date,
			type: "get",
			data: { 'date': date},
			success: function( response ) {
				$( '#admin_page >main' ).load(location.href + '?date='+date + ' #admin_page >main .table-responsive',function(){
					var title = $(document).prop('title'); 
					title = title.substring(0, title.indexOf(','));
					console.log(title);
					title += ', ' +date;

					$(document).prop('title', title);  
					$.getScript('/../restfulizer.js');
					$.getScript('/../js/datatables.js');
					$('.show_button').on('click',function () {
						$('.index_page .dt-buttons').toggle();		
					})
				});
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		});
		
	/* 	var date =  $('#filter_month').val().toLowerCase();
		console.log(date);
		if(date == 'all' ) {
			date = '';
		} 
		
		if(date == ''){
			$('tbody tr').show();
		} else {
			$('tbody tr').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(date) > -1);
			});
		}
		var title = $(document).prop('title'); 
		title += ' - ' +date;
		$(document).prop('title', title); */
	});	

	$('.filter_travel').on('change',function() {
		var employee_id = $('#filter_employee').val().toLowerCase();
		var date = $('#filter_date').val().toLowerCase();
		var url = location.origin + '/travel_orders';

		if(employee_id == 'all' ) {
			employee_id = '';
		} 
		if(date == 'all') {
			date = '';
		}

		if(employee_id == '' && date == ''){
			$('.panel').show();
		} else {
			$('.panel').filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(date) > -1 && $(this).text().toLowerCase().indexOf(employee_id) > -1);
			});
		}
		/* $.ajax({
			url: url,
			type: "get",
			data: { 'employee_id': employee_id,'date': date},
			success: function( response ) {
				console.log(response);
				$( '#admin_page' ).load( url + '?employee_id='+employee_id+'&date='+date, function( response, status, xhr ) {
					 
					if ( status == "error" ) {
						  var msg = "Sorry but there was an error: ";
						  $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
					  }
					
					  $('#filter_employee option.id_'+ employee_id).attr('selected','selected');
					  $('#filter_date option.date_'+ date).attr('selected','selected');
					  $.getScript( '/../restfulizer.js');
				  });
				
			},
			error: function(jqXhr, json, errorThrown) {
				console.log(jqXhr.responseJSON.message);
			}
		}); */
	/* 	$('#filter_employee option.id_'+ employee_id).attr('selected','selected');
		$('#filter_date option.date_'+ date).attr('selected','selected'); */
	});	
});
function mySearchTable() {
  $("#mySearchTbl").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    
    $("#index_table tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    $("#index_table1 tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}

function mySearchTableAbsence() {
  $("#mySearchTbl").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    var godina = $('#year_vacation').val();
    $("#index_table tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
}

function mySearchDoc() {
  $("#mySearch").on('keyup',function() {
    var value = $(this).val().toLowerCase();
    $("#index_table tbody tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    $(".panel").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
  });
}

function mySearch_col1() {
  // Declare variables
  var input, filter, ul, li, a, i;
  input = document.getElementById("mySearch");
  filter = input.value.toUpperCase();
  ul = document.getElementById("myTable");
  li = ul.getElementsByTagName("tr");

  // Loop through all list items, and hide those who don't match the search query
  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName("td")[0];
	if (a != undefined){
		if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
		  li[i].style.display = "";
		} else {
		  li[i].style.display = "none";
		}
	}
  }
}

function mySearchElement() {
  $("#mySearchElement").on('keyup', function() {
		var value = $(this).val().toLowerCase();
		$(".user_card").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});
}
function GO_value(){
	if(document.getElementById("prikaz").value == "GO" ){
		document.getElementById("napomena").value = "GO" ;
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}else {
		document.getElementById("napomena").value = "" ;
	}
	if(document.getElementById("prikaz").value == "Bolovanje" ){
		document.getElementById("zahtjev").innerHTML = "Obavijest";
	}
	if(document.getElementById("prikaz").value == "Izlazak"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	if(document.getElementById("prikaz").value == "NPL" ||document.getElementById("prikaz").value == "PL"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	if(document.getElementById("prikaz").value == "SLD"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	if(document.getElementById("prikaz").value == "Vik"){
		document.getElementById("zahtjev").innerHTML = "Zahtjev";
	}
	
}
$(function() {

    var day = $('.event_day .day').text();
    var month =   $('.event_day .month').text();
    var year =  $('.event_day .year').text();
    var day_of_week = new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat");
    var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];

    var currentDate = new Date(year + '-' + month + '-' + day);
    
    $('.arrow .day_before').click(function(){
        var date_before = new Date(currentDate.setDate(currentDate.getDate() -1));
        var searchDate_bef = date_before.getFullYear() + '-' + ('0' + (date_before.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_before.getDate()) ).slice(-2);
        $('.event_day .day').text(date_before.getDate());
        $('.event_day .week_day').text(day_of_week[date_before.getDay()]);
        $('.month_year').text(monthNames[date_before.getMonth()] + ' ' +  date_before.getFullYear());
        $('.pignose-calendar-body').find('[data-date="' + searchDate_bef + '"] > a' ).click();

   });

    $('.arrow .day_after').click(function(){
        var date_after = new Date(currentDate.setDate(currentDate.getDate() +1));
        var searchDate = date_after.getFullYear() + '-' + ('0' + (date_after.getMonth() +1) ).slice(-2) + '-' + ('0' + ( date_after.getDate()) ).slice(-2);
        $('.event_day .day').text(date_after.getDate());
        $('.event_day .week_day').text(day_of_week[date_after.getDay()]);
        $('.month_year').text(monthNames[date_after.getMonth()] + ' ' + date_after.getFullYear());
        $('.pignose-calendar-body').find('[data-date="' + searchDate + '"] > a' ).click();

    });
});

$('.side_navbar a.link1').click(function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');   
	$('.container').load(page + ' .container .row');
	
	$('.side_navbar a').removeAttr("style");
	$('.nav li').removeAttr("style");
	$('.nav .link_ads').removeAttr("style");
	$('.link_admin').removeAttr("style");
	$(this).css('color','orange');
});
$('nav.navbar a').on("click",function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');  
	$('.container').load(page + ' .container .row');
	
	$('nav a').removeAttr("style");
	$('.side_navbar a').removeAttr("style");
	$(this).css('color','orange');
});

$('.side_navbar a.link3').on("click",function(e){ 

//	e.preventDefault(); // cancel click
	var page = $(this).attr('href'); 

//	$('.container').load(page + ' .container .row .calender_view', function()
//	{ $.getScript("node_modules/moment/moment.js");
//	$.getScript("node_modules/pg-calendar/dist/js/pignose.calendar.min.js");
//	
//	}
//	);
	
	$('nav li').removeAttr("style");
	$('.side_navbar a').removeAttr("style");
	$(this).css('color','orange');
});
var prev_url = location.href;
var url_modul;
/* $(function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var header_height = $('header.header_nav').height();
    var user_header_height = $('.user_header').height();
    var container_height = $('.container').height();
    var section_top_nav_height = $('.section_top_nav').height();
    var user_header_info_height = $('.user_header_info').height();
    var posts_height;
   
   if(body_width > 990) {
      
        $('.container > .calendar').height(container_height - user_header_height -20);  
        $('.container > .posts').height(container_height - user_header_height -20);  
    }
});

$( window ).on('resize',function() {
    var body_width = $('body').width();
    var body_height = $('body').height();
    var header_height = $('header.header_nav').height();
    var user_header_height = $('.user_header').height();
    var container_height = $('.container').height();
    var section_top_nav_height = $('.section_top_nav').height();
    var user_header_info_height = $('.user_header_info').height();
    var posts_height;
    if(body_width>990) {
        $('.container > .calendar').height(container_height - user_header_height -20);  
        $('.container > .posts').height(container_height - user_header_height -20);  
    }
});  */

var section_top_width =  $('.section_top_nav').width();

function myTopNav() {
    var x = $(".section_top_nav");
    if (x.hasClass("responsive")) {
        x.removeClass("responsive");
    } else {
        x.addClass("responsive");
    } 
}

$('.logo_icon').on('click',function(){
    $('.section_top_nav').css('width','250px');
    $('#myTopnav:not(".responsive")').css('display','block');
    $('#myTopnav:not(".responsive")').css('width','250px');
    $('.header_nav .section_top_nav .close_topnav svg').show();
});

$('.close_topnav').on('click',function(){
    $('.header_nav .section_top_nav .close_topnav svg').hide();
    $('#myTopnav:not(".responsive")').css('display','none');
    $('#myTopnav:not(".responsive")').css('width',0);
    $('.section_top_nav').css('width', 0);
});
var body_width = $('body').width();

if(body_width > 768) {
    $("body").on('click',function(){
        $('.close_topnav').trigger('click');
    });
    
    $(".logo_icon").on('click',function(event) {
        event.stopPropagation();
    });
    $(".section_top_nav").on('click',function(event) {
        event.stopPropagation();
    });
}

$("a[rel='modal:open']").addClass('disable');

$(function() {
    $("a[rel='modal:open']").removeClass('disable');
    
    url_modul = window.location.pathname;
    url_modul = url_modul.replace("/","");
    if(url_modul.indexOf("/") > 0) {
        url_modul = url_modul.slice(0, url_modul.indexOf("/"));
    }
 
    if(url_modul.includes('campaign_sequences') ) {
        $('.button_nav').removeClass('active');
        $('.button_nav.'+ 'campaigns_button').addClass('active');
    } else if(url_modul == 'admin_panel/') { //povratna putanja sa admin_panel/templates 
        //
    } else if( $('.button_nav.'+url_modul+'_button').length > 0) {   // na reload stavlja klasu activ na button prema url pathname
        $('.button_nav').removeClass('active');
        $('.button_nav.'+url_modul+'_button').addClass('active');
    }
});

/* $('.evidention_check>form button').on('click',function(e){
    $(this).attr('disabled','disabled');
}); */


$('.form_evidention').on('submit',function(e){
    e.preventDefault();
   // $(this).hide();
    var url = location.origin + '/work_records';
    var form = $(this);
    form.find('button').attr('disabled','disabled');
    var data = form.serialize();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });     
    $.ajax({
        url: url,
        type: "POST",
        data: data,
        success: function( response ) {
            $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + response + '</div></div></div>').appendTo('body').modal();
            $('.header_nav').load(location.href + ' .header_nav .topnav',function(){
                $.getScript('/../js/nav_active.js');
            });
            
            
        }, 
        error: function(jqXhr, json, errorThrown) {
            console.log(jqXhr.responseJSON);
            $(this).show();
            var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                'message':  jqXhr.responseJSON.message,
                                'file':  jqXhr.responseJSON.file,
                                'line':  jqXhr.responseJSON.line };
            $.modal.close();
            $.ajax({
                url: 'errorMessage',
                type: "get",
                data: data_to_send,
                success: function( response ) {
                   $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + "Prijava nije uspjela, osvježi stranicuu i pokušaj ponovno" + '</div></div></div>').appendTo('body').modal();
                   
                }, 
                error: function(jqXhr, json, errorThrown) {
                    console.log(jqXhr.responseJSON); 
                }
            });
        }
    });
});

/* document.addEventListener("visibilitychange", function() {
    if (document.hidden){
    } else {
        location.reload();
    }
});
 */
$('.button_nav').css({
  /*   'background': '#051847',
    'color': '#ffffff' */
});
$( '.button_nav.active' ).css({
/*     'background': '#0A2A79',
    'color': '#ccc' */
});
var form_sequence_height = $('.form_sequence').height();
var header_campaign_height = $('.header_campaign').height();

if($('body').width() > 760) {
    $('.main_campaign').height(form_sequence_height-header_campaign_height);
}

var url = $('form.form_sequence').attr('action');
var form_data;
var data_new = {};
var json;
var html; 
var design;
var temp;
try {
    
    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        id: 'editor-container',
        projectId: 4885,
        displayMode: 'email'
    })

    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
            design = data.design;
        /*  $('#text_html').text(html);
            $('#text_json').text(JSON.stringify(json)); */
        })
    })		
} catch (error) {
    
}

$('.form_sequence.notice_create .btn-submit').on('click',function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('#notice_form')[0];
    
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text_html', html );

    $(".btn-submit").prop("disabled", true);   // disabled the submit button
 
    form_data = $('.form_sequence').serialize();
    form_data_array = $('.form_sequence').serializeArray();
  
    console.log(form_data);
    console.log(form_data_array);
    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {  //$(field).attr('required') && 
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    if( html == undefined  || JSON.stringify(design) == undefined ) {
        validate.push("block");
    } else {
        validate.push(true);
    }
   if(validate.includes("block") ) {
        alert("Nisu uneseni svi parametri, nemoguće spremiti obavijest");
        $(".btn-submit").prop("disabled", false);
    } else { 
      
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Obavijest je spremljena!");
                location.reload();
                $(".btn-submit").prop("disabled", false);
            },
            error: function(jqXhr, json, errorThrown) {
				var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };
				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
					}
				});
			}
        });
    }
});
$('.main_noticeboard .header_document .link_back').on('click',function(e){
    e.preventDefault();
    var url = location['origin'] +'/campaigns';
    
    $('.container').load( url + ' .container > div', function() {		
        $.getScript( '/../js/datatables.js');
        $.getScript( '/../js/filter_table.js');                    
        $.getScript( '/../restfulizer.js');
        $.getScript( '/../js/event.js');
        $.getScript( '/../js/campaign.js');
       /*  $('.collapsible').click(function(event){        
            $(this).siblings().toggle();
        }); */
    });		
});

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});

var dataArrTemplates;
var designTemplates;
var htmlTemplates
if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
  /*   console.log(dataArrTemplates); */
    $.each(dataArrTemplates, function(i, item) {
        htmlTemplates = dataArrTemplates[i].text; 
        var title = dataArrTemplates[i].title; 
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');
    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});
var form_sequence_height = $('.form_sequence').height();
var header_campaign_height = $('.header_campaign').height();

$('.main_campaign').height(form_sequence_height-header_campaign_height);

var form_data = $('.form_sequence').serialize();
var url = $('form.form_sequence').attr('action');
var data_new = {};
var json;
var html; 
var design;

try {
    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        id: 'editor-container',
        projectId: 4885,
        displayMode: 'email'
    })
    
    if($('.dataArr').text()) {
        design = JSON.parse( $('.dataArr').text()); // template JSON */
        html = $('.dataArr_html').text();
        unlayer.loadDesign(design);
    }
    
    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
            design = data.design;
          /*   $('#text_html').text(html);
            $('#text_json').text(JSON.stringify(json)); */
        })
    })	
} catch (error) {
    
}	

$('.form_sequence.notice_edit .btn-submit').on('click',function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('#notice_form')[0];
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text_html', html );
    
    $(".btn-submit").prop("disabled", true);   // disabled the submit button
 
    form_data = $('.form_sequence').serialize();
    form_data_array = $('.form_sequence').serializeArray();

    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {  //$(field).attr('required') && 
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    if( JSON.stringify(design) == undefined ) {
        validate.push("block");
    } else {
        validate.push(true);
    }
    if(validate.includes("block") ) {
        e.preventDefault();
      
        alert("Nisu uneseni svi parametri, nemoguće spremiti obavijest");
        
    } else { 
        $.ajax({
            type: "post",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                $(".btn-submit").prop("disabled", false);
            },
            error: function(jqXhr, json, errorThrown) {
				var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };

				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
						
					}
				});
			}
        });
    }
});
$('.main_noticeboard .header_document .link_back').on('click',function(e){
    e.preventDefault();
    var url = location['origin'] +'/campaigns';
    
    $('.container').load( url + ' .container > div', function() {		
        $.getScript( '/../js/datatables.js');
        $.getScript( '/../js/filter_table.js');                    
        $.getScript( '/../restfulizer.js');
        $.getScript( '/../js/event.js');
        $.getScript( '/../js/campaign.js');
      
    });		
});

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});

var dataArrTemplates;
var designTemplates;
var htmlTemplates
if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */
  /*   console.log(dataArrTemplates); */
    $.each(dataArrTemplates, function(i, item) {
        htmlTemplates = dataArrTemplates[i].text; 
        var title = dataArrTemplates[i].title; 
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');
    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});
var prev_url = location.href;
$(".admin_pages a.admin_link").addClass('disable');
var body_width = $('body').width();
var url_location = location.href;
var active_link;
var url_modul = location.pathname;
url_modul = url_modul.replace("/","");
url_modul = url_modul.split('/')[0];
$(function(){
    if($('.car_links').find('.admin_link').hasClass('active_admin')) {
        $('.car_links').show();
    } else {
        $('.car_links').hide();
    }
    if(body_width > 991) {
        if(url_location.includes('templates')) {
            $('.admin_pages>li>a#emailings').trigger('click');
        
        } else {
         /*    $('.admin_pages>li>a').first().trigger('click'); */
        }
    }
    $(".admin_pages a.admin_link").removeClass('disable');
});

if($(".index_table_filter .show_button").length == 0) {
    $('.index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
} 

var click_element;

$('.admin_pages>li>a').on('click',function(e) {
    click_element = $(this);
    var title = click_element.text();
    $("title").text( title ); 
    var url = $(this).attr('href');
    
    $('.admin_pages>li>a').removeClass('active_admin');
    $(this).addClass('active_admin');
    active_link = $('.admin_link.active_admin').attr('id');
    $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
        window.history.replaceState({}, document.title, url);
        if ( status == "error" ) {
            var msg = "Sorry but there was an error: ";
            $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
        }
        $.getScript( 'js/datatables.js');
        $.getScript( 'js/filter_table.js');
        $.getScript( 'restfulizer.js');
        $.getScript( '/../js/filter_dropdown.js');
    });
    return false;
});

if(body_width < 992) {
    $('.admin_pages>li>a').on('click',function(e) {
        $('aside.admin_aside').hide();
        $('main.admin_main').show();
        click_element = $(this);
        var title = click_element.text();
        $("title").text( title ); 
        var url = $(this).attr('href');
    
        $('.admin_pages>li>a').removeClass('active_admin');
        $(this).addClass('active_admin');
        active_link = $('.admin_link.active_admin').attr('id');
    
        $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
            window.history.replaceState({}, document.title, url);
            if ( status == "error" ) {
                var msg = "Sorry but there was an error: ";
                $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
            }
            $.getScript( 'js/datatables.js');
            $.getScript( 'js/filter_table.js');
            $.getScript( 'js/open_modal.js');
        });
        return false;
    }); 

     $('.link_back').on('click',function () {
        $('aside.admin_aside').show();
        $('main.admin_main').hide();
    });
}
$("a[rel='modal:open']").addClass('disable');
$(function() {
    $("a[rel='modal:open']").removeClass('disable');
    $.modal.defaults = {
        closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
        escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
        clickClose: false,       // Allows the user to close the modal by clicking the overlay
        closeText: 'Close',     // Text content for the close <a> tag.
        closeClass: '',         // Add additional class(es) to the close <a> tag.
        showClose: true,        // Shows a (X) icon/link in the top-right corner
        modalClass: "modal",    // CSS class added to the element being displayed in the modal.
        // HTML appended to the default spinner during AJAX requests.
        spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

        showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
        fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
        fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
    };

    $('.btn-new:not(.create_notice), .add_new, a.create_user[rel="modal:open"], #add_event[rel="modal:open"], .oglasnik_button, .posts_button, .doc_button, .events_button').click(function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.create_notice[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_notice",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.notice_show[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_notice notice_show",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });

    $('.questionnaires_button, a.qname[rel="modal:open"], a.new_questionnaire[rel="modal:open"], .thumb_content a[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_questionnaire",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.open_statistic[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_notice notice_show statistic_index",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.user_show[rel="modal:open"]').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_user",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('.user_show.tr_open_link').on('click',function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_user",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
    });
    $('a.campaign_show[rel="modal:open"]').on('click',function(){  
        $.modal.defaults = { 
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal modal_notice modal_campaign",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)    
        }; 
    });
    $('.open_car_modal').on('click',function(){
		$.modal.defaults = {
			closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
			escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
			clickClose: false,       // Allows the user to close the modal by clicking the overlay
			closeText: 'Close',     // Text content for the close <a> tag.
			closeClass: '',         // Add additional class(es) to the close <a> tag.
			showClose: true,        // Shows a (X) icon/link in the top-right corner
			modalClass: "modal car_modal",    // CSS class added to the element being displayed in the modal.
			// HTML appended to the default spinner during AJAX requests.
			spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

			showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
			fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
			fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
		};
    });
    $('.evidention_check > button').on('click',function(){
		$.modal.defaults = {
			closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
			escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
			clickClose: false,       // Allows the user to close the modal by clicking the overlay
			closeText: 'Close',     // Text content for the close <a> tag.
			closeClass: '',         // Add additional class(es) to the close <a> tag.
			showClose: true,        // Shows a (X) icon/link in the top-right corner
			modalClass: "modal",    // CSS class added to the element being displayed in the modal.
			// HTML appended to the default spinner during AJAX requests.
			spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

			showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
			fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
			fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
		};
    });
    $('tr[data-modal] td:not(:last-child)').on("click", function(e) {
      
        e.preventDefault();
        var href = location.origin + $(this).parent().data('href');
        
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',
    
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
        };
        $.get(href, function(html) { 
            $(html).appendTo('#login-modal');
        }); 
        $('#login-modal').modal();
        $('#login-modal').on($.modal.CLOSE, function(event, modal) {
            $( "#login-modal" ).empty();
        });

        $('a.close-modal').on('click',function(){
            $( "#login-modal" ).empty();
        });
    }); 

});
// on load
if( $('.posts_index').length > 0) {

    var form;
    var data;
    var url;
    var post_id;
    var tab_id;
    var id;
    var content;
    var refresh_height;
    var mess_comm_height;
    var comment_height;
    var body_width = $('body').width();
    var mouse_is_inside = false;
    var active_tabcontent;
    
    $(function() {
        broadcastingPusher();
        tablink_on_click();
        submit_form (); 
        $('.placeholder').show();
        $( '.type_message' ).attr('Placeholder','Type message...');
    
        $('.type_message').on('focus',function(){
            $( this ).attr('Placeholder','');
        });
        $('.type_message').on('blur',function(){
            $( this ).attr('Placeholder','Type message...');
        });
        $('.search_post').on('click',function(){
            $('.search_input').show();       
        });
        $('.search_input').on('hover',function(){ 
            mouse_is_inside=true; 
        }, function(){ 
            mouse_is_inside=false; 
        });
        $("body").on('mouseup',function(){ 
            if(! mouse_is_inside) 
                $('.search_input').hide();
        });
        url = location.search;
        if(body_width > 768 && location.href.includes('/posts') ) {
            if( url ) {
                id = url.replace("?id=", "");
                $('.tablink#' + id ).trigger('click');
            } else {
                $('.tablink').first().trigger('click');
            }
        }
    });
    
    $('.post_sent .link_back').on('click',function () {
        $('.latest_messages').show();
        $('.posts_index .index_main').hide();
    });
    
    // on submit ajax store
    function submit_form () {
        $('.form_post').on('submit',function(e){
            e.preventDefault();
            if( $(this).find('.post-content').val() == '' ) {
                return false;
            } else {
                form = $(this);
                data = form.serialize();
                
                url = '/comment/store';
                post_id = $(this).find('input[name=post_id]').val();
                content = $(this).find('textarea[name=content]').val();
                tab_id = '_' + post_id;
                
                $('.post-content').val('');
                $('.refresh.'+tab_id).append('<b><div class="message"><div class="right"><p class="comment_empl"><small>sada</small></p><div class="content"><p class="comment_content" >'+content+'</p></div></div></div><b>');
               
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type : 'post',
                    url : url,
                    data : data,
                    success:function(msg) {
                        $.get(location.origin + '/posts', function(data, status){
                            var content =  $('.posts>.all_post',data ).get(0).outerHTML;
                            $( '.posts').html( content );
                            var content2 =  $( '.posts_button .button_nav_img .line_btn',data ).get(0).outerHTML;
                            $( '.posts_button .button_nav_img').html( content2 );
                            var content3 =  $('.index_main>section',data ).get(0).outerHTML;
                            $( '.index_main' ).html( content3 );
                            $('.tabcontent#'+tab_id).show();
                            broadcastingPusher();
                            submit_form (); 
                            refreshHeight(tab_id);
                            setPostAsRead(post_id);
                            
                        });
                   
                        $('.tablink#'+post_id).trigger('click');
                    },
                    error: function(jqXhr, json, errorThrown) {
                        var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                            'message':  jqXhr.responseJSON.message,
                                            'file':  jqXhr.responseJSON.file,
                                            'line':  jqXhr.responseJSON.line };
        
                        $.ajax({
                            url: 'errorMessage',
                            type: "get",
                            data: data_to_send,
                            success: function( response ) {
                                $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                            }, 
                            error: function(jqXhr, json, errorThrown) {
                                console.log(jqXhr.responseJSON); 
                                
                            }
                        });
                    }
                })
            }
          
            $('.post-content').css('line-height','70px');
        });
        
    }
    
    function tablink_on_click() {
        $( '.tablink' ).on( "click", function () {
            post_id = $( this ).attr('id');
            tab_id = '_' + post_id;
            if(body_width < 768) {
                $('.latest_messages').hide();
                $('.posts_index .index_main').show();
            }
            $('.tabcontent').hide();
            active_tabcontent = $('.tabcontent#'+tab_id);
            $(active_tabcontent).show();
        
            $.get(location.origin + '/posts', function(data, status){
                var content =  $('.posts>.all_post',data ).get(0).outerHTML;
                $( '.posts').html( content );
                var content2 =  $( '.posts_button .button_nav_img .line_btn',data ).get(0).outerHTML;
                $( '.posts_button .button_nav_img').html( content2 );
                var content3 =  $('.index_main>section',data ).get(0).outerHTML;
                $( '.index_main' ).html( content3 );
                $('.tabcontent#'+tab_id).show();
                broadcastingPusher();
                submit_form (); 
               
                if(post_id != undefined) {
                    refreshHeight(tab_id);
                    setPostAsRead(post_id);
                }
              
                
            });
          
            $(active_tabcontent).find('.type_message ').trigger('focus');
        });
    }
    
    function setPostAsRead(post_id) {
        var url_read = location.origin +"/setCommentAsRead/" + post_id;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "GET",
            url: url_read, 
            success: function(response) {
                $('.tablink#'+post_id).load( location.href + ' .tablink#'+post_id+'>span',function(){
                    tablink_on_click();
                } );
            },
            error: function(jqXhr, json, errorThrown) {
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };
    
                $.ajax({
                    url: 'errorMessage',
                    type: "get",
                    data: data_to_send,
                    success: function( response ) {
                        $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                    }, 
                    error: function(jqXhr, json, errorThrown) {
                        console.log(jqXhr.responseJSON); 
                        
                    }
                });
            }
        });
    }
    
    function refreshHeight(tab_id) {
         mess_comm_height = $("#" + tab_id ).find('.mess_comm').height();
        refresh_height = 90;
        $('.refresh.' + tab_id + ' .message').each( function() {
            refresh_height+= $(this).height();
        });
        comment_height = $("#" + tab_id ).find('.comments').height();
        if(refresh_height < mess_comm_height ) {
            $("#" + tab_id ).find('.refresh').css({"position": "absolute", "bottom": "0", "width": "100%"});
        }
        if(refresh_height > comment_height ) {
            $("#" + tab_id ).find('.refresh').css({"position": "static", "bottom": "0", "height": "100%"});
            $("#" + tab_id ).find('.refresh').scrollTop(refresh_height);
        } else {
            $("#" + tab_id ).find('.mess_comm').scrollTop(refresh_height);
        }
        $("#" + tab_id ).find('.mess_comm').scrollTop(refresh_height);
    }
    
    function broadcastingPusher () {
        // Enable pusher logging - don't include this in production
        /* Pusher.logToConsole = true; */
        var employee_id = $('#employee_id').text();
    
         var pusher = new Pusher('ace40474cf33846103b6', {
                                cluster: 'eu'
                                }); 
        var channel = pusher.subscribe('message_receive');
        channel.bind('my-event', function(data) {
            /* console.log("pusher id employee " + data.show_alert_to_employee); //2
            console.log(data.comment); */
            if(employee_id == data.show_alert_to_employee) {
                /* $('.all_post ').load(  location.origin + '/posts .all_post .main_post');
                $( '.posts_button .button_nav_img').load( location.origin + '/posts .posts_button .button_nav_img .line_btn');
                $( '.refresh.' + tab_id ).load( location.origin + '/posts .refresh.' + tab_id + ' .message',function(){
                });    */
            }
        }); 
    }
    
    function onKeyClick() {
      
        var key = window.event.keyCode;
        // If the user has pressed enter
        if (key === 13) {
            $('.post-content').css('line-height','unset');
        }
        else {
            return true;
        }
    }
}

$(function() { 
    var body_width = $('body').width();
    if(body_width > 450) {
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_questionnaire').height();
        var body_height = index_height - header_height;
        $('.all_documents').height(body_height -65);
    }

    $('.index_page table.dataTable.no-footer').css('height','fit-content');

    var div_width = $( '.preview_doc').width();
    var all_width = 217;

    $( ".preview_doc .thumb_container" ).each( (index, element) => {
        all_width += 217;
    });

    if(all_width > div_width ) {
        $('.preview_doc .scroll_right').show();
    }
        
    $(".clickable-row").click(function() {
     //   window.location = $(this).data("href");

    });
    $('.collapsible').click(function(event){ 
        $(this).siblings().toggle();
    });
    $('#right-button').click(function() {
        event.preventDefault();
        $('.preview_doc').animate({
            scrollLeft: "+=217px"
        }, "slow");
        $('.preview_doc .scroll_left').show();
        
    });

    $('.sendEmail').click(function(){
		if (!confirm("Stvarno želiš poslati obavijest mailom?")) {
			return false;
		}
    });
    
    $('#left-button').click(function() {
        event.preventDefault();
        $('.preview_doc').animate({
            scrollLeft: "-=217px"
        }, "slow");
        if($('.preview_doc').scrollLeft() < 217 ) {
            $('.preview_doc .scroll_left').hide();
        } else {
            $('.preview_doc .scroll_left').show();
        }
    });

    $('.show').click(function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').toggle();
        
        var body_width = $('body').width();
        if(body_width > 1200) {
            var index_height = $('.index_main.main_documents').height();
            var header_height = $('.page-header.header_questionnaire').height();
            var body_height = index_height - header_height;
            $('.all_documents').height(body_height -65 );
            var thumb_height = $('.preview_doc.preview_q .thumb_container').last().height();
            $('.thumb_container').first().height(thumb_height);
        }
    });

    $('.hide').click(function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').toggle();
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_questionnaire').height();
        var body_height = index_height - header_height;
        $('.all_documents').height(body_height - 65);
    });

    $( ".main_questionnaire .change_view" ).on('click', function() {
        $( ".change_view" ).toggle();
        $( ".change_view2" ).toggle();
        $('.table-responsive.first_view').toggle();
        $('.table-responsive.second_view').toggle();        
    });
    $( ".main_questionnaire .change_view2" ).on('click', function() {
        $( ".change_view" ).toggle();
        $( ".change_view2" ).toggle();
        $('.table-responsive.first_view').toggle();
        $('.table-responsive.second_view').toggle();
    });
});

$( window ).resize(function() {
    var body_width = $('body').width();
    if(body_width > 1200) {
        var index_height = $('.index_main.main_documents').height();
        var header_height = $('.page-header.header_questionnaire').height();
        var body_height = index_height - header_height;
        $('.all_documents').height(body_height -65);
    }
});
	$(function() {

		var modal_height = $('.modal.modal_questionnaire').height();
		var header_height =  $('.modal-header').height();
		var body_width = $('body').width();
		var body_height =  modal_height - header_height - 80;
		if(body_width > 450) {
			$('.modal.modal_questionnaire .modal-body').height(body_height);
		}
		
		var countElement = 0;
		$('textarea').each(function(){
			countElement += 1;
		});
		$('input[type="radio"]').parent().parent().each(function(){
			countElement += 1;
		});
		$('input[type="checkbox"]').parent().parent().each(function(){
			countElement += 1;
		});
		$( "#questionnaire_form span.progress_val" ).text( 0 + '/' + countElement);
		var countChecked = function() {
			var n = $( "input[type=radio]:checked" ).length;
			
			$( "textarea" ).each(function(){ 
				if($(this).val() != '') {
					n += 1;
				}
			});
			$('input[type="checkbox"]:checked').parent().parent().each(function(){
				n += 1;
			});
				
			$( "#questionnaire_form span.progress_val" ).text( n + '/' + countElement);
			var valProgress = n/countElement*100;
			$('#questionnaire_form .progress_bar .progress').css('width', valProgress + '%');
		};
		countChecked();
		
		$( "input[type=radio]" ).on( "click", countChecked );
		$( "input[type=checkbox]" ).on( "click", countChecked );
		$( "textarea" ).on( "change", countChecked );
	});
	$( window ).on('resize',function() {
		var modal_height = $('.modal.modal_questionnaire').height();
		var header_height =  $('.modal-header').height();
		var body_height =  modal_height - header_height - 80;
		$('.modal.modal_questionnaire .modal-body').height(body_height);
		
	});
	$('.btn-statistic').on('click',function(){
		$('.statistic').toggle();
		var modal_height = $('.modal.modal_questionnaire').height();
		var header_height =  $('.modal-header').height();
		var body_height =  modal_height - header_height - 80;
		$('.modal.modal_questionnaire .modal-body').height(body_height);
	});

var sequence_id;
var order;
var i;

if( $('.emails_email_body').length > 0) {
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
                error: function(jqXhr, json, errorThrown) {
                    var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                        'message':  jqXhr.responseJSON.message,
                                        'file':  jqXhr.responseJSON.file,
                                        'line':  jqXhr.responseJSON.line };
                    $.ajax({
                        url: 'errorMessage',
                        type: "get",
                        data: data_to_send,
                        success: function( response ) {
                            $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                        }, 
                        error: function(jqXhr, json, errorThrown) {
                            console.log(jqXhr.responseJSON); 
                            
                        }
                    });
                }
            });
        }
    });
    
    
}

 $(function() {
    $('a.notice_show[rel="modal:open"]').on('click',function(){
        $('.modal').addClass('modal_notice');
        $('.modal').addClass('notice_show');
        var height = 0;
        var modal_height = $('.modal.modal_notice').height();
        var header_height =  $('.modal-header').height();
        var body_height =  modal_height - header_height - 65;
        $('.modal_notice.notice_show .modal-body').height(body_height);
    });
});
$( window ).on('resize',function() {
  /*   $('.modal').addClass('modal_notice');
    $('.modal').addClass('notice_show'); */
    var height = 0;
    var modal_height = $('.modal.modal_notice').height();
    var header_height =  $('.modal-header').height();
    var body_height =  modal_height - header_height - 65;
    $('.modal_notice.notice_show .modal-body').height(body_height);
    
});
$('.btn-statistic').on('click',function(){
    $('.statistic').toggle();
    $('.modal').addClass('modal_notice');
    $('.modal').addClass('notice_show');
    var height = 0;
    var modal_height = $('.modal.modal_notice').height();
    var header_height =  $('.modal-header').height();
    var body_height =  modal_height - header_height - 65;
    $('.modal_notice.notice_show .modal-body').height(body_height);
});
$(function(){
    
    var slideIndex = 1;

    if($('.slide_index')) {
        slideIndex = $('.slide_index').text();
    }
    
    showSlides(slideIndex);

    function showSlides(n) {
        var slides = $(".mySlides");
        var slides_info = $(".mySlides_info");

        if (n > $(slides).length) {
            n = 1;
        } else if (n < 1){
            n = $(slides).length;
        } else {
            n = n;
        } 

        $(slides).each(function( index, element ) {
            if(index == n-1) {
                $(element).show();
                $(element).addClass('block');
                $(element).css('display','block');
               
            } else {
                $(element).hide();
                $(element).removeClass('block');
            }
        });
        $(slides_info).each(function( index, element ) {
            if(index == n-1) {
                $(element).show();
                $(element).addClass('block');
                $(element).css('display','block');
               
            } else {
                $(element).hide();
                $(element).removeClass('block');
            }
        });
    }

    function plusSlides(n) {
        var currentSlide;
        var slides = $(".mySlides");
        
        $(slides).each(function( index, element ) {
            if( $(element).hasClass('block')) {
                currentSlide = index+1;
            }
        });
        
        showSlides(currentSlide += n);
    }

    $('.prev').click(function(){
        plusSlides(-1);
    });
    $('.next').click(function(){
        plusSlides(+1);
    });
});
var form_data;
var url = $('form.form_template').attr('action');
var data_new = {};
var json;
var html; 
var design;
var temp;
try {
    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        mergeTags: {
            first_name: {
              name: "First Name",
              value: "Jelena"
            },
            last_name: {
              name: "Last Name",
              value: "Juras"
            }
        },
        id: 'editor-container',
        projectId: 4885,
        displayMode: 'email'
    })
    
    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
            body = json.body;
            design = data.design;
        })
    })
} catch (error) {
    
}
	

$('.form_template.template_create .btn-submit').on('click',function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('#form_template')[0];
    
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text', html );
   
  //  $(".btn-submit").prop("disabled", true);   // disabled the submit button
 
 /*    form_data = $('.form_template').serialize(); */
    form_data_array = $('.form_template').serializeArray();

    
    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "") {  //$(field).attr('required') && 
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    if( html == undefined  || JSON.stringify(design) == undefined ) {
        validate.push("block");
    } else {
        validate.push(true);
    }
    console.log(validate);
    if(validate.includes("block") ) {
        e.preventDefault();
        alert("Nisu uneseni svi parametri, nemoguće spremiti predložak");
    } else { 
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                console.log("responce : ", data);
                $(".btn-submit").prop("disabled", false);
                location.reload();
            },
            error: function(jqXhr, json, errorThrown) {
				var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };

				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
						
					}
				});
			}
        });
    }
});

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});

var dataArrTemplates;
var htmlTemplates;
var designTemplates;
if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */

/*   console.log(dataArrTemplates); */
    $.each(dataArrTemplates, function(i, item) {
        htmlTemplates = dataArrTemplates[i].text; 
        var title = dataArrTemplates[i].title; 
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
  
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});

var form_data;
var url = $('form.form_template').attr('action');
var data_new = {};
var json;
var html; 
var design;
try {
    
    unlayer.init({
        appearance: {
            theme: 'light',
            panels: {
                tools: {
                    dock: 'left'
                }
            }
        },
        id: 'editor-container',
        projectId: 4885,
        displayMode: 'email'
    })
    unlayer.setMergeTags({
        first_name: {
        name: "First Name",
        value: "Jelena"
        },
        last_name: {
        name: "Last Name",
        value: "Juras"
        }
    });
    
    if($('.dataArr').text()) {
        var design = JSON.parse( $('.dataArr').text()); // template JSON */
        unlayer.loadDesign(design);
    }
    $('.template').on('change',function(){
        var template_id = $(this).val();
    
        unlayer.loadTemplate(12187); 
    });

    unlayer.addEventListener('design:updated', function(updates) {
        unlayer.exportHtml(function(data) {
            json = data.design; // design json
            html = data.html; // design html
            design = data.design;
            /* $('#text_html').text(html);
            $('#text_json').text(JSON.stringify(json)); */
        })
    })	
  
} catch (error) {
    
}

$('.form_template.template_edit .btn-submit').on('click', function(e) {
    var validate = [];
    e.preventDefault();
    var form = $('#form_template')[0];
    
    var data = new FormData(form);              // Create an FormData object 
    data.append('text_json',JSON.stringify(design) );
    data.append('text',html );
  //  $(".btn-submit").prop("disabled", true);   // disabled the submit button
 
 /*    form_data = $('.form_template').serialize(); */
    form_data_array = $('.form_template').serializeArray();

    jQuery.each( form_data_array, function( i, field ) {
        if(field.value == "" ) {  //$(field).attr('required') && 
            validate.push("block");
        } else {
            validate.push(true);
        }
    });
    console.log(validate);
    if(validate.includes("block") ) {
        e.preventDefault();
        alert("Nisu uneseni svi parametri, nemoguće spremiti obavijest");
    } else { 
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: url,
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
                alert("Dizajn je spremljen!");
                console.log("responce : ", data);
                $(".btn-submit").prop("disabled", false);
            },
            error: function(jqXhr, json, errorThrown) {
				var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
									'message':  jqXhr.responseJSON.message,
									'file':  jqXhr.responseJSON.file,
									'line':  jqXhr.responseJSON.line };

				$.ajax({
					url: 'errorMessage',
					type: "get",
					data: data_to_send,
					success: function( response ) {
						$('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
					}, 
					error: function(jqXhr, json, errorThrown) {
						console.log(jqXhr.responseJSON); 
						
					}
				});
			}
        });
    }
});
/* 
$('.link_back').click(function(e){
    e.preventDefault();
    var url = location['origin'] +'/campaigns';
    
    $('.container').load( url + ' .container > div', function() {		
        $.getScript( '/../js/datatables.js');
        $.getScript( '/../js/filter_table.js');                    
        $.getScript( '/../restfulizer.js');
     
    });		
}); */

$(function() {
    $('.nav-link').css('background-color','#F8FAFF !important');
    $('.active.nav-link').css('background-color','#1594F0 !important'); 
});


var dataArrTemplates;
var htmlTemplates;
var designTemplates;
if($('.dataArrTemplates').text()) {
    dataArrTemplates = JSON.parse( $('.dataArrTemplates').text()); // template JSON */

/*   console.log(dataArrTemplates); */
    $.each(dataArrTemplates, function(i, item) {
        htmlTemplates = dataArrTemplates[i].text; 
        var title = dataArrTemplates[i].title; 
        $('#template-container').append('<span class="template_button blockbuilder-content-tool" id="' + i +'"><div>'+title+'</div></span>');
    });
}
  
$('.template_button').on('click',function(){
    temp = $( this ).attr('id');

    designTemplates = JSON.parse( dataArrTemplates[temp].text_json); // template JSON */
    htmlTemplates = dataArrTemplates[temp].text; 
    unlayer.loadDesign(designTemplates);
    $('.show_temp#temp' + temp).remove();
});
$( ".template_button" ).on('mouseover', function(){
        temp = $( this ).attr('id');
        htmlTemplates = dataArrTemplates[temp].text; 
        $('body').append('<span class="show_temp" id="temp' + temp +'">'+htmlTemplates+'</span>');
        $('.show_temp#temp'+temp).show();
        
});
$( ".template_button" ).on('mouseout', function(){
    $('.show_temp#temp' + temp).remove();
});
var locale = $('.locale').text();
var saved;

if(locale == 'en') {
    saved = "Data saved successfully.";
} else {
    saved = "Podaci su spremljeni";
}
$('.close_travel').click(function(e){
    console.log("close_travel");
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = $(this).attr('href');
    console.log(url);
    $.ajax({
        url: url,
        type: "get",
        success: function( response ) {

            $('tbody').load(location.origin + '/travel_orders' + ' tbody>tr',function(){
                $.getScript( '/../restfulizer.js');
                $.getScript( '/../js/travel.js');
            });
            $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + response + '</div></div></div>').appendTo('body').modal();
        },
        error: function(jqXhr, json, errorThrown) {
            console.log(jqXhr.responseJSON); 
        }

    });
}); 
//load wifhout refresh pages
$('.load_page').click(function(e){ 
	e.preventDefault(); // cancel click
	var page = $(this).attr('href');  
	$('.container').load(page + ' .container > .row > div', function()
	{$.getScript("js/upload_page.js");}
	);
});



$( function () {
    var div_width = $( '.profile_images').width();
    var all_width = 0;

    $( ".profile_images > .profile_img" ).each( (index, element) => {
        all_width += $(element).width();
    });
    if(all_width > div_width ) {
        $('.profile_images .scroll_right').show();
    }

    $('.profile_images #right-button').on('click',function(event) {
        event.preventDefault();
        $(this).parent().animate({
            scrollLeft: "+=200px"
        }, "slow");
        $('.profile_images .scroll_left').show();
        
    });

    $('.profile_images #left-button').on('click',function(event) {
        event.preventDefault();
        $(this).parent().animate({
            scrollLeft: "-=115px"
        }, "slow");
        if($('.profile_images').scrollLeft() < 115 ) {
            $('.profile_images .scroll_left').hide();
        } else {
            $('.profile_images .scroll_left').show();
        }
    });
    
});
$.getScript( '/../js/filter_table.js');

$('.more').on('click',function(){
    $( this ).siblings('.role').toggle();
    $( this ).hide();
    $( this ).siblings('.hide').show();
});
$('.hide').on('click',function(){
    $( this ).siblings('.role').hide();
    $( this ).siblings('.role._0').show();
    $( this ).siblings('.role._1').show();

    $( this ).siblings('.more').show();
    $( this ).hide();
});

$('.user_header .change_view').on('click', function(){
    $('.index_table_filter label #mySearchTbl').attr('id','mySearchElement');
    $('.index_table_filter label #mySearchElement').attr('onkeyup','mySearchElement()');

    $( ".change_view" ).toggle();
    $( ".change_view2" ).toggle();
   
    $('main.users_main .second_view').css('display','flex');
    $('.table-responsive').toggle();		
});
$( ".user_header .change_view2" ).on('click', function() {
    $('.index_table_filter label #mySearchElement').attr('id','mySearchTbl');
    $('.index_table_filter label #mySearchTbl').attr('onkeyup','mySearchTable()');
    $( ".change_view" ).toggle();
    $( ".change_view2" ).toggle();
    
    $('.second_view').css('display','none');
   
    $('.table-responsive').toggle();
});
$("a.show_user").on('click', function(event) {
    $.modal.defaults = {
        closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
        escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
        clickClose: false,       // Allows the user to close the modal by clicking the overlay
        closeText: 'Close',     // Text content for the close <a> tag.
        closeClass: '',         // Add additional class(es) to the close <a> tag.
        showClose: true,        // Shows a (X) icon/link in the top-right corner
        modalClass: "modal modal_user",    // CSS class added to the element being displayed in the modal.
        // HTML appended to the default spinner during AJAX requests.
        spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

        showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
        fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
        fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
    };
});
$("a.edit_user").on('click', function(event) {
    $.modal.defaults = {
        closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
        escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
        clickClose: false,       // Allows the user to close the modal by clicking the overlay
        closeText: 'Close',     // Text content for the close <a> tag.
        closeClass: '',         // Add additional class(es) to the close <a> tag.
        showClose: true,        // Shows a (X) icon/link in the top-right corner
        modalClass: "modal",    // CSS class added to the element being displayed in the modal.
        // HTML appended to the default spinner during AJAX requests.
        spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

        showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
        fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
        fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
    };
});
$("a.create_user").on('click', function(event) {
    $.modal.defaults = {
        closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
        escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
        clickClose: false,       // Allows the user to close the modal by clicking the overlay
        closeText: 'Close',     // Text content for the close <a> tag.
        closeClass: '',         // Add additional class(es) to the close <a> tag.
        showClose: true,        // Shows a (X) icon/link in the top-right corner
        modalClass: "modal",    // CSS class added to the element being displayed in the modal.
        // HTML appended to the default spinner during AJAX requests.
        spinnerHtml: '<div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div>',

        showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
        fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
        fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
    };
});
var locale = $('.locale').text();
var validate_text;
var email_unique;
var error;
var request_send;
var status_requests;
var all_requests;
var done;
var saved;
var validate = [];

if(locale == 'en') {
    validate_text = "Required field";
    email_unique = "Email must be unique";
    error = "There was an error";
    saved = "Data saved successfully.";
    request_send = "Request sent";
    status_requests = "To see you request status and see all request visit All requests page";
    all_requests = "All requests";
    done = "Done";
    validate_name = "Required name entry";
    validate_lastname = "Required lastname entry ";
    validate_email = "Required e-mail entry";
    validate_password = "Required password entry";
    validate_passwordconf = "Password confirmation required";
    validate_password_lenght = "Minimum of 6 characters is required";
    validate_role = "Required role assignment";   
} else {
    validate_text = "Obavezno polje";
    email_unique = "E-mail mora biti jedinstven";
    error = "Došlo je do greške, poslana je poruka na podršku";
    saved = "Podaci su spremljeni";
    request_send = "Zahtjev je poslan";
    status_requests = "Da biste vidjeli status zahtjeva i pogledali sve zahtjeve posjetite Svi zahtjevi stranicu";
    all_requests = "Svi zahtjevi";
    done = "Gotovo";
    validate_name = "Obavezan unos imena";
    validate_lastname = "Obavezan unos prezimena";
    validate_email = "Obavezan unos emaila";
    validate_password = "Obavezan unos lozinke";
    validate_passwordconf = "Obavezna potvrda lozinke";
    validate_password_lenght = "Obavezan unos minimalno 6 znaka";
    validate_role = "Obavezna dodjela uloge";
}

$('.remove').on('click',function(){
    $(this).parent().remove();
});
var page = $('.admin_pages li').find('a.active_admin');
var modul_name = $('.admin_pages li').find('a.active_admin').attr('id');

function validate_user_form () {
    validate = [];
    $('.roles').on('change',function(event){
        if( roles.is(':checked')) {
            validate.push(true);
        } else {
            validate.push("block");
        }
    });
    $( "textarea" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 ) {
                if( !$( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }
    });
    $( "input" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }      
    });
    $( "select" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val() == null || $(this).val() == '' || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }
    });
     /*    if (tinyMCE.activeEditor) { */
    /*        if(tinyMCE.activeEditor.getContent().length == 0) { */
    /*            if( ! $('#mytextarea').parent().find('.modal_form_group_danger').length) { */
    /*                $('#mytextarea').parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>'); */
    /*            } */
    /*            validate.push("block"); */
    /*            $('#mytextarea').parent().find('.modal_form_group_danger').remove(); */
    /*            validate.push(true); */
    /*        } */
    /*    } */
    if($("#password").length >0) {
        password = $("#password");
        conf_password = $("#conf_password");    
        
        if(password.val().length > 0 ) {
            if( password.val().length < 6) {
                if( password.parent().find('.validate').length  == 0 ) {
                    password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
                } else {
                    password.parent().find('.validate').text(validate_password_lenght);  
                }
                validate.push("block");
            } else {
                password.parent().find('.validate').text("");     
                if( ! conf_password.val() || (password.val() != conf_password.val()) ) {
                if( conf_password.parent().find('.validate').length  == 0 ) {                
                        conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                    }
                    validate.push("block");
                } else {
                    conf_password.parent().find('.validate').text("");     
                    validate.push(true);
                }
            }
        }
    }
}
$('input[type="file"]').on('change',function(e){
    fileName = e.target.files[0].name;
    $('#file_name').text(fileName);
});

$('.btn-submit').on('click',function(event){
   /*  event.preventDefault(); */
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
  
    var url_load = window.location.href;
    var pathname = window.location.pathname;
    validate_user_form ();
/* 
    console.log(url_load);
    console.log(url); 
    console.log(form_data);
    console.log(validate); */

    if(validate.includes("block") ) {
       event.preventDefault();
       validate = [];
    } else {
        $('.roles_form .checkbox').show();
       /*  $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });     
        $.ajax({
            url: url,
            type: "POST",
            data: form_data,
            success: function( response ) {
                $.modal.close();
              
                if(pathname == '/events' && url.includes("/events/")) {  //event edit
                    $('.modal-header').load(url + ' .modal-header h5');
                    $('.modal-body').load(url + ' .modal-body p');
                    $('.main_calendar_day').load(url_load + ' .main_calendar_day>div');
                    $('.main_calendar_month').load(url_load + ' .main_calendar_month table');
                    $('.main_calendar_week').load(url_load + ' .main_calendar_week table');
                    $('.main_calendar_list').load(url_load + ' .main_calendar_list>list');
                    $('.all_events').load(url_load + ' .all_events .hour_in_day');
                } else if(pathname == '/events' ) {
                    $('.all_events').load(url_load + ' .all_events .hour_in_day');
                } else if(url.includes("/vehical_services/")) {
                    url = window.location.origin + '/vehical_services';
                    $('.modal-body').load(url + " .modal-body table" );
                    $.getScript( '/../restfulizer.js');
                } else if(url_load.includes("/oglasnik")) {
                        url = window.location.origin + '/oglasnik';
                        $('.main_ads').load(url + " .main_ads article" );
                        $.getScript( '/../restfulizer.js');

                } else if(url.includes("/fuels/")) {                    
                    url = window.location.origin + '/fuels';
                    $('.modal-body').load(url + " .modal-body table" );
                    $.getScript( '/../restfulizer.js');
                } else if (pathname.includes("/edit_user")) {
                    location.reload();
                } else if (url.includes("/loccos") && pathname == '/dashboard' ) {
                    $('.layout_button').load(url_load + " .layout_button button" ); 

                } else if (url.includes("/events") && pathname == '/dashboard' ) {
                    $('.all_agenda').load(url + " .all_agenda .agenda");
                } else if ( pathname == '/dashboard' ) {
                    $('.salary').load(url_load + " .salary>div");
                } else if (url.includes("/posts") ) {
                    if(pathname == '/dashboard') {
                        $('.all_post').load(url_load + " .all_post>div");
                    } else if (pathname == '/posts') {
                        $('.container').load(url_load + ' .container .posts_index',function(){
                            $('.tablink').first().trigger('click');
                            $('.tabcontent').first().show();
                            broadcastingPusher();
                            refreshHeight(tab_id);
                            setPostAsRead(post_id);
                        });
                    }
                } else {
                    if($('.index_admin').length > 0 ) {
                        if(url.includes("/work_records")) {
                          
                            $('.first_view tbody').load($(page).attr('href') + " .first_view tbody>tr",function(){
                                $.getScript( '/../restfulizer.js');
                            });
                        } else if(url_load.includes('/work_records_table')) {
                            console.log("second");
                            $('tbody.second').load(location.origin+'/work_records_table'+ " tbody.second>tr",function(){
                                $.getScript( '/../restfulizer.js');
                            });
                        } else {
                            $('tbody').load(location.href + " tbody>tr:not(.second_view tbody>tr)",function(){
                                $.getScript( '/../restfulizer.js');
                            });
                        }
                    } else {
                        $('.index_main').load(url + " .index_main>section",function(){
                            if(url.includes("/absences")) {
                                $('#index_table_filter').show();
                                $('#index_table_filter').prepend('<a class="add_new" href="' + location.origin+'/absences/create' +'" class="" rel="modal:open"><i style="font-size:11px" class="fa">&#xf067;</i>Novi zahtjev</a>');
                                $('.all_absences #index_table_filter').append('<span class="show_button"><i class="fas fa-download"></i></span>');
                                $('.index_page table.display.table').show();
                                
                                $.getScript( '/../restfulizer.js');
                            } else if(url.includes("/employees")) {
                                $.getScript( '/../js/users.js');
                            }
                        });
                    }
                   
                }
                if(url.includes("/absences")) {
                    $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + request_send + '<p>' + status_requests + '</p></div></div><div class="modal-footer"><span><button class="btn_all" ><a href="' + location.origin + '/absences' + '" >' + all_requests + '</a></button></span><button class="done"><a href="#close" rel="modal:close" >' + done + '</a></button></div></div>').appendTo('body').modal();
                } else if(! url.includes("/events/") && ! url.includes("/posts"))  {
                    $('<div><div class="modal-header"><span class="img-success"></span></div><div class="modal-body"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>success:</strong>' + saved + '</div></div></div>').appendTo('body').modal();
                }
            }, 
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr.responseJSON);
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };
                if(url.includes("users") && errorThrown == 'Unprocessable Entity' ) {
                    alert(email_unique);
                }  else {
                    $.modal.close();
                    $.ajax({
                        url: 'errorMessage',
                        type: "get",
                        data: data_to_send,
                        success: function( response ) {
                            $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                        }, 
                        error: function(jqXhr, json, errorThrown) {
                            console.log(jqXhr.responseJSON); 
                        }
                    });
                }
            }
        });
        if($(page).length > 0) {
            $(page).trigger('click');
        } else {
           $('.btn-submit').trigger('unbind');
        } */
    }
});

$('.form_user .btn-next').on('click',function(event){
    console.log("btn-next");
    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    password = $("#password");
    conf_password = $("#conf_password");
    file = $("#file");        
    
    validate = [];

    validate_user_form ();
    console.log(validate);
    if(validate.includes("block") ) {
        //
    } else  {
        $('.first_tab').toggle();
        $('.second_tab').toggle();
        if($('.first_tab').is(':visible')) {
            $('.mark1').css('background','#1594F0');
            $('.mark2').css('background','rgba(43, 43, 43, 0.2)');
    
        } 
        if($('.second_tab').is(':visible')) {
            $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
            $('.mark2').css('background','#1594F0');
        }
    }
});

$('.form_user .btn-back').on('click',function(){
    $('.first_tab').toggle();
    $('.second_tab').toggle();
    if($('.first_tab').is(':visible')) {
        $('.mark1').css('background','#1594F0');
        $('.mark2').css('background','rgba(43, 43, 43, 0.2)');

    } 
    if($('.second_tab').is(':visible')) {
        $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
        $('.mark2').css('background','#1594F0');
    }
});
var locale = $('.locale').text();

if(locale == 'hr') {
    validate_text = "Obavezno polje";
} else if( locale = 'en') {
    validate_text = "Required field";            
} else {
    validate_text = "Obavezno polje";
}   

$('.form_doc .btn-submit').click(function(event){
  //  event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
   
    var validate = false;
    $( "select" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val() == null || $(this).val() == '' || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
            }
        }
    });
    $( "input" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {      
        
            if( $(this).val().length == 0 || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate = false;
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate = true;
            }
        }
    });

    if(validate == false) {
        event.preventDefault();
    } 
});

/* var f_name;
var l_name;
var email;
var file;
var validate_name = '';
var validate_lastname = '';
var valiate_email = '';
var password;
var conf_password;
var validate_role = '';
var validate_password = '';
var validate_password_lenght = '';
var validate_passwordconf = '';
var roles;
var fileName;
var validate = false;
var validate2 = [];

var locale = $('.locale').text();

if(locale == 'hr') {
    validate_name = "Obavezan unos imena";
    validate_lastname = "Obavezan unos prezimena";
    valiate_email = "Obavezan unos emaila";
    validate_password = "Obavezan unos lozinke";
    validate_passwordconf = "Obavezna potvrda lozinke";
    validate_password_lenght = "Obavezan unos minimalno 6 znaka";
    validate_role = "Obavezna dodjela uloge";
   
} else if( locale = 'en') {
    validate_name = "Required name entry";
    validate_lastname = "Required lastname entry ";
    valiate_email = "Required e-mail entry";
    validate_password = "Required password entry";
    validate_passwordconf = "Password confirmation required";
    validate_password_lenght = "Minimum of 6 characters is required";
    validate_role = "Required role assignment";   
}

var first_tab_height = $('.first_tab').height();
$('.second_tab').height(first_tab_height);

roles = $('.roles');

$('input[type="file"]').on('change',function(e){
    fileName = e.target.files[0].name;
    $('#file_name').text(fileName);
});

$('.roles').on('change',function(event){
    if( roles.is(':checked')) {
        validate2.push(true);
    } else {
        validate2.push("block");
    }
});

$('.form_create_user .btn-next').on('click',function(event){
    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    password = $("#password");
    conf_password = $("#conf_password");
    file = $("#file");        
    
    if(! f_name.val()) {
        if( f_name.parent().find('.validate').length  == 0) {
            f_name.parent().append(' <p class="validate">' + validate_name + '</p>');               
        }
        validate = false;
    } else {
        f_name.parent().find('.validate').text("");  
        validate = true;
    }
    if(! l_name.val()) {
        if( l_name.parent().find('.validate').length  == 0) {
            l_name.parent().append(' <p class="validate">' + validate_lastname + '</p>');
        }            
        validate = false;
    } else {
        l_name.parent().find('.validate').text("");
        validate = true;
    }
    if(! email.val()) {
        if( email.parent().find('.validate').length  == 0) {
            email.parent().append(' <p class="validate">' + valiate_email + '</p>');
        }
        validate = false;
    } else {
        email.parent().find('.validate').text("");  
        validate = true;     
    }
    if(! password.val()) {
        if( password.parent().find('.validate').length  == 0) {
            password.parent().append(' <p class="validate">' + validate_password + '</p>');
        }
        validate = false;
    } else {
        if(password.val().length < 6) {
            if( password.parent().find('.validate').length  == 0 ) {
                password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
            } else {
                password.parent().find('.validate').text(validate_password_lenght);  
            }
            validate = false;
        } else {
            password.parent().find('.validate').text("");     
            if( ! conf_password.val() || (password.val() != conf_password.val()) ) {
            if( conf_password.parent().find('.validate').length  == 0 ) {                
                    conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                }
                validate = false;
            } else {
                conf_password.parent().find('.validate').text("");     
                validate = true;  
            }
        }            
    }
    
    if(validate == true ) {
        $('.first_tab').toggle();
        $('.second_tab').toggle();
        if($('.first_tab').is(':visible')) {
            $('.mark1').css('background','#1594F0');
            $('.mark2').css('background','rgba(43, 43, 43, 0.2)');
    
        } 
        if($('.second_tab').is(':visible')) {
            $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
            $('.mark2').css('background','#1594F0');
        }
    }

});

$('.form_create_user .btn-back').on('click',function(){
    $('.first_tab').toggle();
    $('.second_tab').toggle();
    if($('.first_tab').is(':visible')) {
        $('.mark1').css('background','#1594F0');
        $('.mark2').css('background','rgba(43, 43, 43, 0.2)');

    } 
    if($('.second_tab').is(':visible')) {
        $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
        $('.mark2').css('background','#1594F0');

    }
});

/* 
$('.submit_user_create').on('click',function(event){
    console.log("submit_user_create");
    event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();

    password = $("#password");
    conf_password = $("#conf_password"); 

    if(password.val().length > 0 ) {
        if( password.val().length < 6) {
            if( password.parent().find('.validate').length  == 0 ) {
                password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
            } else {
                password.parent().find('.validate').text(validate_password_lenght);  
            }
            validate2.push("block");
        } else {
            password.parent().find('.validate').text("");     
            if( ! conf_password.val() || (password.val() != conf_password.val()) ) {
            if( conf_password.parent().find('.validate').length  == 0 ) {                
                    conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                }
                validate2.push("block");
            } else {
                conf_password.parent().find('.validate').text("");     
                validate2.push(true);
            }
        }
    }

    if( validate2.includes("block") ) {
        event.preventDefault();

        if( roles.parent().parent().find('.validate').length  == 0 ) {                
            roles.parent().parent().append(' <p class="validate">' + validate_role + '</p>');
        }
    } else {
            roles.parent().find('.validate').text("");
    }

    console.log(validate2);
    console.log(password);
    console.log(conf_password);
    console.log(url);
    console.log(form_data);
});

 */

/* var f_name;
var l_name;
var email;
var file;
var validate_name = '';
var validate_lastname = '';
var valiate_email = '';
var password;
var conf_password;  
var validate_role = '';
var validate_password = '';
var validate_password_lenght = '';
var validate_passwordconf = '';
var roles;
var fileName;
var validate = [];

var locale = $('.locale').text();

if(locale == 'hr') {
    validate_name = "Obavezan unos imena";
    validate_lastname = "Obavezan unos prezimena";
    valiate_email = "Obavezan unos emaila";
    validate_password = "Obavezan unos lozinke";
    validate_passwordconf = "Obavezna potvrda lozinke";
    validate_password_lenght = "Obavezan unos minimalno 6 znaka";
    validate_role = "Obavezna dodjela uloge";
   
} else if( locale = 'en') {
    validate_name = "Required name entry";
    validate_lastname = "Required lastname entry ";
    valiate_email = "Required e-mail entry";
    validate_password = "Required password entry";
    validate_passwordconf = "Password confirmation required";
    validate_password_lenght = "Minimum of 6 characters is required";
    validate_role = "Required role assignment";   
}

var second_tab_height = $('.second_tab').height();
$('.first_tab').height(second_tab_height);

$('input[type="file"]').on('change',function(e){
    fileName = e.target.files[0].name;
    $('#file_name').text(fileName);
});

function validate_user_form () {
    validate = [];
    $('.roles').on('change',function(event){
        if( roles.is(':checked')) {
            validate.push(true);
        } else {
            validate.push("block");
        }
    });
    $( "textarea" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 ) {
                if( !$( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }
    });
    $( "input" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val().length == 0 || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }      
    });
    $( "select" ).each(function( index ) {
        if($(this).attr('required') == 'required' ) {
            if( $(this).val() == null || $(this).val() == '' || $(this).val() == '') {
                if( ! $( this ).parent().find('.modal_form_group_danger').length) {
                    $( this ).parent().append('<p class="modal_form_group_danger">' + validate_text + '</p>');
                }
                validate.push("block");
            } else {
                $( this ).parent().find('.modal_form_group_danger').remove();
                validate.push(true);
            }
        }
    });
    if($("#password").length >0) {
        password = $("#password");
        conf_password = $("#conf_password");    
        
        if(password.val().length > 0 ) {
            if( password.val().length < 6) {
                if( password.parent().find('.validate').length  == 0 ) {
                    password.parent().append(' <p class="validate">' + validate_password_lenght + '</p>');
                } else {
                    password.parent().find('.validate').text(validate_password_lenght);  
                }
                validate.push("block");
            } else {
                password.parent().find('.validate').text("");     
                if( ! conf_password.val() || (password.val() != conf_password.val()) ) {
                if( conf_password.parent().find('.validate').length  == 0 ) {                
                        conf_password.parent().append(' <p class="validate">' + validate_passwordconf + '</p>');
                    }
                    validate.push("block");
                } else {
                    conf_password.parent().find('.validate').text("");     
                    validate.push(true);
                }
            }
        }
    }
}

$('.form_edit_user').on('submit',function(event){   
    console.log("form_edit_user");
    event.preventDefault();
    var form = $(this).parents('form:first');
    let url = $(this).parents('form:first').attr('action');
    var form_data = form.serialize();
    
    validate_user_form ();

    if( validate.includes("block") ) {
        event.preventDefault();
        validate = [];
        if( roles.parent().parent().find('.validate').length  == 0 ) {                
            roles.parent().parent().append(' <p class="validate">' + validate_role + '</p>');
        }
    } else {
        roles.parent().find('.validate').text("");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });     
        $.ajax({
            url: url,
            type: "POST",
            data: form_data,
            success: function( response ) {
            }, 
            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr.responseJSON);
                var data_to_send = { 'exception':  jqXhr.responseJSON.exception,
                                    'message':  jqXhr.responseJSON.message,
                                    'file':  jqXhr.responseJSON.file,
                                    'line':  jqXhr.responseJSON.line };
                if(url.includes("users") && errorThrown == 'Unprocessable Entity' ) {
                    alert(email_unique);
                }  else {
                    $.modal.close();
                    $.ajax({
                        url: 'errorMessage',
                        type: "get",
                        data: data_to_send,
                        success: function( response ) {
                            $('<div><div class="modal-header"><span class="img-error"></span></div><div class="modal-body"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>danger:</strong>' + response + '</div></div></div>').appendTo('body').modal();
                        }, 
                        error: function(jqXhr, json, errorThrown) {
                            console.log(jqXhr.responseJSON); 
                        }
                    });
                }
            }
        });
    }

    console.log(validate);
    console.log(password);
    console.log(conf_password);
    console.log(url);
    console.log(form_data);
});

$('.form_edit_user .btn-next').on('click',function(event){  
    f_name = $("#first_name");
    l_name = $("#last_name");
    email = $("#email");
    file = $("#file");        
    validate_user_form ();
  
    //console.log(validate);
    if( validate.includes("block") ) {
        validate = [];
    } else {
        $('.first_tab').toggle();
        $('.second_tab').toggle();
        if($('.first_tab').is(':visible')) {
            $('.mark1').css('background','#1594F0');
            $('.mark2').css('background','rgba(43, 43, 43, 0.2)');
    
        } 
        if($('.second_tab').is(':visible')) {
            $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
            $('.mark2').css('background','#1594F0');
        }
    }
    console.log(validate);
});

$('.form_edit_user .btn-back').on('click',function(){
    $('.first_tab').toggle();
    $('.second_tab').toggle();
    if($('.first_tab').is(':visible')) {
        $('.mark1').css('background','#1594F0');
        $('.mark2').css('background','rgba(43, 43, 43, 0.2)');

    } 
    if($('.second_tab').is(':visible')) {
        $('.mark1').css('background','rgba(21, 148, 240, 0.4)');
        $('.mark2').css('background','#1594F0');
    }
}); */
$(function(){
    $.getScript( '/../js/filter_table.js');
});

$(function() {
    $( ".td_izostanak:contains('GO')" ).each(function( index ) {
        $( this ).addClass('abs_GO');
    });
    $( ".td_izostanak:contains('BOL')" ).each(function( index ) {
        $( this ).addClass('abs_BOL');
    });
});
