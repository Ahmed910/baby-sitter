@extends('dashboard.layout.layout')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {!! trans('dashboard.sitter_worker.edit_sitter_worker') !!}
                        </h4>
                    </div>
                    <div class="card-body">
                        {!! Form::model($sitter_worker,['route' => ['dashboard.sitter_worker.update',$sitter_worker->id] , 'method' => 'PUT' , 'files' => true ]) !!}
                           @include('dashboard.sitter_worker.form',['btnSubmit' => trans('dashboard.general.edit'),'current' => trans('dashboard.sitter_worker.edit_sitter_worker')])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
