@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{!! trans('dashboard.available_day.add_available_day') !!}</h5>
    </div>
    <div class="card-body">
        <section class="modern-horizontal-wizard">
            <div class="bs-stepper wizard-modern modern-wizard-example">
                {!! Form::open(['route' => 'dashboard.available_day.store' , 'method' => 'POST' , 'files' => true ,'class' => 'steps-validation wizard-circle','data-locale' => app()->getLocale()]) !!}
                @include('dashboard.available_day.form',['btnSubmit' => trans('dashboard.general.save')])
                {!! Form::close() !!}
            </div>
        </section>
    </div>

</div>
@endsection
