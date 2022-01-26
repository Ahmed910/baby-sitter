@section('vendor_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/vendors/css/charts/apexcharts.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/vendors/css/pickers/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/vendors/css/pickers/pickadate/pickadate.css">

@endsection

@section('page_styles')
    {{--  <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/{{ LaravelLocalization::getCurrentLocaleDirection() }}/css/pages/dashboard-analytics.css">  --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/css/{{ LaravelLocalization::getCurrentLocaleDirection() }}/plugins/forms/pickers/form-flat-pickr.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/css/{{ LaravelLocalization::getCurrentLocaleDirection() }}/plugins/charts/chart-apex.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboardAssets') }}/css/{{ LaravelLocalization::getCurrentLocaleDirection() }}/pages/card-analytics.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
@endsection

@section('vendor_scripts')
<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('dashboardAssets') }}/vendors/js/charts/chart.min.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/charts/apexcharts.min.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/charts/echarts/echarts.min.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/pickers/flatpickr/flatpickr.min.js"></script>

<script src="{{ asset('dashboardAssets') }}/vendors/js/pickers/pickadate/picker.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/pickers/pickadate/picker.date.js"></script>
<!-- END: Page Vendor JS-->
@endsection

@section('page_scripts')
    <script src="{{ asset('dashboardAssets') }}/js/custom/higri_date.js"></script>
    <script src="{{ asset('dashboardAssets') }}/js/scripts/charts/chart-chartjs.js"></script>
<!-- BEGIN: Page JS-->
{{--  <script src="{{ asset('dashboardAssets') }}/js/scripts/cards/card-statistics.js"></script>
<script src="{{ asset('dashboardAssets') }}/js/scripts/charts/chart-apex.js"></script>
<script src="{{ asset('dashboardAssets') }}/js/scripts/charts/chart-echart.js"></script>  --}}
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
{{--  @include('dashboard.home.chart.order_chart')  --}}
@include('dashboard.home.chart.client')
<!-- END: Page JS-->

<script>
document.addEventListener('DOMContentLoaded', function() {
    ClientChart.init();
});

$(function(){
    $('.expire_date').pickadate({
        format: 'mm/dd/yyyy'
    });

    clock({!! json_encode(trans('dashboard.months')) !!}, {!! json_encode(trans('dashboard.days')) !!});

    if (feather) {
        feather.replace({
            width: 14,
            height: 14
        });
    }
});
</script>
@endsection
