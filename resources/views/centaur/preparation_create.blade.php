<form class="create_preparation" accept-charset="UTF-8" role="form" method="post" action="{{ route('preparations.store') }}" enctype="multipart/form-data">
   {{--  <span class="input_preparation file_input"></span> --}}
    <span class="input_preparation project_no_input">
        <input name="project_no" type="text" value="{{ old('project_no') }}" required placeholder="Broj projekta" maxlength="30"  />
    </span>
    <span class="input_preparation name_input">
        <input class="" name="project_name" type="text" value="{{ old('project_name') }}" placeholder="Naziv projekta" required />
    </span>
    <span class="input_preparation name_input">
        <input class="" name="name" type="text" value="{{ old('name') }}" placeholder="Naziv ormara" required/>
    </span>
    <span class="input_preparation delivery_input">
        <input class="" name="delivery" type="date" value="" required />
    </span>
    <span class="input_preparation manager_input">
        <select name="project_manager" class="project_manager" required>
            <option disabled selected >Voditelj projekta</option>
            @foreach ($voditelji as $user)
                @if ($user->first_name && $user->last_name)
                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' .  $user->last_name}}</option>
                @endif                    
            @endforeach
        </select>
    </span>
    <span class="input_preparation designed_input">
        <select name="designed_by" class="designed_by" required>
            <option disabled selected >Projektant</option>
            @foreach ($projektanti as $user)
                @if ($user->first_name && $user->last_name)
                    <option value="{{ $user->id }}">{{ $user->first_name . ' ' .  $user->last_name}}</option>
                @endif                    
            @endforeach
        </select>
    </span>
    <span class="input_preparation for_file">
        <input name="siemens" value="1" type="checkbox"/> Siemens
        <input type="file" style="display:none" name="file" id="file" required />
        <label for="file" class="label_file" title="Učitaj dokumenat"><i class="fas fa-upload"></i></label>
        <span class="file_to_upload"></span>
    </span>
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


