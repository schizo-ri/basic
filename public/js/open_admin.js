var prev_url = location.href;
$(".admin_pages a.admin_link").addClass('disable');
var body_width = $('body').width();
var url_location = location.href;
var active_link;
var url_modul = location.pathname;
var title;
url_modul = url_modul.replace("/","");
url_modul = url_modul.split('/')[0];

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

$(function(){
    if($('.index_admin').length > 0 ) {
        var class_open;

        if(body_width > 992) {
            class_open = $('.admin_link.active_admin').parent().attr('class');

            if(class_open != undefined && class_open != '') {
                class_open = "."+class_open.replace(" ",".");
                $(class_open).show();
            }
        }
    
        $('.open_menu').on('click', function(e){
            e.preventDefault();
            class_open = $( this).attr('id');
            $('.'+class_open).toggle();
        });
        $(".admin_pages a.admin_link").removeClass('disable');
        
        // ako ima shortcut - href edit
        try {
            url_location = location.href;
            $.get( location.origin+"/shortcut_exist", {'url': url_location }, function( id ) {
                if(id != null && id != '') {
                    $('.shortcut').attr('href', location.origin +'/shortcuts/'+id+'/edit/');
                    $('.shortcut_text').text('Ispravi prečac'); 
                } else {
                    title = $('.admin_link.active_admin').attr('id');
                    $('.shortcut').attr('href', location.origin +'/shortcuts/create/?url='+url_location+'&title='+title );
                    $('.shortcut_text').text('Dodaj prečac'); 
                }
            });
        } catch (error) {
            //
        }
    }
    if( $('.select_filter').not('.sort').length > 0 ) {
        selectSearch ();
    }
});

if($(".index_table_filter .show_button").length == 0) {
    $('.index_table_filter').not('.index_table_filter.structure_company').append('<span class="show_button"><i class="fas fa-download"></i></span>');
} 

var click_element;
var title;
var url;

if($('.index_admin').length > 0 ) { 
    $('.admin_pages li>a').not('.open_menu').on('click',function(e) {

        $('#login-modal').remove();
        e.preventDefault();
        click_element = $(this);
        title = click_element.text();
        $("title").text( title ); 
        url = $(this).attr('href');
        // ako ima shortcut - href edit
        try {
            $.get( location.origin+"/shortcut_exist", {'url': url }, function( id ) {
                if(id != null && id != '' && id) {
                    $('.shortcut').attr('href', location.origin +'/shortcuts/'+id+'/edit/');
                    $('.shortcut_text').text('Ispravi prečac'); 
                } else {
                    title = $('.admin_link.active_admin').attr('id');

                    $('.shortcut').attr('href', location.origin +'/shortcuts/create/?url='+url+'&title='+title );
                    $('.shortcut_text').text('Dodaj prečac'); 
                }
            });
        } catch (error) {
            //
        }
       
        $('.admin_pages>li>a').removeClass('active_admin');
        $(this).addClass('active_admin');
        active_link = $('.admin_link.active_admin').attr('id');

        $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
            window.history.replaceState({}, document.title, url);
            if ( status == "error" ) {
                var msg = "Sorry but there was an error: ";
                $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
            }
            if ($('.show_button_upload').length > 0 )  {
                $('.show_button_upload').on('click', function(){
                    $('form.upload_file').modal();
                    $('form.upload_file').show();
                });
            }
            $.getScript( '/../restfulizer.js');
            $.getScript( '/../js/filter_dropdown.js');
            $.getScript( '/../js/open_modal.js');
            $.getScript( '/../js/datatables.js');
            $.getScript('/../select2-develop/dist/js/select2.min.js');
            if( $('.select_filter').not('.sort').length > 0 ) {
                selectSearch ();
            }
            if (url.includes('/work_records')) {
                $.getScript( '/../js/work_records.js');
            } else if(url.includes('/loccos')) {
                $('a.open_locco').on('click',function(event) {
                    event.preventDefault();
                    click_element = $(this);
                    title = click_element.text();
                    $("title").text( title ); 
                    url = $(this).attr('href');

                    $( '.admin_main' ).load( url + ' .admin_main>section', function( response, status, xhr ) {
                        window.history.replaceState({}, document.title, url);
                        if ( status == "error" ) {
                            var msg = "Sorry but there was an error: ";
                            $( "#error" ).html( msg + xhr.status + " " + xhr.statusText );
                        }
                        if ($('.show_button_upload').length > 0 )  {
                            $('.show_button_upload').on('click', function(){
                                $('form.upload_file').modal();
                                $('form.upload_file').show();
                            });
                        }
                        $.getScript( '/../restfulizer.js');
                        $.getScript( '/../js/filter_dropdown.js');
                        $.getScript( '/../js/datatables.js');
                        $.getScript( '/../js/open_modal.js');
                        $.getScript('/../select2-develop/dist/js/select2.min.js');
                        if( $('.select_filter').not('.sort').length > 0 ) {
                            selectSearch ();
                        }
                    });
                    return false;
                });
            }
        
            if(body_width < 992 ) {
                $('aside.admin_aside').hide();
                $('main.admin_main').show();
            
                $('.link_back').on('click',function (e) {
                    e.preventDefault();
                    $('aside.admin_aside').show();
                    $('main.admin_main').hide();
                });
            }
        });
        return false;
    });
   
    if ($('.show_button_upload').length > 0 )  {
        $('.show_button_upload').on('click', function(){
            $('form.upload_file').modal();
            $('form.upload_file').show();
        });
    }
}
