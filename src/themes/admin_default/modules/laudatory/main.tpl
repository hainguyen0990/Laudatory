<!-- BEGIN: main -->
<script src="{LINK_CHART}/js/apexcharts.js"></script>
<div class="main-container">
    <div class="notification">
        <div class="notification-header">{TOTAL}</div>
        <div class="notification-body">
            <!-- BEGIN: category -->
            <div class="category-section">
                <!-- BEGIN: department -->
                <div class="notification-box">
                    <span>{LANG.department}: {DEPARTMENT_NAME}</span>
                    <span>{LANG.tong_so_yeu_cau_de_xuat}: {TOTAL_REWARDS}</span>
                    <a href="{ROW.url_appove}" class="url-box">
                        <span>{LANG.category_laudatory}: {ROW.description}</span>
                        <span>{LANG.admin_id}: {ROW.name_header}</span>
                        <span>{LANG.time_proposed}: {ROW.time_proposed}</span>
                    </a>
                </div>
                <!-- END: department -->
            </div>
            <!-- END: category -->
        </div>
    </div>
</div>
<div id="chart"></div>
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
                data: [{TOTAL_APPROVED}, {TOTAL_REJECTED}, {TOTAL_PROPOSED_CURRENT_MONTH}, {TOTAL_PROPOSED_CURRENT_YEAR}, {TOTAL_TOTAL}]
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
<!-- END: main -->
