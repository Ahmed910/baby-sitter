@extends('dashboard.layout.layout')

@section('content')
<div class="row">
	<div class="col-md-3 mb-2 mb-md-0">
		<ul class="nav nav-pills flex-column mt-md-0 mt-1">
			<li class="nav-item">
				<a class="nav-link d-flex py-75 active" id="account-pill-general" data-toggle="pill" href="#account-vertical-general" aria-expanded="true">
					<i class="feather icon-globe mr-50 font-medium-3"></i>
					{!! trans('dashboard.user.account') !!}
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link d-flex py-75" id="account-pill-password" data-toggle="pill" href="#account-vertical-password" aria-expanded="false">
					<i class="feather icon-lock mr-50 font-medium-3"></i>
					{!! trans('dashboard.user.update_password') !!}
				</a>
			</li>
			{{-- <li class="nav-item">
				<a class="nav-link d-flex py-75" id="account-pill-social" data-toggle="pill" href="#account-vertical-social" aria-expanded="false">
					<i class="feather icon-camera mr-50 font-medium-3"></i>
					{!! trans('dashboard.social.social') !!}
				</a>
			</li> --}}
		</ul>
	</div>
	<div class="col-md-9">
		<div class="card-content">
			<div class="card-body">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane active" id="account-vertical-general" aria-labelledby="account-pill-general" aria-expanded="true">
						<div class="card">
							<div class="card-body">

								{!! Form::model(auth()->user(),['action' => 'Dashboard\ProfileController@store' , 'method' => 'POST' , 'files' => true ]) !!}
								<div class="media">
									<a href="javascript: void(0);">
										<img src="{{ auth()->user()->avatar }}" class="rounded mr-75 image-preview" alt="profile image" height="64" width="64">
									</a>
									<div class="media-body mt-75">
										<div class="col-12 px-0 d-flex flex-sm-row flex-column justify-content-start">
											<label class="btn btn-sm btn-primary ml-50 mb-50 mb-sm-0 cursor-pointer font-medium-1" for="account-upload">{{ trans('dashboard.general.change_image') }}</label>
											<input type="file" name="image" id="account-upload" hidden onchange="readUrl(this)">
											{{-- <button class="btn btn-sm btn-outline-warning ml-50">Reset</button> --}}
										</div>
										{{-- <p class="text-muted ml-75 mt-50"><small>Allowed JPG, GIF or PNG. Max
										size of
										800kB</small></p> --}}
									</div>
								</div>
								<hr>
								<div class="form-group row">
									<label class="col-form-label col-lg-2">{{ trans('dashboard.user.fullname') }} <span class="text-danger font-size-lg">*</span></label>
									<div class="col-lg-4">
										{!! Form::text('name', null , ['class' => 'form-control' , 'placeholder' => trans('dashboard.user.fullname')]) !!}
									</div>
									<label class="col-form-label col-lg-2">{{ trans('dashboard.general.email') }} <span class="text-danger">*</span></label>
									<div class="col-lg-4">
										{!! Form::email('email', null, ['class' => 'form-control' , 'placeholder' => trans('dashboard.general.email')]) !!}
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2">{{ trans('dashboard.general.phone') }} <span class="text-danger">*</span></label>
									<div class="col-lg-10">
										{!! Form::number('phone', null, ['class' => 'form-control' , 'placeholder' => trans('dashboard.general.phone')]) !!}
									</div>
								</div>

								<div class="form-group row">
									<label class="col-form-label col-lg-2">{{ trans('dashboard.user.gender') }} <span class="text-danger">*</span></label>
									<div class="col-md-10">
										<div class="row">
											<div class="vs-radio-con vs-radio-success col-md-7">
												{!! Form::radio('gender', "male", auth()->user()->gender == 'male' ? 'checked' : null) !!}
												<span class="vs-radio">
													<span class="vs-radio--border"></span>
													<span class="vs-radio--circle"></span>
												</span>
												<span class="">{{ trans('dashboard.user.male') }}</span>

											</div>
											<div class="vs-radio-con vs-radio-success">
												{!! Form::radio('gender', "female", auth()->user()->gender == 'female' ? 'checked' : null) !!}
												<span class="vs-radio">
													<span class="vs-radio--border"></span>
													<span class="vs-radio--circle"></span>
												</span>
												<span class="">{{ trans('dashboard.user.female') }}</span>
											</div>
										</div>
									</div>
								</div>

								{{-- <div class="form-group row">
									<label class="label-control col-md-2">{{ trans('dashboard.country.nationality') }} </label>
									<div class="col-md-10">
										{!! Form::select("country_id",$countries, null, ['class' => 'form-control select-search' , 'data-placeholder' => trans('dashboard.country.nationality')]) !!}
									</div>
								</div>

								<div class="form-group row">
									<label class="label-control col-md-2">{{ trans('dashboard.city.city') }} </label>
									<div class="col-md-10">
										{!! Form::select("city_id",$cities, null, ['class' => 'form-control select-search' , 'data-placeholder' => trans('dashboard.city.city')]) !!}
									</div>
								</div> --}}

								<div class="text-right">
									<button type="submit" class="btn btn-primary">{{ trans('dashboard.general.edit') }} <i class="icon-paperplane ml-2"></i></button>
								</div>
								{!! Form::close() !!}
							</div>
						</div>
					</div>
					<div class="tab-pane fade " id="account-vertical-password" role="tabpanel" aria-labelledby="account-pill-password" aria-expanded="false">
						<div class="card">
							<div class="card-body">
								{!! Form::open(['action' => 'Dashboard\ProfileController@updatePassword' , 'method' => 'POST' , 'files' => true ]) !!}
								<div class="form-group row">
									<label class="col-form-label col-lg-2">{{ trans('dashboard.general.old_password') }} <span class="text-danger">*</span></label>
									<div class="col-lg-10">
										{!! Form::password('old_password', ['class' => 'form-control' , 'placeholder' => trans('dashboard.general.old_password')]) !!}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-2">{{ trans('dashboard.general.password') }} <span class="text-danger">*</span></label>
									<div class="col-lg-10">
										{!! Form::password('password', ['class' => 'form-control' , 'placeholder' => trans('dashboard.general.password')]) !!}
									</div>
								</div>
								<div class="form-group row">
									<label class="col-form-label col-lg-2">{{ trans('dashboard.general.password_confirmation') }} <span class="text-danger">*</span></label>
									<div class="col-lg-10">
										{!! Form::password('password_confirmation', ['class' => 'form-control' , 'placeholder' => trans('dashboard.general.password_confirmation')]) !!}
									</div>
								</div>

								<div class="text-right">
									<button type="submit" class="btn btn-primary">{{ trans('dashboard.general.edit') }} <i class="icon-pencil7 ml-2"></i></button>
								</div>
								{!! Form::close() !!}

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('current',auth()->user()->fullname)
