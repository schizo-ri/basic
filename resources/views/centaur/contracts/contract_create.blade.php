<form class="create_preparation" accept-charset="UTF-8" role="form" method="post" action="{{ route('contracts.store') }}" enctype="multipart/form-data">
   {{--  <span class="input_preparation file_input"></span> --}}
    <span class="input_preparation project_no_input">
        <input name="number" type="text" value="{{ old('number') }}" required placeholder="Broj" maxlength="30"  />
    </span>
    <span class="input_preparation name_input">
        <input class=""  name="supplier" type="text" value="{{ old('supplier') }}" placeholder="Dobavljač" />
    </span>
    <span class="input_preparation delivery_input">
        <input class="" name="comment" type="text" maxlength="191"  />
    </span>
    <span class="input_preparation for_file">
        <input type="file" style="display:none" name="file" id="file" required />
        <label for="file" class="label_file" title="Učitaj dokumenat"><i class="fas fa-upload"></i></label>
        <span class="file_to_upload"></span>
    </span>
    <span class="input_preparation submit_preparation">
        {{ csrf_field() }}
        <input class="btn btn_spremi submit_createForm" {{-- disabled --}} type="submit" value="&#10004; Spremi">
    </span>
</form>
<script>
    $('#file').change(function(e){
        $('.file_to_upload').text(e.target.files[0].name);
        $('.submit_createForm').removeAttr('disabled');
    }); 

    var project_manager;
    var designed_by;
   
</script>


