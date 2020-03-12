<a href="#close-modal" rel="modal:close" class="close-modal ">Close</a>
<div class="modal-header" id="first_anchor">
    <h2 class="">Lista opreme: {{ $equipments->first()->preparation1['project_no'] . ' - ' . $equipments->first()->preparation1['name'] }}</h2>
    <h4 class="">Datum isporuke: {!! $equipments->first()->preparation1['delivery'] ? date('d.m.Y', strtotime($equipments->first()->preparation1['delivery'] )) : '' !!}</h4>
    <label class="filter_empl">
        <input type="search" placeholder="Traži..." onkeyup="mySearchList()" id="mySearchList">
        <i class="clearable__clear">&times;</i>
    </label>
</div>
<div class="modal-body">
    <div>
        <form class="edit_list" accept-charset="UTF-8" role="form" method="post" action="{{ route('multiReplaceStore') }}">
            <input name="preparation_id" id="preparation_id"  value="{{ $preparation_id }}" hidden />
            <div class="thead">
                <p class="tr">
                    <span class="th_50"></span> 
                    <span class="th_50">Zamjena</span> 
                </p>
                <p class="tr">
                    <span class="th_15">Produkt</span> 
                    <span class="th_15">Oznaka</span>
                    <span class="th_50">Naziv</span>
                    <span class="th_10">Jed.mj.</span>
                    <span class="th_10">Količina</span>
                    <span class="th_15">Produkt</span> 
                    <span class="th_15">Oznaka</span>
                    <span class="th_50">Naziv</span>
                    <span class="th_10">Jed.mj.</span>
                    <span class="th_10">Količina</span>
                </p>
            </div>
            <div class="tbody item_replace_list">
                @php
                    $i = 1;
                @endphp
                @foreach ($list_dates as $date)
                    <div>
                        <h4>Lista {{ $i }} - {{ date('d.m.Y', strtotime( $date))}} </h4>
                        @foreach ($equipments->where('created_at', $date) as $equipment)
                            @php
                                $listUpdates_item = $listUpdates->where('item_id', $equipment->id );
                                $delivered = $equipment->delivered;

                                foreach ($listUpdates_item as $listUpdate) {
                                    $delivered +=  $listUpdate->quantity;
                                }
                            @endphp
                            @if ($equipment->replace_item != 1 && ( $delivered == null || $delivered == 0 || $delivered < $equipment->quantity)    )
                                
                                <p class="tr row_preparation_text {!! $equipment->replace_item == 1 ? 'removed_item' : '' !!}" id="{{ $equipment->id }}" >
                                    <span class="td_50">
                                        <span class="td_15 text_preparation align_left padding_h_10">{{ $equipment->product_number}}  <span class="open_input"><i class="fas fa-exchange-alt"></i></span></span>
                                        <span class="td_15 text_preparation">{{ $equipment->mark }}</span>
                                        <span class="td_50 text_preparation">{{ $equipment->name }}</span>
                                        <span class="td_10 text_preparation ">{{ $equipment->unit }}</span>
                                        <span class="td_10 text_preparation quantity ">{{ $equipment->quantity }}</span>
                                    </span> 
                                    <span class="td_50 replace_items">
                                        
                                    </span> 
                                </p>
                            @endif
                        @endforeach
                    </div>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </div>
            {{ csrf_field() }}
            <input class="btn btn-lg btn-primary store_changes" type="submit" value="Spremi">
        </form>
        <a href="#first_anchor"><i class="fas fa-arrow-up" ></i></a>
    </div>
</div>
<script>
    $('.open_input').click(function () {
        $( this ).parent().parent().siblings('.replace_items').css('display','flex');
        var id =  $( this ).parent().parent().parent().attr('id');

        $( this ).parent().parent().siblings('.replace_items').prepend('<input name="id[]" value="' + id +'" hidden/><span class="td_15 text_preparation align_left padding_h_10"><input name="product_number[]" maxlength="50" type="text" /></span><span class="td_15 text_preparation"><input name="mark[]" maxlength="255" type="text" /></span><span class="td_50 text_preparation"><textarea name="name[]" maxlength="255" type="text"></textarea></span><span class="td_10 text_preparation "><input name="unit[]" maxlength="20" type="text" /></span><span class="td_10 text_preparation quantity "><input name="quantity[]" maxlength="20" type="text" /></span>');
        
    })
       
$('body').on($.modal.AFTER_CLOSE, function(event, modal) {
    $.modal.defaults = {
        closeExisting: false,    // Close existing modals. Set this to false if you need to stack multiple modal instances.
        escapeClose: true,      // Allows the user to close the modal by pressing `ESC`
        clickClose: false,       // Allows the user to close the modal by clicking the overlay
        closeText: 'Close',     // Text content for the close <a> tag.
        closeClass: '',         // Add additional class(es) to the close <a> tag.
        showClose: true,        // Shows a (X) icon/link in the top-right corner
        modalClass: "modal",    // CSS class added to the element being displayed in the modal.
        // HTML appended to the default spinner during AJAX requests.
        spinnerHtml: "<div id='loader'><span class='ajax-loader1'></span></div>",
        showSpinner: true,      // Enable/disable the default spinner during AJAX requests.
        fadeDuration: null,     // Number of milliseconds the fade transition takes (null means no transition)
        fadeDelay: 0.5          // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
    };
});
</script>