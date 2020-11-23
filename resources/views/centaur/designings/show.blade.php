@extends('Centaur::layout')

@section('title', 'Detalji')
@php
  /*   dd($designing->hasComments); */
@endphp
@section('content')
<div class="page-header">
    <div class="page_navigation pull-left">
        <a class="link_back " href="{{ route('designings.index') }}">Projektiranje</a>
        <span>/</span>
        <span class="pull-left" >Detalji projekta {{ $designing->project_no . ' - ' . $designing->name }}</span>
    </div>
</div>
<div class="row col-md-12">
    <div class="col-md-3">
        <div class="panel panel-default">
            <div class="panel-heading">Dokumenti  <span class="file-upload pull-right" id="upload_{{ $designing->id }}"><i class="fas fa-upload"></i> Upload</span></div>
                @php
                    $docs = array();
                    $path = 'uploads/' . $designing->id . '/';
                    if(file_exists($path)){
                        $docs = array_diff(scandir($path), array('..', '.', '.gitignore'));
                    } 
                @endphp
                <div class="panel-body">
                    @foreach ($docs as $doc)
                        <p>
                            <a href="{{ (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] .'/'. $path . $doc }}" target="_blanck" class="open_file">{{ $doc }}</a>
                            @if( Sentinel::getUser()->hasAccess(['designings.delete']) )
                            <a href="{{ action('DesigningController@delete_file', ['file' => $path . $doc ] ) }}" class="action_confirm btn-delete doc danger" data-token="{{ csrf_token() }}" >
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </p>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
    <div class="col-md-9">
        <div class="panel panel-default">
            <div class="panel-heading">Poruke</div>
                <div class="panel-body">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-md-offset-1">
                        <form accept-charset="UTF-8" role="form" method="post" action="{{ route('designing_comments.store', $designing->id) }}">
                            <div class="form-group {{ ($errors->has('comment')) ? 'has-error' : '' }}">
                                <textarea class="form-control" name="comment" maxlength="21845" rows="5"></textarea>
                                {!! ($errors->has('comment') ? $errors->first('comment', '<p class="text-danger">:message</p>') : '') !!}
                            </div>
                        {{ csrf_field() }}
                        <input type="hidden" name="designing_id" value="{{$designing->id }}">
                        <input class="btn btn-lg btn-primary pull-right" type="submit" value="Pošalji poruku">
                        </form>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 comments_list">
                        @if(count($designing->hasComments ) > 0)
                                @foreach ($designing->hasComments->sortByDesc('created_at') as $comment)
                                    <div class="media">
                                        <div class="media-left">
                                            <a href="#">
                                                <img class="media-object" src="//www.gravatar.com/avatar/{{ md5($comment->user->email) }}?d=mm">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h5 class="media-heading">{{ $comment->user->email }} | <small>{{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }} </small></h5>
                                            {{ $comment->comment}}
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach	
                        @else		
                            <p>{{'No Comments!'}}</p>	
                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
     $('.file-upload').click(function(){
        var id = $( this ).attr('id');
        id = id.replace('upload_','');
        
        $('<form accept-charset="UTF-8" role="form" method="post" action="'+location.origin+'/designings/'+id+'" enctype="multipart/form-data"><div class="form-group"><label>Dodaj dokumenat</label><input type="file" name="fileToUpload[]" id="fileToUpload" multiple></div><div class="form-group file_names"><label>Naziv dokumenta</label></div><input type="hidden" name="file_up" value="1"><input name="_token" value="{{ csrf_token() }}" type="hidden"><input name="_method" value="PUT" type="hidden"><input class="btn btn-md btn-primary pull-right" type="submit" value="Spremi"></form>').appendTo('body').modal();

        $('#fileToUpload').change(function(e){
            var file_list = e.target.files;
            console.log( e.target.files );
            $.each(file_list,function(idx,elm){
              
                $('.file_names').append('<input type="text" class="form-control margin_b_10" name="file_name[]" value="'+ elm.name.substr(0, elm.name.lastIndexOf('.')) +'">');
            });

        });
    });  
</script>
@stop