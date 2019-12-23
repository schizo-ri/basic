<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a>
<div class="modal-header">
    <h2 class="">Lista opreme: {{ $equipments->first()->preparation1['project_no'] . ' - ' . $equipments->first()->preparation1['name'] }}</h2>
    <h4 class="">Datum isporuke: {!! $equipments->first()->preparation1['delivery'] ? date('d.m.Y', strtotime($equipments->first()->preparation1['delivery'] )) : '' !!}</h4>
    <div class="filter_color">
        <span class="all">Sve</span>
        <span class="green" >zeleno</span>
        <span class="red" >crveno</span>
        <span class="yellow" >žuto</span>        
    </div>
</div>
<div class="modal-body">
	<form accept-charset="UTF-8" role="form" method="post" action="{{ route('equipment_lists.update', $preparation_id) }}">
        <div class="thead">
            <p class="tr">
                <span class="th">Produkt</span> 
                <span class="th">Naziv</span>
                <span class="th">Jed.mj.</span>
                <span class="th">Količina</span>
                <span class="th">Isporučena količina</span>
            </p>
        </div>
        <div class="tbody">
            @php
                $i = 1;
            @endphp
            @foreach ($list_dates as $date)
                    <div>
                        <h4>Lista {{ $i }} - {{ date('d.m.Y', strtotime( $date))}} </h4>
                        @foreach ($equipments->where('created_at', $date) as $equipment)
                            <input name="id[]" value="{{ $equipment->id }}" hidden/>
                            <p class="tr row_preparation_text ">
                                <span class="td text_preparation align_left padding_h_10">{{ $equipment->product_number	}}</span>
                                <span class="td text_preparation">{{ $equipment->name }}</span>
                                <span class="td text_preparation ">{{ $equipment->unit }}</span>
                                <span class="td text_preparation quantity ">{{ $equipment->quantity }}</span>
                                <span class="td text_preparation delivered"><input name="delivered[]" value="{{ $equipment->delivered }}"/></span>
                            </p>
                        @endforeach
                    </div>
                @php
                    $i++;
                @endphp
            @endforeach
        </div>
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input class="btn btn-lg btn-primary store_changes" type="submit" value="Spremi">
	</form>
</div>

<script>
    var delivered = 0;
    var quantity = 0;
    $.each($('.row_preparation_text'), function( index, value ) {
        delivered = $( this ).children('.delivered').find('input').val();
        quantity =  $( this ).children('.quantity').text();
       
        if( delivered == 'undefined' || delivered == '' || delivered == '0') {
            $( this ).addClass('not_delivered');          
        } else if(delivered == quantity || delivered > parseInt(quantity)) {
            $( this ).addClass('all_delivered');          
        } else if(delivered < parseInt(quantity)) {
            $( this ).addClass('partial');          
        }
    });
    $('.text_preparation.delivered>input').change(function(){
        delivered = $( this ).val();
        quantity = $( this ).parent().prev().text();
       
        if( delivered == 'undefined' || delivered == '' || delivered == '0') {
            $( this ).parent().parent().removeClass('all_delivered');  
            $( this ).parent().parent().removeClass('partial');   
            $( this ).parent().parent().addClass('not_delivered');  
        } 
        if((delivered == parseInt(quantity)) || (delivered > parseInt(quantity))) {
            $( this ).parent().parent().removeClass('not_delivered');
            $( this ).parent().parent().removeClass('partial');
            $( this ).parent().parent().addClass('all_delivered');          
        }
        if(delivered < parseInt(quantity)) {
            $( this ).parent().parent().removeClass('not_delivered');
            $( this ).parent().parent().removeClass('all_delivered');
            $( this ).parent().parent().addClass('partial');          
        }
    });

    var inputs = $(".text_preparation.delivered > input");

    $(inputs).keypress(function(e){
        if (e.keyCode == 13){
            inputs[inputs.index(this)+1].focus();
            e.preventDefault();
        }
    });

    var color;
    var status;

    $('.filter_color>span').click(function(){
        color = this.className;

        if (color == 'red') {
            status = 'not_delivered';
        } else if (color == 'green') {
            status = 'all_delivered';
        } else if (color == 'yellow') {
            status = 'partial';
        }

        $.each($('.modal .row_preparation_text'), function( index, value ) {
            if(color == 'all') {
                $( this ).show();
            } else {
                if( $(this).hasClass(status)) {
                    $( this ).show();
                } else {
                    $( this ).hide();
                }
            }           
        });       
    });
   
</script>

