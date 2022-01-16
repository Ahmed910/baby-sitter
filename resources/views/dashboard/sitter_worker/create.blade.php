@extends('dashboard.layout.layout')

@section('content')
    <section id="multiple-column-form">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            {!! trans('dashboard.sitter_worker.add_sitter_worker') !!}
                        </h4>
                    </div>
                    <div class="card-body">
                        {!! Form::open(['route' => 'dashboard.sitter_worker.store' , 'method' => 'POST' , 'files' => true ]) !!}
                           @include('dashboard.sitter_worker.form',['btnSubmit' => trans('dashboard.general.save'),'current' => trans('dashboard.sitter_worker.add_sitter_worker')])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
