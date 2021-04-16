<div class="modal-header"><a class="float_right" href="{{ route('manufacturers.create') }}" rel="modal:open"><img class="" src="{{ URL::asset('icons/plus.png') }}" alt="arrow" /></a>
	<h3 class="panel-title">Proizvođači </h3>
</div>
<div class="modal-body manufacturers_body">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="col-10">Naziv</th>
                <th class="col-2">Opcije</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($manufacturers as $manufacturer)
                <tr>
                    <td class="col-10">{{ $manufacturer->name }}</td>
                    <td class="col-2">
                        <a href="{{ route('manufacturers.edit', $manufacturer->id) }}" class="btn" rel="modal:open">
                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            
                        </a>
                        <a href="{{ route('manufacturers.destroy', $manufacturer->id) }}" class=" btn btn-delete" data-token="{{ csrf_token() }}">
                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    $.getScript( '/../restfulizer.js');
    $.getScript( '/../js/manufacturer.js');
</script>
