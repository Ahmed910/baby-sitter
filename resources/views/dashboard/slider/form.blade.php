<div class="bs-stepper-header">

	<div class="step" data-target="#step_public_data">
		<button type="button" class="step-trigger">
			<span class="bs-stepper-box">
				<i data-feather="settings" class="font-medium-3"></i>
			</span>
			<span class="bs-stepper-label">
				<span class="bs-stepper-title">{{ trans('dashboard.general.public_data') }}</span>
				{{-- <span class="bs-stepper-subtitle">Setup Account Details</span> --}}
			</span>
		</button>
	</div>
	{{-- <div class="line">
		<i data-feather="chevron-right" class="font-medium-2"></i>
	</div> --}}
</div>

<div class="bs-stepper-content">


	<div id="step_public_data" class="content">
	    <div class="content-header">
	        <h5 class="mb-0">{{ trans('dashboard.general.public_data') }}</h5>
	    </div>



        <div class="row form-group col-12">
			<label class="form-label" for="modern-image">
				{{ trans('dashboard.general.file') }}
			</label>
			<div class="col-md-10">
				<div class="custom-file">
					<input type="file" value="{{ isset($slider) ?  $slider->file : '' }}" name="file" class="custom-file-input" id="file" onchange="readUrl(this)">
					<label class="custom-file-label" for="file">Choose file</label>
				</div>
			</div>
			{{--  <div class="col-md-1">
				@if (isset($slider))
				<img src="{{ $slider->image }}" class="img-thumbnail image-preview" style="width: 100%; height: 100px;">
				@else
				<img src="{{ asset('dashboardAssets/images/backgrounds/placeholder_image.png') }}" class="img-thumbnail image-preview" style="width: 100%; height: 100px;">
				@endif
			</div>  --}}
		</div>

        <div class="form-group col-12">
            <label class="form-label" for="modern-active_state">
                {{ trans('dashboard.user.active_state') }}
            </label>
        <div class="demo-inline-spacing">
            <div class="custom-control custom-control-success custom-radio col-md-6">
                {!! Form::radio('is_active', 1, !isset($slider) || (isset($slider) && $slider->is_active) ? 'checked' : null , ['class' => 'custom-control-input' , 'id' => 'is_active']) !!}
                <label class="custom-control-label" for="is_active">{!! trans('dashboard.user.active') !!}</label>
            </div>
            <div class="custom-control custom-control-danger custom-radio">
                {!! Form::radio('is_active', 0, isset($slider) && !$slider->is_active ? 'checked' : null , ['class' => 'custom-control-input' , 'id' => 'is_deactive']) !!}
                <label class="custom-control-label" for="is_deactive">{!! trans('dashboard.user.not_active') !!}</label>
            </div>
        </div>

        </div>
	    <div class="d-flex justify-content-between">

	        <button class="btn btn-primary btn-next" type="submit">
	            <span class="align-middle d-sm-inline-block d-none">{!! $btnSubmit !!}</span>
	            <i data-feather="arrow-right" class="align-middle ml-sm-25 ml-0"></i>
	        </button>
	    </div>
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
	$(window).on('load', function() {
		if (feather) {
			feather.replace({
				width: 14,
				height: 14
			});
		}
	})
</script>
@endsection
