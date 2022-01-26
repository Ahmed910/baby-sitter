<script>
    $(window).on("load", function() {

        var isRtl = $('html').attr('data-textdirection') === 'rtl',
          chartColors = {
            column: {
              series1: '#826af9',
              series2: '#d2b0ff',
              bg: '#f8d3ff'
            },
            success: {
              shade_100: '#7eefc7',
              shade_200: '#06774f'
            },
            donut: {
              series1: '#ffe700',
              series2: '#00d4bd',
              series3: '#826bf8',
              series4: '#2b9bf4',
              series5: '#FFA1A1'
            },
            area: {
              series3: '#a4f8cd',
              series2: '#60f2ca',
              series1: '#2bdac7'
            }
          };

        // Radialbar Chart
        // --------------------------------------------------------------------
        var radialBarChartEl = document.querySelector('#radialbar-chart'),
          radialBarChartConfig = {
            chart: {
              height: 350,
              type: 'radialBar'
            },
            colors: [chartColors.donut.series2,chartColors.donut.series1, chartColors.donut.series4],
            plotOptions: {
              radialBar: {
                size: 185,
                hollow: {
                  size: '25%'
                },
                track: {
                  margin: 15
                },
                dataLabels: {
                  name: {
                    fontSize: '1rem',
                    fontFamily: 'Montserrat'
                  },
                  value: {
                    fontSize: '1rem',
                    fontFamily: 'Montserrat'
                  },
                  total: {
                    show: true,
                    fontSize: '1rem',
                    label: '{!! trans('dashboard.booking_request.finished_requests') !!}',
                    formatter: function (w) {
                      return '{{ $finished_bookings_percent }} %';
                    }
                  }
                }
              }
            },
            stroke: {
              lineCap: 'round'
            },
            series: ['{{ $finished_bookings_percent }}','{{ $canceled_bookings_percent }}', '{{ $pending_bookings_percent }}'],
            labels: ['{!! trans('dashboard.booking_request.finished_requests') !!}' ,'{!! trans('dashboard.booking_request.canceled_requests') !!}','{!! trans('dashboard.booking_request.pending_requests') !!}']
          };
        if (typeof radialBarChartEl !== undefined && radialBarChartEl !== null) {
          var radialChart = new ApexCharts(radialBarChartEl, radialBarChartConfig);
          radialChart.render();
        }
    });
</script>
