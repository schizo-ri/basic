<div class="modal-header">
    <h3 class="panel-title">@lang('basic.add_document')</h3>
</div>
<div class="modal-body">
    <form class="form_doc" action="{{ route('documents.store') }}" method="post" enctype="multipart/form-data" style="text-align:left;">
        <div class="form-group ">
            <label class="padd_10">@lang('basic.to_employee') </label>
            <select class="djelatnik" name="employee_id" value="{{ old('employee_id') }}" required>
                <option selected="selected" value=""></option>
                <option name="svi" value="svi">@lang('basic.all_employees')</option>
                @foreach($employees as $employee)
                    <option name="employee_id" value="{{ $employee->id }}">{{ $employee->user['last_name']. ' ' . $employee->user['first_name'] }}</option>
                @endforeach	
            </select>
        </div>
        <div class="form-group">
            <label class="label_file" for="file">@lang('basic.file')<span><img src="{{ URL::asset('icons/download.png') }}" />@lang('basic.read_file')</span></label>
            <input type='file' id="file" name="fileToUpload" required  />
            <span id="file_name"></span>
        </div>
        {{ csrf_field() }}
        <input class="btn-submit" type="submit" value="{{ __('basic.upload_file') }}" name="submit">
    </form>
    
</div>
<span hidden class="locale" >{{ App::getLocale() }}</span>
<script>
    $('#file').change(function(){
        $('#file_name').text( $('input[type=file]').val());
    });

    $.getScript( '/../js/validate_doc.js');
</script>