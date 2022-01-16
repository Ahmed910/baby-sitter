@extends('dashboard.layout.layout')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {!! trans('dashboard.sitter.add_sitter') !!}
                        </h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'dashboard.sitter.store' , 'method' => 'POST' , 'files' => true ]) !!}
                           @include('dashboard.sitter.form',['btnSubmit' => trans('dashboard.general.save'),'current' => trans('dashboard.sitter.add_sitter')])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
