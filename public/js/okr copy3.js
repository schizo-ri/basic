
if( $( '.section_okr' ).length > 0 ) {
    var progress;
    var maxWidth;
    var clickPos;
    var percentage;
    var url;
    var id;
    var okr_line;
    var reload_element;
    var visible_element = $('.tabcontent:visible').attr('id');
    var invisible_element = $('.tabcontent:hidden').attr('id');
    var content;
    var open_element = open_element ? open_element : [];
    var employee;
    var quarter;
    var status;

    $('.hover_open_comment ').on('mouseenter', function() {
        console.log($( this ).find('.okr_comments').length);
        if( $( this ).find('.okr_comments').length > 0 )
            $( this ).find('.okr_comments').show();
     }).on('mouseleave', function() {
        if( $( this ).find('.okr_comments').length > 0 )
            $( this ).find('.okr_comments').hide();
     });


    $('.reminder_btn').on('click',function(){
        console.log("reminder_btn");

        url = $( this ).attr('href');
        
        $.ajax({
            url: url,
            type: "get",
            success: function( response ) {
                alert(response);
            },

            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr.responseJSON.message);
            }
        });


    });
    if(open_element.length == 0) {
        findOpenElement ();
    }
    openResults();
    openTasks();
    deleteOkr ();
    edit_progress();
    progressBar();
    selectSearch ();
    
    $('#filter_okr_tim').on('change',function() {
        tim = $( this ).val();
        console.log(tim);

        url = location.href + '?tim='+tim;
        console.log(url);
        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $('#loader').remove();

                $.get(url, function(data, status){
                    content =  $('#'+visible_element+' .section_okr>div', data );
                    $( '#'+visible_element+' .section_okr').html( content );

                    filterQuarterOpenElements();

                    openResults();
                    openTasks();
                    deleteOkr ();
                    edit_progress();
                    progressBar();
                });
            },

            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr.responseJSON.message);
            }
        });
    });	

    $('#filter_status').on('change',function() {
        filter_status();
    });	

    $('#filter_quarter').on('change',function() {
        filterQuarter();
    });	

    $('#filter_okr_empl').on('change',function() {
        filterQuarter();
        employee = $( this ).find('option:selected').attr('data-value');
        url = location.origin + '/exportOkr?employee_id='+employee;
        $('.exportOkr').attr('href',url)
    });	

    function filterQuarter () {             /* Filter kvartal i djelatnik */
        quarter = $('#filter_quarter').val().toLowerCase();
        employee = $('#filter_okr_empl').length > 0 ? $('#filter_okr_empl').val().toLowerCase() : 'all';

        if( quarter == 'all' && employee == 'all' ) {
            $('.div_okr').show();
        } else if( quarter == 'all') {
            $('.panel_filter').filter(function() {
                if( $(this).text().toLowerCase().indexOf(employee) > -1 ) {
                    $(this).show();
                    $(this).parent().show();
                } else {
                    $(this).hide();
                }
            });
            $('.div_keyResults').hide();
        } else if (employee == 'all' ) {
            $('.panel_filter').filter(function() {
                if( $(this).text().toLowerCase().indexOf(quarter) > -1 ) {
                    $(this).show();
                    $(this).parent().show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('.panel_filter').filter(function() {
                if( $(this).text().toLowerCase().indexOf(quarter) > -1 && $(this).text().toLowerCase().indexOf(employee) > -1 ) {
                    $(this).show();
                    $(this).parent().show();
                } else {
                    $(this).hide();
                }
            });
        }
        $('.div_keyResults').hide();
        $('.div_keyResultTasks').hide();
         
        findOpenElement ();
    }

    function filterQuarterOpenElements(open_element) {
        visible_element = $('.tabcontent:visible').attr('id')

        quarter = $('#filter_quarter').val().toLowerCase();
        employee = $('#filter_okr_empl').length > 0 ? $('#filter_okr_empl').val().toLowerCase() : 'all';
      /*   if( quarter == 'all' && employee == 'all' ) {
            $('.div_okr').show();
        } else if( quarter == 'all') {
            $('.panel_filter').filter(function() {
                if( $(this).text().toLowerCase().indexOf(employee) > -1 ) {
                    $(this).show();
                    $(this).parent().show();
                } else {
                    $(this).hide();
                }
            });
            $('.div_keyResults').hide();
        } else if (employee == 'all' ) {
            $('.panel_filter').filter(function() {
                if( $(this).text().toLowerCase().indexOf(quarter) > -1 ) {
                    $(this).show();
                    $(this).parent().show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('.panel_filter').filter(function() {
                if( $(this).text().toLowerCase().indexOf(quarter) > -1 && $(this).text().toLowerCase().indexOf(employee) > -1 ) {
                    $(this).show();
                    $(this).parent().show();
                } else {
                    $(this).hide();
                }
            });
        } */
        jQuery.each( open_element, function( i, val ) {
            $("#"+this).show();
        });
    }

    function filter_status() {
        status = $( '#filter_status' ).val();
        url = location.href + '?status='+status;
        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $('#loader').remove();

                $.get(url, function(data, status){
                    content =  $('#'+visible_element+' .section_okr>div', data );
                    $( '#'+visible_element+' .section_okr').html( content );                      

                    filterQuarterOpenElements();

                    openResults();
                    openTasks();
                    deleteOkr ();
                    edit_progress();
                    progressBar();
                });
                
            },

            error: function(jqXhr, json, errorThrown) {
                console.log(jqXhr.responseJSON.message);
            }
        });
    }
//ok
    function findOpenElement (){
        open_element = [];
        if( $( ".div_okr" ).length > 0) {
            $( ".div_okr" ).each(function( index ) {
                if( $(this).is(':visible') && $( this ).attr('id') != undefined) {
                    if( jQuery.inArray( $( this ).attr('id'), open_element ) == -1)  {
    
                        open_element.push( $( this ).attr('id'));
                    }
                }
            });
        }
        if( $( ".div_keyResults" ).length > 0) {
            $( ".div_keyResults" ).each(function( index ) {
                if( $(this).is(':visible') && $( this ).attr('id') != undefined) {
                    if( jQuery.inArray( $( this ).attr('id'), open_element ) == -1)  {
                        open_element.push($( this ).attr('id'));
                    }
                }
            });
        }
        if( $( ".keyResults" ).length > 0) {
            $( ".keyResults" ).each(function( index ) {
                if( $(this).is(':visible') && $( this ).attr('id') != undefined) {
                    if( jQuery.inArray( $( this ).attr('id'), open_element ) == -1)  {
                        open_element.push($( this ).attr('id'));
                    }
                }
            });
        }
        if( $( ".div_keyResultTasks" ).length > 0) {
            $( ".div_keyResultTasks" ).each(function( index ) {
                if( $(this).is(':visible') && $( this ).attr('id') != undefined) {
                    if( jQuery.inArray( $( this ).attr('id'), open_element ) == -1)  {
                        open_element.push($( this ).attr('id'));
                    }
                }
            });
        }

        console.log('****** findOpenElement **********');
        console.log(open_element);
    }
//ok
    function openOKR(evt, name) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		$("#"+name).show();
		evt.currentTarget.className += " active";

        visible_element = $('.tabcontent:visible').attr('id');

        status = $('#filter_status').val();

        $.get(location.href, function(data, status){
            content =  $('#'+visible_element+' .section_okr>div',data );
            $( '#'+visible_element+' .section_okr').html( content );  
            
            openResults();
            openTasks();
            deleteOkr ();
            edit_progress();
            progressBar();

            filterQuarterOpenElements ();
        });   

        filter_status();
	} 
//ok
    function openResults() {
        $('.div_okr').on('click',function(e){
            if( $(e.target).is('.not_link') || $(e.target).is('.ui-progressbar-value') || $( e.target ).is('svg') || $( e.target ).is('path')){
                e.preventDefault();
                return;
            }
          
            $( this ).next('.div_keyResults').toggle();

            id = $( this ).next('.div_keyResults').attr('id');

            if( $('.div_keyResults#'+id).is(':visible')) {
                if (jQuery.inArray(id, open_element) == -1 ) {
                    open_element.push(id);
                }
            } else {
                $('.div_keyResults#'+id).removeClass('open_element');
                for( var i = 0; i < open_element.length; i++){ 
                    if ( open_element[i] === id) { 
                        open_element.splice(i, 1); 
                    }
                }
            }
            findOpenElement ();
        });
    }
//ok
    function openTasks() {
        $('.keyResults>div').on('click',function(e){
            if( $( e.target ).is('.not_link') ||  $(e.target).is('.ui-progressbar-value') || $( e.target ).is('svg') || $( e.target ).is('path')){
                e.preventDefault();
                return;
            }
            $( this ).parent().next('.div_keyResultTasks').toggle();
            id = $( this ).parent().next('.div_keyResultTasks').attr('id');

            if( $('.div_keyResultTasks#'+id).is(':visible')) {
                if (jQuery.inArray(id, open_element) == -1 ) {
                    open_element.push(id);
                }
            } else {
                $('.div_keyResultTasks#'+id).removeClass('open_element');
                for( var i = 0; i < open_element.length; i++){ 
                    if ( open_element[i] === id) { 
                        open_element.splice(i, 1); 
                    }
                }
            }
            findOpenElement ();
        });
    }
//ok
    function mySearchOkr() {
        $("#mySearchOkr").on('keyup',function() {
            var value = $(this).val().toLowerCase();
            
            var search_Array = value.split(" ");
        
            if ( value == '' ) {
                $('.div_keyResultTasks').hide();
                $( ".div_keyResults" ).hide();
            } else {
                $('.div_keyResultTasks').show();
                $( ".div_keyResults" ).show();
            }

            $(".panel").filter(function() {
                /* $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1) */
                if( search_Array.length == 1 ) {
                $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1);
                } else if( search_Array.length == 2 ) {
                $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1);
                } else if( search_Array.length == 3 ) {
                $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1);
                } else if( search_Array.length == 4 ) {
                $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[3]) > -1);
                } else if( search_Array.length == 5 ) {
                $(this).toggle($(this).text().toLowerCase().indexOf(search_Array[0]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[1]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[2]) > -1 && $(this).text().toLowerCase().indexOf(search_Array[4]) > -1);
                }
            });
        });
    }
//ok 
    function edit_progress() {
        $( ".edit_progress" ).each(function( index ) {
            progress = $( this ).find('span').text();
            progress = progress.replace('%','');

            $(this).find('.progressBar').progressbar({
                "value" : parseInt(progress)
            });
        });
    }
//ok
    function progressBar() {
        $(".progressBar").on('click',function(e){
            if( $( this ).hasClass('progressResult') ) {
                okr_line = $( this ).parent().parent().parent().prev('.div_okr').attr('id');
                maxWidth = $( this ).width(); 
                clickPos = e.pageX - $(this).offset().left; 
                id = $( this ).attr('id');
                progress = Math.round((clickPos / maxWidth * 100)/5)*5;
                $( this ).progressbar("value", progress); //set the new value
                $( this ).siblings('span').text(progress + '%');
            
                storeProgressOnResult( id, progress );
            }
            if( $( this ).hasClass('progressTask') ) {
                result_line = $( this ).parent().parent().parent().prev('.keyResults').attr('id');
            
                maxWidth = $( this ).width(); 
                clickPos = e.pageX - $(this).offset().left; 
                id = $( this ).attr('id');
                id = id.replace('task_','');				
                progress = Math.round((clickPos / maxWidth * 100)/5)*5;
                $( this ).progressbar("value", progress); //set the new value
                $( this ).siblings('span').text(progress + '%');
                storeProgressOnTask( id, progress );
            }
            if( $( this ).hasClass('progressOkr') ) {
                maxWidth = $( this ).width(); 
                clickPos = e.pageX - $(this).offset().left; 
                id = $( this ).attr('id');
                id = id.replace('okr_','');				
                progress = Math.round((clickPos / maxWidth * 100)/5)*5;
                $( this ).progressbar("value", progress); //set the new value
                $( this ).siblings('span').text(progress + '%');

                storeProgressOnOkr( id, progress );
            }
        });
    }
//ok
    function storeProgressOnOkr( id, progress ) {
        visible_element = $('.tabcontent:visible').attr('id');
        url = location.origin + '/progressOkr' + '?progress='+progress+'&id='+id;

        tim = $('#filter_okr_tim').val();
        status = $( '#filter_status' ).val();

        url_load = location.href + '?tim='+tim+'&status='+status;

        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $('#loader').remove();
                $.get(url_load, function(data, status){
                    content =  $('#'+visible_element+' .section_okr>div', data );
                    $( '#'+visible_element+' .section_okr').html( content );  
                
                    console.log(open_element);
                    jQuery.each( open_element, function( i, val ) {
                        $('#'+val).addClass('open_element');
                        $('.open_element').show();
                    });
                    
                    filterQuarterOpenElements();

                    openResults();
                    openTasks();
                    deleteOkr ();
                    edit_progress();
                    progressBar();
                });
            },
            error: function(jqXhr, json, errorThrown) {
                alert(jqXhr.responseJSON.message);
            }
        });

    }
//ok
    function storeProgressOnResult( id, progress ) {
        visible_element = $('.tabcontent:visible').attr('id');
        url = location.origin + '/progressKeyResult' + '?progress='+progress+'&id='+id;

        tim = $('#filter_okr_tim').val();
        status = $( '#filter_status' ).val();
        url_load = location.href + '?tim='+tim+'&status='+status;

        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $('#loader').remove();

                $.get(url_load, function(data, status){
                    content =  $('#'+visible_element+' .section_okr>div',data );
                    $( '#'+visible_element+' .section_okr').html( content );  
                    
                    console.log(open_element);
                    jQuery.each( open_element, function( i, val ) {
                        $('#'+val).addClass('open_element');
                        $('.open_element').show();
                    });
                    filterQuarterOpenElements();

                    openResults();
                    openTasks();
                    deleteOkr ();
                    edit_progress();
                    progressBar();
                });
            },
            error: function(jqXhr, json, errorThrown) {
                alert(jqXhr.responseJSON.message);
            }
        });
    }
//ok
    function storeProgressOnTask( id, progress ) {
        url = location.origin + '/progressTask' + '?progress='+progress+'&id='+id;
        visible_element = $('.tabcontent:visible').attr('id');

        tim = $('#filter_okr_tim').val();
        status = $( '#filter_status' ).val();
        url_load = location.href + '?tim='+tim+'&status='+status;

        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $('#loader').remove();
                $.get(url_load, function(data, status){
                    content =  $('#'+visible_element+' .section_okr>div',data );
                    $( '#'+visible_element+' .section_okr').html( content );  
                    
                    jQuery.each( open_element, function( i, val ) {
                        $('#'+val).addClass('open_element');
                        $('.open_element').show();
                    });
                    
                    openResults();
                    openTasks();
                    deleteOkr ();
                    edit_progress();
                    edit_progress();
                    progressBar();
                });
            },
            error: function(jqXhr, json, errorThrown) {
                alert(jqXhr.responseJSON.message);
            }
        });
    }
//ok
    function deleteOkr () {
        try {
            $('.action_confirm.btn-delete').on('click', function(e) {                
                if (! confirm("Sigurno želiš nastaviti brisanje?")) {
                    return false;
                } else {
                    e.preventDefault();
                    visible_element = $('.tabcontent:visible').attr('id');

                    url_delete = $( this ).attr('href');
                    url_load = location.href;
                    token = $( this ).attr('data-token');
                    reload_element = $( this ).attr('data-title');

                    if( reload_element != undefined ) {
                        reload_element = reload_element.replace('del_','');;
                    }
    
                    tim = $('#filter_okr_tim').val();
                    status = $( '#filter_status' ).val();

                    url_load = location.href + '?tim='+tim+'&status='+status;

                    $.ajaxSetup({
                        headers: {
                            '_token': token
                        }
                    });
                    $.ajax({
                        url: url_delete,
                        type: 'POST',
                        data: {_method: 'delete', _token :token},
                        beforeSend: function(){
                            $('body').prepend('<div id="loader"></div>');
                        },
                        success: function(result) {
                            $('#loader').remove();
                            $.get(url_load, function(data, status){
                                content =  $('#'+visible_element+' .section_okr>div',data );
                                $( '#'+visible_element+' .section_okr').html( content );  
                                
                             /*    jQuery.each( open_element, function( i, val ) {
                                    $('#'+val).addClass('open_element');
                                    $('.open_element').show();
                                }); */
                                
                                filterQuarterOpenElements();

                                openResults();
                                openTasks();
                                deleteOkr ();
                                edit_progress();
                                progressBar();
                            });
                        }
                    });
                }
            });
        } catch (error) {
            console.log(error);
        }
    }
//ok
    function selectSearch () {
        $(function(){
            if( $('.select_filter').length > 0 ) {
                $('.select_filter').select2({
                    dropdownParent: $('.index_page>main'),
                    matcher: matchCustom,
                    width: 'resolve',
                    placeholder: {
                        id: '-1', // the value of the option
                    },
                    theme: "classic",
                    
                });
            }
        });
    }
//ok
    function matchCustom(params, data) {
        // If there are no search terms, return all of the data
        if ($.trim(params.term) === '') {
          return data;
        }
    
        // Do not display the item if there is no 'text' property
        if (typeof data.text === 'undefined') {
          return null;
        }
    
        // `params.term` should be the term that is used for searching
        // `data.text` is the text that is displayed for the data object
        var value = params.term;
        var search_Array = value.split(" ");
     
        if( search_Array.length == 1 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        } else if( search_Array.length == 2 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        } else if( search_Array.length == 3 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        } else if( search_Array.length == 4 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1  && data.text.toLowerCase().indexOf(search_Array[3]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        }  else if( search_Array.length == 5 ) {
            if (data.text.toLowerCase().indexOf(search_Array[0]) > -1 && data.text.toLowerCase().indexOf(search_Array[1]) > -1 && data.text.toLowerCase().indexOf(search_Array[2]) > -1  && data.text.toLowerCase().indexOf(search_Array[3]) > -1 && data.text.toLowerCase().indexOf(search_Array[4]) > -1) {
                var modifiedData = $.extend({}, data, true);
                return modifiedData;
            }
        } 
        // Return `null` if the term should not be displayed
        return null;
    }
}