<?php
include('header.php');
include('navbar.php');
include('sidebar.php');
include('includes/db_connect.php');


$document_number = isset($_GET['doc']) ? base64_decode($_GET['doc']) : null;
$column_name = ''; // ตัวแปรเก็บ column_name
$created_at = ''; // ตัวแปรเก็บวันที่สร้าง
$total_quantities = 0; // ตัวแปรเก็บจำนวนรวมของสินค้าทั้งหมด
$created_at_update = ''; // ตัวแปรเก็บวันที่อัพเดท
$user_created = ''; // ตัวแปรเก็บผู้สร้าง
$user_operation = ''; // ตัวแปรเก็บผู้ดำเนินการ

if (!empty($document_number)) {
    $sql = "SELECT column_name, created_at, date, created_at_update, Month, Years, Week, user_created ,user_operation , SUM(quantities) as total_quantities 
            FROM pending WHERE document_number = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $document_number);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $column_name = $row['column_name'];
        $created_at = $row['created_at'];
        $total_quantities = $row['total_quantities'];
        $Month = $row['Month'];
        $Years = $row['Years'];
        $Week = $row['Week'];
        $created_at_update = $row['created_at_update'];
        $user_created = isset($row['user_created']) ? $row['user_created'] : 'N/A'; 
        $user_operation = isset($row['user_operation']) ? $row['user_operation'] : 'N/A';
        $date = $row['date'];
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
                Column : <strong class='text-primary'><?= htmlspecialchars($column_name) ?></strong>
            </h3>
        </div>
        <!-- Total Cost Section -->
        <?php if ($column_name !== "Remark") : ?>
        <div class="d-flex align-items-center justify-content-center mb-3 p-2 rounded shadow-sm"
            style="background: #e9f7ef; border-left: 5px solid #e9f7ef; border-radius: 8px; display: inline-block; min-width: 200px;">
            
            <h4 class="fw-bold m-0" style="color:rgb(31, 102, 48);">
                <i class="fa-solid fa-box-archive"></i> Total : <?= htmlspecialchars($total_quantities) ?>
            </h4>
        </div>
        <?php endif; ?>

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
                    <input type="date" class="form-control selected-date" name="date" id="selected-date" <?php echo ($date == "0000-00-00") ? "" : "value='$date'"; ?>style="max-width: 200px !important;">
            </div>
        </div>
        <?php endif; ?>



        <br>
        <div id="pendingItems">
            <!-- Data will be inserted here via JavaScript -->
        </div>

        <div class="d-flex justify-content-between mt-3">
            <div>
                <a href="manage_balance.php" class="btn btn-secondary">Back</a>
            </div>
            <div>
                
                <button class="btn btn-warning btn-updatepending" ><i class="fas fa-rotate"></i> Save</button>
                
                <button class="btn btn-success btn-savepending" ><i class="fas fa-save"></i> Save & Submit</button>
                <?php if ($column_name === "Next Orders") : ?>
                    <!-- ปุ่มเปิด Datepicker -->
                <button class="btn btn-info" id="btnDate" style="background-color: #4C6085; color: white;">
                    <i class="fa">&#xf073;</i> Date
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>


    <!-- 🔹 Modal ฟอร์ม Reserve -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <!-- ✅ เพิ่ม modal-dialog-centered -->
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Reserve</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reserveForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label><strong>Existing number</strong></label>
                                <input type="number" class="form-control" id="existingNumber" readonly>
                            </div>
                            <div class="col-md-6">
                                <label><strong>Quantity to reserve</strong></label>
                                <input type="number" class="form-control" id="reserveQuantity" min="0">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <p id="error-message" class="text-danger" style="margin-top: 10px;">
                                Enter the desired amount to reserve !
                            </p>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success" id="saveReserve">Save</button>
                                <button type="button" class="btn btn-danger ms-2"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

<!-- jQuery และ Bootstrap Datepicker JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<!-- โหลด jQuery -->

<script>
$(document).ready(function() {

    let documentNumber = "<?= $document_number ?>";
    let columnName = "<?= $column_name ?>"; // ✅ ดึงค่า column_name มาตรวจสอบ

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

                response.forEach(row => {
                    let itemHtml = `
                        <div class="row align-items-end stock-row">
                            <div class="col-md-3">
                                <label><strong>Part Name</strong></label>
                                <input type="text" class="form-control" name="part_name" value="${row.part_name}" readonly>
                            </div>
                            <div class="col-auto" style="width: 270px;">
                                <label><strong>Barcode</strong></label>
                                <input type="text" class="form-control barcode" name="barcode" value="${row.barcode}" readonly>
                            </div>
                             <?php if ($column_name !== "Remark"&&$column_name !== "Next Orders") : ?>
                            <div class="col-md-2 quantities-container">
                                <label><strong>Quantities</strong></label>
                                <input type="number" class="form-control quantities" name="quantities" value="${row.total_quantities}" data-original-quantities="${row.total_quantities}">
                            </div>
                            <?php endif; ?>
                            <?php if ($column_name === "Next Orders") : ?>
                                 <div class="col-md-1 quantities-container">
                                    <label><strong>Quantities</strong></label>
                                    <input type="number" class="form-control quantities" name="quantities" value="${row.total_quantities}" data-original-quantities="${row.total_quantities}">
                                </div>
                                <!-- 🔹 ฟิลด์ Reserve (ซ่อนเริ่มต้น) -->
                                <div class="col-md-1 reserve-container">
                                    <label><strong>Reserve</strong></label>
                                    <input type="number" class="form-control reserve" name="reserve" value="${row.order_reserve}"readonly>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-2" style="width: 325px;">
                                <label><strong>Remark</strong></label>
                                <input type="text" class="form-control" name="remark" value="${row.remark}">
                            </div>
                            <?php if ($column_name === "Next Orders") : ?>
                                <div class="col-md-2">
                                    <label><strong>Date</strong></label>
                                    <input type="date" class="form-control selected-date" name="date" id="selected-date" value="${row.date}">
                                </div>
                            <div class="col-auto nextorder" style="padding-left: 0.75rem;">
                                <button class="btn btn-warning open-reserve">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                            </div>
                            
                            <?php endif; ?>
                        </div><br>`;
                    pendingContainer.append(itemHtml);
                });
                
            },
            error: function(xhr, status, error) {
                console.error("Error fetching pending data:", error);
            }
        });
    }



    $(".btn-cancel").click(function() {
        window.history.back();
    });

    $(".btn-savepending").click(function() {
        let formData = [];
        let documentNumber = "<?= $document_number ?>";
        let columnName = "<?= $column_name ?>";
             // ✅ ดึงค่าจาก input นอก row
             let selectedDateGlobal = "0000-00-00";   
        if (columnName === "Process Adjust"||columnName === "Next Orders"||columnName === "Waiting to Receive" ){
            selectedDateGlobal = $("#selected-date").val().trim();    
        }else{
            selectedDateGlobal = "0000-00-00";
        }
        
        console.log("📌 Selected Date (Global):", selectedDateGlobal);
        let allValid = true;
        $(".stock-row").each(function() {
            let part_name = $(this).find('input[name="part_name"]').val().trim();
            let barcode = $(this).find('input[name="barcode"]').val().trim();
            let quantitiesInput = $(this).find('input[name="quantities"]');
            let quantities = quantitiesInput.length ? quantitiesInput.val().trim() : "0"; // ถ้าไม่มี ให้เป็น "0"
            let originalQuantities = quantitiesInput.length ? quantitiesInput.data('original-quantities') : "0";
            let remark = $(this).find('input[name="remark"]').val().trim();
            let reserve = $(this).find('input[name="reserve"]').val();
            let selectedDate = $(this).find('.selected-date').length ? $(this).find('.selected-date').val().trim() : selectedDateGlobal;
            let user_created = "<?= $user_created ?>";
            let created_at = "<?= $created_at ?>";
            console.log("📌 Selected Date (Row):", selectedDate);
            if (!part_name || !barcode || (!quantitiesInput.length && columnName !== "Remark")) {
                Swal.fire({
                    title: "Warning!",
                    text: "Please fill in all the fields.",
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                });
                allValid = false;
                return false;
            }
    
            if ((columnName !== "Minimum" && columnName !== "Maximum") && parseInt(quantities) > parseInt(originalQuantities)) {
                Swal.fire({
                    title: "Warning!",
                    text: "The value of Quantities must be less than or equal to the original value.",
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                }).then(() => {});
                allValid = false;
                return false;
            }
    
            // ✅ ตรวจสอบว่า selectedDate ไม่เป็นค่าว่าง
            if ((columnName === "Waiting to Receive" || columnName === "Process Adjust" || columnName === "Next Orders") && selectedDate === "") {
                Swal.fire({
                    title: "Warning!",
                    text: "Please specify the date before saving the data.",
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                });
                allValid = false;
                return false;
            }
    
            formData.push({
                part_name: part_name,
                barcode: barcode,
                quantities: quantities,
                document_number: documentNumber,
                column_name: columnName,
                selected_date: selectedDate, // ✅ เพิ่มค่าวันที่ (ถ้ามี)
                remark: remark,
                reserve: reserve,
                user_created: user_created,
                created_at: created_at
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
                console.log("📌 Button clicked - Preparing AJAX request...");
                console.log("📌 Sending Data:", JSON.stringify({
                    stockData: formData
                }));
    
                $.ajax({
                    url: "process/save_success.php",
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
                                text: "Data has been saved to Success. Document number: " + response.document_number,
                                icon: "success",
                                timer: 200000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "manage_balance.php"; // ✅ Redirect ไปหน้า manage_balance.php
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

    $(document).on("click", ".open-reserve", function() {
    currentRow = $(this).closest(".row");
    let existingNumber = parseInt(currentRow.find(".quantities").val()) || 0;
    let existingNumber1 = parseInt(currentRow.find(".reserve").val()) || 0;

    console.log("Existing Number:", existingNumber);
    console.log("Reserve Quantity:", existingNumber1);

    // ตั้งค่าใน Modal
    $("#existingNumber").val(existingNumber);
    $("#reserveQuantity").val(existingNumber1);
    $("#saveReserve").prop("disabled", false);
    $("#error-message").hide();

    // ✅ ตรวจสอบว่าจำนวนสำรองต้องไม่เกินจำนวนที่มีอยู่
    if (existingNumber1 > existingNumber) {
        $("#error-message").show();
        $("#saveReserve").prop("disabled", true);
    } else if (existingNumber1 <= 0) {
        $("#error-message").show();
        $("#saveReserve").prop("disabled", true);
    } else {
        $("#error-message").show();
        $("#saveReserve").prop("disabled", false);
    }

    // เปิด Modal
    $("#reserveModal").modal("show");
    });


    // 🔹 ตรวจสอบค่าที่ป้อนใน Reserve
    $("#reserveQuantity").on("input", function() {
        let maxVal = parseInt($("#existingNumber").val(), 10);
        let currentVal = parseInt($(this).val(), 10) || 0;

        if (currentVal > maxVal) {
            $("#error-message").show();
            $("#saveReserve").prop("disabled", true);
        } else {
            $("#saveReserve").prop("disabled", false);
        }
    });

    // 🔹 เมื่อกด Save ใน Modal
    $("#reserveForm").on("submit", function(e) {
        e.preventDefault();
        let reserveValue = $("#reserveQuantity").val() || 0;

        // แสดงช่อง Reserve ในแถวหลัก
        currentRow.find(".reserve-container").show();
        currentRow.find(".reserve").val(reserveValue);

        // ปิด Modal
        $("#reserveModal").modal("hide");
    });

    $(".btn-updatepending").click(function () {
            let updateData = [];
            let selectedDate = "0000-00-00"; // ประกาศตัวแปร selectedDate ภายนอกเงื่อนไข
            let hasMismatch = false; // ✅ ตัวแปรตรวจสอบว่าเจอค่าผิดปกติหรือไม่
            // ✅ เช็คว่ามี `#selected-date` ใน DOM หรือไม่
            if (columnName === "Waiting to Receive" || columnName === "Process Adjust") {
                selectedDate = $("#selected-date").length ? $("#selected-date").val().trim() : "";
            }
        
            $(".stock-row").each(function () {
                let row = $(this);
        
                let barcode = row.find("input[name='barcode']").val().trim();
                let part_name = row.find("input[name='part_name']").val().trim();
                let quantities = row.find("input[name='quantities']").val().trim();
                let originalQuantities = row.find("input[name='quantities']").attr('data-original-quantities') || "0";
                let remarkValue = row.find("input[name='remark']").val().trim();
                if (columnName !== "Waiting to Receive" && columnName !== "Process Adjust") {
                    selectedDate = row.find(".selected-date").val() ? row.find(".selected-date").val().trim() : "0000-00-00"; // ถ้าไม่มีให้เป็น "0000-00-00"
                }
                let reserve = row.find("input[name='reserve']").val() ? row.find("input[name='reserve']").val().trim() : "0"; // ถ้าไม่มีให้เป็น "0"
        
                // ✅ ตรวจสอบว่ามีแถวไหนที่ `quantities` ไม่ตรงกับ `originalQuantities`
                if (columnName === "Waiting to Receive" && quantities !== originalQuantities) {
                    hasMismatch = true;
                }

                if (barcode && part_name) {
                    updateData.push({
                        barcode: barcode,
                        part_name: part_name,
                        document_number: documentNumber, // ส่งค่า document_number
                        remark: remarkValue,
                        quantities: quantities,
                        columnNameUP: columnName, // ส่งค่า column_name
                        selected_date: selectedDate,
                        reserve: reserve
                    });
                }
            });
            // ✅ ถ้ามีแถวไหนที่ `quantities !== originalQuantities` หยุดและแจ้งเตือน
            if (hasMismatch) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Quantities Mismatch',
                    text: 'Some quantities do not match the original values.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload(); // ✅ รีโหลดหน้าเว็บหลังจากกด OK
                });

                return false; // ✅ หยุดการทำงาน ไม่ให้ส่งข้อมูลไปบันทึก
            }
            
            if (updateData.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Data to Update',
                    text: 'Please fill in the required fields before updating.',
                    confirmButtonText: 'OK'
                });
                return;
            }
        
            $.ajax({
                url: "process/update_pending.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({ updateData: updateData }),
                success: function (response) {
                    console.log("📌 Server Response:", response);
        
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
                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed!',
                        text: 'An error occurred while updating. Please try again.',
                        confirmButtonText: 'OK'
                    });
                }
            });
    });

    // โหลด Datepicker เมื่อหน้าเว็บโหลดเสร็จ
    // เมื่อกดปุ่ม Date
    $("#btnDate").click(function () {
        // เปิด Flatpickr ให้เลือกวันที่
        flatpickr("#btnDate", {
            dateFormat: "Y-m-d",
            defaultDate: new Date(), // ตั้งค่าวันที่เริ่มต้นเป็นวันนี้
            onClose: function (selectedDates, dateStr) {
                // ✅ อัปเดตค่าใน input.selected-date
                $(".selected-date").val(dateStr);
            }
        }).open();
    });
    $(document).on("input", ".quantities", function () {
        let inputField = $(this);
        let inputValue = parseInt(inputField.val()) || 0;
        
        let row = inputField.closest(".stock-row"); // ✅ อ้างอิงเฉพาะแถวที่แก้ไข
        let barcode = row.find(".barcode").val(); // ✅ ดึงค่า barcode ของแถวนั้น
        let column_name = "<?php echo $column_name; ?>"; // ✅ ดึงค่า column_name มาใช้
        let label = row.find(".quantities-container label strong");
    
        // ตรวจสอบว่า barcode ไม่ใช่ undefined ก่อนที่จะเรียกใช้ trim()
        if (barcode !== undefined) {
            barcode = barcode.trim();
        } else {
            console.error("❌ Error: Barcode is undefined.");
            return;
        }
    
        console.log("✅ Column Name Used:", column_name);
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
                
                // ❌ ถ้าไม่ใช่ "Next Orders" ต้องไม่เกิน stock
                else if (inputValue > availableStock) {
                    label.html("Quantities <br><span style='color: red;'>Exceeded available stock !</span>");
                    inputField.addClass("border-danger");
                    row.attr("data-exceeded", "true"); // ✅ เพิ่ม attribute เพื่อบอกว่าแถวนี้มีปัญหา
                } else {
                    label.html("Quantities");
                    inputField.removeClass("border-danger");
                    row.removeAttr("data-exceeded"); // ✅ ลบ attribute ถ้าไม่มีปัญหา
                }
    
                checkAllRows(); // ✅ ตรวจสอบทุกแถวอีกครั้ง
            },
            error: function () {
                console.error("❌ Error fetching stock data.");
            }
        });
    });
    
    // ✅ ฟังก์ชันตรวจสอบทุกแถวว่ามีแถวไหนเกิน stock หรือไม่
    function checkAllRows() {
        let disableSave = false;
        let column_name = "<?php echo $column_name; ?>"; // ✅ ดึงค่า column_name มาใช้
    
        if (column_name === "Next Orders") {
            disableSave = false; // ✅ ไม่ต้องตรวจสอบค่าเมื่อเป็น "Next Orders"
        } else {
            $(".stock-row").each(function() {
                if ($(this).attr("data-exceeded") === "true") {
                    disableSave = true;
                    return false; // ออกจาก loop ทันทีถ้าพบปัญหา
                }
            });
        }
    
        $(".btn-savepending").prop("disabled", disableSave); // ✅ ปิดหรือปลดล็อคปุ่ม Save ตามเงื่อนไข
        $(".btn-updatepending").prop("disabled", disableSave); // ✅ ปิดหรือปลดล็อคปุ่ม Save ตามเงื่อนไข
    }
        
   

});
// ตรวจสอบว่า DOM โหลดเสร็จแล้ว
document.addEventListener("DOMContentLoaded", function() {
    console.log("✅ Flatpickr Loaded!");

    let columnName = "<?= $column_name ?>"; // ✅ ดึงค่า column_name มาตรวจสอบ

    // ถ้า column_name เป็น "Process Adjust" หรือ "Waiting to Receive" ไม่ต้องทำคำสั่งพวกนี้
    if (columnName === "Process Adjust" || columnName === "Waiting to Receive") {
        return;
    }

    let datePicker = flatpickr("#selected-date", {
        dateFormat: "Y-m-d", // แสดงวันที่ในรูปแบบ ปี-เดือน-วัน
        enableTime: false, // ปิดการเลือกเวลา
        locale: "th" // ตั้งค่าเป็นภาษาไทย
    });

    // เมื่อกดปุ่มให้เปิด Datepicker
    document.getElementById("btnDate").addEventListener("click", function() {
        console.log("✅ Button Clicked! Opening Datepicker...");
        datePicker.open(); // เปิด Datepicker
    });
    
});
</script>

<?php include('footer.php'); ?>