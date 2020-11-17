<div class="modal-header">
	<h3 class="panel-title">{{ $educationArticle->subject }}<a class="btn color_grey btn_edit_ad" href="{{ route('education_articles.edit', $educationArticle->id) }}"  title="{{ __('basic.edit_educationArticle')}}" rel="modal:open">
		<i class="fas fa-edit"></i>
	</a></h3>	
</div>
<div class="modal-body ad">	
	<div class="panel-body">
		{!! $educationArticle->article !!} 
	</div>
	<div class="panel-footer ad">
		<small>{{ $educationArticle->employee->user['first_name'] .' | ' . \Carbon\Carbon::createFromTimeStamp(strtotime($educationArticle->created_at))->diffForHumans()  }}</small>		
	</div>
</div>

