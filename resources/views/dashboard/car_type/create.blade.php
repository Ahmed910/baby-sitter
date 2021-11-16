@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{!! trans('dashboard.car_type.add_car_type') !!}</h5>
    </div>
    <div class="card-body">
        <section class="modern-horizontal-wizard">
            <div class="bs-stepper wizard-modern modern-wizard-example">
                {!! Form::open(['route' => 'dashboard.car_type.store' , 'method' => 'POST' , 'files' => true ,'class' => 'steps-validation wizard-circle','data-locale' => app()->getLocale()]) !!}
                @include('dashboard.car_type.form',['btnSubmit' => trans('dashboard.general.save')])
                {!! Form::close() !!}
            </div>
        </section>
    </div>

</div>
@endsection
