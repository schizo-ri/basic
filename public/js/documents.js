$( function () {
    console.log("collaps");
    var body_width = $('body').width();
    var div_width = $( '.preview_doc').width();
    var all_width = 115;
   
    $( ".preview_doc > .thumbnail" ).each( (index, element) => {
        all_width += 115;
    });

    if(all_width > div_width ) {
        $('.preview_doc .scroll_right').show();
    }
    $('.collapsible').click(function(event){ 
        $(this).siblings().toggle();
    });
    $('#right-button').click(function() {
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

    $('#left-button').click(function() {
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

	$('.show').click(function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').show();
        
        index_height = $('.index_main.main_documents').height();
        header_height = $('.page-header.header_document').height();
        body_height = index_height - header_height - 60;
        $('.all_documents').height(body_height);
    });
    
    $('.hide').click(function(){
        $('.show').toggle();
        $('.hide').toggle();
        $('.preview_doc').hide();
        index_height = $('.index_main.main_documents').height();
        header_height = $('.page-header.header_document').height();
        body_height = index_height - header_height - 60;
        $('.all_documents').height(body_height);
    });
    
    $('.button_nav').css({
        'background': '#051847',
        'color': '#ffffff'
    });
    
    $( '.doc_button' ).css({
        'background': '#0A2A79',
        'color': '#ccc'
    });
    
    $(function() {
		 $('#index_table').css('height','fit-content');
    });

    $('.add_new').click(function(){
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
    $('.notice_show').click(function(){
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
    $('a.create_notice[rel="modal:open"]').click(function(){
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
    $('.ads_button, .post_button, .doc_button, .event_button').click(function(){
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
    $('.quest_button').click(function(){
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
});