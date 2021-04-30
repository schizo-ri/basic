<div class="modal-header">
	<h3 class="panel-title">@lang('basic.add_contract_template')</h3>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('contract_templates.store') }}">
		<fieldset>
			<div class="form-group {{ ($errors->has('name')) ? 'has-error' : '' }}">
				<label >@lang('basic.contract_name')</label>
				<p class="contract_name"><span>Ugovor</span><input class="form-control" name="name" type="text" maxlength="200" value="{{ old('name') }}" required /></p>
				{!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<div class="form-group {{ ($errors->has('general_conditions')) ? 'has-error' : '' }}">
				<label >Opći uvjeti</label>
				<textarea name="general_conditions" class="contract_article"  rows="5" maxlength="65535"  >{{ old('general_conditions') }}</textarea>
				{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
			</div>
			<p>Članci ugovora</p>
			<div class="group_article">
				<div class="form-group article {{ ($errors->has('article_text')) ? 'has-error' : '' }}">
					<label class="article_no">Članak <span>1</span></label>
					<textarea name="article_text[]" class="contract_article"  rows="5" maxlength="65535"  >{{ old('article_text') }}</textarea>
					{!! ($errors->has('description') ? $errors->first('description', '<p class="text-danger">:message</p>') : '') !!}
				</div>
			</div>
			<span class="add_article cursor">Dodaj članak</span>
			{{ csrf_field() }}
			<input class="btn-submit" type="submit" value="{{ __('basic.save')}}">
		</fieldset>
	</form>
</div>
<script>
	$('.add_article').on('click', function () {
		$( ".article" ).first().clone().appendTo( ".group_article" );
		var count = $( ".article" ).length;
		$( ".article" ).last().find('textarea').val("");
		$( ".article" ).last().find('.article_no span').text(count);
	});
	
	tinymce.init({
        selector: '.contract_article',
        height : 300,	
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste imagetools wordcount'
        ],
        menubar: 'file edit insert view format table tools help',
        toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        /* enable title field in the Image dialog*/
        image_title: true,
        /* enable automatic uploads of images represented by blob or data URIs*/
        automatic_uploads: true,
        /*
            URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
            images_upload_url: 'postAcceptor.php',
            here we add custom filepicker only to Image dialog
        */
        file_picker_types: 'image',
        /* and here's our custom image picker*/
        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            /*
            Note: In modern browsers input[type="file"] is functional without
            even adding it to the DOM, but that might not be the case in some older
            or quirky browsers like IE, so you might want to add it to the DOM
            just in case, and visually hide it. And do not forget do remove it
            once you do not need it anymore.
            */

            input.onchange = function () {
            var file = this.files[0];

            var reader = new FileReader();
            reader.onload = function () {
                /*
                Note: Now we need to register the blob in TinyMCEs image blob
                registry. In the next release this part hopefully won't be
                necessary, as we are looking to handle it internally.
                */
                var id = 'blobid' + (new Date()).getTime();
                var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                var base64 = reader.result.split(',')[1];
                var blobInfo = blobCache.create(id, file, base64);
                blobCache.add(blobInfo);

                /* call the callback and populate the Title field with the file name */
                cb(blobInfo.blobUri(), { title: file.name });
            };
            reader.readAsDataURL(file);
            };

            input.click();
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    $('body').on($.modal.CLOSE, function(event, modal) {
        $.getScript('/../node_modules/tinymce/tinymce.min.js');
    });
</script>