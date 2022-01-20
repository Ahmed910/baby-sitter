@extends('dashboard.layout.layout')

@section('content')
<!-- page users view start -->
<section class="page-users-view">
    <div class="row">
        <!-- account start -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ trans('dashboard.order.order_data') }}</div>
                    <div class="heading-elements">
                        <div class="badge badge-primary block badge-md mr-1 mb-1">
                            {{ $order->created_at->format("Y-m-d") }}
                        </div>

                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="users-view-image d-flex align-items-center justify-content-center">
                            <span class="text-info users-avatar-shadow w-100 rounded d-flex align-items-center justify-content-center">
                                <i class="feather icon-truck font-large-3"></i>
                            </span>
                        </div>
                        <div class="col-6 col-sm-9 col-md-6 col-lg-5">

                    <p>{{ trans('dashboard.order.from') .' : '.optional($order->client)->name }}</p>
                    <p>{{ trans('dashboard.order.to') .' : '.$name }}</p>
                    <p>{{ trans('dashboard.order.status.status') .' : '.trans('dashboard.order.status.'.$order_details->status) }}</p>
                    <p>{{ trans('dashboard.order.service_type') .' : '.optional($order_details->service)->name }}</p>


                    </div>

                    <div class="col-6">

                        <h3>{{ trans('dashboard.order.kids.kids_info') }}</h3>
                        @foreach ($order_details->kids as $key=>$order_kid)
                           <h5>{{ trans('dashboard.order.kids.kid_number',['kid_number'=>++$key]) }}</h5>
                           <p>{{ trans('dashboard.order.kids.name') .' : '. optional($order_kid->kid)->kidname }}</p>
                           <p>{{ trans('dashboard.order.kids.age') .' : '. optional($order_kid->kid)->age }}</p>
                        @endforeach


                    </div>
                </div>
                <div class="row">

                    <div class="col-6">
                        <h3>{{ trans('dashboard.order.schedules.schedules') }}</h3>
                        @if(optional($order_details->service)->service_type == 'hour')
                            <h5>{{ trans('dashboard.order.schedules.hour') }}</h5>
                           <p>{{ trans('dashboard.order.schedules.day').' : '.optional($order_details->hours)->date->toFormattedDateString() }}</p>
                           <p>{{ trans('dashboard.order.schedules.start_time').' : '.optional($order_details->hours)->start_time->format('g:i A') }}</p>
                           <p>{{ trans('dashboard.order.schedules.end_time').' : '.optional($order_details->hours)->end_time->format('g:i A') }}</p>

                        @else
                        <h5>{{ trans('dashboard.order.schedules.month') }}</h5>
                        <p>{{ trans('dashboard.order.schedules.start_date').' : '.optional($order_details->months)->start_date->toFormattedDateString() }}</p>
                        <p>{{ trans('dashboard.order.schedules.end_date').' : '.optional($order_details->months)->end_date->toFormattedDateString() }}</p>
                          <h6>{{ trans('dashboard.order.schedules.schedules_during_month') }}</h6>
                        @foreach (optional($order_details->months)->month_days as $day)
                          <span>{{ optional($day->day)->name }}</span> : <span>{{ trans('dashboard.order.schedules.from') .':' .$day->start_time->format('g:i A') }}</span> ,<span>{{ trans('dashboard.order.schedules.to') .':' .$day->end_time->format('g:i A') }}</span></br>
                        @endforeach
                        @endif
                    </div>
                    <div class="col-6">
                        <h4>{{ trans('dashboard.order.financials.financials') }}</h4>
                        <p>{{ trans('dashboard.order.financials.app_profit_percentage') .' : '.$order->app_profit_percentage.'%' }}</p>
                        <p>{{ trans('dashboard.order.financials.app_profit') .' : '.$order->app_profit }}</p>
                        <p>{{ trans('dashboard.order.financials.price_before_offer') .' : '.$order->price_before_offer }}</p>
                        <p>{{ trans('dashboard.order.financials.price_after_offer') .' : '.$order->price_after_offer }}</p>
                        <p>{{ trans('dashboard.order.financials.provider_profit') .' : '.$order->final_price }}</p>
                    </div>
                </div>


            </div>
        </div>
        <!-- account end -->

        <!-- information start -->
    </div>
</section>

<!-- page users view end -->
@endsection

@section('vendor_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/vendors/css/extensions/swiper.min.css">
@endsection

@section('page_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/pages/app-user.css">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/pages/app-ecommerce-details.css">
<link rel="stylesheet" href="{{ asset('dashboardAssets') }}/global/css/custom/custom_rate.css">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/pages/users.css">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/plugins/extensions/swiper.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />

@endsection

@section('vendor_scripts')
<script src="{{ asset('dashboardAssets') }}/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/extensions/swiper.min.js"></script>
@endsection

@section('page_scripts')
<script src="{{ asset('dashboardAssets') }}/js/scripts/pages/app-ecommerce-details.js"></script>
<script src="{{ asset('dashboardAssets') }}/js/scripts/forms/number-input.js"></script>
<script src="{{ asset('dashboardAssets') }}/js/scripts/pages/user-profile.js"></script>
<script src="{{ asset('dashboardAssets') }}/js/scripts/extensions/swiper.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>

@endsection
