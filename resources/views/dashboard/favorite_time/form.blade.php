<div class="bs-stepper-header">
	<div class="step" data-target="#step_locales">
		<button type="button" class="step-trigger">
            <span class="bs-stepper-box">
				<i data-feather="settings" class="font-medium-3"></i>
			</span>
			<span class="bs-stepper-label">
				<span class="bs-stepper-title">{!! trans('dashboard.general.public_data') !!}</span>
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
			<h5 class="mb-0">{!! trans('dashboard.general.public_data') !!}</h5>
		</div>

        <div class="row">
            <div class="form-group col-md-12">
	            <label class="form-label" for="modern-available_day">{{ trans('dashboard.available_day.available_days') }} <span class="text-danger">*</span></label>
                <select name="time" class = 'select2 w-100'>
                    @for($i = 1; $i <= 24 ; $i++)
                    <option value="{{ $i.':00' }}">{{ $i.':00' }}</option>
                    @endfor
                </select>

                </div>
        </div>

        <div class="row">
	        <div class="form-group col-md-12">
	            <label class="form-label" for="modern-district">{{ trans('dashboard.district.district') }} <span class="text-danger">*</span></label>

				{!! Form::select('district_id', $districts, null , ['class' => 'select2 w-100','onchange' => 'getAvailableDaysByDistrict(this.value)' , 'placeholder' => trans('dashboard.district.district') , 'id' => 'modern-district']) !!}
	        </div>
	    </div>
        <?php  $day_keys = ['sat','sun','mon','tue','wed','thu','fri']; ?>
        <div class="row">
	        <div class="form-group col-md-12 available_day">
                <label class="form-label" for="modern-available_day">{{ trans('dashboard.available_day.available_day') }} <span class="text-danger">*</span></label>
                <select name="available_day_id" class = 'select2 form-control available_day_select w-100'>
                    <option value="">{{ trans('dashboard.available_day.available_day') }}</option>
                    @foreach ($available_days as $day)
                   
                    <option value="{{ $day->id }}" {{ (isset($favorite_time) && in_array($favorite_time->availableDay->day ,$day_keys)) ? 'selected':'' }}>{{ trans('dashboard.day_keys.'.$day->day) }}</option>
                    @endforeach
                </select>
	        </div>
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
	function getAvailableDaysByDistrict(district_id) {
		$.ajax({
			url: "{{ LaravelLocalization::localizeUrl('dashboard/ajax/get_available_days_by_district') }}/" + district_id,
			method: "GET",
			dataType: "json",
			success: function(data) {
				if (data['value'] == 1) {
					$('.available_day').html(data['view']);
				}
			}
		});
	}
</script>
@endsection
