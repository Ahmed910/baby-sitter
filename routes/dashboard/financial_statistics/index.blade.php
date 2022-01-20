@extends('dashboard.layout.layout')
@include('dashboard.financial_statistics.styles')
@section('content')
    <!-- Dashboard Analytics Start -->
    <section id="dashboard-analytics">
        {{-- Charts --}}
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card" style="min-height: 230px">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="fa fa-heart text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="text-bold-700 mt-1 col-8">{!! trans('dashboard.financial_statistics.today_profits_bookings') !!}</h6>
                            <h4 class="text-bold-700 mt-1">{{ $today_profits_bookings }}$</h4>
                        </div>
                        {{--  <h6 class="text-bold-700 mt-1"><a href="{{route('dashboard.product.show',$most_sellers_products->first()->id)}}">{{ $most_sellers_products->first()->name }}</a></h6>
                        <p class="mb-0">{{trans('dashboard.report.most_sellers_product')}}</p>  --}}
                    </div>
                    <div class="card-content">
                        <div id="line-area-chart-1"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card" style="min-height: 230px">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="fa fa-fire text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="text-bold-700 mt-1 col-8">{!! trans('dashboard.financial_statistics.current_week_profits_bookings') !!}</h6>
                            <h4 class="text-bold-700 mt-1">{{ $current_week_profits_bookings ."$" }}</h4>
                        </div>
                        {{--  <h6 class="text-bold-700 mt-1"><a href="{{route('dashboard.provider.show',$best_sales_providers->first()->id)}}">{{ $best_sales_providers->first()->fullname }}</a></h6>
                        <p class="mb-0">{!! trans('dashboard.report.best_sales_provider') !!}</p>  --}}
                    </div>
                    <div class="card-content">
                        <div id="line-area-chart-2"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card" style="min-height: 230px">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                <i class="fal fa-coin text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="text-bold-700 mt-1 col-8">{!! trans('dashboard.financial_statistics.current_month_profits_bookings') !!}</h6>
                            <h4 class="text-bold-700 mt-1">{{ $current_month_profits_bookings ."$" }}</h4>
                        </div>
                        {{--  <h6 class="text-bold-700 mt-1"><a href="{{route('dashboard.subscription.show',$most_subscriptionss->first()->id)}}">{{ $most_subscriptionss->first()->name }}</a></h6>
                        <p class="mb-0">{!! trans('dashboard.report.most_subscriptions') !!}</p>  --}}
                    </div>
                    <div class="card-content">
                        <div id="line-area-chart-2"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card" style="min-height: 230px">
                    <div class="card-header d-flex flex-column align-items-start pb-0">
                        <div class="avatar bg-rgba-primary p-50 m-0">
                            <div class="avatar-content">
                                {{--  <i class="feather icon-package text-primary font-medium-5"></i>  --}}
                                <i class="fas fa-sack-dollar text-primary font-medium-5"></i>
                            </div>
                        </div>
                        <div class="row">
                            <h6 class="text-bold-700 mt-1 col-8">{!! trans('dashboard.financial_statistics.current_year_profits_bookings') !!}</h6>
                            <h4 class="text-bold-700 mt-1">{{ $current_year_profits_bookings ."$" }}</h4>
                        </div>
                        {{--  <h6 class="text-bold-700 mt-1"><a href="{{route('dashboard.client.show',$most_bought_clients->first()->id)}}">{{ $most_bought_clients->first()->fullname }}</a></h6>
                        <p class="mb-0">{!! trans('dashboard.report.most_bought_client') !!}</p>  --}}
                    </div>
                    <div class="card-content">
                        <div id="line-area-chart-2"></div>
                    </div>
                </div>
            </div>
        </div>
    {{--  <div class="content-body">
                <!-- Statistics card section start -->
                <section id="statistics-card">
                    <div class="row">
                        <div class="col-lg-6  col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-start pb-0">
                                    <div>
                                        <h2 class="text-bold-700 mb-0">{{$annual_orders_total_price}}</h2>
                                        <p>{{trans('dashboard.report.annual_orders_total_price')}}</p>
                                    </div>
                                    <div class="avatar bg-rgba-primary p-50 m-0">
                                        <div class="avatar-content">
                                            <i class="fa fa-list text-primary font-medium-5"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-start pb-0">
                                    <div>
                                        <h2 class="text-bold-700 mb-0">{{$annual_orders_total_tax}}</h2>
                                        <p>{{trans('dashboard.report.annual_orders_total_tax')}}</p>
                                    </div>
                                    <div class="avatar bg-rgba-success p-50 m-0">
                                        <div class="avatar-content">
                                            <i class="feather icon-server text-success font-medium-5"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center" style="height: 200px">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="avatar bg-rgba-info p-50 m-0 mb-1">
                                            <div class="avatar-content">
                                                <i class="feather icon-file text-info font-medium-5"></i>
                                            </div>
                                        </div>
                                        <h2 class="text-bold-700">{{$today_orders_total_price != null ? $today_orders_total_price : 0}}</h2>
                                        <p class="mb-0 line-ellipsis">{{trans('dashboard.report.today_orders_total_price')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center" style="height: 200px">
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                            <div class="avatar-content">
                                                <i class="feather icon-file text-danger font-medium-5"></i>
                                            </div>
                                        </div>
                                        <h2 class="text-bold-700">{{$today_orders_total_tax != null ? $today_orders_total_tax : 0}}</h2>
                                        <p class="mb-0 line-ellipsis">{{trans('dashboard.report.today_orders_total_tax')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-content" style="height: 200px">
                                    <div class="card-body">
                                        <div class="avatar bg-rgba-warning p-50 m-0 mb-1">
                                            <div class="avatar-content">
                                                <i class="feather icon-message-square text-warning font-medium-5"></i>
                                            </div>
                                        </div>
                                        <h2 class="text-bold-700">{{$current_month_orders_total_price != null ? $current_month_orders_total_price : 0}}</h2>
                                        <p class="mb-0 line-ellipsis">{{trans('dashboard.report.current_month_orders_total_price')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-content" style="height: 200px">
                                    <div class="card-body">
                                        <div class="avatar bg-rgba-danger p-50 m-0 mb-1">
                                            <div class="avatar-content">
                                                <i class="feather icon-shopping-bag text-danger font-medium-5"></i>
                                            </div>
                                        </div>
                                        <h2 class="text-bold-700">{{$month_orders_total_tax != null ? $month_orders_total_tax : 0}}</h2>
                                        <p class="mb-0 line-ellipsis">{{trans('dashboard.report.month_orders_total_tax')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-content" style="height: 200px">
                                    <div class="card-body">
                                        <div class="avatar bg-rgba-success p-50 m-0 mb-1">
                                            <div class="avatar-content">
                                                <i class="feather icon-award text-success font-medium-5"></i>
                                            </div>
                                        </div>
                                        <h2 class="text-bold-700">{{$last_month_orders_total_price != null ? $last_month_orders_total_price : 0}}</h2>
                                        <p class="mb-0 line-ellipsis">{{trans('dashboard.report.last_month_orders_total_price')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <div class="card-content" style="height: 200px">
                                    <div class="card-body">
                                        <div class="avatar bg-rgba-success p-50 m-0 mb-1">
                                            <div class="avatar-content">
                                                <i class="feather icon-award text-success font-medium-5"></i>
                                            </div>
                                        </div>
                                        <h2 class="text-bold-700">{{$last_month_orders_total_tax != null ? $last_month_orders_total_tax : 0}}</h2>
                                        <p class="mb-0 line-ellipsis">{{trans('dashboard.report.last_month_orders_total_tax')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- // Statistics Card section end-->

            </div>  --}}
        {{--  <div class="row">
            <div class="col-md-3 mb-2 mb-md-0">
                <ul class="nav nav-pills flex-column mt-md-0 mt-">
                    <li class="nav-item">
                        <a class="nav-link d-flex py-75 active" id="most_sellers_product" data-toggle="pill" href="#new" aria-expanded="true">
                            <i class="fa fa-star mr-50 font-medium-3"></i>
                            {!! trans('dashboard.report.most_sellers_product') !!}
                        </a>
                    </li>
                    <li class="nav-item mt-1">
                        <a class="nav-link d-flex py-75" id="account-pill-current" data-toggle="pill" href="#current" aria-expanded="false">
                            <i class="fa fa-star mr-50 font-medium-3"></i>
                            {!! trans('dashboard.report.most_bought_client') !!}
                        </a>
                    </li>
                    <li class="nav-item mt-1">
                        <a class="nav-link d-flex py-75" id="account-pill-finished" data-toggle="pill" href="#finished" aria-expanded="false">
                            <i class="fa fa-star mr-50 font-medium-3"></i>
                            {!! trans('dashboard.report.best_sales_provider') !!}
                        </a>
                    </li>
                    <li class="nav-item mt-1">
                        <a class="nav-link d-flex py-75" id="reservations_written" data-toggle="pill" href="#written" aria-expanded="false">
                            <i class="fa fa-star mr-50 font-medium-3"></i>
                            {!! trans('dashboard.report.most_subscriptions') !!}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-9">
                <div class="card border-info">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="new" aria-labelledby="most_sellers_product" aria-expanded="true">
                                    <div class="card">
                                        <div class="card-header row">
                                            <h4 class="mb-0 col-6">{!! trans('dashboard.report.most_sellers_product') !!}</h4>
                                            <h4 class="mb-0">{!! trans('dashboard.report.total_sellers_prices') .' : ' .$most_sellers_products->first()->total_price ."$" !!}</h4>
                                        </div>
                                        <div class="card-content list-orders new_orders">
                                            <div class="table-responsive mt-1">
                                                <table class="table table-hover-animation mb-0 new_orders_scroll">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th>{!! trans('dashboard.general.image') !!}</th>
                                                        <th>{!! trans('dashboard.general.name') !!}</th>
                                                        <th>{!! trans('dashboard.provider.provider') !!}</th>
                                                        <th>{!! trans('dashboard.report.quantity_sold') !!}</th>
                                                        <th>{!! trans('dashboard.general.price') !!}</th>
                                                        <th>{!! trans('dashboard.report.total_sellers_prices') !!}</th>
                                                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                                                        <th><i class="feather icon-zap"></i></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($most_sellers_products as $most_sellers_product)
                                                    <tr class="{{ $most_sellers_product->id }} text-center">
                                                        <td class="product-img sorting_1">
                                                            <a href="{{ $most_sellers_product->img }}" data-fancybox="gallery">
                                                                <img src="{{ $most_sellers_product->img }}" alt="" style="width:90px; height:80px;" class="img-thumbnail rounded">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="{!! route('dashboard.product.show',$most_sellers_product->id) !!}" class="text-info mr-1">
                                                                {{ $most_sellers_product->name }}
                                                            </a>
                                                        </td>
                                                        @if($most_sellers_product->user_id != null)
                                                            <td>{{ \App\Models\User::find($most_sellers_product->user_id)->fullname }}</td>
                                                        @else
                                                            <td>{{trans('dashboard.product.not_have_phone')}}</td>
                                                        @endif
                                                        <td>{{ $most_sellers_product->quantity_sold }}</td>
                                                        <td>{{ $most_sellers_product->price }}</td>
                                                        <td>{{ $most_sellers_product->total_price }}</td>
                                                        <td>
                                                            <div class="badge badge-violet badge-md mr-1 mb-1">{{ $most_sellers_product->created_at->format("Y-m-d") }}</div>
                                                        </td>
                                                        <td class="product-action text-center font-medium-3">
                                                            <a href="{!! route('dashboard.product.show',$most_sellers_product->id) !!}" class="text-info mr-1">
                                                                <i class="feather icon-monitor" title="{!! trans('dashboard.general.show') !!}"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="ajax-load text-center" style="display:none">
                                                    <div class="spinner-border text-success" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="current" aria-labelledby="account-pill-current" aria-expanded="false">
                                    <div class="card">
                                        <div class="card-header row">
                                            <h4 class="mb-0 col-6">{!! trans('dashboard.report.most_bought_client') !!}</h4>
                                            <h4 class="mb-0">{!! trans('dashboard.report.total_bought_prices') .' : ' .$most_bought_clients->first()->total_price ."$" !!}</h4>
                                        </div>
                                        <div class="card-content list-orders current_orders">
                                            <div class="table-responsive mt-1">
                                                <table class="table table-hover-animation mb-0 new_orders_scroll">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th>{!! trans('dashboard.general.image') !!}</th>
                                                        <th>{!! trans('dashboard.general.name') !!}</th>
                                                        <th>{!! trans('dashboard.general.email') !!}</th>
                                                        <th>{!! trans('dashboard.general.phone') !!}</th>
                                                        <th>{!! trans('dashboard.user.active_state') !!}</th>
                                                        <th>{!! trans('dashboard.report.total_bought_prices') !!}</th>
                                                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                                                        <th><i class="feather icon-zap"></i></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($most_bought_clients as $most_bought_client)
                                                    <tr class="{{ $most_bought_client->id }} text-center">
                                                        <td class="product-img sorting_1">
                                                            <a href="{{ $most_bought_client->profile_image }}" data-fancybox="gallery">
                                                                <img src="{{ $most_bought_client->profile_image }}" alt="" style="width:90px; height:80px;" class="img-thumbnail rounded">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="{!! route('dashboard.client.show',$most_bought_client->id) !!}" class="text-info mr-1">
                                                                {{ $most_bought_client->fullname }}
                                                            </a>
                                                        </td>
                                                        @if($most_bought_client->email != null)
                                                            <td>{{ $most_bought_client->email }}</td>
                                                        @else
                                                            <td>{{trans('dashboard.general.not_found')}}</td>
                                                        @endif
                                                        @if($most_bought_client->phone != null)
                                                            <td>{{ $most_bought_client->phone }}</td>
                                                        @else
                                                            <td>{{trans('dashboard.client.not_have_phone')}}</td>
                                                        @endif
                                                        <td>
                                                            @if($most_bought_client->is_active==0)
                                                                <span class="text-warning" >{{ trans('dashboard.user.not_active') }}</span>
                                                            @else
                                                                <span class="text-success" >{{ trans('dashboard.user.active') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{$most_bought_client->total_price}}</td>
                                                        <td>
                                                            <div class="badge badge-violet badge-md mr-1 mb-1">{{ $most_bought_client->created_at->format("Y-m-d") }}</div>
                                                        </td>
                                                        <td class="product-action text-center font-medium-3">
                                                            <a href="{!! route('dashboard.client.show',$most_bought_client->id) !!}" class="text-info mr-1">
                                                                <i class="feather icon-monitor" title="{!! trans('dashboard.general.show') !!}"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="ajax-load text-center" style="display:none">
                                                    <div class="spinner-border text-success" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="finished" aria-labelledby="account-pill-finished" aria-expanded="false">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="mb-0">{!! trans('dashboard.report.best_sales_provider') !!}</h4>
                                            <h4 class="mb-0">{!! trans('dashboard.report.total_sellers_prices') .' : ' .$best_sales_providers->first()->total_items_price ."$" !!}</h4>
                                        </div>
                                        <div class="card-content list-orders finished_orders">
                                            <div class="table-responsive mt-1">
                                                <table class="table table-hover-animation mb-0 new_orders_scroll">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th>{!! trans('dashboard.general.image') !!}</th>
                                                        <th>{!! trans('dashboard.general.name') !!}</th>
                                                        <th>{!! trans('dashboard.general.email') !!}</th>
                                                        <th>{!! trans('dashboard.general.phone') !!}</th>
                                                        <th>{!! trans('dashboard.user.active_state') !!}</th>
                                                        <th>{!! trans('dashboard.report.total_sellers_prices') !!}</th>
                                                        <th>{!! trans('dashboard.report.tax_price') !!}</th>
                                                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                                                        <th><i class="feather icon-zap"></i></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($best_sales_providers as $best_sales_provider)
                                                            <tr class="{{ $best_sales_provider->id }} text-center">
                                                                <td class="product-img sorting_1">
                                                                    <a href="{{ $best_sales_provider->profile_image }}" data-fancybox="gallery">
                                                                        <img src="{{ $best_sales_provider->profile_image }}" alt="" style="width:90px; height:80px;" class="img-thumbnail rounded">
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a href="{!! route('dashboard.provider.show',$best_sales_provider->id) !!}" class="text-info mr-1">
                                                                        {{ $best_sales_provider->fullname }}
                                                                    </a>
                                                                </td>
                                                                @if($best_sales_provider->email != null)
                                                                    <td>{{ $best_sales_provider->email }}</td>
                                                                @else
                                                                    <td>{{trans('dashboard.general.not_found')}}</td>
                                                                @endif
                                                                @if($best_sales_provider->phone != null)
                                                                    <td>{{ $best_sales_provider->phone }}</td>
                                                                @else
                                                                    <td>{{trans('dashboard.provider.not_have_phone')}}</td>
                                                                @endif
                                                                <td>
                                                                    @if($best_sales_provider->is_active==0)
                                                                        <a href="{{ route('dashboard.active_provider', $best_sales_provider->id) }}" class="btn btn-secondary text-white" >{{ trans('dashboard.user.not_active') }}</a>
                                                                    @else
                                                                        <a href="{{ route('dashboard.active_provider', $best_sales_provider->id) }}" class="btn btn-success text-white" >{{ trans('dashboard.user.active') }}</a>
                                                                    @endif
                                                                </td>
                                                                <td>{{$best_sales_provider->total_items_price}}</td>
                                                                <td>{{ $best_sales_provider->tax_price }}</td>
                                                                <td>
                                                                    <div class="badge badge-violet badge-md mr-1 mb-1">{{ $best_sales_provider->created_at->format("Y-m-d") }}</div>
                                                                </td>
                                                                <td class="product-action text-center font-medium-3">
                                                                    <a href="{!! route('dashboard.provider.show',$best_sales_provider->id) !!}" class="text-info mr-1">
                                                                        <i class="feather icon-monitor" title="{!! trans('dashboard.general.show') !!}"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="ajax-load text-center" style="display:none">
                                                    <div class="spinner-border text-success" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="written" aria-labelledby="account-pill-finished" aria-expanded="false">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="mb-0">{!! trans('dashboard.report.most_subscriptions') !!}</h4>
                                            <h4 class="mb-0">{!! trans('dashboard.report.total_subscriptions_price') .' : ' .$most_subscriptionss->first()->total_subscriptions_price ."$" !!}</h4>
                                        </div>
                                        <div class="card-content list-orders finished_orders">
                                            <div class="table-responsive mt-1">
                                                <table class="table table-hover-animation mb-0 new_orders_scroll">
                                                    <thead>
                                                    <tr class="text-center">
                                                        <th>{!! trans('dashboard.subscription.provider') !!}</th>
                                                        <th>{!! trans('dashboard.subscription.name') !!}</th>
                                                        <th>{!! trans('dashboard.subscription.desc') !!}</th>
                                                        <th>{!! trans('dashboard.subscription.number_of_deliveries') !!}</th>
                                                        <th>{!! trans('dashboard.subscription.period') !!}</th>
                                                        <th>{!! trans('dashboard.subscription.period_type') !!}</th>
                                                        <th>{!! trans('dashboard.subscription.price') !!}</th>
                                                        <th>{!! trans('dashboard.report.clients_subscriptions_count') !!}</th>
                                                        <th>{!! trans('dashboard.general.added_date') !!}</th>
                                                        <th>{!! trans('dashboard.general.control') !!}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($most_subscriptionss as $most_subscriptions)
                                                            <tr class="{{ $most_subscriptions->id }} text-center">
                                                                <td>{{ \App\Models\User::find($most_subscriptions->client_id)->fullname }}</td>
                                                                <td>
                                                                    <a href="{!! route('dashboard.subscription.show',$most_subscriptions->id) !!}" class="text-info mr-1">
                                                                        {{ $most_subscriptions->name }}
                                                                    </a>
                                                                </td>
                                                                <td>{{ substr($most_subscriptions->desc, 0, 30)."....." }}</td>
                                                                <td>{{ $most_subscriptions->number_of_deliveries }}</td>
                                                                <td>{{ $most_subscriptions->period }}</td>
                                                                <td>{{ $most_subscriptions->period_type }}</td>
                                                                <td>{{ $most_subscriptions->price }}</td>
                                                                <td>{{ $most_subscriptions->subscriptions_count }}</td>
                                                                <td>
                                                                    <div class="badge badge-violet badge-md mr-1 mb-1">{{ $most_subscriptions->created_at->format("Y-m-d") }}</div>
                                                                </td>
                                                                <td class="product-action text-center font-medium-3">
                                                                    <a href="{!! route('dashboard.subscription.show',$most_subscriptions->id) !!}" class="text-info mr-1">
                                                                        <i class="feather icon-monitor" title="{!! trans('dashboard.general.show') !!}"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="ajax-load text-center" style="display:none">
                                                    <div class="spinner-border text-success" role="status">
                                                        <span class="sr-only">Loading...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>  --}}








    </section>
    <!-- Dashboard Analytics end -->
@endsection
@section('vendor_styles')

    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/vendors/css/tables/datatable/datatables.min.css">

@endsection
@section('page_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/pages/users.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/pages/data-list-view.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <style>
        .acc-or{
            background-color:#fff;
            padding: 15px 10px;
            border-radius: 10px;
            margin-bottom: 20px;
            color:#7367F0 !important
        }

        .acc-or span
        {
            font-weight: 600
        }

        .me-cla
        {
            text-align:center
        }

        .me-cla .fst-one
        {
            margin-bottom: 20px
        }

    </style>

@endsection
@section('vendor_scripts')
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>

@endsection
@section('page_scripts')
    <script src="{{ asset('dashboardAssets') }}/js/scripts/pages/user-profile.js"></script>
    <script src="{{ asset('dashboardAssets') }}/js/scripts/datatables/datatable.js"></script>
    <script src="{{ asset('dashboardAssets') }}/js/scripts/navs/navs.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
@endsection
