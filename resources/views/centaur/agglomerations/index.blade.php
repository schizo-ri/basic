@extends('Centaur::layout')

@section('title', 'Aglomeracije')

@section('content')
<div class="preparation">
    <div class="page-header">
        <div class="page_navigation pull-left">
            <h1>Aglomeracije</h1>
        </div>
        <div class='btn-toolbar pull-right'>           
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="table-responsive">
                <div class="table table-hover table_preparations preparation_list" id="index_table">
                    <div class="col-xs-12">
                        <p class="tr">
                            <span class="col-xs-12">
                                <span class="col-md-2">Naziv</span>
                                <span class="col-md-4">Korisnik</span>
                                <span class="col-md-6">Komentar</span>
                            </span>
                        </p>
                    </div>
                    <div class="col-xs-12">
                        @foreach ($agglomerations as $agglomeration)
                            <div class="tr open_project col-xs-12">
                                <a href="{{ route('agglomerations.show', $agglomeration->id) }}" class="show_preparations col-xs-12">
                                    <span class="col-md-2">
                                        {{ $agglomeration->name  }}
                                    </span>
                                    <span class="col-md-4">
                                        {{ $agglomeration->customer }}
                                    </span>
                                    <span class="col-md-6">
                                        {{ $agglomeration->comment }}
                                    </span>
                                </a>                                
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $.getScript('/../js/preparation.js');
</script>
@stop