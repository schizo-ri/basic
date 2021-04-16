<div class="modal-header">
    <h3 class="panel-title">@lang('basic.edit_document') - {{ $document->title }}</h3>
</div>
<div class="modal-body">
    <form class="form_doc" action="{{ route('documents.update', $document->id) }}" method="post" enctype="multipart/form-data" style="text-align:left;">
        <div class="form-group ">
            <label class="padd_10">@lang('basic.document_category') </label>
            <select class="" name="category_id" value="{{ old('category_id') }}" required > 
                <option selected disabled ></option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {!! $document->category_id == $category->id ? 'selected' : '' !!}>{{ $category->name }}</option>
                @endforeach	
            </select>
        </div>
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input class="btn-submit" type="submit" value="{{ __('basic.save') }}" name="submit">
    </form>
    
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
    $('#file').change(function(){
        $('#file_name').text( $('input[type=file]').val());
    });

    $.getScript( '/../js/validate_doc.js');
</script>