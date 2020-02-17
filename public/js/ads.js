$('.select_filter.sort').change(function () {
    $('main.main_ads').load($(this).val() + ' main.main_ads article');
});

var body_width = $('body').width();
if(body_width > 450) {
    var all_height = [];
    $('.ad.panel .ad_content').each(function(){
        all_height.push($(this).height());
    });

    all_height.sort(function(a, b) {
        return b-a;
    });
    var max_height = all_height[0];

    $('.ad.panel .ad_content').height(max_height);
}

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