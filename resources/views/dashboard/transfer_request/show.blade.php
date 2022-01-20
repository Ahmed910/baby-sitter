@extends('dashboard.layout.layout')

@section('content')
<!-- page users view start -->
<section class="page-users-view">
    <div class="row">
        <!-- account start -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ trans('dashboard.transfer_request.transfer_request_data') }}</div>
                    <div class="heading-elements">
                        <div class="badge badge-primary block badge-md mr-1 mb-1">
                            {{ $transfer_request->created_at->format("Y-m-d") }}
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
                        <div class="col-12 col-sm-9 col-md-6 col-lg-5">



                    <table class="ml-0 ml-sm-0 ml-lg-0">
                        <tr>
                            <td class="font-weight-bold">{{ trans('dashboard.transfer_request.amount') }} :
                            </td>
                            <td>
                                {{ $transfer_request->amount }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">{{ trans('dashboard.transfer_request.status.status') }} :
                            </td>
                            <td>
                                {{ trans('dashboard.transfer_request.status.'.$transfer_request->status) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.transfer_request.bank_account_data.account_name') }} :
                            </td>
                            <td>
                                {{ optional($transfer_request->walletWithdraw)->account_name }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.transfer_request.bank_account_data.bank_name') }} :
                            </td>
                            <td>
                                {{ optional($transfer_request->walletWithdraw)->bank_name }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.transfer_request.user_name') }} :
                            </td>
                            <td>
                                {{ optional($transfer_request->user)->name }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.transfer_request.bank_account_data.account_number') }} :
                            </td>
                            <td>
                                {{ optional($transfer_request->walletWithdraw)->account_number }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.transfer_request.bank_account_data.iban_number') }} :
                            </td>
                            <td>
                                {{ optional($transfer_request->walletWithdraw)->iban_number }}
                            </td>
                        </tr>


                    </table>

                    @if($transfer_request->status == 'pending')
                    <div style="float: left">
                        <a href="{{ route('dashboard.transfer_request.accept',$transfer_request->id) }}" class="btn btn-success">{{ trans('dashboard.transfer_request.status.accept') }}</a>
                        <a href="{{ route('dashboard.transfer_request.reject',$transfer_request->id) }}" class="btn btn-danger">{{ trans('dashboard.transfer_request.status.reject') }}</a>
                    </div>
                    @endif
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
