<?php
include('header.php');
include('navbar.php');
include('sidebar.php');
include('includes/db_connect.php');

$document_number = isset($_GET['doc']) ? base64_decode($_GET['doc']) : null;
$column_name = ''; // ตัวแปรเก็บ column_name
$created_at = ''; // ตัวแปรเก็บวันที่สร้าง
$total_quantities = 0; // ตัวแปรเก็บจำนวนรวมของสินค้าทั้งหมด
$status_balance = ''; // ✅ เพิ่มตัวแปรเก็บค่า status_balance
$created_at_update = ''; // ตัวแปรเก็บวันที่อัพเดท
$user_created = ''; // ตัวแปรเก็บผู้สร้าง
$user_operation = ''; // ตัวแปรเก็บผู้ดำเนินการ

if (!empty($document_number)) {
    $sql = "SELECT column_name, created_at, created_at_update, user_created ,user_operation, SUM(quantities) as total_quantities, status_balance
            FROM pending WHERE document_number = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $document_number);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $column_name = $row['column_name'];
        $created_at = $row['created_at'];
        $total_quantities = $row['total_quantities'];
        $status_balance = $row['status_balance'];
        $created_at_update = $row['created_at_update'];
        $user_created = isset($row['user_created']) ? $row['user_created'] : 'N/A'; 
        $user_operation = isset($row['user_operation']) ? $row['user_operation'] : 'N/A';
    }
}
?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Pending</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Stock</li>
                <li class="breadcrumb-item active"><a href="manage_balance.php">Manage Balance</a></li>
            </ol>
        </nav>
    </div>
    <br>

    <div class="rounded p-3 bg-white shadow-sm" style="border: 2px solid rgb(109, 109, 109) !important;">

        <h4 class="fw-bold mb-3"><strong>Document ID</strong> :
            <span class="badge bg-dark text-white"><?= htmlspecialchars($document_number) ?></span>
        </h4>
        <div class="fw-bold mb-1 d-flex justify-content-between" style="max-width: 500px; width: 100%;">
            <p class="me-1" style="min-width: 150px;"><strong>Created by</strong> :
                <span class="text-secondary"><?php echo htmlspecialchars($user_created, ENT_QUOTES, 'UTF-8'); ?></span>
            </p>
            <p style="min-width: 200px; text-align: left;">
                <strong>Date</strong> :
                <span class="text-secondary"><?php echo date("d-m-Y H:i", strtotime($created_at)); ?></span>
            </p>
        </div>

        <div class="fw-bold mb-1 d-flex justify-content-between" style="max-width: 500px; width: 100%;">
            <p class="me-1" style="min-width: 150px;"><strong>Operation by</strong> :
                <span
                    class="text-secondary"><?php echo htmlspecialchars($user_operation, ENT_QUOTES, 'UTF-8'); ?></span>
            </p>
            <p style="min-width: 200px; text-align: left;">
                <strong>Date</strong> :
                <span class="text-secondary">
                    <?php 
                if ($created_at_update == "0000-00-00 00:00:00" || empty($created_at_update)) {
                    echo "N/A"; // ถ้าไม่มีข้อมูลให้แสดงเป็นสีเทา
                } else {
                    echo date("d-m-Y H:i", strtotime($created_at_update));
                }
            ?>
                </span>
            </p>
        </div>
        <!-- ✅ แสดงชื่อ Column Name -->
        <div id="selectedColumnContainer" class="alert text-center p-2 rounded shadow-sm"
            style="background:rgb(238, 246, 253); color: #212529; font-size: 1rem; ">
            <h3 class="fw-bold mb-0">
                <?php if (!empty($column_name)) : ?>
                Column : <strong class='text-primary'><?= htmlspecialchars($column_name) ?></strong>
                <?php endif; ?>
                <?php if ($status_balance=="New Branch" || $status_balance=="Replace" || $status_balance=="Additionnal" || $status_balance=="New DVR") : ?>
                Status : <strong class='text-primary'><?= htmlspecialchars($status_balance) ?></strong>
                <?php endif; ?>

            </h3>
        </div>
        <!-- Total Cost Section -->
        <div class="d-flex align-items-center justify-content-center mb-3 p-2 rounded shadow-sm"
            style="background: #e9f7ef; border-left: 5px solid #e9f7ef; border-radius: 8px; display: inline-block; min-width: 200px;">
            <h4 class="fw-bold m-0" style="color:rgb(31, 102, 48);">
                <i class="fa-solid fa-box-archive"></i> Total : <?= htmlspecialchars($total_quantities) ?>
            </h4>
        </div>

        <!-- ✅ แสดงช่องกรอกวันที่เพียงช่องเดียว ถ้าตรงกับเงื่อนไข -->
        <?php if ($column_name === "Waiting to Receive" || $column_name === "Process Adjust") : ?>
        <div class="row">
            <div class="col-md-3">
                <label><strong>Status</strong></label>
                <input type="text" class="form-control" name="selected_date" style="max-width: 200px !important;"
                    value="<?= ($column_name === 'Waiting to Receive') ? 'Receive' : (($column_name === 'Process Adjust') ? 'Adjustment' : '') ?>"
                    readonly>
            </div>
            <div class="col-md-3">
                <label><strong>
                        <?php echo ($column_name === "Waiting to Receive") ? "Inbound Date" : "Outbound Date"; ?>
                    </strong></label>
                <input type="date" class="form-control" id="selected-date" name="selected_date"
                    style="max-width: 200px !important;">
            </div>
        </div>
        <?php endif; ?>

        <br>
        <div id="pendingItems">
            <!-- Data will be inserted here via JavaScript -->
        </div>

        <div class="d-flex justify-content-between mt-3">
            <a href="manage_balance.php" class="btn btn-secondary">Back</a>
            <div>
            <button class="btn btn-warning btn-updatepending"><i class="fas fa-rotate"></i> Save</button>
                <button class="btn btn-success btn-savepending"><i class="fas fa-save"></i> Save & Submit</button>

            </div>

        </div>
    </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let documentNumber = "<?= $document_number ?>";
    let columnName = "<?= $column_name ?>"; // ✅ ดึงค่า column_name มาตรวจสอบ
    let status_balance = "<?= $status_balance ?>"; // ✅ ดึงค่า status_balance มาตรวจสอบ

    if (documentNumber) {
        loadPendingData(documentNumber);
    }

    function loadPendingData(docNumber) {
        $.ajax({
            url: "includes/fetch_pending_by_document.php",
            type: "GET",
            data: {
                document_number: docNumber
            },
            dataType: "json",
            success: function(response) {
                let pendingContainer = $("#pendingItems");
                pendingContainer.empty();

                // เช็คว่าแถวแรก
                let isFirstRow = true;

                response.forEach(row => {
                    let itemHtml = '';

                    // ถ้าเป็นแถวแรกให้แสดงข้อมูลเหล่านี้
                    if (isFirstRow) {
                        itemHtml = `
                            <div class="container2">
                                <div class="row align-items-end" style="margin-top: 10px; margin-left: -11px;">
                                    
                                    <div class="col-auto" style="width: 216px;">
                                        <label><strong>DOC-No.</strong></label>
                                        <input type="text" class="form-control" name="addDOC-No" id="addDOC-No" value="${row.doc_no}">
                                    </div>
                                    <div class="col-auto custom4-col" style="width: 265px;">
                                        <label><strong>Request Date</strong></label>
                                        <input type="date" class="form-control" name="addRequestDate" id="addRequestDate" value="${row.date}">
                                    </div>
                                    
                                </div>
                            </div>`;

                        // ตั้งค่าให้ isFirstRow เป็น false หลังจากแถวแรก
                        isFirstRow = false;
                    }

                    // แสดงข้อมูลของแต่ละแถวที่เหลือ (ไม่เกี่ยวข้องกับ DO-No, DOC-No, Request Date)
                    itemHtml += `
                        
                        <div class="row align-items-end stock-row" style="margin-top: 15px;">
                            <input type="hidden" value="${row.id}" name="id" readonly>
                            <div class="col-md-3" style="width: 300px;">
                                <label><strong>Part Name</strong></label>
                                <input type="text" class="form-control tpart_name" name="part_name" value="${row.part_name}" readonly>
                            </div>
                            <div class="col-md-1">
                                <label><strong>Barcode</strong></label>
                                <input type="text" class="form-control tbarcode" name="barcode" value="${row.barcode}" readonly>
                            </div>
                            <div class="col-md-1 quantities-container" style="width: 150px;">
                                <label><strong>Quantities</strong></label>
                                <input type="number" class="form-control quantities" name="quantities" value="${row.total_quantities}" data-original-quantities="${row.total_quantities}">
                            </div>
                            <div class="col-md-1 custom1-col">
                                <label><strong>S/N#1</strong></label>
                                <input type="text" class="form-control" name="addS1" value="${row.addS1}">
                            </div>
                            <div class="col-md-1 custom1-col">
                                <label><strong>S/N#2</strong></label>
                                <input type="text" class="form-control" name="addS2"value="${row.addS2}">
                            </div>
                            <div class="col-md-1 custom1-col">
                                <label><strong>S/N#3</strong></label>
                                <input type="text" class="form-control" name="addS3"value="${row.addS3}">
                            </div>
                            <div class="col-md-1 custom1-col">
                                <label><strong>S/N#4</strong></label>
                                <input type="text" class="form-control" name="addS4"value="${row.addS4}">
                            </div>
                            <div class="col-md-1 custom3-col">
                                <label><strong>cost</strong></label>
                                <input type="number" class="form-control" name="addcost"value="${row.cost}">
                            </div>
                            <div class="col-md-1" style="width: 232px;">
                                <label><strong>Remark</strong></label>
                                <input type="text" class="form-control" name="remark" value="${row.remark}">
                            </div>
                            <div class="col-auto" style="width: 150px;">
                                <label><strong>DO-No.</strong></label>
                                <input type="text" class="form-control" name="addDO-No" id="addDO-No" value="${row.do_no}">
                            </div>
                            <div class="col-auto" style="width: 150px;">
                                        <label><strong>INV-No.</strong></label>
                                        <input type="text" class="form-control" name="addINV-No" id="addINV-No" value="${row.inv_no}">
                                    </div>
                                    <div class="col-auto"style="width: 150px;">
                                        <label><strong>Store</strong></label>
                                        <input type="text" class="form-control" name="addStore" id="addStore" value="${row.store}">
                                    </div>
                                    <div class="col-auto" style="width: 150px;">
                                        <label><strong>Outlets</strong></label>
                                        <input type="text" class="form-control" name="addOutlets" id="addOutlets" value="${row.outlets}">
                                    </div>
                             <div class="col-md-1 reserved-checkbox" style="display: none;">
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>`;

                    // เพิ่มแถวที่แสดงข้อมูลลงใน pendingContainer
                    pendingContainer.append(itemHtml);
                });
                 // ✅ ดึงค่า `reserved` ทันทีหลังจากโหลด `stock-row`
                fetchReservedStatus();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching pending data:", error);
            }
        });
    }
    function fetchReservedStatus() {
        $(".stock-row").each(function () {
            let row = $(this);
            let barcode = row.find(".tbarcode").val();
            let partName = row.find(".tpart_name").val();
            let reservedCheckbox = row.find(".reserved-checkbox");

            console.log("Fetching stock reserved status for:", barcode, partName); // ✅ Debug

            $.ajax({
                url: "process/get_stock.php",
                type: "POST",
                data: { barcode: barcode, part_name: partName },
                dataType: "json",
                success: function (data) {
                    console.log("Received reserved data:", data); // ✅ Debug

                    if (data.reserved && data.reserved !== 0) {
                        reservedCheckbox.show();
                        reservedCheckbox.find("input[type='checkbox']").prop("checked", false);
                    } else {
                        reservedCheckbox.hide();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching reserved stock data:", error);
                    console.log("Response Text:", xhr.responseText); // ดูข้อความที่ได้รับจากเซิร์ฟเวอร์
                }
            });
        });
    }

    $(".btn-cancel").click(function() {
        window.history.back();
    });

    $(".btn-savepending").click(function() {
        let formData = [];
        let documentNumber = "<?= $document_number ?>";
        let columnName = "<?= $column_name ?>";
        let status_balance = "<?= $status_balance ?>"; // ✅ เพิ่มค่า status_balance

        // ✅ เช็คว่ามี `#selected-date` ใน DOM หรือไม่
        let selectedDate = $("#addRequestDate").length ? $("#addRequestDate").val().trim() : "";

        // ✅ ถ้าเป็น Waiting to Receive หรือ Process Adjust → ต้องกรอกวันที่
        if ((columnName === "Waiting to Receive" || columnName === "Process Adjust") && selectedDate ===
            "") {
            Swal.fire({
                title: "Warning!",
                text: "Please specify the date before saving the data",
                icon: "warning",
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }
        let allValid = true;
        $(".stock-row").each(function() {
            let id = $(this).find('input[name="id"]').val().trim();
            let part_name = $(this).find('input[name="part_name"]').val().trim();
            let order_reserve = $(this).find('input[name="order_reserve"]').val()?.trim() || "0";
            let barcode = $(this).find('input[name="barcode"]').val().trim();
            let quantities = $(this).find('input[name="quantities"]').val().trim();
            let originalQuantities = $(this).find('input[name="quantities"]').data(
                'original-quantities');
            let remark = $(this).find('input[name="remark"]').val().trim();
            let addS1 = $(this).find('input[name="addS1"]').val()?.trim() || "";
            let addS2 = $(this).find('input[name="addS2"]').val()?.trim() || "";
            let addS3 = $(this).find('input[name="addS3"]').val()?.trim() || "";
            let addS4 = $(this).find('input[name="addS4"]').val()?.trim() || "";
            let cost = $(this).find('input[name="addcost"]').val()?.trim() || "0";
            let user_created = "<?= $user_created ?>";
            let created_at = "<?= $created_at ?>";

            let do_no = $(this).find('input[name="addDO-No"]').val()?.trim() || "";
            let doc_no = $("#addDOC-No").val()?.trim() || "";  
            let inv_no = $(this).find('input[name="addINV-No"]').val()?.trim() || "";
            let store = $(this).find('input[name="addStore"]').val()?.trim() || "";
            let outlets = $(this).find('input[name="addOutlets"]').val()?.trim() || "";

            let isChecked = $(this).find("input[type='checkbox']").prop("checked") ? "1" : "0";
            if (!part_name || !barcode || !quantities) {
                Swal.fire({
                    title: "Warning!",
                    text: "Please fill in all the fields.",
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                });
                return false;
            }

            // หากมีค่าใดๆ ว่างให้แสดงข้อความเตือน
            if (!do_no || !inv_no || !store || !outlets || !selectedDate) {
                Swal.fire({
                    title: "Warning!",
                    text: "Please fill in all the required fields before saving the data.",
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                });
                return; // ป้องกันไม่ให้ส่งข้อมูลหากฟิลด์ว่าง
            }

            if (parseInt(quantities) > parseInt(originalQuantities)) {
                Swal.fire({
                    title: "Warning!",
                    text: "The value of Quantities must be less than or equal to the original value.",
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                }).then(() => {
                    location.reload(); // Reload the page after the alert is closed
                });
                allValid = false;
                return false;
            }

            formData.push({
                id: id,
                part_name: part_name,
                barcode: barcode,
                quantities: quantities,
                document_number: documentNumber,
                column_name: columnName,
                selected_date: selectedDate, // ✅ เพิ่มค่าวันที่ (ถ้ามี)
                remark: remark,
                addS1: addS1,
                addS2: addS2,
                addS3: addS3,
                addS4: addS4,
                cost: cost,
                do_no: do_no,
                doc_no: doc_no,
                inv_no: inv_no,
                store: store,
                outlets: outlets,
                status_balance: status_balance,
                user_created: user_created,
                created_at: created_at,
                order_reserve: order_reserve,
                reserved: isChecked

            });
        });

        if (!allValid || formData.length === 0) {
            return;
        }

        // ✅ แสดง SweetAlert ยืนยันก่อนบันทึก
        Swal.fire({
            title: "Confirm save?",
            text: "Are you sure you want to save this data?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Save it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("📌 Sending Data:", JSON.stringify({
                    stockData: formData
                }));
                $.ajax({
                    url: "process/save_success_balance.php",
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify({
                        stockData: formData
                    }),
                    success: function(response) {
                        console.log("📌 Server Response:", response);
                        if (response.status === "success") {
                            Swal.fire({
                                title: "Save successful!",
                                text: "Data has been saved to Success. Document number: " +
                                    response.document_number,
                                icon: "success",
                                timer: 200000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href =
                                "manage_balance.php"; // ✅ Redirect ไปหน้า manage_balance.php
                            });
                        } else {
                            Swal.fire({
                                title: "An error occurred!",
                                text: response.message,
                                icon: "error",
                                timer: 2500,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("❌ Error:", error);
                        Swal.fire({
                            title: "An error occurred!",
                            text: "Unable to save the data. Please try again.",
                            icon: "error",
                            timer: 2500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });
    $(".btn-updatepending").click(function () {
        let formData = [];
        let documentNumber = "<?= $document_number ?>";
        let columnName = "<?= $column_name ?>";
        let status_balance = "<?= $status_balance ?>"; // ✅ เพิ่มค่า status_balance
        console.log("columnName:", columnName); // ตรวจสอบค่า columnName
        // ✅ เช็คว่ามี `#selected-date` ใน DOM หรือไม่
        let selectedDate = $("#addRequestDate").length ? $("#addRequestDate").val().trim() : "";

        // ✅ ถ้าเป็น Waiting to Receive หรือ Process Adjust → ต้องกรอกวันที่
        if ((columnName === "Waiting to Receive" || columnName === "Process Adjust") && selectedDate ===
            "") {
            Swal.fire({
                title: "Warning!",
                text: "Please specify the date before saving the data",
                icon: "warning",
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }


      // ✅ Check for New Branch or New DVR column names
      if (status_balance === "New Branch" || status_balance === "New DVR") {
        let firstRow = $(".stock-row:first");
        let invNo = firstRow.find('input[name="addINV-No"]').val()?.trim() || "";
        let store = firstRow.find('input[name="addStore"]').val()?.trim() || "";
        let outlets = firstRow.find('input[name="addOutlets"]').val()?.trim() || "";
        let do_no = firstRow.find('input[name="addDO-No"]').val()?.trim() || "";

        console.log("First Row Values:", { invNo, store, outlets, do_no });

        if (!invNo || !store || !outlets || !do_no) {
            Swal.fire({
                title: "Warning!",
                text: "Please fill in all required fields in the first row (INV-No, Store, Outlets, DO-No).",
                icon: "warning",
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        $(".stock-row").each(function () {
            let row = $(this);
            if (row.is(firstRow)) return;

            row.find('input[name="addINV-No"]').val(invNo);
            row.find('input[name="addStore"]').val(store);
            row.find('input[name="addOutlets"]').val(outlets);
            row.find('input[name="addDO-No"]').val(do_no);

            console.log("Updated Row:", {
                invNo: row.find('input[name="addINV-No"]').val(),
                store: row.find('input[name="addStore"]').val(),
                outlets: row.find('input[name="addOutlets"]').val(),
                do_no: row.find('input[name="addDO-No"]').val()
            });
        });
    }


        let allValid = true;
        $(".stock-row").each(function() {
            let id = $(this).find('input[name="id"]').val().trim();
            let part_name = $(this).find('input[name="part_name"]').val().trim();
            let barcode = $(this).find('input[name="barcode"]').val().trim();
            let quantities = $(this).find('input[name="quantities"]').val().trim();
            let originalQuantities = $(this).find('input[name="quantities"]').data(
                'original-quantities');
            let remark = $(this).find('input[name="remark"]').val().trim();
            let addS1 = $(this).find('input[name="addS1"]').val()?.trim() || "";
            let addS2 = $(this).find('input[name="addS2"]').val()?.trim() || "";
            let addS3 = $(this).find('input[name="addS3"]').val()?.trim() || "";
            let addS4 = $(this).find('input[name="addS4"]').val()?.trim() || "";
            let cost = $(this).find('input[name="addcost"]').val()?.trim() || "0";
            let order_reserve = $(this).find('input[name="order_reserve"]').val()?.trim() || "0";
            let user_created = "<?= $user_created ?>";
            let created_at = "<?= $created_at ?>";

            let do_no = $(this).find('input[name="addDO-No"]').val()?.trim() || "";
            let doc_no = $("#addDOC-No").val()?.trim() || "";
            let inv_no = $(this).find('input[name="addINV-No"]').val()?.trim() || "";
            let store = $(this).find('input[name="addStore"]').val()?.trim() || "";
            let outlets = $(this).find('input[name="addOutlets"]').val()?.trim() || "";

            if (!part_name || !barcode || !quantities) {
                Swal.fire({
                    title: "Warning!",
                    text: "Please fill in all the fields.",
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                });
                return false;
            }

          

            formData.push({
                id: id,
                part_name: part_name,
                barcode: barcode,
                quantities: quantities,
                document_number: documentNumber,
                column_name: columnName,
                selected_date: selectedDate, // ✅ เพิ่มค่าวันที่ (ถ้ามี)
                remark: remark,
                addS1: addS1,
                addS2: addS2,
                addS3: addS3,
                addS4: addS4,
                cost: cost,
                do_no: do_no,
                doc_no: doc_no,
                inv_no: inv_no,
                store: store,
                outlets: outlets,
                status_balance: status_balance,
                user_created: user_created,
                created_at: created_at,
                order_reserve: order_reserve
                

            });
        });

        if (!allValid || formData.length === 0) {
            return;
        }

         // ✅ ส่งข้อมูลไปยัง PHP ทันที (ไม่มี SweetAlert ยืนยัน)
        console.log("📌 Sending Data:", JSON.stringify({ stockData: formData }));

        $.ajax({
            url: "process/update_pending_balance.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({ stockData: formData }),
            success: function(response) {
                console.log("📌 Server Response:", response);

                try {
                    if (typeof response === "string") {
                        response = JSON.parse(response); // แปลงเป็น JSON ถ้าจำเป็น
                    }
                } catch (error) {
                    console.error("❌ JSON Parse Error:", error);
                    Swal.fire({
                        title: "An error occurred!",
                        text: "Invalid response format. Please try again.",
                        icon: "error",
                        timer: 2500,
                        showConfirmButton: false
                    });
                    return;
                }

                if (response.status && response.status.toLowerCase() === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Update Successful!',
                        text: 'The data has been successfully updated.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: "An error occurred!",
                        text: response.message || "Unknown error occurred.",
                        icon: "error",
                        timer: 2500,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("❌ AJAX Error:", error);
                console.error("❌ Server Response:", xhr.responseText);

                Swal.fire({
                    title: "An error occurred!",
                    text: "Unable to save the data. Please try again.",
                    icon: "error",
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        });
    });
    

    $(document).on("input", ".quantities", function () {
            let inputField = $(this);
            let inputValueStr = inputField.val().trim(); // เปลี่ยนชื่อเป็น inputValueStr
            
            // ถ้า inputValueStr เป็นค่าว่าง ไม่ต้องแสดงข้อความเตือน
            if (inputValueStr === "") {
                let row = inputField.closest(".stock-row");
                let label = row.find(".quantities-container label strong");
                label.html("Quantities");
                inputField.removeClass("border-danger");
                row.removeAttr("data-exceeded"); // ✅ ลบ attribute ถ้าไม่มีปัญหา
                checkAllRows(); // ✅ ตรวจสอบทุกแถวอีกครั้ง
                return;
            }
            // ตรวจสอบว่าค่า inputValueStr เป็นตัวเลขที่ถูกต้องและไม่มีการนำหน้าด้วยศูนย์
            let isValidNumber = /^[1-9]\d*$/.test(inputValueStr);
            if (!isValidNumber) {
                let row = inputField.closest(".stock-row");
                let label = row.find(".quantities-container label strong");
                label.html("Quantities <br><span style='color: red;'>Please enter a valid number !</span>");
                inputField.addClass("border-danger");
                row.attr("data-exceeded", "true"); // ✅ เพิ่ม attribute เพื่อบอกว่าแถวนี้มีปัญหา
                checkAllRows(); // ✅ ตรวจสอบทุกแถวอีกครั้ง
                return;
            }
            let inputValue = parseInt(inputValueStr) || 0; // ใช้ inputValueStr ในการแปลงเป็นตัวเลข
            let row = inputField.closest(".stock-row"); // ✅ อ้างอิงเฉพาะแถวที่แก้ไข
            let barcode = row.find(".tbarcode").val().trim(); // ✅ ใช้ค่า barcode ของแถวนั้น
            let column_name = $("#selectedColumnName").text(); // อัปเดตชื่อหัวข้อ
            let label = row.find(".quantities-container label strong");
            console.log("✅ Raw column_name value:", $("#selectedColumnName").val()); 
        
            console.log("✅ Column Name Used (trimmed):", `"${column_name}"`);
            console.log("✅ Barcode Used:", barcode);
        
            // ส่ง AJAX เพื่อดึงค่าจากฐานข้อมูล
            $.ajax({
                url: "process/get_stock.php",
                type: "POST",
                data: { barcode: barcode },
                success: function (response) {
                    let stockData = JSON.parse(response);
                    let availableStock = parseInt(stockData.quantities_on_hand) || 0;
        
                    console.log("✅ Available Stock for", barcode, ":", availableStock);
                    console.log("✅ Input Value:", inputValue);
        
                    // ✅ อนุญาตให้ "Next Orders" เกินได้
                    if (column_name === "Next Orders") {
                        label.html("Quantities"); // ✅ เคลียร์ข้อความเตือน
                        inputField.removeClass("border-danger");
                        row.removeAttr("data-exceeded"); // ✅ ลบ attribute ถ้าไม่มีปัญหา
                        $(".btn-savepending").prop("disabled", false); // ✅ ปลดล็อคปุ่ม Save
                        $(".btn-updatepending").prop("disabled", false); // ✅ ปลดล็อคปุ่ม Save
                    }
                    else if(inputValue <= 0) {
                        label.html("Quantities <br><span style='color: red;'>Please enter a valid number !</span>");
                        inputField.addClass("border-danger");
                        row.attr("data-exceeded", "true"); // ✅ เพิ่ม attribute เพื่อบอกว่าแถวนี้มีปัญหา
                        checkAllRows(); // ✅ ตรวจสอบทุกแถวอีกครั้ง
                    }
                    // ❌ ถ้าไม่ใช่ "Next Orders" ต้องไม่เกิน stock
                    else if (inputValue > availableStock) {
                        label.html("Quantities <br><span style='color: red;'>Exceeded stock !</span>");
                        inputField.addClass("border-danger");
                        row.attr("data-exceeded", "true"); // ✅ เพิ่ม attribute เพื่อบอกว่าแถวนี้มีปัญหา
                        checkAllRows(); // ✅ ตรวจสอบทุกแถวอีกครั้ง
                    } else {
                        label.html("Quantities");
                        inputField.removeClass("border-danger");
                        row.removeAttr("data-exceeded"); // ✅ ลบ attribute ถ้าไม่มีปัญหา
                        checkAllRows(); // ✅ ตรวจสอบทุกแถวอีกครั้ง
                    }
                },
                error: function () {
                    console.error("❌ Error fetching stock data.");
                }
            });
        });
        
        // ✅ ฟังก์ชันตรวจสอบทุกแถวว่ามีแถวไหนเกิน stock หรือไม่
        function checkAllRows() {
            let disableSave = false;
            
                $(".stock-row").each(function() {
                    if ($(this).attr("data-exceeded") === "true") {
                        disableSave = true;
                        return false; // ออกจาก loop ทันทีถ้าพบปัญหา
                    }
                });

    
                $(".btn-savepending").prop("disabled", disableSave); // ✅ ปิดหรือปลดล็อคปุ่ม Save ตามเงื่อนไข
                $(".btn-updatepending").prop("disabled", disableSave); // ✅ ปิดหรือปลดล็อคปุ่ม Save ตามเงื่อนไข
        }
    

});



</script>
<style>
    .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<?php include('footer.php'); ?>