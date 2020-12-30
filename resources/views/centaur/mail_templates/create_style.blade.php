<div class="form-group {{ $element }}{!! isset($count_input) ? ($count_input) : '' !!}" hidden>
    <h5>{{ ucfirst( $element) }}  {!! isset($count_input) ? ($count_input+1) : '' !!}</h5>
    <p>Font</p>
    <div>
        <label>Boja <input name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[color]" type="color" id="color[{{ $element }}]" class="color_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" value="#000000"/></label>
        <label>Veličina
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '[0]': '' !!}[font-size]" id="font-size[{{ $element }}]" class="font-size_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" >
                @for ( $i = 8; $i < 30; $i++)
                    <option value="{{ $i }}px" {!! $i== 12 ? 'selected' : '' !!}>{{ $i }}</option>
                @endfor
            </select>
        </label>
        <label>Debljina
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[font-weight]" id="font-weight[{{ $element }}]" class="font-weight_{{ $element }}{!! str_contains($element, 'input') ? $count_input: '' !!}" >
                <option value="lighter" >Tanko</option>
                <option value="normal" selected >Normalno</option>
                <option value="bold" >Podebljano</option>
            </select>
        </label>
        <label>Poravnanje 
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[text-align]" id="text-align[{{ $element }}]" class="text-align_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" >
                <option value="left" selected>Lijevo</option>
                <option value="center" >Sredina</option>
                <option value="right">Desno</option>
            </select>
        </label>
        <label>Uvlaka
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[padding-left]" id="padding-left[{{ $element }}]" class="padding-left_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" >
                @for ( $i = 0; $i < 50; $i++)
                    <option value="{{ $i }}px" {!! $i== 0 ? 'selected' : '' !!}>{{ $i }}</option>
                @endfor
            </select>
        </label>
    </div>
    <p>Obrub</p>
    <div>
        <label>Boja <input name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[border-color]" type="color" id="border-color[{{ $element }}]" class="border-color_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" value="#ffffff"/></label>
        <label>Debljina<input name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[border-width]" type="number" min="0" max="4" id="border-width" class="border-width_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" value="0"/></label>
        <label>Stil
            <select name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[border-style]" id="border-style[{{ $element }}]" class="border-style_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}">
                <option value="dotted">Točkasta</option>
                <option value="dashed" >Isprekidana</option>
                <option value="solid">Čvrsta</option> 
                <option value="double">Dvostruka</option> 
                <option value="none" selected>Bez</option> 
            </select>
        </label>
    </div>
    <p>Pozadina</p>
    <div>
        <label>Boja <input name="{{ $element }}{!! str_contains($element, 'input') ? '['.$count_input.']' : '' !!}[background-color]" type="color" id="background-color[{{ $element }}]" class="background-color_{{ $element }}{!! str_contains($element, 'input') ? $count_input : '' !!}" value="#ffffff"/></label>
    </div>
</div>