<?php  $day_keys = ['sat','sun','mon','tue','wed','thu','fri']; ?>
   <div class="form-group">
        <label class="form-label" for="modern-available_day">{{ trans('dashboard.available_day.available_day') }} <span class="text-danger">*</span></label>
        <select name="available_day_id" class = 'select2 form-control available_day_select w-100'>
            <option value="">{{ trans('dashboard.available_day.available_day') }}</option>
            @foreach ($available_days as $day)
            <option value="{{ $day->id }}">{{ trans('dashboard.day_keys.'.$day->day) }}</option>
            @endforeach
        </select>

    </div>



<script>
    $('.available_day_select').select2();
</script>
