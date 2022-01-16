@extends('dashboard.layout.layout')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {!! trans('dashboard.sitter.edit_sitter') !!}
                        </h4>
                    </div>
                    <div class="card-body">
                        {!! Form::model($sitter,['route' => ['dashboard.sitter.update',$sitter->id] , 'method' => 'PUT' , 'files' => true ]) !!}
                           @include('dashboard.sitter.form',['btnSubmit' => trans('dashboard.general.edit'),'current' => trans('dashboard.sitter.edit_sitter')])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
