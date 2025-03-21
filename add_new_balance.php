<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>

<main id="main" class="main">
    <div class="pagetitle">
        <h1>Sales Record</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Stock</li>
                <li class="breadcrumb-item active"><a href="add_new_balance.php">Sales Record</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- Section for Stock Management -->
    <div class="container1">
        <div class="rounded p-3 bg-white shadow-lg" style="border: 2px solid rgb(109, 109, 109) !important;">

            <div class="icon-container ">
                <!-- ปุ่มเลือกหัวข้อสต็อก -->
                <button class="btn btn-icon card-option" style="margin-left:10px" data-option="New Branch">
                    <img src="assets/img/New Branch.png" alt="Waiting to Receive"
                        style="width: 65px; height: 65px; margin-right: 8px;"> New Branch
                </button>
                <button class="btn btn-icon card-option" style="margin-left:10px" data-option="Replace">
                    <img src="assets/img/Replace.png" alt="Waiting to Receive"
                        style="width: 65px; height: 65px; margin-right: 8px;"> Replace
                </button>
                <button class="btn btn-icon card-option" style="margin-left:10px" data-option="Additionnal">
                    <img src="assets/img/Additionnal.png" alt="Waiting to Receive"
                        style="width: 65px; height: 65px; margin-right: 8px;"> Additionnal
                </button>
                <button class="btn btn-icon card-option" style="margin-left:10px" data-option="New DVR">
                    <img src="assets/img/New DVR.png" alt="Waiting to Receive"
                        style="width: 65px; height: 65px; margin-right: 8px;"> New DVR
                </button>
            </div>
        </div>
    </div>
    <br>

    <!-- Display selected option message -->

    <input type="hidden" id="selectedOptionMessage" class="alert alert-info" style="display:none; font-size: 18px; text-align: center; 
    background-color: #007bff; color: #fff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); padding: 20px; 
    animation: slideIn 0.5s ease-out;">
    <input type="hidden" id="selectedColumnName">


    <!-- Form to manage stock, show when an option is selected -->
    <div class="container1" id="editStockContainer" style="display: none;">
        <div class="card rounded p-3 bg-white shadow-lg" style="border: 2px solid rgb(109, 109, 109) !important;">
            <!-- ✅ ซ่อนฟิลด์แก้ไขข้อมูลไว้ก่อน -->
            <div class="container2" >
                <div class="row align-items-end" style="margin-top: 10px; margin-left: -11px;">
                    <div class="col-auto" style="width: 228px;">
                        <label><strong>DOC-No.</strong></label>
                        <input type="text" class="form-control" name="addDOC-No" id="addDOC-No">
                    </div>
                    <div class="col-auto custom4-col" style="width: 266px;">
                        <label><strong>Request Date</strong></label>
                        <input type="date" class="form-control" name="addRequestDate" id="addRequestDate">
                    </div>
                    <div class="col-auto custom4-col" style="width: 266px;">
                    <button class="btn btn-primary" id="add-row-btn"><strong>Default New Branch</strong></button>
                    </div>
                </ฝdiv>
            </div>
            <div class="row align-items-end stock-row" style="margin-top: 20px;" id="stock-container">
                <div class="col-md-3 position-relative" style="margin-top: 12px; width: 325px;">
                    <label><strong>Part Name</strong></label>
                    <input type="text" class="form-control part-name" name="addpart_name" autocomplete="off">
                    <ul class="dropdown-list list-unstyled"></ul>
                </div>
                <div class="col-md-1 custom-col">
                    <label><strong>Barcode</strong></label>
                    <input type="text" class="form-control barcode" name="addbarcode" readonly>
                </div>
                <div class="col-md-1 custom-col quantities-container" style="width: 150px;">
                    <label><strong>Quantities</strong></label>
                    <input type="number" class="form-control quantities" name="addquantities">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#1</strong></label>
                    <input type="text" class="form-control" name="addS1">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#2</strong></label>
                    <input type="text" class="form-control" name="addS2">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#3</strong></label>
                    <input type="text" class="form-control" name="addS3">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#4</strong></label>
                    <input type="text" class="form-control" name="addS4">
                </div>
                <div class="col-md-1 custom3-col">
                    <label><strong>cost</strong></label>
                    <input type="number" class="form-control" name="addcost">
                </div>
                <div class="col-md-1 custom2-col" style="width: 275px;">
                    <label><strong>Remark</strong></label>
                    <input type="text" class="form-control" name="addremark">
                </div>
                <div class="col-auto" style="width: 150px;">
                        <label><strong>DO-No.</strong></label>
                        <input type="text" class="form-control" name="addDO-No" id="addDO-No">
                </div>
                <div class="col-auto" style="width: 150px;">
                        <label><strong>INV-No.</strong></label>
                        <input type="text" class="form-control" name="addINV-No" id="addINV-No">
                    </div>
                    <div class="col-auto" style="width: 150px;">
                        <label><strong>Store</strong></label>
                        <input type="text" class="form-control" name="addStore" id="addStore">
                    </div>
                    <div class="col-auto" style="width: 150px;">
                        <label><strong>Outlets</strong></label>
                        <input type="text" class="form-control" name="addOutlets" id="addOutlets">
                    </div>
                <div class="col-md-1 d-flex align-items-end" style="margin-right: -75px;">
                    <button class="btn btn-danger remove-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                 <!-- ✅ เพิ่ม Checkbox -->
                <div class="col-md-1 reserved-checkbox" style="display: none;">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>


            
            <!-- Add buttons inside the container -->
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-success custom-btn-size1" id="addStockRow"><i class="fas fa-plus"></i></button>
                <div>
                    <button type="submit" class="btn btn-warning btn-update" id="update"><i class="fas fa-save"></i> update</button>
                    <button type="submit" class="btn btn-success btn-saveaddbalance" id="savepending"><i class="fas fa-save"></i> Save</button>
                    <button class="btn btn-danger btn-cancel"><i class="fas fa-times"></i> Cancel</button>
                    
                </div>
            </div>
        </div>
    </div>

</main><!-- End #main -->

<?php include 'footer.php'; ?>

<!-- Include DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    let formChanged = false; // ติดตามว่ามีการเปลี่ยนแปลงในฟอร์มหรือไม่
    let currentOption = null; // เก็บหัวข้อที่เลือกอยู่ตอนนี้
    let previousOption = null; // เก็บหัวข้อก่อนหน้า

    // ซ่อน Edit Form ไว้ก่อน
    $("#editStockContainer").hide();
    $("#selectedOptionMessage").hide();

    // ตรวจจับการกรอกข้อมูลในฟอร์ม
    $(document).on("input",
        "input[name='part_name'], input[name='barcode'], input[name='quantities'], input[name='remark'], input[name='addS1'], input[name='addS2'], input[name='addS3'], input[name='addS4'], input[name='cost'], input[name='addpart_name'], input[name='addbarcode'], input[name='addquantities'], input[name='addremark'], input[name='addS1'], input[name='addS2'], input[name='addS3'],  input[name='addS4'], input[name='addcost'], input[name='addDO-No'], input[name='addDOC-No'], input[name='addRequestDate'], input[name='addINV-No'], input[name='addStore'], input[name='addOutlets']",
        function() {
            formChanged = true; // เมื่อมีการกรอกข้อมูลในช่องฟอร์ม จะตั้งสถานะว่าเปลี่ยนแปลง
        });

    // เมื่อคลิกปุ่มใน icon-container ให้แสดงฟอร์มแก้ไข
    $(".card-option").click(function() {
        let selectedOption = $(this).data("option");

        // ถ้าหัวข้อที่เลือกซ้ำกับหัวข้อปัจจุบัน → ไม่ต้องทำอะไร
        if (selectedOption === currentOption) return;

        if (formChanged) {
            // เก็บค่าปัจจุบันเป็น previousOption ก่อนจะแสดง SweetAlert
            previousOption = currentOption;
            // ถ้ามีการเปลี่ยนแปลงข้อมูล จะแสดง SweetAlert ให้ยืนยัน
            Swal.fire({
                title: 'Are you sure?',
                text: "You have unsaved changes. Do you want to discard them? ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, discard changes',
                cancelButtonText: 'No, stay here'
            }).then((result) => {
                if (result.isConfirmed) {
                    resetForm(); // รีเซ็ตค่าฟอร์ม
                    $(".stock-row").not(":first").remove(); // ✅ ลบทุกแถว ยกเว้นแถวแรก
                    switchStockOption(selectedOption, $(this)); // เปลี่ยนหัวข้อ
                } else {
                    // ถ้าเลือก No, stay here → คงค่า currentOption เดิมไว้
                    currentOption = previousOption; // คืนค่าเดิม
                    $(".card-option").removeClass("selected").addClass(
                    "dimmed"); // รีเซ็ตทุกปุ่ม
                    $(".card-option[data-option='" + previousOption + "']")
                        .removeClass("dimmed") // ลบ dimmed ออกจากปุ่มเดิม
                        .addClass("selected"); // ใส่ selected ให้ปุ่มเดิม
                }
            });
        } else {
            switchStockOption(selectedOption, $(this)); // เปลี่ยนหัวข้อได้ทันทีถ้าไม่มีการเปลี่ยนแปลง
        }
    });

    function resetForm() {
        formChanged = false; // รีเซ็ตสถานะการเปลี่ยนแปลง
        $("#editStockContainer input").val(""); // เคลียร์ค่าฟอร์ม
    }

    function switchStockOption(option, element) {
        previousOption = currentOption; // เก็บหัวข้อก่อนหน้า
        currentOption = option; // อัปเดตหัวข้อปัจจุบัน
        $("#selectedColumnName").text(option).attr("data-selected-option", option); // อัปเดตชื่อหัวข้อ
        console.log("Updated Column Name:", option); // Debug เพื่อตรวจสอบว่าหัวข้อถูกอัปเดต

        $("#selectedOptionMessage").show();
        $("#editStockContainer").show();

        // ไฮไลต์หัวข้อที่เลือก
        $(".card-option").removeClass("selected").addClass("dimmed");
        element.removeClass("dimmed").addClass("selected");
    }

    $(".card-option").click(function() {
        // ตั้งค่าให้ทุกปุ่มที่ไม่ได้ถูกเลือกมีสีเทาอ่อน
        $(".card-option").removeClass("selected").addClass("dimmed");

        // ตั้งค่าให้ปุ่มที่ถูกเลือกมีสีเดิมของมันเอง
        $(this).removeClass("dimmed").addClass("selected");
    });

    // ฟังก์ชันที่ใช้สำหรับเพิ่มแถวใหม่ในฟอร์ม
    $("#addStockRow").click(function() {
        $("#addStockRow").closest(".d-flex").before(createRow()); // เพิ่มแถวใหม่
        formChanged = true; // ตั้งสถานะการเปลี่ยนแปลงเป็น true
    });

    // การลบแถว
    $(document).on("click", ".remove-row", function() {
        $(this).closest(".stock-row").remove();
        formChanged = true; // ตั้งสถานะการเปลี่ยนแปลงเป็น true
    });

    // เมื่อกดปุ่ม "Cancel"
    $(".btn-cancel").click(function() {
        formChanged = false; // รีเซ็ตสถานะการเปลี่ยนแปลงเมื่อกดปุ่ม Cancel
        $("#editStockContainer input, #editStockContainer select").val(""); // เคลียร์ค่าฟอร์ม
        $(".stock-row").remove(); // ลบแถวที่เพิ่มมา
    });

    // เมื่อกดปุ่ม "Save"
    $(".btn-saveaddbalance").click(function () {
        let allFilled = true;
        let formData = [];
        let barcodeSet = new Set(); // ใช้ตรวจสอบ barcode ซ้ำกัน
        let rowCount = $(".stock-row").length; // นับจำนวนแถว

        // ถ้าไม่มีแถวเลย ให้หยุดทำงานและแจ้งเตือน
        if (rowCount === 0) {
            console.warn("⚠️ No data in the form!");
            Swal.fire({
                title: "Warning!",
                text: "Unable to save because there is no data.",
                icon: "warning",
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        $(".stock-row").each(function (index) {
            let row = $(this);
            let part_name = row.find('input[name="addpart_name"]').val()?.trim() || "";
            let barcode = row.find('input[name="addbarcode"]').val()?.trim() || "";
            let quantities = row.find('input[name="addquantities"]').val()?.trim() || "";
            let remark = row.find('input[name="addremark"]').val()?.trim() || "";
            let addS1 = row.find('input[name="addS1"]').val()?.trim() || "";
            let addS2 = row.find('input[name="addS2"]').val()?.trim() || "";
            let addS3 = row.find('input[name="addS3"]').val()?.trim() || "";
            let addS4 = row.find('input[name="addS4"]').val()?.trim() || "";
            let cost = row.find('input[name="addcost"]').val()?.trim() || "0";
            let column_name = $("#selectedColumnName").text();

            let do_no = row.find('input[name="addDO-No"]').val()?.trim() || "";
            let doc_no = $("#addDOC-No").val()?.trim() || "";
            let rq_date = $("#addRequestDate").val()?.trim() || "";
            let inv_no = row.find('input[name="addINV-No"]').val()?.trim() || "";
            let store = row.find('input[name="addStore"]').val()?.trim() || "";
            let outlets = row.find('input[name="addOutlets"]').val()?.trim() || "";

            // ✅ ดึงค่าจาก checkbox (ถ้าถูกเลือกให้เป็น "1", ถ้าไม่เลือกให้เป็น "0")
            let isChecked = row.find("input[type='checkbox']").prop("checked") ? "1" : "0";

            console.log(`🔹 Row ${index + 1}:`, {
                part_name,
                barcode,
                quantities,
                rq_date,
                column_name,
                isChecked // ✅ แสดงค่า checkbox
            });
            if (!rq_date ){
                Swal.fire({
                    title: "Warning!",
                    text: `Please specify the date before saving.`,
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                });
                allFilled = false;
                return false; // หยุดการทำงานถ้าพบว่ามีข้อมูลไม่ครบ
            }

            // ตรวจสอบว่า `column_name` ไม่ว่าง
            if (!part_name || !barcode || !quantities || !column_name) {
                console.error(`❌ Row ${index + 1} has incomplete data!`, {
                    part_name,
                    barcode,
                    quantities,
                    rq_date,
                    column_name
                });

                Swal.fire({
                    title: "Warning!",
                    text: `Please fill in all the fields or the barcode is duplicate.`,
                    icon: "warning",
                    timer: 2500,
                    showConfirmButton: false
                });
                allFilled = false;
                return false; // หยุดการทำงานถ้าพบว่ามีข้อมูลไม่ครบ
            }

            // ตรวจสอบว่า barcode ซ้ำในเอกสารเดียวกันหรือไม่
            // if (barcodeSet.has(barcode)) {
            //     console.warn(`⚠️ Duplicate barcode at row ${index + 1}: ${barcode}`);
            //     Swal.fire({
            //         title: "Warning!",
            //         text: `Barcode '${barcode}' has already been used (Row ${index + 1})`,
            //         icon: "warning",
            //         timer: 2000,
            //         showConfirmButton: false
            //     });
            //     allFilled = false;
            //     return false;
            // }

            barcodeSet.add(barcode);

            formData.push({
                part_name: part_name,
                barcode: barcode,
                quantities: quantities,
                remark: remark,
                column_name: column_name,
                do_no: do_no,
                doc_no: doc_no,
                rq_date: rq_date,
                inv_no: inv_no,
                store: store,
                outlets: outlets,
                addS1: addS1,
                addS2: addS2,
                addS3: addS3,
                addS4: addS4,
                cost: cost,
                checkbox_value: isChecked // ✅ เพิ่มค่า checkbox
            });
        });

        // ถ้ามีช่องว่างให้หยุดทำงาน
        if (!allFilled) {
            console.error("🚨 Stopped working due to incomplete data");
            return;
        }

        console.log("✅ JSON Data to be sent:", JSON.stringify(formData));

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
                $.ajax({
                    url: "process/save_pendingForbalance.php",
                    type: "POST",
                    data: { stockData: JSON.stringify(formData) },
                    success: function(response) {
                        try {
                            let res = typeof response === "string" ? JSON.parse(response) : response;
                            console.log("🔹 Response from Server:", res);

                            if (res.status === "success") {
                                // แสดงข้อความ Save สำเร็จและตัวเลือกให้ไปหน้า Manage Balance
                                Swal.fire({
                                    title: "Save successful!",
                                    text: "Data has been saved to Pending. Document number: " +
                                        response.document_number,
                                    icon: "success",
                                    showCancelButton: true,
                                    confirmButtonText: 'Go to Balance Control',
                                    cancelButtonText: 'Stay here'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // ไปที่หน้า manage_balance.php
                                        window.location.href =
                                            "manage_balance.php"; // เปลี่ยนเส้นทางไปยัง manage_balance.php
                                    } else {
                                        location.reload(); // รีเฟรชหน้าเว็บ
                                    }

                                });    

                                $(".stock-row").remove();
                                $("#select-status").val("");
                                // $("#addDO-No").val("");
                                $("#addDOC-No").val("");
                                $("#addRequestDate").val("");
                                // $("#addINV-No").val("");
                                // $("#addStore").val("");
                                // $("#addOutlets").val("");
                                $("#selectedColumnName").text("");

                            } else {
                                console.error("❌ Error from Server:", res.message);
                                Swal.fire({ title: "An error occurred!", text: res.message, icon: "error" });
                            }
                        } catch (error) {
                            console.error("❌ JSON Parse Error:", error);
                            Swal.fire({ title: "An error occurred!", text: "Invalid JSON. Please check the response.", icon: "error" });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("❌ AJAX Error:", status, error);
                        Swal.fire({ title: "An error occurred!", text: "Unable to save the data. Please try again.", icon: "error" });
                    }
                });
            }
        });
    });


    // ✅ เรียก `fetchDropdown()` เมื่อพิมพ์ในช่อง `Part_Name`
    $(document).on("input", ".part-name", function() {
        let input = $(this);
        let query = input.val().trim();
        let dropdown = input.siblings(".dropdown-list"); // หา dropdown ที่อยู่ในแถวเดียวกัน
        let barcodeInput = input.closest(".stock-row").find(".barcode"); // หา input ของ barcode ในแถวเดียวกัน
        let row = input.closest(".stock-row"); // ค้นหาแถวของ input

        if (query.length >= 1) {
            fetchDropdown(query, dropdown, input);
            // checkReserved(query, row); // ✅ ตรวจสอบค่า reserved
        } else {
            dropdown.hide();
            barcodeInput.val(""); // ✅ รีเซ็ตค่า barcode เป็นค่าว่างเมื่อ Part Name เป็นค่าว่าง
            row.find(".reserved-checkbox").hide(); // ✅ ซ่อน checkbox ถ้าไม่มี Part Name
        }
    });

    // ✅ ฟังก์ชันเช็คค่า `reserved`
function checkReserved(partName, row) {
    console.log("🔍 Checking reserved for part name:", partName);

    $.ajax({
        url: "process/get_stock.php",
        type: "POST",
        data: { part_name: partName },
        success: function(response) {
            try {
                let stockData = JSON.parse(response);
                let reserved = parseInt(stockData.reserved) || 0;

                console.log("✅ Reserved for", partName, ":", reserved);

                // ซ่อน checkbox ถ้าจำนวน reserved = 0
                if (reserved > 0) {
                    row.find(".reserved-checkbox").show(); // ✅ แสดง checkbox
                } else {
                    row.find(".reserved-checkbox").hide(); // ✅ ซ่อน checkbox
                }
            } catch (error) {
                console.error("❌ JSON Parse Error:", error);
            }
        },
        error: function () {
            console.error("❌ Error fetching stock data.");
        }
    });
}


    // ✅ ฟังก์ชันดึงข้อมูลจากฐานข้อมูล
function fetchDropdown(query, dropdown, input) {
    fetch("includes/fetch_part_name.php?query=" + encodeURIComponent(query))
        .then(response => response.json())
        .then(data => {
            dropdown.html(''); // ล้าง Dropdown ก่อนเติมข้อมูลใหม่

            if (data.success && data.data.length > 0) {
                data.data.forEach(item => {
                    let li = $("<li>").text(item.part_name)
                        .css({
                            "padding": "10px",
                            "cursor": "pointer",
                            "border-bottom": "1px solid #ddd"
                        })
                        .hover(
                            function() {
                                $(this).css("background-color", "#d0d0d0");
                            },
                            function() {
                                $(this).css("background-color", "#fff");
                            }
                        )
                        .click(function() {
                            input.val(item.part_name); // ✅ ใส่ค่า Part Name
                            let row = input.closest(".stock-row"); // ✅ ค้นหาแถวปัจจุบัน
                            let barcodeInput = row.find(".barcode");

                            barcodeInput.val(item.barcode); // ✅ ใส่ค่า Barcode อัตโนมัติ

                            dropdown.hide();
                            // checkReserved(item.part_name, row); // ✅ เช็คค่า reserved
                        });

                    dropdown.append(li);
                });
                dropdown.show();
            } else {
                dropdown.append('<li class="text-muted text-center">No results found</li>').show();
            }
        })
        .catch(error => {
            console.error('Error fetching part names:', error);
        });
}

    // ปิด Dropdown เมื่อคลิกข้างนอก
    $(document).click(function(e) {
        if (!$(e.target).closest(".part-name, .dropdown-list").length) {
            $(".dropdown-list").hide();
        }
    });

    function createRow() {
        return `
                    <div class="row align-items-end stock-row" style="margin-top: 10px;">
                <div class="col-md-3 position-relative" style="margin-top: 12px; width: 325px;">
                    <label><strong>Part Name</strong></label>
                    <input type="text" class="form-control part-name" name="addpart_name" autocomplete="off">
                    <ul class="dropdown-list list-unstyled"></ul>
                </div>
                <div class="col-md-1 custom-col">
                    <label><strong>Barcode</strong></label>
                    <input type="text" class="form-control barcode" name="addbarcode" readonly>
                </div>
                <div class="col-md-1 custom-col quantities-container"style="width: 150px;">
                    <label><strong>Quantities</strong></label>
                    <input type="number" class="form-control quantities" name="addquantities">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#1</strong></label>
                    <input type="text" class="form-control" name="addS1">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#2</strong></label>
                    <input type="text" class="form-control" name="addS2">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#3</strong></label>
                    <input type="text" class="form-control" name="addS3">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#4</strong></label>
                    <input type="text" class="form-control" name="addS4">
                </div>
                <div class="col-md-1 custom3-col">
                    <label><strong>cost</strong></label>
                    <input type="number" class="form-control" name="addcost">
                </div>
                <div class="col-md-1 custom2-col" style="width: 275px;">
                    <label><strong>Remark</strong></label>
                    <input type="text" class="form-control" name="addremark">
                </div>
                <div class="col-auto" style="width: 150px;">
                        <label><strong>DO-No.</strong></label>
                        <input type="text" class="form-control" name="addDO-No" id="addDO-No">
                </div>
                <div class="col-auto" style="width: 150px;">
                        <label><strong>INV-No.</strong></label>
                        <input type="text" class="form-control" name="addINV-No" id="addINV-No">
                    </div>
                    <div class="col-auto" style="width: 150px;">
                        <label><strong>Store</strong></label>
                        <input type="text" class="form-control" name="addStore" id="addStore">
                    </div>
                    <div class="col-auto" style="width: 150px;">
                        <label><strong>Outlets</strong></label>
                        <input type="text" class="form-control" name="addOutlets" id="addOutlets">
                    </div>
                <div class="col-md-1 d-flex align-items-end" style="margin-right: -75px;">
                    <button class="btn btn-danger remove-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                 <!-- ✅ เพิ่ม Checkbox -->
                <div class="col-md-1 reserved-checkbox" style="display: none;">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
    `;
    }

        // เมื่อกดปุ่ม Add Row
    $("#add-row-btn").click(function () {
        $.ajax({
            url: "process/get_master_balance.php", // URL สำหรับดึงข้อมูลจากฐานข้อมูล
            type: "GET",
            dataType: "json",
            success: function (response) {
    console.log("Response from server:", response); // Debug ข้อมูลที่ส่งกลับมา
    if (response.success && response.data.length > 0) {
        // จัดเรียงข้อมูลตาม row_number
        response.data.sort((a, b) => parseInt(a.row_number) - parseInt(b.row_number));

        // ลบแถวที่มีอยู่ทั้งหมด ยกเว้นแถวแรก (แถวที่ 0)
        $(".stock-row").not(":first").remove();

        // รีเซ็ตค่าของแถวแรก (แถวที่ 0)
        let firstRow = $(".stock-row:first");
        resetRow(firstRow);

        // วนลูปสร้างแถวใหม่ตามข้อมูลที่จัดเรียงแล้ว
        response.data.forEach(function (item) {
            let rowNumber = parseInt(item.row_number); // ดึง row_number จากฐานข้อมูล
            if (rowNumber === 0) {
                // ถ้า row_number = 0 ให้เติมข้อมูลในแถวแรก
                populateRow(firstRow, item);
            } else {
                // ถ้า row_number > 0 ให้สร้างแถวใหม่
                let newRow = $(newCreateRow());
                populateRow(newRow, item);
                $("#stock-container").append(newRow); // เพิ่มแถวใหม่ใน container
            }
        });
    } else {
        console.error("❌ No data found in the database.");
        Swal.fire({
            title: "Error",
            text: "No data found in the database.",
            icon: "error",
        });
    }
},
            error: function (xhr, status, error) {
                console.error("❌ Error fetching data:", error);
                Swal.fire({
                    title: "Error",
                    text: "Unable to fetch data from the database.",
                    icon: "error",
                });
            },
        });
    });
    
    // ฟังก์ชันสำหรับรีเซ็ตค่าในแถว
    function resetRow(row) {
        row.find("input").val(""); // ล้างค่าทั้งหมดใน input
        row.find(".reserved-checkbox").hide(); // ซ่อน checkbox
    }
    
    // ฟังก์ชันสำหรับเติมข้อมูลในแถว
    function populateRow(row, data) {
        row.find('input[name="addpart_name"]').val(data.part_name || "");
        row.find('input[name="addbarcode"]').val(data.barcode || "");
        row.find('input[name="addquantities"]').val(data.quantities || "");
        row.find('input[name="addS1"]').val(data.addS1 || "");
        row.find('input[name="addS2"]').val(data.addS2 || "");
        row.find('input[name="addS3"]').val(data.addS3 || "");
        row.find('input[name="addS4"]').val(data.addS4 || "");
        row.find('input[name="addcost"]').val(data.cost || "");
        row.find('input[name="addremark"]').val(data.remark || "");
        row.find('input[name="addDO-No"]').val(data.do_no || "");
        row.find('input[name="addINV-No"]').val(data.inv_no || "");
        row.find('input[name="addStore"]').val(data.store || "");
        row.find('input[name="addOutlets"]').val(data.outlets || "");

        // แสดง checkbox ถ้าจำเป็น
        if (data.reserved && parseInt(data.reserved) > 0) {
            row.find(".reserved-checkbox").show();
            row.find(".reserved-checkbox input[type='checkbox']").prop("checked", true);
        }
    }
    
    // ฟังก์ชันสำหรับสร้างแถวใหม่
    function newCreateRow() {
        return `
            <div class="row align-items-end stock-row" style="margin-top: 10px;">
                <div class="col-md-3 position-relative" style="margin-top: 12px; width: 325px;">
                    <label><strong>Part Name</strong></label>
                    <input type="text" class="form-control part-name" name="addpart_name" autocomplete="off">
                    <ul class="dropdown-list list-unstyled"></ul>
                </div>
                <div class="col-md-1 custom-col">
                    <label><strong>Barcode</strong></label>
                    <input type="text" class="form-control barcode" name="addbarcode" readonly>
                </div>
                <div class="col-md-1 custom-col quantities-container" style="width: 150px;">
                    <label><strong>Quantities</strong></label>
                    <input type="number" class="form-control quantities" name="addquantities">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#1</strong></label>
                    <input type="text" class="form-control" name="addS1">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#2</strong></label>
                    <input type="text" class="form-control" name="addS2">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#3</strong></label>
                    <input type="text" class="form-control" name="addS3">
                </div>
                <div class="col-md-1 custom1-col">
                    <label><strong>S/N#4</strong></label>
                    <input type="text" class="form-control" name="addS4">
                </div>
                <div class="col-md-1 custom3-col">
                    <label><strong>cost</strong></label>
                    <input type="number" class="form-control" name="addcost">
                </div>
                <div class="col-md-1 custom2-col" style="width: 275px;">
                    <label><strong>Remark</strong></label>
                    <input type="text" class="form-control" name="addremark">
                </div>
                <div class="col-auto" style="width: 150px;">
                    <label><strong>DO-No.</strong></label>
                    <input type="text" class="form-control" name="addDO-No" id="addDO-No">
                </div>
                <div class="col-auto" style="width: 150px;">
                    <label><strong>INV-No.</strong></label>
                    <input type="text" class="form-control" name="addINV-No" id="addINV-No">
                </div>
                <div class="col-auto" style="width: 150px;">
                    <label><strong>Store</strong></label>
                    <input type="text" class="form-control" name="addStore" id="addStore">
                </div>
                <div class="col-auto" style="width: 150px;">
                    <label><strong>Outlets</strong></label>
                    <input type="text" class="form-control" name="addOutlets" id="addOutlets">
                </div>
                <div class="col-md-1 d-flex align-items-end" style="margin-right: -75px;">
                    <button class="btn btn-danger remove-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
                <div class="col-md-1 reserved-checkbox" style="display: none;">
                    <label class="switch">
                        <input type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </div>
            </div>
        `;
    }
     // การเพิ่มแถวใหม่ 14 แถวเมื่อคลิกปุ่ม "Add Row"

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
            let barcode = row.find(".barcode").val().trim(); // ✅ ใช้ค่า barcode ของแถวนั้น
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
                        $("#savepending").prop("disabled", false); // ✅ ปลดล็อคปุ่ม Save
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

    
            $("#savepending").prop("disabled", disableSave); // ✅ ปิดหรือปลดล็อคปุ่ม Save ตามเงื่อนไข
        }
            // ซ่อนปุ่ม Add Row ไว้ก่อน
    $("#add-row-btn").hide();

// เมื่อคลิกปุ่มใน icon-container
$(".card-option").click(function() {
    let selectedOption = $(this).data("option");

    // ถ้าเลือก New Branch ให้แสดงปุ่ม Add Row
    if (selectedOption === "New Branch") {
        $("#stock-container").show(); 
        $("#add-row-btn").show();
        $("#update").show(); // แสดงปุ่ม Update
    }else if(selectedOption === "New DVR"){
        $("#stock-container").show(); 
        $("#add-row-btn").hide();
        $("#update").show(); // แสดงปุ่ม Update
    } 
    else {
        $("#stock-container").show(); 
        $("#add-row-btn").hide();
        $("#update").hide(); // ซ่อนปุ่ม Update
        // ลบแถวทั้งหมด ยกเว้นแถวแรก
        $(".stock-row").not(":first").remove();

        // รีเซ็ตค่าของแถวแรก
        $(".stock-row:first input").val("");
    }
});
        
$("#update").click(function () {
    // ดึงค่าจากแถวที่ 0 (แถวแรก)
    let firstRow = $(".stock-row:first");
    let invNo = firstRow.find('input[name="addINV-No"]').val()?.trim() || "";
    let store = firstRow.find('input[name="addStore"]').val()?.trim() || "";
    let outlets = firstRow.find('input[name="addOutlets"]').val()?.trim() || "";
    let do_no = firstRow.find('input[name="addDO-No"]').val()?.trim() || "";
    // ตรวจสอบว่ามีค่าหรือไม่
    if (!invNo && !store && !outlets) {
        Swal.fire({
            title: "Warning!",
            text: "Please fill in at least one field (INV-No, Store, or Outlets) in the first row before updating.",
            icon: "warning",
            timer: 2500,
            showConfirmButton: false
        });
        return;
    }

    // วนลูปผ่านแต่ละแถวและอัปเดตค่า
    $(".stock-row").each(function () {
        let row = $(this);

        // อัปเดตค่าในแต่ละแถว (ยกเว้นแถวแรก)
        if (row.is(firstRow)) return; // ข้ามแถวแรก

        row.find('input[name="addINV-No"]').val(invNo);
        row.find('input[name="addStore"]').val(store);
        row.find('input[name="addOutlets"]').val(outlets);
        row.find('input[name="addDO-No"]').val(do_no);
    });

    // แสดงข้อความแจ้งเตือนสำเร็จ
    Swal.fire({
        title: "Updated!",
        text: "Values have been updated for all rows.",
        icon: "success",
        timer: 2000,
        showConfirmButton: false
    });
});

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

.card-option.selected {
    border: 2px solid black;
}

/* สไตล์การทำให้ปุ่มที่ไม่ได้เลือกจาง */
.card-option.dimmed {
    background-color: rgb(231, 231, 231) !important;
    color: rgb(94, 93, 93) !important;
    opacity: 1;
    border: 2px solid rgb(185, 185, 185);
    opacity: 0.5;
    /* ทำให้หัวข้อที่ไม่ได้เลือกจาง */
}

.card-option:focus {
    outline: none;
    /* เอาเส้นกรอบสีฟ้าออก */
    box-shadow: none;
    /* ป้องกันเอฟเฟกต์เงาที่เกิดจาก focus */
}

/* สีพื้นหลังของ card ตามประเภท */
.card-option[data-option="New Branch"] {
    background-color: #FFF777;
    color: black;
}

.card-option[data-option="Replace"] {
    background-color: #FF6962D1;
    color: black;
}

.card-option[data-option="Additionnal"] {
    background-color: #8BADD3;
    color: black;
}

.card-option[data-option="New DVR"] {
    background-color: #92CA68;
    color: black;
}

/* รูปแบบการแสดงข้อความที่เลือก */
#selectedOptionMessage {
    animation: slideIn 0.5s ease-out;
    background-color: rgb(66, 66, 66);
    color: white;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    font-size: 18px;
    text-align: center;
}

#selectedOptionMessage i {
    font-size: 24px;
    margin-right: 10px;
}

/* เพิ่มการเคลื่อนไหว */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.custom-btn-size1 {
    height: 35px;
    font-size: 14px;
    width: 40px;
    min-width: 10px;
    padding: 5px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-option {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 150px;
    height: 150px;
    border-radius: 8px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    text-align: center;
    border: 2px solid rgb(185, 185, 185);
    font-size: 16px;
    font-weight: bold;
    color: white;
    cursor: pointer;
}

.card-option:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    border-color: rgb(0, 0, 0);
}

.card-option i {
    font-size: 32px;
    /* Increase icon size for better visibility */
    margin-bottom: 10px;
    /* Increased margin for more space */
}

.card-option p {
    font-size: 14px;
    color: #333;
    margin: 0;
}

.card-option a {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.dropdown-list {
    width: 94%;
    position: absolute;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1050;
    top: 100%;
    display: none;
}

.dropdown-list li {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #ddd;
    list-style-type: none;
}

.dropdown-list li:hover {
    background-color: #f8f9fa;
}

.stock-row {
    position: relative;
}

.card {
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.alert-info {
    font-size: 18px;
    text-align: center;
}

.icon-container {
    display: flex;
    flex-wrap: wrap;
    /* ทำให้ขึ้นบรรทัดใหม่เมื่อจอเล็ก */
    gap: 10px;
    /* กำหนดระยะห่างระหว่างปุ่ม */
    justify-content: flex-start;
    /* เรียงชิดซ้าย */
    align-items: flex-start;
    /* จัดให้การ์ดเรียงแนวเดียวกันด้านบน */
}
</style>