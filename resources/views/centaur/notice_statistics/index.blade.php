<div class="modal-header">
    <a class="link_back" rel="modal:close">
        <img src="{{ URL::asset('icons/arrow_left2.png') }}" /> 
    </a>
    <span class="title_page">Detailed statistic</span>
    <h3 class="panel-title statistic_title">{{ $notice->title }}</h3>
    @php
        $docs = '';
        $user_name = explode('.',strstr($notice->employee['email'],'@',true));
        if(count($user_name) == 2) {
            $user_name = $user_name[1] . '_' . $user_name[0];
        } else {
            $user_name = $user_name[0];
        }

        $path = 'storage/' . $user_name . "/profile_img/";
        if(file_exists($path)){
            $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
        }else {
            $docs = '';
        }
    @endphp
    <span class="notice_name">
        @if($docs)
        <img class="notice_img radius50" src="{{ URL::asset('storage/' . $user_name . '/profile_img/' . end($docs)) }}" alt="Profile image" title="{{ $notice->employee->user['first_name'] . ' ' . $notice->employee->user['last_name'] }}"  />
        @else
        <img class="notice_img radius50" src="{{ URL::asset('img/profile.png') }}" alt="Profile image"  />
        @endif
        {{ $notice->employee->user['first_name'] . ' ' .  $notice->employee->user['last_name']}}
    </span>
    <p class="notice_date">{{  date('l, d.F Y.', strtotime($notice->created_at)) }}</p>
</div>
<div class="modal-body statistic_body">
    <div class="filter" >
        <select class="select_department">
            <option>All departments</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" >{{ $department->name }}</option>
            @endforeach   
        </select>
        <select class="time">
            <option>Last 24 hours</option>
            <option>Last month</option>
            <option>Last year</option>
        </select>
    </div>
    <div class="total_read">
        <h5>Total notice read</h5>
        <div class="statistic_data col-6 float_l">
            <p class="col-6 float_l">53<span>total members</span></p>
            <p class="col-6 float_l">12<span>Some stats</span></p>
            <p class="col-6 float_l">21<span>Some stats</span></p>
            <p class="col-6 float_l">41<span>Some stats</span></p>
        </div>
        <div class="statistic_chart col-6 float_r">
            <canvas id="myChart" width="214" height="214"></canvas>
            <span class="chart_value">{{  number_format($data[0],0)}}<small>%</small></span>
        </div>
    </div> 
    <div class="col-4 float_l notice_read">
        <div>
            <h5>Read at least 50%</h5>
            <div class="statistic_chart">
                <canvas id="myChart1" width="138" height="138" class="myChart"></canvas>
                <span class="chart_value first">{{  number_format($data[0],0)}}<small>%</small></span>
            </div>
            <div class="statistic_data col-12">
                <p class="col-12 float_l chart_value first">21<span>Some information</span></p>
            </div>
        </div>
        
    </div>
    <div class="col-4 float_l notice_read">
        <div>
            <h5>Read whole notice</h5>
            <div class="statistic_chart">
                    <canvas id="myChart2" width="138" height="138" class="myChart"></canvas>
                    <span class="chart_value second">{{  number_format($data[0],0)}}<small>%</small></span>
                </div>
            <div class="statistic_data col-12 ">
                <p class="col-12 float_l chart_value second">21<span>Some information</span></p>
            </div>
        </div>
    </div>
    <div class="col-4 float_l notice_read">
        <div>
            <h5>Average notice read</h5>
            <div class="statistic_chart">
                    <canvas id="myChart3" width="138" height="138" class="myChart"></canvas>
                    <span class="chart_value last">{{  number_format($data[0],0)}}<small>%</small></span>
                </div>
            <div class="statistic_data col-12 ">
                <p class="col-12 float_l chart_value last">21<span>Some information</span></p>
            </div>
        </div>
        
    </div>
    </div>
</div>
<span hidden class="dataArr">{{ json_encode($dataArr) }}</span>
<script>
    $(function() {
        $.getScript( '/../js/chart.js');
        $.getScript( '/../js/set_height_notice.js');
       
    });
</script>
