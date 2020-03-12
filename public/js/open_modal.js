$( document ).ready(function() {  
   
    $("a[rel='modal:open']").show();

    $.modal.defaults = {
    closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
    escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
    clickClose: false,       // Allows the user to close the modal by clicking the overlay
    closeText: 'Close',     // Text content for the close <a> tag.
    closeClass: '',         // Add additional class(es) to the close <a> tag.
    showClose: true,        // Shows a (X) icon/link in the top-right corner
    modalClass: "modal",    // CSS class added to the element being displayed in the modal.
    // HTML appended to the default spinner during AJAX requests.
    spinnerHtml:  "<div id='loader'><span class='ajax-loader1'></span></div>",
    showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
    fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
    fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
    };
   
    $('.equipment_lists_open').click(function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal equipment_lists",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: "<div id='loader'><span class='ajax-loader1'></span></div>",
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
            };
    });
    $('.open_upload_link').click(function(){
        $.modal.defaults = {
            closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
            escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
            clickClose: false,       // Allows the user to close the modal by clicking the overlay
            closeText: 'Close',     // Text content for the close <a> tag.
            closeClass: '',         // Add additional class(es) to the close <a> tag.
            showClose: true,        // Shows a (X) icon/link in the top-right corner
            modalClass: "modal",    // CSS class added to the element being displayed in the modal.
            // HTML appended to the default spinner during AJAX requests.
            spinnerHtml: "<div id='loader'><span class='ajax-loader1'></span></div>",  
            showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
            fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
            fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
            };
    });
});