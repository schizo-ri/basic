$.getScript( '/../js/filter_table.js');
if($('.users_main').length > 0) {

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
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
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
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
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
            clickClose: true,       // Allows the user to close the modal by clicking the overlay
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
}