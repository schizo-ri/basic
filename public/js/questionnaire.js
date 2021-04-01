$(function() { 
    if( $('.main_questionnaire').length > 0) {
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

        $('tr.clickable-row[data-modal] td:not(:last-child)').on("click", function(e) {
            e.preventDefault();
            var href = location.origin + $(this).parent().data('href');
            console.log(href);

            if( $('.main_questionnaire').length > 0 ) {
                var class_modal = 'modal modal_questionnaire';
            } else {
                var class_modal = 'modal';
            }
            $.modal.defaults = {
                closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
                escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
                clickClose: true,      // Allows the user to close the modal by clicking the overlay
                closeText: 'Close',     // Text content for the close <a> tag.
                closeClass: '',         // Add additional class(es) to the close <a> tag.
                showClose: true,        // Shows a (X) icon/link in the top-right corner
                modalClass: class_modal,    // CSS class added to the element being displayed in the modal.
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

        $('.collapsible').on("click", function(event){ 
            $(this).siblings().toggle();
        });
        $('#right-button').on("click", function() {
            event.preventDefault();
            $('.preview_doc').animate({
                scrollLeft: "+=217px"
            }, "slow");
            $('.preview_doc .scroll_left').show();
            
        });

        $('.sendEmail').on("click", function(){
            if (!confirm("Stvarno želiš poslati obavijest mailom?")) {
                return false;
            }
        });
        
        $('#left-button').on("click", function() {
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

        $('.show').on("click", function(){
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

        $('.hide').on("click", function(){
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
    }
    
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