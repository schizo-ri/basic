 <div class="modal-header">
	<h3 class="panel-title">Dodaj stanicu</h3>
</div>
<div class="modal-body">
    <form class="" accept-charset="UTF-8" role="form" method="post" action="{{ route('agglomeration_station_lists.store') }}"  enctype="multipart/form-data">
        <input name="station_id" type="text" value="{{ $station_id }}" required hidden />
        <span class="input_preparation for_file">
            <input type="file" style="display:none" name="file" id="file" required />
            <label for="file" class="label_file" title="Učitaj dokumenat"><i class="fas fa-upload"></i></label>
            <span class="file_to_upload"></span>
        </span>
        {{ csrf_field() }}
        <input class="btn btn_spremi submit_createForm float_right" disabled type="submit" value="&#10004; Spremi">
    </form>
</div>
<script> 
$('#file').change(function(e){
    $('.file_to_upload').text(e.target.files[0].name);
    $('.submit_createForm').removeAttr('disabled');
}); 
</script>