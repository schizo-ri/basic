<div class="modal-body">
    <form action="{{ route('documents.store') }}" class="box" method="post" id="form_submit" enctype="multipart/form-data" >
        @if (! $profileIMG)
            <div class="form-group ">
                <label>Name your content</label>
                <input type='text' name="title" class="form-control" required />
            </div>
            <input type='hidden' name="users_interest" value="true"/>
        @else
            <input type='hidden' name="profileIMG" value="true"/>
        @endif
        <div class="form-group box__input">
            <input class="box__file  {!! $profileIMG ? 'upload_profile_photo' : '' !!} " type="file" name="fileToUpload[]" id="file" data-multiple-caption="{count} files selected" {!! ! $profileIMG ? 'multiple' : '' !!}  required/>
            <label class="label_file" for="file"><span class="img_plus"></span><span class="text_upload box__dragndrop">
                Choose a file </span>
            </label>
        </div>
        {{ csrf_field() }}
        <input class="btn-submit box__button" type="submit" value="{{ __('basic.publish') }}" name="submit" />
    </form>
</div>
<script>
     $(function(){
        $('.modal').addClass('upload');
        $('.modal.upload').css({'width':'auto','max-width':'90%'});
        $.getScript( '/../js/user_profile.js');

     });
        var isAdvancedUpload = function() {
            var div = document.createElement('div');
            return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window;
        }();
    
        var $form = $('.box');
        var $input    = $form.find('input[type="file"]');
        var  $label    = $form.find('label.label_file');
        var url = location.href;
    
        showFiles = function(files) {
            $label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
        };
    
        if (isAdvancedUpload) {
            $form.addClass('has-advanced-upload');
            var droppedFiles = false;
    
            $form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                })
                .on('dragover dragenter', function() {
                    $form.addClass('is-dragover');
                })
                .on('dragleave dragend drop', function() {
                    $form.removeClass('is-dragover');
                })
                .on('drop', function(e) {
                    droppedFiles = e.originalEvent.dataTransfer.files;
                    showFiles( droppedFiles );
                    $form.unbind();
                    $form.trigger('submit');
               //     $('.close-modal').click();
    
                    $('.main_profile .profile_images').load(url + ' .main_profile .profile_images .profile_img');
    
                });
          
            $('.box__file').change(function (e) {
                showFiles(e.target.files);
                $form.unbind();
                $form.trigger('submit');
             //   $('.close-modal').click();
                if( $('.box__file').hasClass('upload_profile_photo') ) {
                    $('.profile_main .main_profile .profile_photo').load(url + ' .profile_main .main_profile .profile_photo');
                } else {
                    $('.main_profile .profile_images').load(url + ' .main_profile .profile_images .profile_img');
                }
            
            });
        }
    
        $form.on('submit', function(e) {
            if ($form.hasClass('is-uploading')) return false;
            $form.addClass('is-uploading').removeClass('is-error');
    
            if (isAdvancedUpload) {
                e.preventDefault();
                var url1 = $form.attr('action') ;
                var type = $form.attr('method')
                var ajaxData = new FormData($form.get(0));
                if (droppedFiles) {
                    $.each( droppedFiles, function(i, file) {
                        ajaxData.append( $input.attr('name'), file );
                    });
                }
            
                $.ajax({
                    url: url1,
                    type: type,
                    data: ajaxData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $form.addClass( data.success == true ? 'is-success' : 'is-error' );
                        if (!data.success) $errorMsg.text(data.error);
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
                    },
                    complete: function() {
                        $form.removeClass('is-uploading');
                    }
                });
            } else {   //stari browseri
                var iframeName  = 'uploadiframe' + new Date().getTime();
                    $iframe   = $('<iframe name="' + iframeName + '" style="display: none;"></iframe>');
    
                $('body').append($iframe);
                $form.attr('target', iframeName);
    
                $iframe.one('load', function() {
                    var data = JSON.parse($iframe.contents().find('body' ).text());
                    $form
                    .removeClass('is-uploading')
                    .addClass(data.success == true ? 'is-success' : 'is-error')
                    .removeAttr('target');
                    if (!data.success) $errorMsg.text(data.error);
                    $form.removeAttr('target');
                    $iframe.remove();
                });
            }
        });
</script>