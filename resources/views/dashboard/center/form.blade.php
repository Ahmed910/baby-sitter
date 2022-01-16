<div class="row">
    <!-- users edit media object start -->
    <div class="col-12">
        <div class="media mb-2">
            @if (isset($center) && $center->image)
                <img src="{{ $center->avatar }}" alt="{{ $center->name }}"
                    class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer image-preview" height="90"
                    width="90" />
            @else
                <img src="{{ asset('dashboardAssets/images/backgrounds/placeholder_image.png') }}" alt="users avatar"
                    class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer image-preview" height="90"
                    width="90" />
            @endif
            <div class="media-body mt-50">
                <h4>{{ isset($center) ? $center->name : trans('dashboard.general.image') }}</h4>
                <div class="col-12 d-flex mt-1 px-0">
                    <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                        <span class="d-none d-sm-block">Change</span>
                        <input class="form-control" type="file" id="change-picture" name="image" hidden
                            accept="image/png, image/jpeg, image/jpg" onchange="readUrl(this)" />
                        <span class="d-block d-sm-none">
                            <i class="mr-0" data-feather="edit"></i>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <!-- users edit media object ends -->

    <div class="col-md-6 col-12">
        <div class="form-group">
            <label for="center-name-column">{{ trans('dashboard.center.name') }} <span
                    class="text-danger">*</span></label>
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'center-name-column', 'placeholder' => trans('dashboard.center.name')]) !!}
        </div>
    </div>



    <div class="col-md-6 col-12">
        <div class="form-group">
            <label for="phone-column">{{ trans('dashboard.general.phone') }} <span
                    class="text-danger">*</span></label>
            {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone-column', 'placeholder' => trans('dashboard.general.phone')]) !!}
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group">
            <div class="d-flex justify-content-between">
                <label for="login-password">{!! trans('dashboard.general.password') !!}</label>
            </div>
            <div class="input-group input-group-merge form-password-toggle">
                {!! Form::password('password', ['class' => 'form-control form-control-merge', 'id' => 'login-password', 'placeholder' => '&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;', 'aria-describedby' => 'login-password', 'tabindex' => '2']) !!}
                <div class="input-group-append">
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <div class="d-flex justify-content-between">
                <label for="login-password_confirmation">{!! trans('dashboard.general.password_confirmation') !!}</label>
            </div>
            <div class="input-group input-group-merge form-password-toggle">
                {!! Form::password('password_confirmation', ['class' => 'form-control form-control-merge', 'id' => 'login-password_confirmation', 'placeholder' => '&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;', 'aria-describedby' => 'login-password_confirmation', 'tabindex' => '2']) !!}

                <div class="input-group-append">
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="business-register-column">{{ trans('dashboard.center.business_register') }} <span
                    class="text-danger">*</span></label>
            {!! Form::text('business_register', isset($center) ? optional($center->child_centre)->business_register : null, ['class' => 'form-control', 'id' => 'business-register-column', 'placeholder' => trans('dashboard.center.business_register')]) !!}
        </div>
    </div>

    <div class="col-md-10">
        <label class="form-label" for="modern-image">
            {{ trans('dashboard.center.business_license') }}
        </label>

            <div class="custom-file">
                <input type="file" name="business_license_image" class="custom-file-input" id="business_license" onchange="readUrl(this,'business_license_preview')">
                <label class="custom-file-label" for="business_license">Choose file</label>
            </div>
        </div>
        <div class="col-md-1">
            @if (isset($center) && optional($center->child_centre)->BusinessLicenseImage)
            <img src="{{ optional($center->child_centre)->BusinessLicenseImage }}" class="img-thumbnail business_license_preview" style="width: 100%; height: 100px;">
            @else
            <img src="{{ asset('dashboardAssets/images/backgrounds/placeholder_image.png') }}" class="img-thumbnail business_license_preview" style="width: 100%; height: 100px;">
            @endif
        </div>



    <div class="col-12 city">
        <div class="form-group">
            <label>{{ trans('dashboard.city.city') }}
                <span class="text-danger">*</span>
            </label>
            {!! Form::select('city_id', $cities, Request::is('*/edit') ? optional($center->profile)->city_id : null, ['class' => 'select2 form-control', 'placeholder' => trans('dashboard.city.city')]) !!}
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
    <label for="client_loaction-column">{{ trans('dashboard.center.center_location') }}</label>
        </div>
    </div>
<div id="map" style="width:100%;height:380px;"></div>
            <div class="form-group row">
                <div class="col-lg-9">
                <input type="hidden" name="lat" value="{{ isset($center)? optional($center->profile)->lat : old('lat')}}" class="form-control" placeholder="latitude" id="lat" >
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-9">
                <input type="hidden" name="lng" value="{{ isset($center)? optional($center->profile)->lng : old('lng')}}" class="form-control" placeholder="latitude" id="lng" >
                </div>
            </div>

            <div class="form-group row">
                <input type="text" id="pac-input" name="location" value="{{ isset($center) ? optional($center->profile)->location:null }}"  class="form-control bg-white" placeholder="ابحث في الخريطة" style="width: 500px;"/>
                <div class="col-lg-12" style="width:100%; height:400px;" id="map"></div>
            </div>

    <div class="col-12">
        <div class="form-group">
            <label>{{ trans('dashboard.user.service_type') }} <span
                    class="text-danger">*</span></label>

                @foreach ($services as $key=>$service)
                 <label>{{ $service->name }}</label> <input type="checkbox" value="{{ $service->id }}"  {{ isset($center) && $center->services->contains($service->id)? 'checked':'' }}  name="services[{{ $key }}][service_id]">
                 <label>{{ trans("dashboard.center.{$service->translate('en')->name}") }}</label> <input type="text" value="{{ isset($center) && $center->services->contains($service->id) ? (float)$center->user_services()->where('service_id',$service->id)->first()->price:'' }}" name="services[{{ $key }}][price]" />
                @endforeach

                {{--  {!! Form::select("services[]", $services, isset($center) ? $center->services : null, ['class' => 'select2 form-control','multiple' => 'multiple']) !!}  --}}


        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label>{{ trans('dashboard.user.features') }} <span
                    class="text-danger">*</span></label>



                {!! Form::select("features[]", $features, isset($center) ? $center->features : null, ['class' => 'select2 form-control','multiple' => 'multiple']) !!}


        </div>
    </div>

    {{--  <div class="col-12">  --}}

    {{--  </div>  --}}


    <div class="form-group col-12">
        <label class="form-label" for="modern-active_state">
            {{ trans('dashboard.user.active_state') }}
        </label>
        <div class="demo-inline-spacing">
            <div class="custom-control custom-control-success custom-radio col-md-6">
                {!! Form::radio('is_active', 1, !isset($center) || (isset($center) && $center->is_active) ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'is_active']) !!}
                <label class="custom-control-label" for="is_active">{!! trans('dashboard.user.active') !!}</label>
            </div>
            <div class="custom-control custom-control-danger custom-radio">
                {!! Form::radio('is_active', 0, isset($center) && !$center->is_active ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'is_deactive']) !!}
                <label class="custom-control-label" for="is_deactive">{!! trans('dashboard.user.not_active') !!}</label>
            </div>

        </div>
    </div>

    <div class="form-group col-12">
        <label class="form-label" for="modern-active_state">
            {{ trans('dashboard.center.is_educational') }}
        </label>
        <div class="demo-inline-spacing">
            <div class="custom-control custom-control-success custom-radio col-md-6">
                {!! Form::radio('is_educational', 1, !isset($center) || (isset($center) && optional($center->child_centre)->is_educational) ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'yes']) !!}
                <label class="custom-control-label" for="yes">{!! trans('dashboard.center.yes') !!}</label>
            </div>
            <div class="custom-control custom-control-danger custom-radio">
                {!! Form::radio('is_educational', 0, isset($center) && !optional($center->child_centre)->is_educational ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'no']) !!}
                <label class="custom-control-label" for="no">{!! trans('dashboard.center.no') !!}</label>
            </div>

        </div>
    </div>

    <div class="form-group col-12">
        <label class="form-label" for="modern-ban_state">
            {{ trans('dashboard.user.ban_state') }}
        </label>
        <div class="demo-inline-spacing">
            <div class="custom-control custom-control-success custom-radio col-md-6">
                {!! Form::radio('is_ban', 1, !isset($center) || (isset($center) && $center->is_ban) ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'is_ban']) !!}
                <label class="custom-control-label" for="is_ban">{!! trans('dashboard.user.ban') !!}</label>
            </div>

            <div class="custom-control custom-control-danger custom-radio">
                {!! Form::radio('is_ban', 0, isset($center) && !$center->is_ban ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'is_not_ban']) !!}
                <label class="custom-control-label" for="is_not_ban">{!! trans('dashboard.user.not_ban') !!}</label>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="ban_reason-column">{{ trans('dashboard.user.ban_reason') }}</label>
            {!! Form::textarea('ban_reason', null, ['class' => 'form-control', 'id' => 'ban_reason-column', 'placeholder' => trans('dashboard.user.ban_reason')]) !!}
        </div>
    </div>

    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">{{ $btnSubmit }}</button>
    </div>
</div>


<script src="{{ asset('dashboardAssets') }}/js/scripts/forms/form-number-input.js"></script>
<script src="{{ asset('dashboardAssets') }}/js/scripts/components/components-navs.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/lang/summernote-ar-AR.min.js"></script>


@section('vendor_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/vendors/css/forms/wizard/bs-stepper.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/vendors/css/forms/select/select2.min.css">
@endsection
@section('page_styles')
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/css/{{ LaravelLocalization::getCurrentLocaleDirection() }}/core/menu/menu-types/horizontal-menu.css">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/css/{{ LaravelLocalization::getCurrentLocaleDirection() }}/plugins/forms/form-validation.css">
<link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/css/{{ LaravelLocalization::getCurrentLocaleDirection() }}/plugins/forms/form-wizard.css">
@endsection
@section('vendor_scripts')
<script src="{{ asset('dashboardAssets') }}/vendors/js/ui/jquery.sticky.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/forms/wizard/bs-stepper.min.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/forms/select/select2.full.min.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/forms/validation/jquery.validate.min.js"></script>
@endsection
@section('page_scripts')
<script src="{{ asset('dashboardAssets') }}/js/scripts/forms/form-wizard.js"></script>

<script src="{{ asset('dashboardAssets') }}/js/scripts/forms/form-number-input.js"></script>
<script src="{{ asset('dashboardAssets') }}/js/scripts/components/components-navs.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.11/lang/summernote-ar-AR.min.js"></script>
<script>
$('.editor').summernote({
    // airMode: true,
    tabsize: 10,
    height: 250,
    lang: "{{ app()->getLocale() == 'ar' ? 'ar-AR' : '' }}"
});
</script>
       <script>
        function initMap() {
            let lat = $('#lat').val() !== '' ? $('#lat').val() : 24.7135517;
            let lng = $('#lng').val() !== '' ? $('#lng').val() : 46.6752957;
            var myLatlng = new google.maps.LatLng(lat, lng);
            var map;
            var myOptions = {
                zoom: 12,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            map = new google.maps.Map(document.getElementById("map"), myOptions);
            // marker refers to a global variable
            marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                draggable: true
            });
            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function () {
                searchBox.setBounds(map.getBounds());
            });
            searchBox.addListener('places_changed', function () {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function (place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    // marker refers to a global variable
                    marker.setPosition(place.geometry.location);
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                    document.getElementById("lat").value = place.geometry.location.lat();
                    document.getElementById("lng").value = place.geometry.location.lng();
                        //   document.getElementById("address").value = place.geometry.location.address();
                    console.log(place);
                });
                map.fitBounds(bounds);
            });
            google.maps.event.addListener(marker, "dragend", function (event) {
                // get lat/lon of click
                var clickLat = event.latLng.lat();
                var clickLon = event.latLng.lng();
                // show in input box
                document.getElementById("lat").value = clickLat.toFixed(5);
                document.getElementById("lng").value = clickLon.toFixed(5);
                /*document.getElementById("address").value = event.latLng.address();*/
            });
        }
    </script>

    <script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap&key=AIzaSyAITrPfT5r_qmCm_8ekZyPmnebGo8o_r18" ></script>


@endsection

@include('dashboard.center.scripts')
