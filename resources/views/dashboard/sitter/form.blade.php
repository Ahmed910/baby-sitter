<div class="row">
    <!-- users edit media object start -->
    <div class="col-12">
        <div class="media mb-2">
            @if (isset($sitter) && $sitter->image)
                <img src="{{ $sitter->avatar }}" alt="{{ $sitter->name }}"
                    class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer image-preview" height="90"
                    width="90" />
            @else
                <img src="{{ asset('dashboardAssets/images/backgrounds/placeholder_image.png') }}" alt="users avatar"
                    class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer image-preview" height="90"
                    width="90" />
            @endif
            <div class="media-body mt-50">
                <h4>{{ isset($sitter) ? $sitter->name : trans('dashboard.general.image') }}</h4>
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
            <label for="full-name-column">{{ trans('dashboard.user.fullname') }} <span
                    class="text-danger">*</span></label>
            {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'full-name-column', 'placeholder' => trans('dashboard.user.fullname')]) !!}
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
            <label for="email-id-column">{{ trans('dashboard.general.identity_number') }}</label>
            {!! Form::text('identity_number', null, ['class' => 'form-control', 'id' => 'email-id-column', 'autocomplete' => 'off', 'placeholder' => trans('dashboard.general.identity_number')]) !!}
        </div>
    </div>

    <div class="col-12 city">
        <div class="form-group">
            <label>{{ trans('dashboard.city.city') }}
                <span class="text-danger">*</span>
            </label>
            {!! Form::select('city_id', $cities, Request::is('*/edit') ? optional($sitter->profile)->city_id : null, ['class' => 'select2 form-control', 'placeholder' => trans('dashboard.city.city')]) !!}
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label>{{ trans('dashboard.user.service_type') }} <span
                    class="text-danger">*</span></label>

                @foreach ($services as $key=>$service)
                 <label>{{ $service->name }}</label> <input type="checkbox" value="{{ $service->id }}"  {{ isset($sitter) && $sitter->services->contains($service->id)? 'checked':'' }}  name="services[{{ $key }}][service_id]">
                 <label>{{ trans("dashboard.sitter.{$service->translate('en')->name}") }}</label> <input type="text" value="{{ isset($sitter) && $sitter->services->contains($service->id) ? (float)$sitter->user_services()->where('service_id',$service->id)->first()->price:'' }}" name="services[{{ $key }}][price]" />
                @endforeach

                {{--  {!! Form::select("services[]", $services, isset($sitter) ? $sitter->services : null, ['class' => 'select2 form-control','multiple' => 'multiple']) !!}  --}}


        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label>{{ trans('dashboard.user.features') }} <span
                    class="text-danger">*</span></label>



                {!! Form::select("features[]", $features, isset($sitter) ? $sitter->features : null, ['class' => 'select2 form-control','multiple' => 'multiple']) !!}


        </div>
    </div>

    {{--  <div class="col-12">  --}}
         <div class="col-md-10">
        <label class="form-label" for="modern-image">
            {{ trans('dashboard.sitter.certificates') }}
        </label>

            <div class="custom-file">
                <input type="file" name="certificates" class="custom-file-input" id="certificates" onchange="readUrl(this,'certificates_preview')">
                <label class="custom-file-label" for="certificates">Choose file</label>
            </div>
        </div>
        <div class="col-md-1">
            @if (isset($sitter) && $sitter->certificates)
            <img src="{{ $sitter->certificates }}" class="img-thumbnail certificates_preview" style="width: 100%; height: 100px;">
            @else
            <img src="{{ asset('dashboardAssets/images/backgrounds/placeholder_image.png') }}" class="img-thumbnail certificates_preview" style="width: 100%; height: 100px;">
            @endif
        </div>
    {{--  </div>  --}}


    <div class="form-group col-12">
        <label class="form-label" for="modern-active_state">
            {{ trans('dashboard.user.active_state') }}
        </label>
        <div class="demo-inline-spacing">
            <div class="custom-control custom-control-success custom-radio col-md-6">
                {!! Form::radio('is_active', 1, !isset($sitter) || (isset($sitter) && $sitter->is_active) ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'is_active']) !!}
                <label class="custom-control-label" for="is_active">{!! trans('dashboard.user.active') !!}</label>
            </div>
            <div class="custom-control custom-control-danger custom-radio">
                {!! Form::radio('is_active', 0, isset($sitter) && !$sitter->is_active ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'is_deactive']) !!}
                <label class="custom-control-label" for="is_deactive">{!! trans('dashboard.user.not_active') !!}</label>
            </div>

        </div>
    </div>

    <div class="form-group col-12">
        <label class="form-label" for="modern-ban_state">
            {{ trans('dashboard.user.ban_state') }}
        </label>
        <div class="demo-inline-spacing">
            <div class="custom-control custom-control-success custom-radio col-md-6">
                {!! Form::radio('is_ban', 1, !isset($sitter) || (isset($sitter) && $sitter->is_ban) ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'is_ban']) !!}
                <label class="custom-control-label" for="is_ban">{!! trans('dashboard.user.ban') !!}</label>
            </div>

            <div class="custom-control custom-control-danger custom-radio">
                {!! Form::radio('is_ban', 0, isset($sitter) && !$sitter->is_ban ? 'checked' : null, ['class' => 'custom-control-input', 'id' => 'is_not_ban']) !!}
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
<script>
	{{--  $("input[type='checkbox']").change(function(){

        console.log("checkBox with id " + $(this).attr("id") + " is " + $(this).is(":checked"))
      })  --}}
</script>
{{--  <script>
    $(document).ready(function () {
        var counter = 0,
            myid = 1;
            counter =  @isset($category) {!! $category->features->count() !!} @else 1 @endisset;
        $(document).on("click", '.add', function () {
            counter++;
            myid++;
            $(this).parent().parent().parent().append([
                `<div class="row col-12">
                    <label class="font-medium-1 col-md-2">{{ trans('dashboard.feature.feature') }} <span class="text-danger">*</span></label>
                    <div class="col-md-4">
                        {!! Form::select("features[` + myid + `][feature_id]", $features, isset($category) ? $category->features : null, ['class' => 'select2 form-control']) !!}
                    </div>
                    <div class="col-md-4">
                        {!! Form::number('features[` + myid + `][ordering]', null, ['class' => 'form-control', 'id' => 'ordering-column', 'placeholder' => trans('dashboard.frontage.ordering')]) !!}
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-icon btn-success mr-1 mb-1 waves-effect waves-light add"><i class="icofont-plus"></i></button>
                        <button type="button" class="btn btn-icon btn-danger mr-1 mb-1 waves-effect waves-light minus"><i class="icofont-bin"></i></button>
                    </div>
                </div>`
            ].join(''));
            $('.add').show();
            // $('.dod').children().last().find('.add').hide();
            // $(".minus").hide();

            $('.dod').children().last().find('.minus').show();
            // $(".dod").children().find(".minus").show();

            // if (counter > 1) {
            //     $('.dod').find('.flex-div').addClass('bbb');
            // } else {
            //     $('.dod').find('.flex-div').removeClass('bbb');
            // }
            if(counter > 1){
                $(".kkk").find('.minus').show();
            }

            console.log(counter);
        })

        $(document).on("click", '.minus', function () {
            counter--;
            if (counter === 1) {
                $('.dod').find('.flex-div').addClass('kkk');
            } else {
                $('.dod').find('.flex-div').removeClass('kkk');
            }
            $(this).parent().parent().remove();
            $('.dod').children().last().find('.minus').show();
            // $('.dod').children().last().find('.add').hide();
            $(".kkk").find('.minus').hide();
            $(".kkk").find('.add').show();
            console.log(counter);
        });
        console.log(counter);
        $('.dod').children().find('.minus').show();
        if(counter > 1){
            $(".kkk").find('.minus').show();
        }
    });
</script>  --}}
@endsection
