<?php include('header.php'); ?>
<?php include('navbar.php');?>
<?php include('sidebar.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Report</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item active"><a href="report.php">Report</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section contact">

    <div class="container1">
    <div class="rounded p-3 bg-white shadow-sm" style="border: 2px solid rgb(109, 109, 109) !important;">
        <div class="d-flex align-items-center justify-content-start flex-wrap">
            <div class="filter-group" id="status-group">
                <label for="status">Status:</label>
                <select id="status" name="status" class="form-control">
                    <option value="">----</option>
                    <option value="Replace">Replace</option>
                    <option value="Additionnal">Additional</option>
                    <option value="New Branch">New Branch</option>
                    <option value="New DVR">New DVR</option>
                </select>
            </div>

            <div class="filter-group" id="request-year-group" style="display: none;">
                <label for="request_year">Request Year:</label>
                <select id="request_year" name="request_year" class="form-control">
                    <option value="" selected>xxxx</option>
                    <?php
                    $startYear = 2020;
                    $endYear = date('Y');
                    for ($year = $startYear; $year <= $endYear; $year++) {
                        echo "<option value=\"$year\">$year</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
<br>
<div class="rounded p-3 bg-white shadow-sm" style="border: 2px solid rgb(109, 109, 109) !important; display: none;" id="editmodel">
                <div class="d-flex flex-row align-items-center">

                    <div class="d-flex flex-row align-items-center mr-3">  <div class="btn-group mr-2" role="group" aria-label="Week/Month Selection">
                            <button type="button" class="btn btn-secondary"  name="request_week" id="week-button">Week</button>
                            <button type="button" class="btn btn-secondary"  name="request_month" id="month-button">Month</button>
                        </div>
                        <div id="dropdown-container" style="width: 200px;">

                        </div>
                    </div>


                    <div>
                        <button type="button" class="btn btn-primary" id="search-button"  style="display:none; margin-left: auto;" onclick="Searchchack()">search 🔍</button>
                    </div>

                </div>
            </div>

        <!-- ส่วนของข้อมูล Weekly list -->
                <div class="report-content">
            <!-- Weekly List -->
            <div class="weekly-list"style="border: 2px solid rgb(109, 109, 109) !important;">
                <h3 >Weekly List </h3>
                <table>
                    <thead>
                        <tr>
                            <th>Part Name</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        <!-- Rows will be dynamically added here -->
                    </tbody>
                </table>
            </div>

            <!-- Weekly Chart -->
            <div class="weekly-chart"style="border: 2px solid rgb(109, 109, 109) !important;">
                <h3>Weekly Chart</h3>
                <canvas id="myPieChart"></canvas>
            </div>
        </div>
    

        <!-- Chart.js สำหรับกราฟ -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
          let myPieChart = null; // เก็บตัวแปร Pie Chart

          function Searchchack() {
    const status = document.getElementById('status').value;
    const week = document.getElementById('request_week') ? document.getElementById('request_week').value : '';
    const year = document.getElementById('request_year').value;
    const month = document.getElementById('request_month') ? document.getElementById('request_month').value : '';

    // เช็คว่าผู้ใช้เลือก Status และ Request Year แล้วหรือยัง
    if (!status || !year) {
        Swal.fire({
        title:  "ผิดพลาด!",
        text: " Select Status and Request Year",
        icon: "warning"
        });
        return;
        
    }

    // เช็คว่าผู้ใช้เลือก Request Week หรือ Request Month อย่างน้อยหนึ่งอย่าง

    if (!week && !month) {
        Swal.fire({
        title:  "ผิดพลาด!",
        text: " Select Request Week and Request Month",
        icon: "warning"
        });
        return;
    }

    // ถ้าผ่านเงื่อนไขทั้งหมดแล้ว ให้ส่งข้อมูลไปยัง fetch_report.php
    fetch('includes/fetch_report.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status, request_week: week, request_year: year, request_month: month })
    })
    .then(response => response.json())
    .then(data => {
        updateTable(data);
        updatePieChart(data); // อัปเดตแผนภูมิวงกลม
    })
    .catch(error => console.error('เกิดข้อผิดพลาด:', error));
}
function updateTable(data) {
    const tableBody = document.getElementById('table-body');
    tableBody.innerHTML = ''; // ล้างข้อมูลเก่าก่อน

    let grandTotal = 0;  // ตัวแปรสำหรับเก็บผลรวม

    // เพิ่มข้อมูลลงในตาราง
    data.forEach(item => {
        // แปลง quantity เป็นตัวเลข
        const quantity = Number(item.quantity);

        // ตรวจสอบว่า quantity เป็นตัวเลขและไม่เป็น NaN
        if (!isNaN(quantity)) {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${item.part_name}</td><td>${quantity}</td>`;
            tableBody.appendChild(row);

            grandTotal += quantity; // คำนวณผลรวม
        } else {
            console.error("Invalid quantity value:", item.quantity);  // ถ้าไม่ใช่ตัวเลข
        }
    });

    // เพิ่มแถวแสดงผลรวมทั้งหมด
    const grandTotalRow = document.createElement('tr');
    grandTotalRow.innerHTML = `<td><strong>Alltotal</strong></td><td><strong>${grandTotal}</strong></td>`;
    tableBody.appendChild(grandTotalRow);
}
function updatePieChart(data) {
    const ctx = document.getElementById('myPieChart').getContext('2d');

    // ล้างกราฟเก่าถ้ามี
    if (myPieChart) myPieChart.destroy();

    myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.map(item => item.part_name),
            datasets: [{
                data: data.map(item => item.quantity),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
}
document.addEventListener('DOMContentLoaded', function() {

const statusSelect = document.getElementById('status');
const requestYearGroup = document.getElementById('request-year-group');
const requestYearSelect = document.getElementById('request_year');
const editModel = document.getElementById('editmodel');
const weekButton = document.getElementById('week-button');
const monthButton = document.getElementById('month-button');
const dropdownContainer = document.getElementById('dropdown-container');
const searchButton = document.getElementById('search-button');

let currentDropdown = null;
let currentType = null;

function createDropdown(name, start, end, prefix = '', optionsText = null) {
    const select = document.createElement('select');
    select.id = `request_${name}`;
    select.name = `request_${name}`;
    select.classList.add('form-control');

    const defaultOption = document.createElement('option');
    defaultOption.value = "";
    defaultOption.textContent = "----";
    defaultOption.selected = true;
    select.appendChild(defaultOption);

    for (let i = start; i <= end; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = optionsText ? optionsText[i - 1] : `${prefix} ${i}`;
        select.appendChild(option);
    }
    return select;
}

function resetWeekAndMonth() {
    if (currentDropdown) {
        $(currentDropdown).slideUp(300, function() {
            this.remove();
            currentDropdown = null;
            currentType = null;
        });
    }

    weekButton.classList.remove('btn-primary');
    weekButton.classList.add('btn-secondary');
    monthButton.classList.remove('btn-primary');
    monthButton.classList.add('btn-secondary');
    weekButton.disabled = false;
    monthButton.disabled = false;
    $(searchButton).slideUp(300);
}

function setActiveButton(activeButton, inactiveButton) {
    activeButton.classList.remove('btn-secondary');
    activeButton.classList.add('btn-primary');
    inactiveButton.classList.remove('btn-primary');
    inactiveButton.classList.add('btn-secondary');
}

statusSelect.addEventListener('change', function() {
    if (this.value !== "") {
        $(requestYearGroup).slideDown(300);
    } else {
        $(requestYearGroup).slideUp(300);
        $(editModel).slideUp(300);
        $(searchButton).slideUp(300);
        resetWeekAndMonth();
    }
});

requestYearSelect.addEventListener('change', function() {
    if (this.value !== "") {
        $(editModel).slideDown(300);
    } else {
        $(editModel).slideUp(300);
        $(searchButton).slideUp(300);
    }
    resetWeekAndMonth();
});

weekButton.addEventListener('click', function() {
    if (currentType === 'week') {
        resetWeekAndMonth();
        return;
    }

    if (currentDropdown) {
        $(currentDropdown).slideUp(300, function() {
            this.remove();
        });
    }
    currentDropdown = createDropdown('week', 1, 52, 'Week');
    $(dropdownContainer).hide().append(currentDropdown).slideDown(300);
    currentType = 'week';
    setActiveButton(weekButton, monthButton);

    currentDropdown.addEventListener('change', function() {
        if (this.value !== "") {
            $(searchButton).slideDown(300);
        } else {
            $(searchButton).slideUp(300);
        }
    });
});

monthButton.addEventListener('click', function() {
    if (currentType === 'month') {
        resetWeekAndMonth();
        return;
    }

    if (currentDropdown) {
        $(currentDropdown).slideUp(300, function() {
            this.remove();
        });
    }
    currentDropdown = createDropdown('month', 1, 12, '', ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']);
    $(dropdownContainer).hide().append(currentDropdown).slideDown(300);
    currentType = 'month';
    setActiveButton(monthButton, weekButton);

    currentDropdown.addEventListener('change', function() {
        if (this.value !== "") {
            $(searchButton).slideDown(300);
        } else {
            $(searchButton).slideUp(300);
        }
    });
});
});

        </script>
    <!-- CSS สำหรับจัดสไตล์ -->
    <style>
        
        #search-button-container {
    display: flex;
    align-items: center;
}
select:disabled {
    color: gray;
    background-color: #f8f9fa;
    cursor: not-allowed;
}

/* (Optional) เพิ่ม styling ให้กับปุ่ม - ถ้ายังไม่มี */
.btn-secondary {
    margin-right: 10px;
}

/* ทำให้ปุ่มที่ active เป็นสีฟ้า (ถ้าใช้ Bootstrap) */
.btn-primary { /* ไม่ต้องใส่ :active หรือ :focus ที่นี่ */
    /* background-color: #007bff;  ถ้าอยาก override สี default ของ Bootstrap */
    /* border-color: #007bff; */
}

/* สไตล์สำหรับปุ่มที่ถูก disabled */
.btn:disabled {
  opacity: 0.65; /* ทำให้จางลง */
  cursor: not-allowed;
}

.btn-primary:hover {
    background: #0056b3;
}

            .export-btn {
                background: #2c3e50;
                color: white;
                padding: 8px 15px;
                border: none;
                cursor: pointer;
                border-radius: 5px;
            }

            .report-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start; /* จัดให้อยู่ในแนวเดียวกันด้านบน */
    flex-wrap: wrap; /* ให้ปรับขนาดอัตโนมัติเมื่อหน้าจอเล็ก */
    margin-top: 20px;
    gap: 20px;
}

            .weekly-list {
                flex: 1;
                padding: 15px;
                background: white;
                border-radius: 5px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }
            .weekly-chart {
    flex: 1;
    min-width: 400px;
    max-width: 600px;
    background: white;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 15px;
    display: flex;
    flex-direction: column; /* จัดให้ Weekly Chart อยู่ด้านบน */
    align-items: center;
    justify-content: center;
}
#myPieChart {
    width: 100% !important;
    height: auto !important;
}
.chart-container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}
            table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
                margin-top: 10px;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
            }

            th {
                background: #2c3e50;
                font-weight: bold;
                color: white;
            }

            tbody tr:last-child {
                font-weight: bold;
                background: #f0f0f0;
            }


            .filter-group {
        margin-right: 10px;
        margin-bottom: 0px;
        min-width: 120px; /* กำหนดขนาดของ select ให้อยู่ในขนาดที่พอเหมาะ */
    }

    .filter-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        text-align: left;
    }

    .filter-group select {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
        font-size: 14px;
    }

    .filter-group select:focus {
        outline: none;
        border-color: #007bff;
    }

    .btn-primary {
        padding: 10px 15px;
        font-size: 14px;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .d-flex {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 10px; /* ลดระยะห่างระหว่างช่องเลือก */
        flex-wrap: wrap;
    }

    .d-flex .filter-group {
        flex: 0 1 auto; /* ทำให้ขนาด select ไม่ยืดตามขนาดของ div */
    }

    .ml-2 {
        margin-left: 10px; /* เว้นระยะเล็กน้อยให้กับปุ่ม */
    }

    .d-flex .btn {
        margin-top: 0px;
    }


        </style>

          </div>

        </div>

      </div>

    </section>

  </main><!-- End #main -->

 <?php include('footer.php'); ?>