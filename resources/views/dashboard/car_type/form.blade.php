<div class="bs-stepper-header">
	<div class="step" data-target="#step_locales">
		<button type="button" class="step-trigger">
            <span class="bs-stepper-box">
				<i data-feather="flag" class="font-medium-3"></i>
			</span>
			<span class="bs-stepper-label">
				<span class="bs-stepper-title">{!! trans('dashboard.general.locales') !!}</span>
				{{-- <span class="bs-stepper-subtitle">Setup Account Details</span> --}}
			</span>
		</button>
	</div>


	{{-- <div class="line">
		<i data-feather="chevron-right" class="font-medium-2"></i>
	</div> --}}
</div>

<div class="bs-stepper-content">
	<div id="step_locales" class="content">
		<div class="content-header">
			<h5 class="mb-0">{!! trans('dashboard.general.locales') !!}</h5>
		</div>

        <div class="row">
			@foreach (config('translatable.locales') as $locale)
			<div class="form-group col-md-6">
				<label class="form-label" for="modern-{{ $locale }}">{{ trans('dashboard.'.$locale.'.car_type') }} <span class="text-danger">*</span></label>
				{!! Form::text($locale."[name]", isset($car_type) ? $car_type->translate($locale)->name : null, ['class' => 'form-control' , 'placeholder' => trans('dashboard.'.$locale.'.car_type'),'id' => "modern-{{ $locale }}"]) !!}
			</div>
			@endforeach
		</div>
		<div class="d-flex justify-content-end">
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
