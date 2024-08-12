<!-- BEGIN: main -->
<div id="chart"></div>
<h2 class="text-center">{LANG.top_10}</h2>
<script src="{LINK_CHART}/js/apexcharts.js"></script>
<script>
    var options = {
        chart: {
            height: 350,
            type: "bar",
            stacked: false,
            toolbar: {
                show: false
            },
        },
        dataLabels: {
            enabled: true
        },
        plotOptions: {
            bar: {
                distributed: true
            }
        },
        colors: ["#3dabfa", "#00de78", "#a9a7c3", "#f39c12", "#ff5733"], // Các màu cho từng cột
        series: [
            {
                name: "{LANG.reward_count}",
                data: [{TOTAL_APPROVED}, {TOTAL_REJECTED}, {TOTAL_PROPOSED_CURRENT_MONTH}, {TOTAL_PROPOSED_CURRENT_YEAR}, {TOTAL}]
            }
        ],
        stroke: {
            width: 2,
            curve: "smooth"
        },
        xaxis: {
            categories: ["{LANG.total_approved}", "{LANG.total_rejected}", " {LANG.total_month} {CURRENT_MONTH}", " {LANG.total_year} {CURRENT_YEAR}", "{LANG.total_proposed}"],
            labels: {
                rotate: 0,
                show: false
            }
        },
        markers: {
            size: 6
        },
        legend: {
            position: "bottom",
            horizontalAlign: "center",
            floating: false,
            itemMargin: {
                horizontal: 10,
                vertical: 5
            },
            labels: {
                colors: ["#3dabfa", "#00de78", "#a9a7c3", "#f39c12", "#ff5733"],
                maxWidth: 100
            }
        }
    };
    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>
<hr>
<table class="table table-striped table-hover table-bordered">
    <thead>
    <tr>
        <th class="text-center">{LANG.stt}</th>
        <th class="text-center">{LANG.employed}</th>
        <th class="text-center">{LANG.department}</th>
        <th class="text-center">{LANG.number_lau}</th>
    </tr>
    </thead>
    <tbody>
    <!-- BEGIN: loop -->
    <tr>
        <td class="text-center">{ROW.stt}</td>
        <td class="text-center">{ROW.employee_name}</td>
        <td class="text-center">{ROW.employed_department}</td>
        <td class="text-center">{ROW.reward_count}</td>
    </tr>
    <!-- END: loop -->
    </tbody>
</table>
<!-- END: main -->
