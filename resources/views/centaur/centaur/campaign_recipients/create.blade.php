<div class="modal-header">
		<h3 class="panel-title">@lang('basic.add_recipients')</h3>
</div>
<div class="modal-body">
    <form accept-charset="UTF-8" role="form" method="post" action="{{ route('campaign_recipients.store') }}" enctype="multipart/form-data" >
        <input name="campaign_id" type="hidden" value="{{ $campaign->id }}" >
        <div class="form-group {{ ($errors->has('employee_id'))  ? 'has-error' : '' }} ">
            <div class="selectBox" onclick="showCheckboxes()">
                <select>
                    <option>@lang('basic.to_employee')</option>
                </select>
                <div class="overSelect"></div>
            </div>
           <div id="checkboxes" {!! $campaign_recipients->where('employee_id', '<>', null)->first() ? 'style="display:block;" ' : 'style="display:none;" ' !!} >
                <input type="search"  placeholder="{{ __('basic.search')}}"  id="mySearch">
                @foreach($employees as $employee)
                    <label for="{{ $employee->id }}" class="col-4 float_left panel" >
                        <input name="employee_id[]" type="checkbox" id="{{ $employee->id }}" value="{{ $employee->id }}" {!! $campaign_recipients->where('employee_id',$employee->id )->first() ? 'checked' : '' !!} />
                        <span>{{ $employee->user['last_name']. ' ' . $employee->user['first_name'] }}</span>
                    </label>
                @endforeach	
            </div>
        </div>
        <div class="form-group {{ ($errors->has('to_department'))  ? 'has-error' : '' }} ">
            <div class="selectBox department" onclick="showCheckboxesDepartment()">
                <select>
                    <option>@lang('basic.to_department')</option>
                </select>
                <div class="overSelect"></div>
            </div>
            <div id="checkboxes1"  {!! $campaign_recipients->where('department_id', '<>', null)->first() ? 'style="display:block;" ' : 'style="display:none;" '  !!}>
                <input type="search"  placeholder="{{ __('basic.search')}}"  id="mySearch1">
                @foreach($departments0 as $department0)
                    <div class="col-12">
                        <label for="{{  '0_'.$department0->id }}" class="col-12 float_left panel1" >
                            <input name="department_id[]" type="checkbox" id="{{ '0_'.$department0->id }}" value="{{ $department0->id }}" {!! $campaign_recipients->where('department_id',$department0->id )->first() ? 'checked' : '' !!} />
                            <span>{{ $department0->name }}</span>
                        </label>
                        @foreach($departments2 as $department2)
                            @if ($department2->level2 == $department0->id )
                                <label for="{{  '2_'.$department2->id }}" class="col-offset-1 col-md-10 float_left panel1" >
                                    <input name="department_id[]" type="checkbox" id="{{ '1_'.$department2->id }}"  value="{{ $department2->id }}" {!! $campaign_recipients->where('department_id',$department2->id )->first() ? 'checked' : '' !!}  />
                                    <span>{{ $department2->name }}</span>
                                </label>
                            @endif
                        @endforeach
                        @foreach($departments1 as $department1)
                            @if ($department1->level2 == $department0->id )
                                <label for="{{  '1_'.$department1->id }}" class="col-12 float_left panel1" >
                                    <input name="department_id[]" type="checkbox" id="{{ '1_'.$department1->id }}"  value="{{ $department1->id }}" {!! $campaign_recipients->where('department_id',$department1->id )->first() ? 'checked' : '' !!} />
                                    <span>{{ $department1->name }}</span>
                                </label>
                            @endif
                            @foreach($departments2 as $department2)
                                
                                @if ($department2->level2 == $department1->id )
                                    <label for="{{  '2_'.$department2->id }}" class="col-offset-1 col-md-10 float_left panel1" >
                                        <input name="department_id[]" type="checkbox" id="{{ '2_'.$department2->id }}" value="{{ $department2->id }}" {!! $campaign_recipients->where('department_id',$department2->id )->first() ? 'checked' : '' !!}  />
                                        <span>{{ $department2->name }}</span>
                                    </label>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
		{{ csrf_field() }}
		<input class="btn-submit" type="submit" value="{{ __('basic.save')}}" id="stil1">
		<a href="#" rel="modal:close" class="btn-close">@lang('basic.cancel')</a>
	</form>
</div>
<script>
var expanded = false;

function showCheckboxes() {
  var checkboxes = document.getElementById("checkboxes");
  if (!expanded) {
    checkboxes.style.display = "block";
    expanded = true;
  } else {
    checkboxes.style.display = "none";
    expanded = false;
  }
}

function showCheckboxesDepartment() {
  var checkboxes1 = document.getElementById("checkboxes1");
  if (!expanded) {
    checkboxes1.style.display = "block";
    expanded = true;
  } else {
    checkboxes1.style.display = "none";
    expanded = false;
  }
}
$.getScript( '/../js/filter.js');  
</script>