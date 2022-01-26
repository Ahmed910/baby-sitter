<script>
    var ClientChart = function() {
        var _componentEcharts = function() {
            if (typeof echarts == 'undefined') {
                console.warn('Warning - echarts.min.js is not loaded.');
                return;
            }
            // Define elements
            var client_chart_element = document.getElementById('client_chart');
            // Weekly statistics chart config
            if (client_chart_element) {
                // Initialize chart
                var client_chart = echarts.init(client_chart_element);
                // Options
                client_chart.setOption({
                    // Define colors
                    color: ['#EF5350', '#03A9F4' , '#4eeF24' , '#8abF2e', '#ff6600', '#3c28b2' , '#cc33ff'],
                    // Global text styles
                    textStyle: {
                        fontFamily: 'Cairo',
                        fontSize: 14
                    },
                    // Chart animation duration
                    animationDuration: 750,
                    // Setup grid
                    grid: {
                        left: 0,
                        right: 10,
                        top: 35,
                        bottom: 0,
                        containLabel: true
                    },
                    // Add legend
                    legend: {
                        data: [`{{ trans('dashboard.client.clients') }}`, `{{ trans('dashboard.sitter.sitters') }}`,`{{ trans('dashboard.center.centers') }}`],
                        itemHeight: 8,
                        itemGap: 20,
                        textStyle: {
                            padding: [0, 5],
                            color:"#ff6600"
                        }
                    },
                    // Add tooltip
                    tooltip: {
                        trigger: 'axis',
                        backgroundColor: 'rgba(0,0,0,0.75)',
                        padding: [10, 15],
                        textStyle: {
                            fontSize: 13,
                            fontFamily: 'Cairo',
                        },
                        axisPointer: {
                            type: 'shadow',
                            shadowStyle: {
                                color: 'rgba(0,0,0,0.025)'
                            }
                        }
                    },

                    // Horizontal axis
                    xAxis: [{
                        type: 'category',
                        data: @json($months_arr)
                        ,
                        axisLabel: {
                            color:"#ff6600"
                        },
                        axisLine: {
                            lineStyle: {
                                color:"#eee"
                            }
                        },
                        splitLine: {
                            show: false,
                            lineStyle: {
                                color: '#eee',
                                type: 'dashed'
                            }
                        }
                    }],

                    // Vertical axis
                    yAxis: [
                        {
                            type: 'value',
                            name: '{{ trans('dashboard.general.counter') }}',
                            axisTick: {
                                show: false
                            },
                            axisLabel: {
                                color:"#ff6600",
                                formatter: function (value, index) {
                                          return Math.abs(value) > 999 ? Math.sign(value)*((Math.abs(value)/1000).toFixed(1)) + 'k' : Math.sign(value)*Math.abs(value)
                                      },
                            },
                            axisLine: {
                                lineStyle: {
                                    color: '#eee'
                                }
                            },
                            splitLine: {
                                show: true,
                                lineStyle: {
                                    color: ['#eee']
                                }
                            },
                            splitArea: {
                                show: true,
                                areaStyle: {
                                    color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.015)']
                                }
                            }
                        }
                    ],

                    // Add series
                    series: [
                        {
                            name: `{{ trans('dashboard.client.clients') }}`,
                            type: 'bar',
                            smooth: true,
                            symbolSize: 7,
                            data: @json(array_reverse(array_values(array_intersect_key($client_analytics,array_flip($months_arr))))),
                            itemStyle: {
                                normal: {
                                    borderWidth: 2
                                }
                            }
                        },
                        {
                            name: `{{ trans('dashboard.sitter.sitters') }}`,
                            type: 'bar',
                            smooth: true,
                            symbolSize: 7,
                            data: @json(array_reverse(array_values(array_intersect_key($babysitter_analytics,array_flip($months_arr))))),
                            itemStyle: {
                                normal: {
                                    borderWidth: 2
                                }
                            }
                        },
                        {
                            name: `{{ trans('dashboard.center.centers') }}`,
                            type: 'bar',
                            smooth: true,
                            symbolSize: 7,
                            data: @json(array_reverse(array_values(array_intersect_key($childcenter_analytics,array_flip($months_arr))))),
                            itemStyle: {
                                normal: {
                                    borderWidth: 2
                                }
                            }
                        },

                    ]
                });

                //client_chart.on('click', function (e) {
                // printing data name in console
                //});
            }


            //
            // Resize client
            //

            // Resize function
            var triggerChartResize = function() {
                client_chart_element && client_chart.resize();
            };

            // On sidebar width change
            $(document).on('click', '.sidebar-control', function() {
                setTimeout(function () {
                    triggerChartResize();
                }, 0);
            });

            // On window resize
            var resizeCharts;
            window.onresize = function () {
                clearTimeout(resizeCharts);
                resizeCharts = setTimeout(function () {
                    triggerChartResize();
                }, 200);
            };
        };
        return {
            init: function() {
                _componentEcharts();
            }
        }
    }();
</script>
