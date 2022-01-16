<div class="row">
    <!-- users edit media object start -->
    <div class="col-12">
        <div class="media mb-2">
            @if (isset($sitter_worker) && $sitter_worker->image)
                <img src="{{ $sitter_worker->image }}" alt="{{ $sitter_worker->name }}"
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
            <label for="max_num_of_child_care-column">{{ trans('dashboard.sitter_worker.max_num_of_child_care') }} <span
                    class="text-danger">*</span></label>
            {!! Form::number('max_num_of_child_care', null, ['class' => 'form-control', 'id' => 'max_num_of_child_care-column', 'placeholder' => trans('dashboard.sitter_worker.max_num_of_child_care')]) !!}
        </div>
    </div>


    <div class="col-md-6 col-12">
        <div class="form-group">
            <label for="level-experience-column">{{ trans('dashboard.sitter_worker.level_experience') }} <span
                    class="text-danger">*</span></label>
            {{--  {!! Form::select('level_experience', ['entry_level'=>'entry_level','intermediate'=>'intermediate','mid_level'=>'mid_level','senior'=>'senior'], ['class' => 'select2 form-control', 'id' => 'level-experience-column']) !!}  --}}
            <select name="level_experience" class="select2 form-control">
                <option value=""></option>
                <option value="entry_level" {{ (isset($sitter_worker) && $sitter_worker->level_experience == 'entry_level') ? 'selected':''}}>Entry Level</option>
                <option value="intermediate" {{ (isset($sitter_worker) && $sitter_worker->level_experience == 'intermediate') ? 'selected':''}}>Intermediate</option>
                <option value="mid_level" {{ (isset($sitter_worker) && $sitter_worker->level_experience == 'mid_level') ? 'selected':''}}>Mid Level</option>
                <option value="senior" {{ (isset($sitter_worker) && $sitter_worker->level_experience == 'senior') ? 'selected':''}}>Senior</option>
            </select>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="form-group">
            <label for="max_num_of_child_care-column">{{ trans('dashboard.sitter_worker.level_percentage') }} <span
                    class="text-danger">*</span></label>
            {!! Form::number('level_percentage',null, ['class' => 'form-control', 'id' => 'level_percentage-column', 'placeholder' => trans('dashboard.sitter_worker.level_percentage')]) !!}
        </div>
    </div>

    <div class=" col-12">
        <div class="form-group">
            <label for="level-experience-column">{{ trans('dashboard.sitter_worker.center') }} <span
                    class="text-danger">*</span></label>
            {!! Form::select('center_id', $centers, isset($sitter_worker) ? $sitter_worker->center_id:null  ,['class' => 'select2 form-control']) !!}

        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="total_num_of_student-column">{{ trans('dashboard.sitter_worker.total_num_of_student') }} <span
                    class="text-danger">*</span></label>
            {!! Form::number('total_num_of_student', null, ['class' => 'form-control', 'id' => 'total_num_of_student-column', 'placeholder' => trans('dashboard.sitter_worker.total_num_of_student')]) !!}
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
