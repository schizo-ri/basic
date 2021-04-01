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

    findOpenElement ();
    openResults();
    openTasks();
    deleteOkr ();
    edit_progress();
    progressBar();
    selectSearch ();

    $('#filter_status').on('change',function() {
        filter_status();
    });	

    $('#filter_quarter').on('change',function() {
        filter_quarter();
    });	

    $('#filter_okr_empl').on('change',function() {
        filter_quarter();
    });	

//ok
    function filter_quarter () {                            /* Filter kvartal i djelatnik */
        quarter = $('#filter_quarter').val().toLowerCase();
        employee = $('#filter_okr_empl').val().toLowerCase();
        
        console.log(quarter);
        console.log(employee);

        if( quarter == 'all' && employee == 'all' ) {
            $('.div_okr').show();
        } else if( quarter == 'all') {
            $('.panel').filter(function() {
                if( $(this).text().toLowerCase().indexOf(employee) > -1 ) {
                    $(this).show();
                    $(this).parent().show();
                } else {
                    $(this).hide();
                }
            });
        } else if (employee == 'all' ) {
            $('.panel').filter(function() {
                if( $(this).text().toLowerCase().indexOf(quarter) > -1 ) {
                    $(this).show();
                    $(this).parent().show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('.panel').filter(function() {
                if( $(this).text().toLowerCase().indexOf(quarter) > -1 && $(this).text().toLowerCase().indexOf(employee) > -1 ) {
                    $(this).show();
                    $(this).parent().show();
                } else {
                    $(this).hide();
                }
            });
        }
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


                    jQuery.each( open_element, function( i, val ) {
                        /* $('#'+val).addClass('open_element'); */
                        $('.open_element').show();
                    });

                    filter_quarter();

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
     /*    if( $( ".keyResults" ).length > 0) {
            $( ".keyResults" ).each(function( index ) {
                if( $(this).is(':visible') && $( this ).attr('id') != undefined) {
                    if( jQuery.inArray( $( this ).attr('id'), open_element ) == -1)  {
                        open_element.push($( this ).attr('id'));
                    }
                }
            });
        } */
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

      /*   filter_status(); */
	} 
//ok
    function openResults() {
        $('.div_okr').on('click',function(e){
            console.log("openResults");
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
            console.log("openTasks");
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
                reload_element = $( this ).parent().parent().attr('id');
                console.log("reload_element " +reload_element );
                result_line = $( this ).parent().parent().parent().prev('.div_okr').attr('id');
                console.log("result_line " +result_line );

                maxWidth = $( this ).width(); 
                clickPos = e.pageX - $(this).offset().left; 
                id = $( this ).attr('id');
                progress = Math.round((clickPos / maxWidth * 100)/5)*5;
                $( this ).progressbar("value", progress); //set the new value
                $( this ).siblings('span').text(progress + '%');
            
                storeProgressOnResult( id, progress, reload_element, result_line );
            }
            if( $( this ).hasClass('progressTask') ) {
                reload_element = $( this ).parent().parent().attr('id');
                console.log("reload_element " +reload_element );

                result_line = $( this ).parent().parent().parent().prev('.keyResults').attr('id');
                console.log("result_line " +result_line );
               
                maxWidth = $( this ).width(); 
                clickPos = e.pageX - $(this).offset().left; 
                id = $( this ).attr('id');
                id = id.replace('task_','');				
                progress = Math.round((clickPos / maxWidth * 100)/5)*5;
                $( this ).progressbar("value", progress); //set the new value
                $( this ).siblings('span').text(progress + '%');
                storeProgressOnTask( id, progress, reload_element, result_line);
            }
            if( $( this ).hasClass('progressOkr') ) {
                reload_element = $( this ).parent().parent().attr('id');
                console.log("reload_element " +reload_element );
                maxWidth = $( this ).width(); 
                clickPos = e.pageX - $(this).offset().left; 
                id = $( this ).attr('id');
                id = id.replace('okr_','');				
                progress = Math.round((clickPos / maxWidth * 100)/5)*5;
                $( this ).progressbar("value", progress); //set the new value
                $( this ).siblings('span').text(progress + '%');

                storeProgressOnOkr( id, progress, reload_element );
            }
        });
    }
//ok
    function storeProgressOnOkr( id, progress, reload_element ) {
        visible_element = $('.tabcontent:visible').attr('id');
        url = location.origin + '/progressOkr' + '?progress='+progress+'&id='+id;
        employee = $('#filter_okr_empl').val().toLowerCase();
        quarter = $('#filter_quarter').val().toLowerCase();
        
        console.log(url);
        console.log(employee);
        console.log(quarter);

        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $('#loader').remove();
                $.get(location.href, function(data, status){
                    content =  $('#'+reload_element+' >div',data );
                    $( '#'+reload_element).html( content );
                    
                    edit_progress();
                   /*  progressBar(); */
                });
            },
            error: function(jqXhr, json, errorThrown) {
                alert(jqXhr.responseJSON.message);
            }
        });

    }
//ok
    function storeProgressOnResult( id, progress, reload_element, result_line ) {
        visible_element = $('.tabcontent:visible').attr('id');
        
        url = location.origin + '/progressKeyResult' + '?progress='+progress+'&id='+id;

        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $('#loader').remove();

                $.get(location.href, function(data, status){
                    content =  $('#'+reload_element+' >div',data );
                    $( '#'+reload_element).html( content );

                    if ( result_line != undefined ) {
                        content2 =  $('#'+result_line+' >div',data );
                        $( '#'+result_line).html( content2 );
                        
                        
                    }
                  
                    edit_progress();
                    /* progressBar(); */
                  
                });                
            },
            error: function(jqXhr, json, errorThrown) {
                alert(jqXhr.responseJSON.message);
            }
        });
    }
//ok
    function storeProgressOnTask( id, progress, reload_element, result_line ) {
        console.log("storeProgressOnTask");
        console.log(open_element);
        url = location.origin + '/progressTask' + '?progress='+progress+'&id='+id;
        visible_element = $('.tabcontent:visible').attr('id');
        employee = $('#filter_okr_empl').val().toLowerCase();
        quarter = $('#filter_quarter').val().toLowerCase();

        $.ajax({
            url: url,
            type: "get",
            beforeSend: function(){
                $('body').prepend('<div id="loader"></div>');
            },
            success: function( response ) {
                $('#loader').remove();
                $.get(location.href, function(data, status){
                    content =  $('#'+reload_element+' >div',data );
                    $( '#'+reload_element).html( content );
                    
                    if ( result_line != undefined ) {
                        content2 =  $('#'+result_line+' >div',data );
                        $( '#'+result_line).html( content2 );
                    }
                    /* openTasks(); */
                    edit_progress();
                    /* progressBar(); */

                   /*  content =  $('#'+visible_element+' .section_okr>div',data );
                    $( '#'+visible_element+' .section_okr').html( content );  
                    
                    jQuery.each( open_element, function( i, val ) {
                        $('#'+val).addClass('open_element');
                        $('.open_element').show();
                    });
                    
                    openResults();
                    openTasks();
                    deleteOkr ();
                    edit_progress();
                    progressBar(); */
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

                    console.log("reload_element "+reload_element);
    
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
                            $.get(location.href, function(data, status){
                                content =  $('#'+reload_element+'>div', data );
                                $( '#'+reload_element).html( content );  

                                if( $('#'+reload_element).hasClass( 'div_keyResults')) {
                                    console.log( 'hasClass div_keyResults');
                                    openTasks();
                                } else if ($('#'+reload_element).hasClass( 'div_keyResultTasks')) {
                                    console.log( 'hasClass div_keyResultTasks');
                                }
                               /*  openResults(); */
                               /*  openTasks(); */
                                /* 
                                jQuery.each( open_element, function( i, val ) {
                                    $('#'+val).addClass('open_element');
                                    $('.open_element').show();
                                });
                                
                                openResults();
                                openTasks();
                                deleteOkr ();
                                edit_progress();
                                progressBar(); */
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
      /*   console.log(params);
        console.log(params.term);
        
        console.log(data);
        console.log(data.text); */
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
        /* console.log(value);
        console.log(search_Array); */
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