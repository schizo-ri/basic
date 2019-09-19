<div class="modal-header">
    <h3 class="panel-title">@lang('basic.add_document')</h3>
</div>
<div class="modal-body">
    <form action="{{ route('documents.store') }}" method="post" enctype="multipart/form-data" style="text-align:left;">
        <div class="form-group ">
            <label class="padd_10">Za djelatnika </label>
            <select class="djelatnik" name="employee_id" value="{{ old('employee_id') }}" required>
                <option selected="selected" value=""></option>
                <option name="svi" value="svi">Svi zaposlenici</option>
                @foreach($employees as $employee)
                    <option name="employee_id" value="{{ $employee->id }}">{{ $employee->user['last_name']. ' ' . $employee->user['first_name'] }}</option>
                @endforeach	
            </select>
        </div>
        <div class="form-group">
            <label class="label_file" for="file">File<span><img src="{{ URL::asset('icons/download.png') }}" />@lang('basic.read_file')</span></label>
            <input type='file' id="file" name="fileToUpload" required onchange="getFileData(this)" />
            <span id="file_name"></span>
        </div>
            {{ csrf_field() }}
            <input class="btn-submit" type="submit" value="{{ __('basic.upload_file') }}" name="submit">
    </form>
</div>
<script>
    $(function(){ 
        $.getScript( 'js/documents.js');
    });
</script>
    

