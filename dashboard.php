<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>
<?php include('includes/db_connect.php');?>
<?php include('includes/fetch_dashboard.php');?>
<!-- ✅ โหลด Chart.js และ Plugin สำหรับแสดงตัวเลข -->
 
<!-- ✅ โหลด Chart.js และ Plugin สำหรับ 3D -->
<!-- ✅ โหลด Chart.js และ Plugin Datalabels -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="js/dashboard.js"></script>


<main id="main" class="main">
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item active"><a href="dashboard.php">Dashboard</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard" >
      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-8">
          <div class="row">
            <!-- Sales Card -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card shadow-sm p-3"style="border: 2px solid rgb(109, 109, 109) !important;">
                <div class="filter" data-chart="monthlyComparisonChart">
                <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start"><h6>Filter</h6></li>
                    <li><a class="dropdown-item filter-option" data-filter="month" data-chart="monthlyComparisonChart" href="#">This Month</a></li>
                    <li><a class="dropdown-item filter-option" data-filter="year" data-chart="monthlyComparisonChart" href="#">This Year</a></li>
                </ul>
            </div>
                    <h5 class="card-title">Total cost of all devices </h5>
                    <div class="d-flex align-items-center">
                            <div class="ps-3" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                            <h6 id="chartTitle" style="font-size: 24px; font-weight: bold; color: #34495e; margin-bottom: 10px;">
                            </h6>
                                <div style="width: 450px; height: 250px; background: #f4f4f4; padding: 10px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <canvas id="monthlyComparisonChart"></canvas>
                                </div>
                                <script>
                                            document.addEventListener("DOMContentLoaded", function () {
                                            var ctx = document.getElementById('monthlyComparisonChart').getContext('2d');
                                            var months = <?php echo json_encode($filtered_month_labels); ?>;
                                            var totalCostsCurrent = <?php echo json_encode(array_values($filtered_costs_current)); ?>;
                                            var totalCostsPrevious = <?php echo json_encode(array_values($filtered_costs_previous)); ?>;
                                            var totalCostCurrentYear = totalCostsCurrent.reduce((a, b) => a + (b || 0), 0);
                                            var totalCostPreviousYear = totalCostsPrevious.reduce((a, b) => a + (b || 0), 0);
                                            var labelsYear = ["<?php echo $previous_year; ?>", "<?php echo $current_year; ?>"];
                                            var chartData = {
                                                labels: months,
                                                datasets: [
                                                    {
                                                        label: "ปีปัจจุบัน (<?php echo $current_year; ?>)",
                                                        data: totalCostsCurrent,
                                                        borderColor: 'rgba(54, 162, 235, 1)',
                                                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                                        borderWidth: 3,
                                                        pointRadius: 1.5,
                                                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                                                        tension: 0.4,
                                                        fill: true
                                                    },
                                                    {
                                                        label: "ปีที่แล้ว (<?php echo $previous_year; ?>)",
                                                        data: totalCostsPrevious,
                                                        borderColor: 'rgba(255, 99, 132, 1)',
                                                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                                        borderWidth: 3,
                                                        pointRadius: 1.5,
                                                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                                                        tension: 0.4,
                                                        fill: true
                                                    }
                                                ]
                                            };
                                            var chartOptions = {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                scales: {
                                                    y: {
                                                        beginAtZero: true,
                                                        ticks: { callback: value => "฿" + value.toLocaleString() }
                                                    }
                                                },
                                                plugins: {
                                                    legend: { display: true },
                                                    tooltip: { enabled: true }
                                                }
                                            };

                                            var myChart = new Chart(ctx, {
                                                type: 'line',
                                                data: chartData,
                                                options: chartOptions
                                            });

                                            // ✅ แก้ไขโค้ดให้ใช้ `data-chart` เชื่อมโยงกับ `monthlyComparisonChart`
                                            document.querySelectorAll('.filter-option').forEach(item => {
                                                item.addEventListener('click', function (e) {
                                                    e.preventDefault();
                                                    var filterType = this.getAttribute('data-filter');
                                                    var chartId = this.getAttribute('data-chart'); // ตรวจสอบว่ากรองของกราฟไหน

                                                    if (chartId !== 'monthlyComparisonChart') return; // ✅ ป้องกันไม่ให้กระทบกราฟอื่น

                                                    if (filterType === 'month') {
                                                        document.getElementById('chartTitle').innerText = "";
                                                        myChart.config.type = 'line';
                                                        myChart.data.labels = months;
                                                        myChart.data.datasets[0].data = totalCostsCurrent;
                                                        myChart.data.datasets[1].data = totalCostsPrevious;
                                                        myChart.data.datasets[0].borderWidth = 3;
                                                        myChart.data.datasets[1].borderWidth = 3;
                                                        myChart.data.datasets[0].pointRadius = 1.5;
                                                        myChart.data.datasets[1].pointRadius = 1.5;
                                                    } 
                                                    else if (filterType === 'year') {
                                                        document.getElementById('chartTitle').innerText = "";
                                                        myChart.config.type = 'bar';
                                                        myChart.data.labels = labelsYear;
                                                        myChart.data.datasets[0].data = [totalCostPreviousYear, totalCostCurrentYear];
                                                        myChart.data.datasets[1].data = [totalCostPreviousYear, totalCostCurrentYear];
                                                        myChart.data.datasets[0].backgroundColor = 'rgba(54, 162, 235, 0.6)';
                                                        myChart.data.datasets[1].backgroundColor = 'rgba(255, 99, 132, 0.6)';
                                                        myChart.data.datasets[0].borderWidth = 1;
                                                        myChart.data.datasets[1].borderWidth = 1;
                                                        myChart.data.datasets[0].pointRadius = 1.5;
                                                        myChart.data.datasets[1].pointRadius = 1.5;
                                                    }

                                                    myChart.update();
                                                });
                                            });
                                        });
                                </script>
                            </div>
                        </div>                        
                    </div>
            </div>
            <!-- ✅ กล่องที่ 2 -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card shadow-sm p-3"style="border: 2px solid rgb(109, 109, 109) !important;">
                <div class="filter" data-chart="newBranchChart">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start"><h6>Filter</h6></li>
                        <li><a class="dropdown-item filter-option" data-filter="month" data-chart="newBranchChart" href="#">This Month</a></li>
                        <li><a class="dropdown-item filter-option" data-filter="year" data-chart="newBranchChart" href="#">This Year</a></li>
                    </ul>
                </div>
                    <h5 class="card-title">Total Cost of Sent to New Store</h5>
                    <div class="d-flex align-items-center">
                            <div class="ps-3" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                                <h6 id="chartTitle-newBranch"  style="font-size: 24px; font-weight: bold; color: #34495e; margin-bottom: 10px;">

                                </h6>
                                <div style="width: 450px; height: 250px; background: #f4f4f4; padding: 10px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <canvas id="newBranchChart"></canvas>
                                </div>
                                <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                        var ctx = document.getElementById('newBranchChart').getContext('2d');
                                        var labelsYear = ["<?php echo $previousYear; ?>", "<?php echo $currentYear; ?>"];
                                        var months = <?php echo json_encode($filteredMonths); ?>;
                                        var dataCurrent = <?php echo json_encode(array_values($filteredNewBranchCurrent)); ?>;
                                        var dataPrevious = <?php echo json_encode(array_values($filteredNewBranchPrevious)); ?>;
                                        var totalCostCurrentYear = <?php echo $totalCostCurrentYear; ?>;
                                        var totalCostPreviousYear = <?php echo $totalCostPreviousYear; ?>;
                                        var myChart2 = new Chart(ctx, {
                                            type: 'line',
                                            data: {
                                                labels: months,
                                                datasets: [
                                                    { 
                                                        label: "ปีปัจจุบัน (<?php echo $currentYear; ?>)", 
                                                        data: dataCurrent, 
                                                        borderColor: 'rgba(54, 162, 235, 1)', 
                                                        backgroundColor: 'rgba(54, 162, 235, 0.2)', 
                                                        borderWidth: 3, 
                                                        pointRadius: 1.5, 
                                                        tension: 0.4, 
                                                        fill: true,
                                                        spanGaps: false 
                                                    },
                                                    { 
                                                        label: "ปีที่แล้ว (<?php echo $previousYear; ?>)", 
                                                        data: dataPrevious, 
                                                        borderColor: 'rgba(255, 99, 132, 1)', 
                                                        backgroundColor: 'rgba(255, 99, 132, 0.2)', 
                                                        borderWidth: 3, 
                                                        pointRadius: 1.5, 
                                                        tension: 0.4, 
                                                        fill: true,
                                                        spanGaps: false 
                                                    }
                                                ]
                                            }
                                        });
                                        document.querySelectorAll('.filter-option').forEach(item => {
                                            item.addEventListener('click', function (e) {
                                                e.preventDefault();
                                                var filterType = this.getAttribute('data-filter');
                                                var chartId = this.getAttribute('data-chart'); // ดึง ID ของกราฟที่ต้องการเปลี่ยน
                                                if (chartId !== 'newBranchChart') return; // ✅ ป้องกันไม่ให้ไปเปลี่ยนกราฟอื่น
                                                if (filterType === 'month') {
                                                    document.getElementById('chartTitle-newBranch').innerText = "";
                                                    myChart2.config.type = 'line';
                                                    myChart2.data.labels = months;
                                                    myChart2.data.datasets[0].data = dataCurrent;
                                                    myChart2.data.datasets[1].data = dataPrevious;
                                                    myChart2.data.datasets[0].borderWidth = 3;
                                                    myChart2.data.datasets[1].borderWidth = 3;
                                                    myChart2.data.datasets[0].pointRadius = 1.5;
                                                    myChart2.data.datasets[1].pointRadius = 1.5;
                                                } 
                                                else if (filterType === 'year') {
                                                    document.getElementById('chartTitle-newBranch').innerText = "";
                                                    myChart2.config.type = 'bar';
                                                    myChart2.data.labels = labelsYear;
                                                    myChart2.data.datasets[0].data = [totalCostPreviousYear, totalCostCurrentYear];
                                                    myChart2.data.datasets[1].data = [totalCostPreviousYear, totalCostCurrentYear];
                                                    myChart2.data.datasets[0].backgroundColor = 'rgba(54, 162, 235, 0.6)';
                                                    myChart2.data.datasets[1].backgroundColor = 'rgba(255, 99, 132, 0.6)';
                                                    myChart2.data.datasets[0].borderWidth = 1;
                                                    myChart2.data.datasets[1].borderWidth = 1;
                                                    myChart2.data.datasets[0].pointRadius = 0;
                                                    myChart2.data.datasets[1].pointRadius = 0;
                                                }
                                                myChart2.update();
                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </div>                        
                    </div>
            </div>
            <!-- ✅ กล่องที่ 3 -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card shadow-sm p-3"style="border: 2px solid rgb(109, 109, 109) !important;">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow filter-menu-replace">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
                            <li><a class="dropdown-item filter-option" data-filter="month" data-chart="replaceChart" href="#">This Month</a></li>
                            <li><a class="dropdown-item filter-option" data-filter="year" data-chart="replaceChart" href="#">This Year</a></li>

                        </ul>
                    </div>
                    <h5 class="card-title">Top Replacements </h5>
                    <div class="d-flex align-items-center">
                            <div class="ps-3" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                            <h6 id="chartTitle-replace" style="font-size: 24px; font-weight: bold; color: #34495e; margin-bottom: 10px;">
                                </h6>
                                <div style="width: 450px; height: 250px; background: #f4f4f4; padding: 10px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <canvas id="replaceChart"></canvas>
                                </div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        var ctx = document.getElementById('replaceChart').getContext('2d');
                                        var labelsYear = ["<?php echo $previousYear; ?>", "<?php echo $currentYear; ?>"];
                                        var months = <?php echo json_encode($filteredMonths); ?>;
                                        var dataCurrent = <?php echo json_encode($filteredReplaceCurrent); ?>;
                                        var dataPrevious = <?php echo json_encode($filteredReplacePrevious); ?>;
                                        var totalCostCurrentYear = dataCurrent.reduce((a, b) => a + (b || 0), 0);
                                        var totalCostPreviousYear = dataPrevious.reduce((a, b) => a + (b || 0), 0);
                                        var myChartReplace = new Chart(ctx, {
                                            type: 'line',
                                            data: {
                                                labels: months,
                                                datasets: [
                                                    { 
                                                        label: "ปีปัจจุบัน (<?php echo $currentYear; ?>)", 
                                                        data: dataCurrent, 
                                                        borderColor: 'rgba(54, 162, 235, 1)', 
                                                        backgroundColor: 'rgba(54, 162, 235, 0.2)', 
                                                        borderWidth: 3, 
                                                        pointRadius: 1.5, 
                                                        tension: 0.4, 
                                                        fill: true 
                                                    },
                                                    { 
                                                        label: "ปีที่แล้ว (<?php echo $previousYear; ?>)", 
                                                        data: dataPrevious, 
                                                        borderColor: 'rgba(255, 99, 132, 1)', 
                                                        backgroundColor: 'rgba(255, 99, 132, 0.2)', 
                                                        borderWidth: 3, 
                                                        pointRadius: 1.5, 
                                                        tension: 0.4, 
                                                        fill: true 
                                                    }
                                                ]
                                            }
                                        });
                                        // ✅ ฟังก์ชันเปลี่ยนข้อมูลในกราฟเมื่อกด Filter
                                        document.querySelectorAll('.filter-option').forEach(item => {
                                            item.addEventListener('click', function (e) {
                                                e.preventDefault();
                                                var filterType = this.getAttribute('data-filter');
                                                var chartId = this.getAttribute('data-chart'); // ดึง ID ของกราฟที่ต้องการเปลี่ยน
                                                if (!chartId || chartId !== 'replaceChart') return; // ✅ ป้องกันไม่ให้ไปเปลี่ยนกราฟอื่น
                                                var chart = Chart.getChart('replaceChart'); // ✅ ดึงกราฟที่ต้องการเปลี่ยน
                                                if (!chart) return; // ✅ ถ้าไม่มีกราฟ ไม่ต้องทำอะไร
                                                if (filterType === 'month') {
                                                    chart.config.type = 'line';
                                                    chart.data.labels = months;
                                                    chart.data.datasets[0].data = dataCurrent;
                                                    chart.data.datasets[1].data = dataPrevious;
                                                } else if (filterType === 'year') {
                                                    chart.config.type = 'bar';
                                                    chart.data.labels = labelsYear;
                                                    chart.data.datasets[0].data = [totalCostPreviousYear, totalCostCurrentYear];
                                                    chart.data.datasets[1].data = [totalCostPreviousYear, totalCostCurrentYear];
                                                    chart.data.datasets[0].fill = true;
                                                    chart.data.datasets[1].fill = true;
                                                }
                                                chart.update();
                                            });
                                        });
                                    });
                                </script>
                            </div>
                        </div>                        
                    </div>
            </div>
            <!-- ✅ กล่องที่ 4 -->
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="card shadow-sm p-3"style="border: 2px solid rgb(109, 109, 109) !important;">
                <div class="filter" data-chart="additionnalChart">
                    <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start"><h6>Filter</h6></li>
                        <li><a class="dropdown-item filter-option" data-filter="month" data-chart="additionnalChart" href="#">This Month</a></li>
                        <li><a class="dropdown-item filter-option" data-filter="year" data-chart="additionnalChart" href="#">This Year</a></li>
                    </ul>
                </div>
                    <h5 class="card-title">Total cost of devices additional request</h5>
                    <div class="d-flex align-items-center">
                        <div class="ps-3" style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                            <h6 id="chartTitle-additionnal" style="font-siz e: 24px; font-weight: bold; color: #34495e; margin-bottom: 10px;">
                            </h6>
                            <div style="width: 450px; height: 250px; background: #f4f4f4; padding: 10px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                            <canvas id="additionnalChart"></canvas>
                            </div>
                            <script>
                                document.addEventListener("DOMContentLoaded", function () {
                                    var ctx = document.getElementById('additionnalChart').getContext('2d');

                                    // ✅ นำเข้าข้อมูลจาก PHP
                                    var labelsYear = ["<?php echo $previousYear; ?>", "<?php echo $currentYear; ?>"];
                                    var months = <?php echo json_encode($filteredMonthsAdditionnal ?? []); ?>;
                                    var dataCurrent = <?php echo json_encode($filteredAdditionnalCurrent ?? []); ?>;
                                    var dataPrevious = <?php echo json_encode($filteredAdditionnalPrevious ?? []); ?>;
                                    var totalCostCurrentYear = <?php echo $totalCostCurrentYear; ?>;
                                    var totalCostPreviousYear = <?php echo $totalCostPreviousYear; ?>;

                                    console.log("Months Data:", months);
                                    console.log("Current Year Data:", dataCurrent);
                                    console.log("Previous Year Data:", dataPrevious);

                                    if (months.length === 0 || dataCurrent.length === 0 || dataPrevious.length === 0) {
                                        console.error("❌ No data for additionnalChart");
                                        return;
                                    }

                                    var myChartAdditionnal = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: months,
                                            datasets: [
                                                { 
                                                    label: "ปีปัจจุบัน (<?php echo $currentYear; ?>)", 
                                                    data: dataCurrent, 
                                                    borderColor: 'rgba(54, 162, 235, 1)', 
                                                    backgroundColor: 'rgba(54, 162, 235, 0.2)', 
                                                    borderWidth: 3, 
                                                    pointRadius: 1.5, 
                                                    tension: 0.4, 
                                                    fill: true
                                                },
                                                { 
                                                    label: "ปีที่แล้ว (<?php echo $previousYear; ?>)", 
                                                    data: dataPrevious, 
                                                    borderColor: 'rgba(255, 99, 132, 1)', 
                                                    backgroundColor: 'rgba(255, 99, 132, 0.2)', 
                                                    borderWidth: 3, 
                                                    pointRadius: 1.5, 
                                                    tension: 0.4, 
                                                    fill: true
                                                }
                                            ]
                                        }
                                    });

                                    // ✅ ใช้ querySelectorAll เพื่อให้รองรับหลาย filter
                                    document.querySelectorAll('.filter-option').forEach(item => {
                                        item.addEventListener('click', function (e) {
                                            e.preventDefault();
                                            var filterType = this.getAttribute('data-filter');
                                            var chartId = this.getAttribute('data-chart'); 

                                            if (chartId !== 'additionnalChart') return; 

                                            if (filterType === 'month') {
                                                document.getElementById('chartTitle-additionnal').innerText = "";
                                                myChartAdditionnal.config.type = 'line';
                                                myChartAdditionnal.data.labels = months;
                                                myChartAdditionnal.data.datasets[0].data = dataCurrent;
                                                myChartAdditionnal.data.datasets[1].data = dataPrevious;
                                            } 
                                            else if (filterType === 'year') {
                                                document.getElementById('chartTitle-additionnal').innerText = "";
                                                myChartAdditionnal.config.type = 'bar';
                                                myChartAdditionnal.data.labels = labelsYear;
                                                myChartAdditionnal.data.datasets[0].data = [totalCostPreviousYear, totalCostCurrentYear];
                                                myChartAdditionnal.data.datasets[1].data = [totalCostPreviousYear, totalCostCurrentYear];

                                                myChartAdditionnal.data.datasets[0].backgroundColor = 'rgba(54, 162, 235, 0.6)';
                                                myChartAdditionnal.data.datasets[1].backgroundColor = 'rgba(255, 99, 132, 0.6)';
                                            }

                                            myChartAdditionnal.update();
                                        });
                                    });
                                });


                            </script>
                        </div>
                    </div>            
            </div>
        </div>

            <div class="col-xxl-4 col-md-6">

        </div><!-- End Revenue Card -->

            
            <!-- Reports -->
            <div class="col-12">
              <div class="card"style="border: 2px solid rgb(109, 109, 109) !important;">
                <div class="card-body">
                  <h5 class="card-title">Reports <span class="currentYear"></span>
                  </h5>
                  <!-- Line Chart -->
                  <canvas id="reportsChart"></canvas>
<script>
        fetch("includes/fetch_dashboard.php", {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(response => response.json())
        .then(data => {
            if (!Array.isArray(data) || data.length === 0) {
                console.error("No valid data received.");
                return;
            }
            let labels = [];
            let additionnalValues = [];
            let replaceValues = [];
            let newBranchValues = [];
            let dataMap = {};
            data.forEach(item => {
                let date = item.date;
                if (!dataMap[date]) {
                    dataMap[date] = { additionnal: 0, replace: 0, newBranch: 0 };
                }
                if (item.status_balance === 'Additionnal') {
                    dataMap[date].additionnal = item.total;
                } else if (item.status_balance === 'Replace') {
                    dataMap[date].replace = item.total;
                } else if (item.status_balance === 'New Branch') {
                    dataMap[date].newBranch = item.total;
                }
            });
            Object.keys(dataMap).sort().forEach(date => {
                labels.push(date);
                additionnalValues.push(dataMap[date].additionnal);
                replaceValues.push(dataMap[date].replace);
                newBranchValues.push(dataMap[date].newBranch);
            });
            let ctx = document.getElementById('reportsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                        label: 'Additionnal',
                        data: additionnalValues,
                        borderColor: 'rgba(54, 162, 235, 1)', // ✅ สีน้ำเงิน
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // ✅ เติมสีฟ้าอ่อนใต้เส้น
                        borderWidth: 3,
                        pointRadius: 1.5,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                        tension: 0.4, // ✅ ทำให้เส้นโค้งขึ้น
                        fill: true // ✅ เติมสีใต้กราฟ
                        },
                        {
                        label: 'Replace',
                        data: replaceValues,
                        borderColor: 'rgba(255, 99, 132, 1)', // ✅ สีแดง
                        backgroundColor: 'rgba(255, 99, 132, 0.2)', // ✅ เติมสีชมพูอ่อนใต้เส้น
                        borderWidth: 3,
                        pointRadius: 1.5,
                        pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                        tension: 0.4, // ✅ ทำให้เส้นโค้งขึ้น
                        fill: true // ✅ เติมสีใต้กราฟ
                        },
                       
                    ]
                },
                options: {
                    elements: {
                        line: {
                            tension: 0.4 // เพิ่มความโค้ง
                        }
                    }
                }
            });
        })
        .catch(error => console.error("Error fetching data:", error));
</script>
                    <!-- End Line Chart -->
                </div>
              </div>
            </div><!-- End Reports -->
            <!-- Top Selling -->
            <div class="col-12">
              <div class="card top-selling overflow-auto">
                <div class="card-body pb-0"style="border: 2px solid rgb(109, 109, 109) !important;">
                  <h5 class="card-title">Top Replacements <span>Latest</span></h5>
                    <table class="table table-hover text-center align-middle table-bordered shadow-lg rounded-3 overflow-hidden" id="replaceTable">
                        <thead class="table-dark text-white fs-5">
                            <tr>
                                <th scope="col">Preview</th>
                                <th scope="col">Product</th>
                                <th scope="col">Export</th>
                                <th scope="col">Cost</th>
                            </tr>
                        </thead>
                        <tbody class="bg-light">
                            <!-- 🔹 ข้อมูลจะถูกใส่โดย JavaScript -->
                        </tbody>
                    </table>
                </div>
              </div>
            </div><!-- End Top Selling -->
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                fetch("includes/fetch_replace.php")  // ✅ ใช้ API ที่อัปเดต
                    .then(response => response.json())
                    .then(data => {
                        console.log("✅ API Data:", data);
                        if (!data || !data.top5_replace || data.top5_replace.length === 0) {
                            console.warn("❌ No data received for top_replace.");
                            document.querySelector("#replaceTable tbody").innerHTML = "<tr><td colspan='4' class='text-center text-muted'>No data available</td></tr>";
                            return;
                        }
                        let tableBody = document.querySelector("#replaceTable tbody");
                        tableBody.innerHTML = ""; // เคลียร์ข้อมูลเดิมก่อน
                        // ✅ วนลูปเพิ่มแถวในตาราง
                        data.top5_replace.forEach((item, index) => {
                            let pictureUrl = item.picture_url ? item.picture_url : "assets/img/default-product.jpg"; // ✅ ใช้ Default รูปถ้าไม่มี

                            let row = `
                                <tr class="align-middle">
                                    <td>
                                        <img src="${pictureUrl}" alt="Product Image" class="img-thumbnail" style="width: 50px; height: 50px;">
                                    </td>
                                    <td><a href="#" class="text-primary fw-bold">${item.part_name}</a></td>
                                    <td class="fw-bold text-dark">${item.total_quantity.toLocaleString()}</td>
                                    <td class="fw-bold text-success">฿${item.total_cost.toLocaleString()}</td>
                                </tr>
                            `;
                            tableBody.innerHTML += row;
                        });

                        // ✅ เพิ่มแถวรวม cost ทั้งหมด
                        let totalRow = `
                <tr class="table-dark fw-bold text-white">
                    <td colspan="2" class="text-end fs-5 text-uppercase">💰 Total Cost:</td>
                    <td colspan="2" class="text-danger fs-4 bg-warning text-center border border-dark">฿${data.total_cost_all.toLocaleString()}</td>
                </tr>
            `;
            tableBody.innerHTML += totalRow;
                    })
                .catch(error => console.error("❌ Fetch Data Error:", error));
            });
        </script>
        <style>
        .table-dark {
            background-color: #212529 !important;  /* สีพื้นหลังเข้ม */
            color: #fff !important;  /* ตัวอักษรสีขาว */
        }
        .bg-warning {
            background-color: #ffc107 !important;  /* สีเหลืองสด */
        }
        .text-danger {
            color: #dc3545 !important;  /* ตัวอักษรสีแดง */
        }
        .fs-5 {
            font-size: 1.25rem !important; /* ขยายตัวอักษรให้ใหญ่ขึ้น */
        }
        .fs-4 {
            font-size: 1.5rem !important; /* ขยายตัวอักษรของ Total Cost */
        }
        .border-dark {
            border: 2px solid #000 !important; /* เส้นขอบสีดำ */
        }
        </style>
             <!-- Recent Sales -->
    <div class="col-12">
        <div class="card recent-sales overflow-auto">
                <br>
                <div class="card-body">
    <div class="summary-header" style="background: #2c3e50; color: white; padding: 12px; font-size: 16px; font-weight: bold; text-align: center; border-radius: 5px 5px 0 0;">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div style="flex: 1; text-align: center;" id="currentPeriod"></div>
        <script>
    document.addEventListener("DOMContentLoaded", function () {
        // ดึงเดือนและปีปัจจุบัน
        const currentDate = new Date();
        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        const currentMonth = monthNames[currentDate.getMonth()]; // เดือนปัจจุบัน
        const currentYear = currentDate.getFullYear(); // ปีปัจจุบัน

        // อัปเดต HTML ให้แสดงเดือนและปีปัจจุบัน
        document.getElementById("currentPeriod").innerText = `${currentMonth} ${currentYear}`;
    });
</script>

        <div style="flex: 1; text-align: center;">TOP 10 REPLACEMENT </div>
        </div>
    </div>

    <div class="table-container">  <!-- ✅ ใส่ div คลุมตารางเพื่อให้ Scroll ได้ -->
        <table id="replaceTable">
            <thead>
            <tr style="background: #34495e; color: white;">
                    <th>MONTH</th>
                    <th>Qubepos AP-1500</th>
                    <th>Qubepos BL-J6412</th>
                    <th>UPS</th>
                    <th>Lenovo Backend</th>
                    <th>Printer D300M</th>
                    <th>Cash Drawer</th>
                    <th>Price Checker SK100</th>
                    <th>Autoid LIM</th>
                    <th>Scanner 7190G</th>
                    <th>Barcode Printer</th>
                    <th>NVR</th>
                    <th>DVR</th>
                    <th>Printer HP</th>
                    <th>AVERAGE</th>
                </tr>
            </thead>
            <tbody id="replaceBody">
                <!-- ข้อมูลจาก JavaScript -->
            </tbody>
        </table>
    </div>
</div>
            </div>
        </div><!-- End Recent Sales -->
    </div><!-- End Left side columns -->
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                fetch("includes/fetch_replace_percentage.php")
                    .then(response => response.json())
                    .then(data => {
                        console.log("Fetched Data:", data);
                        if (!data || Object.keys(data).length === 0) {
                            console.warn("No data received.");
                            return;
                        }
                        let months = [
                            "January", "February", "March", "April", "May", "June",
                            "July", "August", "September", "October", "November", "December"
                        ];
                        let partNames = [
                            "QUBEPOS CORE AP-1500 (C1)", "QUBEPOS CORE EL J6412 (C2)", "UPS", "Backend CPU",
                            "Cashier Printer D300M", "Cashier drawer", "Price Checker (SK100)", "SEUIC AUTOID CRUISE1",
                            "Cashier Scanner : 7190G", "Barcode Printer ZX420", "NVR & HDD 16CH H.265",
                            "DVR Type BCN", "Printer HP Deskjet"
                        ];
                        let tbody = document.getElementById("replaceBody");
                        tbody.innerHTML = "";
                        // วนลูปแสดงผล 12 เดือน
                        months.forEach((month, index) => {
                            let monthIndex = index + 1; // 1-12
                            let rowData = data[monthIndex] || {};

                            let bgColor = (index % 2 === 0) ? "#ecf0f1" : "white";
                            let row = `<tr style="background: ${bgColor};">
                                <td style="padding: 10px; border: 1px solid #ddd;">${month}</td>`;
                            // คำนวณยอดรวมของทั้งเดือนเพื่อใช้เป็นฐาน 100%
                            let totalForMonth = Object.values(rowData).reduce((acc, val) => acc + val, 0);
                            // ตรวจสอบว่ามีข้อมูลหรือไม่
                            if (totalForMonth === 0) {
                                partNames.forEach(() => {
                                    row += `<td style="padding: 10px; border: 1px solid #ddd;">0%</td>`;
                                });
                                row += `<td style="padding: 10px; border: 1px solid #ddd;">0%</td></tr>`;
                                tbody.innerHTML += row;
                                return;
                            }
                            let totalPercentage = 0; // ใช้เก็บเปอร์เซ็นต์รวมของแต่ละเดือน
                            partNames.forEach(part => {
                                let qty = rowData[part] || 0;
                                let percent = (qty / totalForMonth * 100).toFixed(2);

                                totalPercentage += parseFloat(percent); // บวกค่าทั้งหมดเพื่อเช็คไม่ให้เกิน 100%

                                row += `<td style="padding: 10px; border: 1px solid #ddd;">${percent}%</td>`;
                            });

                            // จำกัดค่าเฉลี่ยรวมไม่ให้เกิน 100% ถ้าจำเป็น
                            let adjustedAverage = Math.min(totalPercentage / partNames.length, 100).toFixed(2) + "%";
                            row += `<td style="padding: 10px; border: 1px solid #ddd;">${adjustedAverage}</td></tr>`;
                            tbody.innerHTML += row;
                        });
                    })
                    .catch(error => console.error("Error fetching data:", error));
            });
            </script>
            <style>
    .table-container {
        width: 100%;
        overflow-x: auto; /* เพิ่มการเลื่อนแนวนอน */
    }
    #replaceTable {
        width: 100%;
        max-width: 100%;
        font-size: 14px; /* ลดขนาดตัวอักษร */
        table-layout: auto; /* ให้ขนาดคอลัมน์ปรับตามเนื้อหา */
    }
    #replaceTable th, #replaceTable td {
        padding: 6px; /* ลด padding เพื่อให้ช่องไม่กว้างเกินไป */
        border: 1px solid #ddd;
        white-space: nowrap; /* ป้องกันการตัดบรรทัด */
        text-align: center;
    }
</style>

        <!-- Right side columns -->
        <div class="col-lg-4">
          <!-- Recent Activity -->
          <div class="card">
            <div class="card-body"style="border: 2px solid rgb(109, 109, 109) !important;">
              <h5 class="card-title">Recent Activity</h5>
                 <div class="box summary-box" style=" text-align: center; max-width: 400px; margin: auto;">
                      <!-- Title with underline -->
                    <?php
                    $month_name = date("F"); // รับชื่อเดือนปัจจุบัน
                    ?>
                    <p style="font-size: 26px; font-weight: bold; color: #2c3e50; text-transform: uppercase; margin-bottom: 15px; text-align: center; letter-spacing: 1px; border-bottom: 3px solid #2c3e50; display: inline-block; padding-bottom: 5px;">
                        <?php echo $month_name; ?>
                    </p>
                    <!-- Total Cost Section -->
                    <h2 style="font-size: 22px; font-weight: bold; color: #2c3e50; margin: 15px 0; text-transform: uppercase;">Total Cost in <span class="currentYear"></span>
                    </h2>
                    <p id="total-cost" style="font-size: 22px; font-weight: bold; color: #f1c40f; background: #2c3e50; display: inline-block; padding: 8px 15px; border-radius: 8px; margin: 10px 0;">
                        <?php echo number_format($total_cost_all_status, 2); ?> <!-- แสดงผลรวมของ cost -->
                    </p>
                    <!-- New Devices Section -->
                    <h3 style="font-size: 18px; font-weight: bold; color: #34495e; margin-top: 20px;">New Devices</h3>
                    <p style="font-size: 20px; font-weight: bold; color: #27ae60; background: #ecf0f1; display: inline-block; padding: 5px 12px; border-radius: 5px; margin: 5px 0;">
                        ฿<?php echo number_format( $total_cost_new_branch_dvr_current_year, 2); ?>
                    </p>
                    <!-- Replacement Section -->
                    <h3 style="font-size: 18px; font-weight: bold; color: #34495e; margin-top: 20px;">Replacement</h3>
                    <!-- แสดงผลรวม cost เฉพาะที่มี status_balance = 'Replace' -->
                    <p style="font-size: 20px; font-weight: bold; color: #9b59b6; background: #ecf0f1; display: inline-block; padding: 5px 12px; border-radius: 5px; margin: 5px 0;">
                        ฿<?php echo number_format($total_cost_replace, 2); ?>
                    </p>
                    <!-- Additional Section -->
                    <h3 style="font-size: 18px; font-weight: bold; color: #34495e; margin-top: 20px;">Additional</h3>
                    <p style="font-size: 20px; font-weight: bold; color: #3498db; background: #ecf0f1; display: inline-block; padding: 5px 12px; border-radius: 5px; margin: 5px 0;">
                        ฿<?php echo number_format( $total_cost_additional_current_year, 2); ?>
                    </p>
                </div>
            </div>
          </div><!-- End Recent Activity -->    
          <!-- Budget Report -->
<br>
    <div class="card-body d-flex justify-content-center gap-4">
        <div class="status-box pending"style="border: 2px solid rgb(109, 109, 109) !important;">
            <span class="status-text">Pending :</span>
            <span id="pending-count" class="status-number">0</span>
        </div>
        <div class="status-box success"style="border: 2px solid rgb(109, 109, 109) !important;">
            <span class="status-text">Success :</span>
            <span id="success-count">0</span> <!-- ค่านี้จะถูกอัปเดตโดย JavaScript -->        </div>
    </div>
    <script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('includes/fetch_pendingall.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('pending-count').textContent = data.total;
        })
        .catch(error => console.error("❌ Error fetching data:", error));
});

fetch('includes/fetch_successall.php')
    .then(response => response.json())
    .then(data => {
        console.log("Fetched Success Data:", data); // ✅ Debug ค่า
        document.getElementById('success-count').textContent = data.total; // แสดงผลใน HTML
    })
    .catch(error => console.error("❌ Error fetching data:", error));
</script>
<style>
 .status-box {
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    font-weight: bold;
    padding: 12px 20px;
    border-radius: 8px;
    min-width: 180px;
    transition: all 0.3s ease-in-out;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.pending {
    background-color: #f39c12; /* สีส้ม */
    color: white;
}

.success {
    background-color: #2ecc71; /* สีเขียว */
    color: white;
}

.status-text {
    margin-right: 8px;
}

.status-box:hover {
    transform: scale(1.05); /* ขยายเล็กน้อยเวลา hover */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}
</style>
<br>
            <div class="card-body pb-0">
                    <div class="summary-section">
                        <div style="width: 105%; max-width: 700px; border: 1px solid #bdc3c7; border-radius: 5px; overflow: hidden; margin: auto;">
                            <div class="summary-header" style="background-color: #2c3e50; color: white; padding: 8px; font-size: 16px; font-weight: bold; text-align: center;">NEW STORE <span class="currentYear"></span></div>
                            <table class="info-table" style="width: 100%; border-collapse: collapse; font-size: 14px;">
                                <tr style="background-color: #ecf0f1; font-weight: bold;">
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">Item</td>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">Value</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">NEW STORE</td>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">
                                    <?php echo number_format(num: $total_new_branch_case_current_year); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">DELIVERY DEVICES</td>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">
                                    <?php echo number_format(num: $total_quantities_new_branch_current_year); ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="summary-section">
                        <div style="width: 105%; max-width: 700px; border: 1px solid #bdc3c7; border-radius: 5px; overflow: hidden; margin: auto;">
                            <div class="summary-header" style="background-color: #2c3e50; color: white; padding: 8px; font-size: 16px; font-weight: bold; text-align: center;">Replace case <span class="currentYear"></span></div>
                            <table class="info-table" style="width: 100%; border-collapse: collapse; font-size: 14px;">
                                <tr style="background-color: #ecf0f1; font-weight: bold;">
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">Item</td>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">Value</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">Replace case</td>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">
                                      <?php echo number_format(num: $total_replace); ?>
                                  </td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">DELIVERY DEVICES</td>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">
                                        <?php echo number_format($total_quantities_replace_current_year); ?>
                                    </td>                               
                                 </tr>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="summary-section">
                        <div style="width: 105%; max-width: 700px; border: 1px solid #bdc3c7; border-radius: 5px; overflow: hidden; margin: auto;">
                            <div class="summary-header" style="background-color: #2c3e50; color: white; padding: 8px; font-size: 16px; font-weight: bold; text-align: center;">Additional case <span class="currentYear"></span></div>
                            <table class="info-table" style="width: 100%; border-collapse: collapse; font-size: 14px;">
                                <tr style="background-color: #ecf0f1; font-weight: bold;">
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">Item</td>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">Value</td>
                                </tr>
                                <tr>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">Additional case</td>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">
                                      <?php echo number_format( $total_additional_case_current_year); ?>
                                    </td>                                
                                  </tr>
                                <tr>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">DELIVERY DEVICES</td>
                                    <td style="padding: 6px; border: 1px solid #bdc3c7;">
                                        <?php echo number_format( $total_quantities_additionnal_current_year); ?>
                                    </td>                                
                                    </tr>
                            </table>
                        </div>
                    </div>
            <br>
          </div><!-- End Budget Report -->
          <!-- Website Traffic -->
          <div class="card">
            <div class="card-body pb-0"style="border: 2px solid rgb(109, 109, 109) !important;">
              <h5 class="card-title">Top10 Replacements</h5>
              <canvas id="top10replace" style="min-height: 400px;"></canvas>
              <script>
                         document.addEventListener("DOMContentLoaded", function () {
                        fetch("includes/fetch_dashboardtop10.php")
                        .then(response => response.json())
                        .then(data => {
                            console.log("Fetched Data:", data);

                            if (!Array.isArray(data) || data.length === 0) {
                                console.warn("No data received for top_replace.");
                                return;
                            }
                            let labels = [];
                            let quantities = [];
                            data.forEach(item => {
                                labels.push(item.part_name);
                                quantities.push(item.total_quantity);
                            });
                            let ctx = document.getElementById('top10replace').getContext('2d');
                            new Chart(ctx, {
                                type: 'doughnut',
                                data: {
                                    labels: labels,
                                    datasets: [
                                        {
                                            label: 'Top 10 Replace Quantities',
                                            data: quantities,
                                            backgroundColor: [
                                                'rgba(255, 99, 132, 0.6)',
                                                'rgba(54, 162, 235, 0.6)',
                                                'rgba(255, 206, 86, 0.6)',
                                                'rgba(75, 192, 192, 0.6)',
                                                'rgba(153, 102, 255, 0.6)',
                                                'rgba(255, 159, 64, 0.6)',
                                                'rgba(199, 199, 199, 0.6)',
                                                'rgba(83, 102, 255, 0.6)',
                                                'rgba(100, 159, 64, 0.6)',
                                                'rgba(200, 200, 86, 0.6)'
                                            ],
                                            borderWidth: 1
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: true, // ✅ ป้องกันไม่ให้กราฟขยายยาวเกินไป
                                    aspectRatio: 1, // ✅ ทำให้กราฟเป็นวงกลมเสมอ
                                    plugins: {
                                        legend: {
                                            position: 'bottom', // ✅ แสดง Legend ด้านล่าง ไม่ให้ยืดออกด้านข้าง
                                            labels: {
                                                boxWidth: 10, // ✅ ลดขนาดช่องสีใน Legend
                                                padding: 10 // ✅ ลดช่องว่างของ Legend
                                            }
                                        }
                                    }
                                }
                            });
                        })
                        .catch(error => console.error("Error fetching data:", error));
                });
              </script>
            </div>
          </div><!-- End Website Traffic -->
          <!-- News & Updates Traffic -->
          <div class="card">
          <div class="box supply-box" style="background: #ffffff; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
                <div class="summary-header" style="background: #2c3e50; color: white; padding: 12px; font-size: 18px; font-weight: bold; text-align: center; border-radius: 5px 5px 0 0;">
                    DEVICES SUPPLY TO STORES WITH COST AND AVERAGE COST PER STORE
                </div>
                <table id="newBranchTable" style="width: 100%; border-collapse: collapse; text-align: center; font-size: 16px;">
                    <thead>
                        <tr style="background: #34495e; color: white;">
                            <th style="padding: 12px; border: 1px solid #ddd;">MONTH</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">STORES</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">QTY</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">COST</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- 🔹 ข้อมูลจะถูกใส่โดย JavaScript -->
                    </tbody>
                    <tfoot>
                            <tr style="background: #34495e; color: white;">
                                <th style="padding: 12px; border: 1px solid #ddd;">AVERAGE</th>
                                <th style="padding: 12px; border: 1px solid #ddd;">STORE</th>
                                <th style="padding: 12px; border: 1px solid #ddd;">COST</th>
                                <th style="padding: 12px; border: 1px solid #ddd;">AVERAGE</th>
                            </tr>
                            <tr style="background: #ecf0f1;">
                                <td style="padding: 10px; border: 1px solid #ddd;">AVERAGE</td>
                                <td style="padding: 10px; border: 1px solid #ddd;">0</td>
                                <td style="padding: 10px; border: 1px solid #ddd;">00</td>
                                <td style="padding: 10px; border: 1px solid #ddd;">00000</td>
                            </tr>
                        </tfoot>
                </table>
            </div>
            <Script>
                document.addEventListener("DOMContentLoaded", function () {
                fetch("includes/fetch_new_branch.php")
                    .then(response => response.json())
                    .then(data => {
                        console.log("Fetched Data:", data);

                        let tbody = document.querySelector("#newBranchTable tbody");
                        let tfoot = document.querySelector("#newBranchTable tfoot");

                        tbody.innerHTML = ""; // เคลียร์ข้อมูลเก่า
                        tfoot.innerHTML = ""; // เคลียร์ข้อมูลใน `tfoot`

                        let months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                        months.forEach((month, index) => {
                            let monthData = data.monthly_data[index + 1] || { stores: 0, qty: 0, cost: 0 };
                            let row = `
                                <tr ${index % 2 === 0 ? 'style="background: #ecf0f1;"' : ""}>
                                    <td style="padding: 10px; border: 1px solid #ddd;">${month}</td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">${monthData.stores}</td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">${monthData.qty}</td>
                                    <td style="padding: 10px; border: 1px solid #ddd;">฿${parseFloat(monthData.cost).toLocaleString()}</td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });

                        // ✅ เพิ่มข้อมูลรวมใน `<tfoot>`
                        let footerRow = `
                            <tr style="background: #34495e; color: white;">
                                <th style="padding: 12px; border: 1px solid #ddd;">TOTAL</th>
                                <th style="padding: 12px; border: 1px solid #ddd;">${data.total_stores}</th>
                                <th style="padding: 12px; border: 1px solid #ddd;">${data.total_qty}</th>
                                <th style="padding: 12px; border: 1px solid #ddd;">฿${parseFloat(data.total_cost).toLocaleString()}</th>
                            </tr>
                        `;
                        tfoot.innerHTML = footerRow;
                    })
                    .catch(error => console.error("Error fetching data:", error));
            });
            var currentYear = new Date().getFullYear();
    
    // ใส่ปีปัจจุบันเข้าไปในทุกๆ span ที่มี class="currentYear"
    var elements = document.querySelectorAll(".currentYear");
    elements.forEach(function(element) {
        element.textContent = currentYear;
    });
            </Script>
        </div><!-- End Right side columns -->
      </div>
    </section>

</main><!-- End #main -->

<?php include 'footer.php'; ?>    

