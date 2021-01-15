<div class="form-group {{ $element }}{!! isset( $count_input) ? ($count_input) : '' !!}" hidden >
    <h5>{{ ucfirst( $element) }} {!! isset($count_input) ? ($count_input+1) : '' !!}</h5>
    <p>Font</p>
    <div>
        <label>Boja <input name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[color]" type="color" id="color[{{ $element }}]" class="color_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" value="{!! isset($style['color']) ? $style['color'] : '#000000' !!}"/></label>
        <label>Veličina
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[font-size]" id="font-size[{{ $element }}]" class="font-size_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" >
                @for ( $i = 8; $i < 30; $i++)
                    <option value="{{ $i }}px" {!! isset($style['font-size']) && $i.'px' == $style['font-size'] ? 'selected' : $i== 12 ? 'selected' : '' !!}>{{ $i }}</option>
                @endfor
            </select>
        </label>
        <label>Debljina
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[font-weight]" id="font-weight[{{ $element }}]" class="font-weight_{{ $element }}{!! str_contains($element, 'input') ? $count_input: '' !!}" >
                <option value="lighter"  {!! isset($style['font-weight']) && $style['font-weight'] == "lighter" ? 'selected' : ''!!}>Tanko</option>
                <option value="normal" {!! isset($style['font-weight']) && $style['font-weight'] == "normal" ? 'selected' : ! isset($style['font-weight']) ? 'selected' : '' !!} >Normalno</option>
                <option value="bold" {!! isset($style['font-weight']) && $style['font-weight'] == "bold" ? 'selected' : ''!!}>Podebljano</option>
            </select>
        </label>
        <label>Poravnanje 
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[text-align]" id="text-align[{{ $element }}]" class="text-align_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" >
                <option value="left" {!! isset($style['text-align']) && $style['text-align'] == "left" ? 'selected' : ''!!}>Lijevo</option>
                <option value="center" {!!  isset($style['text-align']) && $style['text-align'] == "center" ? 'selected' : ''!!} >Sredina</option>
                <option value="right" {!!  isset($style['text-align']) && $style['text-align'] == "right" ? 'selected' : ''!!}>Desno</option>
            </select>
        </label>
        <label>Uvlaka
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[padding-left]" id="padding-left[{{ $element }}]" class="padding-left_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" >
                @for ( $i = 0; $i <= 100; $i++)
                    @if( ($i % 5) == 0 )
                        <option value="{{ $i }}px" {!! isset( $style['padding-left']) &&  $i.'px' == $style['padding-left'] ? 'selected' : $i== 10 ? 'selected' : '' !!}>{{ $i }}</option>
                    @endif
                @endfor
            </select>
        </label>
    </div>
    <p>Obrub</p>
    <div>
        <label>Boja <input name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[border-color]" type="color" id="border-color[{{ $element }}]" class="border-color_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" value="{!! isset($style['border-color']) ? $style['border-color'] : '#ffffff' !!}" /></label>
        <label>Debljina<input name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[border-width]" type="number" min="0" max="4" id="border-width" class="border-width_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" value="{!! isset( $style['border-width']) ?  $style['border-width'] : 0 !!}"/></label>
        <label>Stil
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[border-style]" id="border-style[{{ $element }}]" class="border-style_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}">
                <option value="dotted" {!! isset($style['border-style']) && $style['border-style'] == "dotted" ? 'selected' : ''!!}>Točkasta</option>
                <option value="dashed" {!! isset($style['border-style']) && $style['border-style'] == "dashed" ? 'selected' : ''!!} >Isprekidana</option>
                <option value="solid" {!! isset($style['border-style']) && $style['border-style'] == "solid" ? 'selected' : '' !!}>Čvrsta</option> 
                <option value="double" {!! isset($style['border-style']) && $style['border-style'] == "double" ? 'selected' : ''!!}>Dvostruka</option> 
                <option value="none" {!! isset($style['border-style']) && $style['border-style'] == "none" ? 'selected' : ! isset($style['border-style'])  ? 'selected' : '' !!}>Bez</option> 
            </select>
        </label>
    </div>
    <p>Pozadina</p>
    <div>
        <label>Boja <input name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[background-color]" type="color" id="background-color[{{ $element }}]" class="background-color_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" value="{!! isset($style['background-color'] ) ? $style['background-color']  : '#ffffff' !!}"/></label>
    </div>
</div>