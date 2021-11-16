@extends('dashboard.layout.layout')

@section('content')
<div class="card border-info">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{!! trans('dashboard.favorite_time.edit_favorite_time') !!}</h5>
    </div>
    <div class="card-body">
        <section class="modern-horizontal-wizard">
            <div class="bs-stepper wizard-modern modern-wizard-example">
                {!! Form::model($favorite_time,['route' => ['dashboard.favorite_time.update',$favorite_time->id] , 'method' => 'PUT' , 'files' => true ,'class' => 'steps-validation wizard-circle','data-locale' => app()->getLocale() ]) !!}
                   @include('dashboard.favorite_time.form',['btnSubmit' => trans('dashboard.general.edit')])
                {!! Form::close() !!}
            </div>
        </section>
    </div>

</div>
@endsection
