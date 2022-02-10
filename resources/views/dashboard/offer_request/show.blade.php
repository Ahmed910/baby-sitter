@extends('dashboard.layout.layout')

@section('content')
<!-- page users view start -->
<section class="page-users-view">
    <div class="row">
        <!-- account start -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ trans('dashboard.offer_request.offer_request_data') }}</div>
                    <div class="heading-elements">
                        <div class="badge badge-primary block badge-md mr-1 mb-1">
                            {{ $offer_request->created_at->format("Y-m-d") }}
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



                    <table class="ml-0 ml-sm-0 ml-lg-0">

                        <tr>
                            <td class="font-weight-bold">{{ trans('dashboard.offer_request.offer_photo') }} :
                            </td>
                            <td>
                                <a href="{{ $offer_request->photo }}" data-fancybox="gallery">
                                    <div class="avatar">
                                    <img src="{{ $offer_request->photo }}" alt="" style="width:100px; height:100px;" class="img-thumbnail rounded">
                                    {{--  <span class="avatar-status-busy avatar-status-md" id="online_{{ $center->id }}"></span>  --}}
                                    </div>
                                    </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">{{ trans('dashboard.offer_request.title') }} :
                            </td>
                            <td>
                                {{ $offer_request->title }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">{{ trans('dashboard.offer_request.offer_user') }} :
                            </td>
                            <td>
                                {{ optional($offer_request->user)->name }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">{{ trans('dashboard.offer_request.provider_type') }} :
                            </td>
                            <td>
                                {{ $offer_request->user_type }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">{{ trans('dashboard.offer_request.status.status') }} :
                            </td>
                            <td>
                                {{ trans('dashboard.offer_request.status.'.$offer_request->status) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.offer_request.start_date') }} :
                            </td>
                            <td>
                                {{ $offer_request->start_date->toFormattedDateString() }}
                            </td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.offer_request.end_date') }} :
                            </td>
                            <td>
                                {{ $offer_request->end_date->toFormattedDateString() }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.offer_request.discount') }} :
                            </td>
                            <td>
                                {{ $offer_request->discount .' %' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.offer_request.promo_code') }} :
                            </td>
                            <td>
                                {{ $offer_request->promo_code }}
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                {{ trans('dashboard.offer_request.max_num') }} :
                            </td>
                            <td>
                                {{ $offer_request->max_num }}
                            </td>
                        </tr>




                    </table>

                    {{--  @if($offer_request->status == 'pending')
                    <div style="float: left">
                        @if(auth()->user()->hasPermissions('offer_request','accept'))
                        <a href="{{ route('dashboard.offer_request.accept',$offer_request->id) }}" class="btn btn-success">{{ trans('dashboard.offer_request.status.accept') }}</a>
                        @endif
                        @if(auth()->user()->hasPermissions('offer_request','reject'))
                        <a href="{{ route('dashboard.offer_request.reject',$offer_request->id) }}" class="btn btn-danger">{{ trans('dashboard.offer_request.status.reject') }}</a>
                        @endif
                    </div>
                    @endif  --}}
                    </div>
                    @if($offer_request->status == 'pending')
                    @if(auth()->user()->hasPermissions('offer_request','change_status'))
                    <div class="col-6 col-sm-3 col-md-6 col-lg-7">
                        <form action="{{ route('dashboard.offer_request.change_status',['id'=>$offer_request->id]) }}" method="POST">
                            @csrf
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" value="accepted">
                                <label class="form-check-label" for="accept">
                                    {{ trans('dashboard.offer_request.status.accept') }}
                                </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="reject" value="rejected">
                                <label class="form-check-label" for="reject">
                                    {{ trans('dashboard.offer_request.status.reject') }}
                                </label>
                              </div>

                                    <label for="ban_reason-column">{{ trans('dashboard.offer_request.reject_reason') }}</label>
                                    {!! Form::textarea('reject_reason', null, ['class' => 'form-control' ,"id" => "ban_reason-column", 'placeholder' => trans('dashboard.user.ban_reason')]) !!}
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary">{{ trans('dashboard.offer_request.confirm_state') }}</button>
                                    </div>

                        </form>
                    </div>
                    @endif
                    @endif
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
