@extends('dashboard.layout.layout')

@section('content')

<section id="dashboard-analytics">
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $clients_count }}</h2>
                                <p>{{ trans('dashboard.client.clients') }}</p>
                            </div>
                            <div class="avatar bg-rgba-success p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.client.index') }}">
                                        <i data-feather="users" class="text-success font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $clients_is_ban_count }}</h2>
                                <p>{{ trans('dashboard.client.banned_clients') }}</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.client.index') }}?status=ban">
                                        <i data-feather="corner-down-left" class="text-warning font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $clients_is_deactive_count }}</h2>
                                <p>{{ trans('dashboard.client.deacive_clients') }}</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.client.index') }}?status=deactive">
                                        <i data-feather="corner-down-left" class="text-warning font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $baby_sitters_count }}</h2>
                                <p>{{ trans('dashboard.sitter.sitters_count') }}</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.sitter.index') }}">
                                        <i class="fa fa-child font-medium-5" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $baby_sitters_is_ban_count }}</h2>
                                <p>{{ trans('dashboard.sitter.banned_sitters') }}</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.sitter.index') }}?status=ban">
                                        <i data-feather="corner-down-left" class="text-warning font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $baby_sitters_is_deactive_count }}</h2>
                                <p>{{ trans('dashboard.sitter.deactive_sitters') }}</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.client.index') }}?status=deactive">
                                        <i data-feather="corner-down-left" class="text-warning font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $child_center_count }}</h2>
                                <p>{{ trans('dashboard.center.child_center_count') }}</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.center.index') }}">
                                        <i class="fa fa-child font-medium-5" aria-hidden="true"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $child_center_is_ban_count }}</h2>
                                <p>{{ trans('dashboard.center.banned_centers') }}</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.center.index') }}?status=ban">
                                        <i data-feather="corner-down-left" class="text-warning font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $child_center_is_deactive_count }}</h2>
                                <p>{{ trans('dashboard.center.deactive_centers') }}</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.client.index') }}?status=deactive">
                                        <i data-feather="corner-down-left" class="text-warning font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $managers_count }}</h2>
                                <p>{{ trans('dashboard.manager.managers') }}</p>
                            </div>
                            <div class="avatar bg-rgba-violet p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.manager.index') }}">
                                        <i data-feather="user" class="text-warning font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $countries_count }}</h2>
                                <p>{{ trans('dashboard.country.countries') }}</p>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.country.index') }}">
                                        <i data-feather="flag" class="text-danger font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-4 col-12">
                    <div class="card border-info bg-transparent">
                        <div class="card-header rounded d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ $cities_count }}</h2>
                                <p>{{ trans('dashboard.city.cities') }}</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <a href="{{ route('dashboard.city.index') }}">

                                        <i class="fas fa-city font-medium-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-info bg-transparent text-center">
                <div class="card-content">
                    <div class="card-body">
                        <div class="avatar bg-rgba-info p-50 m-0">
                            <div class="avatar-content">
                                <i data-feather="clock" class="text-info font-medium-5"></i>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="clock text-center text-white">
                                <span id="Date" class=""></span>
                                <p id="islamicDate" class=""></p>
                                <span class="">
                                    <ul>
                                        <li id="hours"></li>
                                        <li id="point">:</li>
                                        <li id="min"></li>
                                        <li id="point">:</li>
                                        <li id="sec"></li>
                                    </ul>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>


     {{-- Charts --}}
     <div class="row">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header pb-1">
                    <h4 class="card-title">{!! trans('dashboard.chart.charts') !!}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form action="{!! route('dashboard.home') !!}" method="get">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="font-medium-1 col-md-2">{{ trans('dashboard.general.from_date') }} </label>
                                            <div class="col-md-10">
                                                {!! Form::date("from_date", request('from_date') ? date("Y-m-d",strtotime(request('from_date'))) : null , ['class' => 'form-control expire_date' , 'placeholder' => trans('dashboard.general.from_date')])
                                                !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="font-medium-1 col-md-2">{{ trans('dashboard.general.to_date') }} </label>
                                            <div class="col-md-10">
                                                {!! Form::date("to_date", request('to_date') ? date("Y-m-d",strtotime(request('to_date'))) : null , ['class' => 'form-control expire_date' , 'placeholder' => trans('dashboard.general.to_date')]) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-md btn-block btn-primary float-right"> {!! trans('dashboard.general.send') !!}</button>
                                </div>

                            </div>
                        </form>
                        <div class="divider divider-success">
                            <div class="divider-text"><i data-feather="bar-chart-2"></i></div>
                        </div>
                        <div id="client_chart" style="height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</section>

@endsection
@include('dashboard.home.scripts')
