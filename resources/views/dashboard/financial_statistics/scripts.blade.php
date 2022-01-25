@section('vendor_scripts')
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/pdfmake.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/vfs_fonts.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/datatables.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/datatables.buttons.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/buttons.html5.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/buttons.print.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/buttons.bootstrap.min.js"></script>
    <script src="{{ asset('dashboardAssets') }}/vendors/js/tables/datatable/datatables.bootstrap4.min.js"></script>
@endsection
@section('page_scripts')
    <script src="{{ asset('dashboardAssets') }}/js/scripts/datatables/datatable.js"></script>
@endsection

@section('vendor_scripts')
<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('dashboardAssets') }}/vendors/js/pickers/pickadate/picker.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/pickers/pickadate/picker.date.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/charts/apexcharts.min.js"></script>
<script src="{{ asset('dashboardAssets') }}/vendors/js/charts/echarts/echarts.min.js"></script>
<!-- END: Page Vendor JS-->
@endsection

@section('page_scripts')
<!-- BEGIN: Page JS-->
<script src="{{ asset('dashboardAssets') }}/js/scripts/cards/card-statistics.js"></script>
<script src="{{ asset('dashboardAssets') }}/js/scripts/charts/chart-apex.js"></script>
<script src="{{ asset('dashboardAssets') }}/js/scripts/charts/chart-echart.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@include('dashboard.reports.chart.client')
<!-- END: Page JS-->
<script>
document.addEventListener('DOMContentLoaded', function() {
    ClientChart.init();
});

$(function(){
    $('.expire_date').pickadate({
        format: 'mm/dd/yyyy'
    });
});



{{--var new_page = 1;--}}
{{--var last_new_page = {{ $new_orders->lastPage() }};--}}
{{--var current_page = 1;--}}
{{--var last_current_page = {{ $current_orders->lastPage() }};--}}
{{--var finished_page = 1;--}}
{{--var last_finished_page = {{ $finished_orders->lastPage() }};--}}
{{--$(function(){--}}
{{--    $('.new_orders').scroll(function() {--}}
{{--        var container = $(this);--}}
{{--        var scrollHeight = $('.new_orders_scroll').height();--}}
{{--        var height = container.height();--}}
{{--        if(container.scrollTop() + height >= scrollHeight && new_page <= last_new_page) {--}}
{{--            new_page++;--}}
{{--            loadMoreData(new_page,".new_orders_row","{{ LaravelLocalization::localizeUrl('dashboard/ajax/get_new_orders') }}");--}}
{{--        }--}}
{{--    });--}}
{{--    $('.current_orders').scroll(function() {--}}
{{--        var container = $(this);--}}
{{--        var scrollHeight = $('.current_orders_scroll').height();--}}
{{--        var height = container.height();--}}
{{--        if(container.scrollTop() + height >= scrollHeight  && current_page <= last_current_page) {--}}
{{--            current_page++;--}}
{{--            loadMoreData(current_page,".current_orders_row","{{ LaravelLocalization::localizeUrl('dashboard/ajax/get_current_orders') }}");--}}
{{--        }--}}
{{--    });--}}
{{--    $('.finished_orders').scroll(function() {--}}
{{--        var container = $(this);--}}
{{--        var scrollHeight = $('.finished_orders_scroll').height();--}}
{{--        var height = container.height();--}}
{{--        if(container.scrollTop() + height >= scrollHeight  && finished_page <= last_finished_page) {--}}
{{--            finished_page++;--}}
{{--            loadMoreData(finished_page,".finished_orders_row","{{ LaravelLocalization::localizeUrl('dashboard/ajax/get_finished_orders') }}");--}}
{{--        }--}}
{{--    });--}}
{{--})--}}

function loadMoreData(page,append_place,route){
  $.ajax(
        {
            url: route + '?page=' + page,
            global:false,
            type: "get",
            beforeSend: function()
            {
                $('.ajax-load').show();
            }
        })
        .done(function(data)
        {
            if(data.view == ""){
                // $('.ajax-load').html("No more records found");
                $('.ajax-load').hide();
                return;
            }
            $('.ajax-load').hide();
            $(append_place).append(data.view);
        })
        .fail(function(jqXHR, ajaxOptions, thrownError)
        {
              alert('server not responding...');
        });
}
</script>
@endsection
