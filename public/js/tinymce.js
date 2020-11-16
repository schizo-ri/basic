if( $('#tinymce_textarea').length >0 ) {
    tinymce.init({
        selector: '#tinymce_textarea',
        height : 300,	
        plugins: "image",
        menubar: 'file edit insert view format table tools help',
        toolbar: [
            {
            name: 'history', items: [ 'undo', 'redo' ]
            },
            {
            name: 'formatting', items: [ 'bold', 'italic', 'forecolor', 'backcolor' ]
            },
            {
            name: 'alignment', items: [ 'alignleft', 'aligncenter', 'alignright', 'alignjustify' ]
            },
            {
            name: 'indentation', items: [ 'outdent', 'indent' ]
            },
            {
            name: 'image', items: [ 'image','url' ]
            },
            {
            name: 'styles', items: [ 'styleselect' ]
            },
        ],

        image_list: [
            {title: 'My image 1', value: 'https://www.example.com/my1.gif'},
            {title: 'My image 2', value: 'http://www.moxiecode.com/my2.gif'}
        ]		
    });
    $('body').on($.modal.CLOSE, function(event, modal) {
        $.getScript('/../node_modules/tinymce/tinymce.min.js');
    });
}