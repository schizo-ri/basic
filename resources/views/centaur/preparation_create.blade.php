<form class="create_preparation" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.store') }}" enctype="multipart/form-data">
    <span class="input_preparation file_input"></span>
    <span class="input_preparation project_no_input">
        <input name="project_no" type="text" value="{{ old('project_no') }}" required placeholder="Broj" maxlength="30"  />
    </span>
    <span class="input_preparation name_input">
        <input class=""  name="name" type="text" value="{{ old('name') }}" placeholder="Naziv projekta" />
    </span>
    <span class="input_preparation delivery_input">
        <input class="" name="delivery" type="date" value="" required />
    </span>
    @if (Sentinel::inRole('moderator') || Sentinel::inRole('administrator') || Sentinel::inRole('upload_list'))
        <span class="input_preparation manager_input">
            <select name="project_manager" class="project_manager" required>
                <option disabled selected >Voditelj projekta</option>
                @foreach ($users as $user)
                    @if ($user->first_name && $user->last_name)
                        <option value="{{ $user->id }}">{{ $user->first_name . ' ' .  $user->last_name}}</option>
                    @endif                    
                @endforeach
            </select>
        </span>
        <span class="input_preparation designed_input">
            <select name="designed_by" class="designed_by" required>
                <option disabled selected >Projektant</option>
                @foreach ($users as $user)
                    @if ($user->first_name && $user->last_name)
                        <option value="{{ $user->id }}">{{ $user->first_name . ' ' .  $user->last_name}}</option>
                    @endif                    
                @endforeach
            </select>
        </span>
    @endif
    @if (Sentinel::inRole('subscriber') || Sentinel::inRole('administrator') || Sentinel::inRole('upload_list'))
    <!-- <span class="input_preparation preparation_input">
        <span class="col-md-4"><input type="radio" name="preparation" value="DA" id="prep_da_{{ $preparation->id }}" /><label for="prep_da_{{ $preparation->id }}">DA</label></span>
        <span class="col-md-4"><input type="radio" name="preparation" value="NE" id="prep_ne_{{ $preparation->id }}" /><label for="prep_ne_{{ $preparation->id }}">NE</label></span>
        <span class="col-md-4"><input type="radio" name="preparation" value="N/A" checked id="prep_na_{{ $preparation->id }}" /><label for="prep_na_{{ $preparation->id }}">N/A</label></span>
    </span>
    <span class="input_preparation mechanical_input">
        <span class="col-md-4"><input type="radio" name="mechanical_processing" value="DA" id="meh_da_{{ $preparation->id }}" /><label for="meh_da_{{ $preparation->id }}">DA</label></span>
        <span class="col-md-4"><input type="radio" name="mechanical_processing" value="NE" id="meh_ne_{{ $preparation->id }}" /><label for="meh_ne_{{ $preparation->id }}">NE</label></span>
        <span class="col-md-4"><input type="radio" name="mechanical_processing" value="N/A" checked id="meh_na_{{ $preparation->id }}" /><label for="meh_na_{{ $preparation->id }}">N/A</label></span>
    </span>
    <span class="input_preparation marks_input">
        <span class="col-md-4"><input type="radio" name="marks_documentation" value="DA" id="mark_da_{{ $preparation->id }}" /><label for="mark_da_{{ $preparation->id }}">DA</label></span>
        <span class="col-md-4"><input type="radio" name="marks_documentation" value="NE" id="mark_ne_{{ $preparation->id }}" /><label for="mark_ne_{{ $preparation->id }}">NE</label></span>
        <span class="col-md-4"><input type="radio" name="marks_documentation" value="N/A" checked id="mark_na_{{ $preparation->id }}" /><label for="mark_na_{{ $preparation->id }}">N/A</label></span>
    </span> -->
    @endif
    @if (Sentinel::inRole('moderator') || Sentinel::inRole('administrator') || Sentinel::inRole('upload_list'))
        <span class="input_preparation for_file">
            <input name="siemens" value="1" type="checkbox"/> Siemens
            <input type="file" style="display:none" name="file" id="file" required />
            <label for="file" class="label_file" title="Učitaj dokumenat"><i class="fas fa-upload"></i></label>
            <span class="file_to_upload"></span>
        </span>
       
    @endif
    <span class="input_preparation submit_preparation">
        {{ csrf_field() }}
        <input class="btn btn_spremi submit_createForm" disabled type="submit" value="&#10004; Spremi">
    </span>
</form>
<script>
    $('#file').change(function(e){
        $('.file_to_upload').text(e.target.files[0].name);
        $('.submit_createForm').removeAttr('disabled');
    });

    var project_manager;
    var designed_by;
    $( ".create_preparation" ).submit(function( event ) {
        project_manager = $(this).find('.project_manager');
        designed_by= $(this).find('.designed_by');
     
        if( ! project_manager.val() ) {
            event.preventDefault();
           $( project_manager ).css('border','2px solid red');
        } else {
            $( project_manager ).css('border','1px solid rgb(169,169,169)');
        }
        if( ! designed_by.val() ) {
            event.preventDefault();
           $( designed_by ).css('border','2px solid red');
        } else {
            $( designed_by ).css('border','1px solid rgb(169,169,169)');
        }
        if( $( designed_by ).val() &&  $( project_manager ).val() ) {
            $( ".create_preparation" ).unbind();
        }
    });
</script>


