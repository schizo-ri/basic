@extends('Centaur::layout')

@section('title', 'Ugovori')

@section('content')
<div class="preparation">
    <span hidden class="today">{{ date('Y-m-d') }}</span>
    <div class="page-header">
        <h1>Ugovori</h1>
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
                                <span class="col-md-2">Broj ugovora</span>
                                <span class="col-md-4">Dobavljač</span>
                                <span class="col-md-6">Komentar</span>
                            </span>
                        </p>
                    </div>
                    <div class="col-xs-12">
                        @foreach ($contracts as $contract)
                            <div class="tr open_project col-xs-12">
                                <a href="{{ route('agglomerations.show', $contract->id) }}" class="show_preparations col-xs-12">
                                    <span class="col-md-2">
                                        {{ $contract->number  }}
                                    </span>
                                    <span class="col-md-4">
                                        {{ $contract->supplier }}
                                    </span>
                                    <span class="col-md-6">
                                        {{ $contract->comment }}
                                    </span>
                                </a>                                
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
         <!-- Novi unos -->
         @if(Sentinel::getUser()->hasAccess(['contracts.create']))
            @include('centaur.contracts.contract_create')
        @endif
    </div>
</div>
<script>
    $.getScript('/../js/preparation.js');
</script>
@stop