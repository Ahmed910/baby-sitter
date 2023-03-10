<div class="row">
    <!-- users edit media object start -->
    <div class="col-12">
        <div class="media mb-2">
            @if (isset($client) && $client->image)
            <img src="{{ $client->avatar }}" alt="{{ $client->name }}" class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer image-preview" height="90" width="90" />
            @else
            <img src="{{ asset('dashboardAssets/images/backgrounds/placeholder_image.png') }}" alt="users avatar" class="user-avatar users-avatar-shadow rounded mr-2 my-25 cursor-pointer image-preview" height="90" width="90" />
            @endif
            <div class="media-body mt-50">
                <h4>{{ isset($client) ? $client->name : trans('dashboard.general.image') }}</h4>
                <div class="col-12 d-flex mt-1 px-0">
                    <label class="btn btn-primary mr-75 mb-0" for="change-picture">
                        <span class="d-none d-sm-block">Change</span>
                        <input class="form-control" type="file" id="change-picture" name="image" hidden accept="image/png, image/jpeg, image/jpg" onchange="readUrl(this)" />
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
            <label for="full-name-column">{{ trans('dashboard.user.fullname') }} <span class="text-danger">*</span></label>
            {!! Form::text('name', null , ['class' => 'form-control','id' => "full-name-column" , 'placeholder' => trans('dashboard.user.fullname')]) !!}
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="form-group">
            <label for="phone-column">{{ trans('dashboard.general.phone') }} <span class="text-danger">*</span></label>
            {!! Form::text('phone', null , ['class' => 'form-control','id' => "phone-column" , 'placeholder' => trans('dashboard.general.phone')]) !!}
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group">
            <div class="d-flex justify-content-between">
                <label for="login-password">{!! trans('dashboard.general.password') !!}</label>
            </div>
            <div class="input-group input-group-merge form-password-toggle">
                {!! Form::password('password', ['class' => 'form-control form-control-merge', "id" => "login-password",'placeholder' => "&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" ,"aria-describedby" =>
                "login-password", "tabindex" => "2"]) !!}
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
                {!! Form::password('password_confirmation', ['class' => 'form-control form-control-merge', "id" => "login-password_confirmation",'placeholder' => "&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                ,"aria-describedby" => "login-password_confirmation", "tabindex" => "2"]) !!}

                <div class="input-group-append">
                    <span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <div class="form-group">
            <label for="email-id-column">{{ trans('dashboard.general.email') }}</label>
            {!! Form::email('email', null, ['class' => 'form-control' ,"id" => "email-id-column", 'placeholder' => trans('dashboard.general.email')]) !!}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="email-id-column">{{ trans('dashboard.general.identity_number') }}</label>
            {!! Form::text('identity_number', null, ['class' => 'form-control' ,"id" => "email-id-column", 'autocomplete'=>'off' ,'placeholder' => trans('dashboard.general.identity_number')]) !!}
        </div>
    </div>

    <div class="col-12 city">
        <div class="form-group">
            <label>{{ trans('dashboard.city.city') }}
                <span class="text-danger">*</span>
            </label>
            {!! Form::select('city_id', $cities, Request::is('*/edit') ? optional($client->profile)->city_id :null, ['class' => 'select2 form-control', 'onchange' => 'getDistrictsByCity(this.value)' ,'placeholder' => trans('dashboard.city.city')]) !!}
        </div>
    </div>

    <div class="form-group col-12">
        <label class="form-label" for="modern-gender">
            {{ trans('dashboard.user.gender') }}
        </label>
        <div class="demo-inline-spacing">
            <div class="custom-control custom-control-success custom-radio col-md-6">
                {!! Form::radio('gender', 'male', !isset($client) || (isset($client) && $client->gender) ? 'checked' : null , ['class' => 'custom-control-input' , 'id' => 'male']) !!}
                <label class="custom-control-label" for="male">{!! trans('dashboard.user.male') !!}</label>
            </div>
            <div class="custom-control custom-control-danger custom-radio">
                {!! Form::radio('gender', 'female', isset($client) && !$client->gender ? 'checked' : null , ['class' => 'custom-control-input' , 'id' => 'female']) !!}
                <label class="custom-control-label" for="female">{!! trans('dashboard.user.female') !!}</label>
            </div>

        </div>
    </div>

    <div class="form-group col-12">
        <label class="form-label" for="modern-active_state">
            {{ trans('dashboard.user.active_state') }}
        </label>
        <div class="demo-inline-spacing">
            <div class="custom-control custom-control-success custom-radio col-md-6">
                {!! Form::radio('is_active', 1, !isset($client) || (isset($client) && $client->is_active) ? 'checked' : null , ['class' => 'custom-control-input' , 'id' => 'is_active']) !!}
                <label class="custom-control-label" for="is_active">{!! trans('dashboard.user.active') !!}</label>
            </div>
            <div class="custom-control custom-control-danger custom-radio">
                {!! Form::radio('is_active', 0, isset($client) && !$client->is_active ? 'checked' : null , ['class' => 'custom-control-input' , 'id' => 'is_deactive']) !!}
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
                {!! Form::radio('is_ban', 1, !isset($client) || (isset($client) && $client->is_ban) ? 'checked' : null , ['class' => 'custom-control-input' , 'id' => 'is_ban']) !!}
                <label class="custom-control-label" for="is_ban">{!! trans('dashboard.user.ban') !!}</label>
            </div>

            <div class="custom-control custom-control-danger custom-radio">
                {!! Form::radio('is_ban', 0, isset($client) && !$client->is_ban ? 'checked' : null , ['class' => 'custom-control-input' , 'id' => 'is_not_ban']) !!}
                <label class="custom-control-label" for="is_not_ban">{!! trans('dashboard.user.not_ban') !!}</label>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="ban_reason-column">{{ trans('dashboard.user.ban_reason') }}</label>
            {!! Form::textarea('ban_reason', null, ['class' => 'form-control' ,"id" => "ban_reason-column", 'placeholder' => trans('dashboard.user.ban_reason')]) !!}
        </div>
    </div>

    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">{{ $btnSubmit }}</button>
    </div>
</div>
