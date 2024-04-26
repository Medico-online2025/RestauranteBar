<script>
    var cardColor, labelColor, headingColor, borderColor, legendColor;
    if (isDarkStyle) {
        cardColor = config.colors_dark.cardColor;
        labelColor = config.colors_dark.textMuted;
        legendColor = config.colors_dark.bodyColor;
        headingColor = config.colors_dark.headingColor;
        borderColor = config.colors_dark.borderColor;
    } else {
        cardColor = config.colors.cardColor;
        labelColor = config.colors.textMuted;
        legendColor = config.colors.bodyColor;
        headingColor = config.colors.headingColor;
        borderColor = config.colors.borderColor;
    }

    // Donut Chart Colors
    const chartColors = {
        donut: {
        series1: config.colors.success,
        series2: '#28c76fb3',
        series3: '#28c76f80',
        series4: config.colors_label.success
        }
    };

    // Bills
    function dash_bills()
    {
        $.ajax({
            url         : "{{ route('admin.dash_bills') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}"
            },
            success     : function(r){
                if(!r.status)
                {
                    toast_msg(r.msg, r.type);
                    return;
                }

                const expensesRadialChartEl = document.querySelector('#expensesChart'),
                expensesRadialChartConfig = {
                chart: {
                    height: 145,
                    sparkline: {
                    enabled: true
                    },
                    parentHeightOffset: 0,
                    type: 'radialBar'
                },
                colors: [config.colors.warning],
                series: [r.porcentaje],
                plotOptions: {
                    radialBar: {
                    offsetY: 0,
                    startAngle: -90,
                    endAngle: 90,
                    hollow: {
                        size: '65%'
                    },
                    track: {
                        strokeWidth: '45%',
                        background: borderColor
                    },
                    dataLabels: {
                        name: {
                        show: false
                        },
                        value: {
                        fontSize: '22px',
                        color: headingColor,
                        fontWeight: 600,
                        offsetY: -5
                        }
                    }
                    }
                },
                grid: {
                    show: false,
                    padding: {
                    bottom: 5
                    }
                },
                stroke: {
                    lineCap: 'round'
                },
                labels: ['Progress'],
                responsive: [
                    {
                    breakpoint: 1442,
                    options: {
                        chart: {
                        height: 120
                        },
                        plotOptions: {
                        radialBar: {
                            dataLabels: {
                            value: {
                                fontSize: '18px'
                            }
                            },
                            hollow: {
                            size: '60%'
                            }
                        }
                        }
                    }
                    },
                    {
                    breakpoint: 1025,
                    options: {
                        chart: {
                        height: 136
                        },
                        plotOptions: {
                        radialBar: {
                            hollow: {
                            size: '65%'
                            },
                            dataLabels: {
                            value: {
                                fontSize: '18px'
                            }
                            }
                        }
                        }
                    }
                    },
                    {
                    breakpoint: 769,
                    options: {
                        chart: {
                        height: 120
                        },
                        plotOptions: {
                        radialBar: {
                            hollow: {
                            size: '55%'
                            }
                        }
                        }
                    }
                    },
                    {
                    breakpoint: 426,
                    options: {
                        chart: {
                        height: 145
                        },
                        plotOptions: {
                        radialBar: {
                            hollow: {
                            size: '65%'
                            }
                        }
                        },
                        dataLabels: {
                        value: {
                            offsetY: 0
                        }
                        }
                    }
                    },
                    {
                    breakpoint: 376,
                    options: {
                        chart: {
                        height: 105
                        },
                        plotOptions: {
                        radialBar: {
                            hollow: {
                            size: '60%'
                            }
                        }
                        }
                    }
                    }
                ]
                };

                if (typeof expensesRadialChartEl !== undefined && expensesRadialChartEl !== null) {
                    const expensesRadialChart = new ApexCharts(expensesRadialChartEl, expensesRadialChartConfig);
                    expensesRadialChart.render();
                }
            },
            dataType    : "json"
        });
    }
    
    function dash_profits()
    {
        $.ajax({
            url             : "{{ route('admin.dash_profits') }}",
            method          : "POST",
            data            : {
                '_token'    : "{{ csrf_token() }}"
            },
            success         : function(r){
                if(!r.status){
                    toast_msg(r.msg, r.type);
                    return;
                } 
                const profitLastMonthEl = document.querySelector('#profitLastMonth'),
                profitLastMonthConfig = {
                chart: {
                    height: 90,
                    type: 'line',
                    parentHeightOffset: 0,
                    toolbar: {
                    show: false
                    }
                },
                grid: {
                    borderColor: borderColor,
                    strokeDashArray: 6,
                    xaxis: {
                    lines: {
                        show: true,
                        colors: '#000'
                    }
                    },
                    yaxis: {
                    lines: {
                        show: false
                    }
                    },
                    padding: {
                    top: -18,
                    left: -4,
                    right: 7,
                    bottom: -10
                    }
                },
                colors: [config.colors.info],
                stroke: {
                    width: 2
                },
                series: [
                    {
                        data: r.graphics
                    }
                ],
                tooltip: {
                    shared: false,
                    intersect: true,
                    x: {
                    show: false
                    }
                },
                xaxis: {
                    labels: {
                    show: false
                    },
                    axisTicks: {
                    show: false
                    },
                    axisBorder: {
                    show: false
                    }
                },
                yaxis: {
                    labels: {
                    show: false
                    }
                },
                tooltip: {
                    enabled: false
                },
                markers: {
                    size: 3.5,
                    fillColor: config.colors.info,
                    strokeColors: 'transparent',
                    strokeWidth: 3.2,
                    discrete: [
                    {
                        seriesIndex: 0,
                        dataPointIndex: 5,
                        fillColor: cardColor,
                        strokeColor: config.colors.info,
                        size: 5,
                        shape: 'circle'
                    }
                    ],
                    hover: {
                    size: 5.5
                    }
                },
                responsive: [
                    {
                    breakpoint: 1442,
                    options: {
                        chart: {
                        height: 100
                        }
                    }
                    },
                    {
                    breakpoint: 1025,
                    options: {
                        chart: {
                        height: 86
                        }
                    }
                    },
                    {
                    breakpoint: 769,
                    options: {
                        chart: {
                        height: 93
                        }
                    }
                    }
                ]
                };
            if (typeof profitLastMonthEl !== undefined && profitLastMonthEl !== null) {
                const profitLastMonth = new ApexCharts(profitLastMonthEl, profitLastMonthConfig);
                profitLastMonth.render();
            }
            },
            dataType        : "json"
        });
    }

    function dash_clients()
    {
        $.ajax({
            url         : "{{ route('admin.dash_clients') }}",
            method      : "POST",
            data        : {
                '_token': "{{ csrf_token() }}"
            },
            success     : function(r){
                if(!r.status)
                {
                    toast_msg(r.msg);
                    return;
                }

                const generatedLeadsChartEl = document.querySelector('#generatedLeadsChart'),
                generatedLeadsChartConfig = {
                chart: {
                    height: 140,
                    width: 130,
                    parentHeightOffset: 0,
                    type: 'donut'
                },
                labels: r.names,
                series: r.quantitys,
                colors: [
                    chartColors.donut.series1,
                    chartColors.donut.series2,
                    chartColors.donut.series3,
                    chartColors.donut.series4
                ],
                stroke: {
                    width: 0
                },
                dataLabels: {
                    enabled: false,
                    formatter: function (val, opt) {
                    return parseInt(val) + '%';
                    }
                },
                legend: {
                    show: false
                },
                tooltip: {
                    theme: false
                },
                grid: {
                    padding: {
                    top: 15,
                    right: -20,
                    left: -20
                    }
                },
                states: {
                    hover: {
                    filter: {
                        type: 'none'
                    }
                    }
                },
                plotOptions: {
                    pie: {
                    donut: {
                        size: '70%',
                        labels: {
                        show: true,
                        value: {
                            fontSize: '1.375rem',
                            fontFamily: 'Public Sans',
                            color: headingColor,
                            fontWeight: 600,
                            offsetY: -15,
                            formatter: function (val) {
                            return parseInt(val) + '%';
                            }
                        },
                        name: {
                            offsetY: 20,
                            fontFamily: 'Public Sans'
                        },
                        total: {
                            show: true,
                            showAlways: true,
                            color: config.colors.success,
                            fontSize: '.8125rem',
                            label: 'Total',
                            fontFamily: 'Public Sans',
                            formatter: function (w) {
                            return r.total_quantity;
                            }
                        }
                        }
                    }
                    }
                },
                responsive: [
                    {
                    breakpoint: 1025,
                    options: {
                        chart: {
                        height: 172,
                        width: 160
                        }
                    }
                    },
                    {
                    breakpoint: 769,
                    options: {
                        chart: {
                        height: 178
                        }
                    }
                    },
                    {
                    breakpoint: 426,
                    options: {
                        chart: {
                        height: 147
                        }
                    }
                    }
                ]
                };
                if (typeof generatedLeadsChartEl !== undefined && generatedLeadsChartEl !== null) {
                    const generatedLeadsChart = new ApexCharts(generatedLeadsChartEl, generatedLeadsChartConfig);
                    generatedLeadsChart.render();
                }
            },
            dataType    : "json"
        });
    }

    function dash_incomes()
    {
        $.ajax({
            url             : "{{ route('admin.dash_incomes') }}",
            method          : "POST",
            data            : {
                '_token'    : "{{ csrf_token() }}"
            },
            success         : function(r){
                if(!r.status){
                    toast_msg(r.msg, r.type);
                    return;
                }

                const totalRevenueChartEl = document.querySelector('#totalRevenueChart'),
                totalRevenueChartOptions = {
                series: [
                    {
                    name: 'Ganancias',
                    data: r.ganancias
                    },
                    {
                    name: 'Gastos',
                    data: r.gastos
                    }
                ],
                chart: {
                    height: 348,
                    parentHeightOffset: 0,
                    stacked: true,
                    type: 'bar',
                    toolbar: { show: false }
                },
                tooltip: {
                    enabled: false
                },
                plotOptions: {
                    bar: {
                    horizontal: false,
                    columnWidth: '40%',
                    borderRadius: 10,
                    startingShape: 'rounded',
                    endingShape: 'rounded'
                    }
                },
                colors: [config.colors.primary, config.colors.warning],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 6,
                    lineCap: 'round',
                    colors: [cardColor]
                },
                legend: {
                    show: true,
                    horizontalAlign: 'left',
                    position: 'top',
                    fontFamily: 'Public Sans',
                    markers: {
                    height: 12,
                    width: 12,
                    radius: 12,
                    offsetX: -3,
                    offsetY: 2
                    },
                    labels: {
                    colors: legendColor
                    },
                    itemMargin: {
                    horizontal: 5
                    }
                },
                grid: {
                    show: false,
                    padding: {
                    bottom: -8,
                    top: 20
                    }
                },
                xaxis: {
                    categories: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                    labels: {
                    style: {
                        fontSize: '13px',
                        colors: labelColor,
                        fontFamily: 'Public Sans'
                    }
                    },
                    axisTicks: {
                    show: false
                    },
                    axisBorder: {
                    show: false
                    }
                },
                yaxis: {
                    labels: {
                    offsetX: -16,
                    style: {
                        fontSize: '13px',
                        colors: labelColor,
                        fontFamily: 'Public Sans'
                    }
                    },
                    min: -300,
                    max: 500,
                    tickAmount: 5
                },
                responsive: [
                    {
                    breakpoint: 1700,
                    options: {
                        plotOptions: {
                        bar: {
                            columnWidth: '43%'
                        }
                        }
                    }
                    },
                    {
                    breakpoint: 1441,
                    options: {
                        plotOptions: {
                        bar: {
                            columnWidth: '52%'
                        }
                        },
                        chart: {
                        height: 375
                        }
                    }
                    },
                    {
                    breakpoint: 1300,
                    options: {
                        plotOptions: {
                        bar: {
                            columnWidth: '62%'
                        }
                        }
                    }
                    },
                    {
                    breakpoint: 1025,
                    options: {
                        plotOptions: {
                        bar: {
                            columnWidth: '70%'
                        }
                        },
                        chart: {
                        height: 390
                        }
                    }
                    },
                    {
                    breakpoint: 991,
                    options: {
                        plotOptions: {
                        bar: {
                            columnWidth: '38%'
                        }
                        }
                    }
                    },
                    {
                    breakpoint: 850,
                    options: {
                        plotOptions: {
                        bar: {
                            columnWidth: '48%'
                        }
                        }
                    }
                    },
                    {
                    breakpoint: 449,
                    options: {
                        plotOptions: {
                        bar: {
                            columnWidth: '70%'
                        }
                        },
                        chart: {
                        height: 360
                        },
                        xaxis: {
                        labels: {
                            offsetY: -5
                        }
                        }
                    }
                    },
                    {
                    breakpoint: 394,
                    options: {
                        plotOptions: {
                        bar: {
                            columnWidth: '88%'
                        }
                        }
                    }
                    }
                ],
                states: {
                    hover: {
                    filter: {
                        type: 'none'
                    }
                    },
                    active: {
                    filter: {
                        type: 'none'
                    }
                    }
                }
                };
                if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
                    const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
                    totalRevenueChart.render();
                }
            },
            dataType        : "json"
        });
    }
    
    
    $(document).ready(function(){
        dash_profits();
        dash_bills();
        dash_clients();
        dash_incomes();
    });
</script>