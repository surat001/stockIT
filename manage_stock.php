<?php include 'header.php'; ?>
<?php include 'navbar.php'; ?>
<?php include 'sidebar.php'; ?>
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Stock Management</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item">Stock</li>
                <li class="breadcrumb-item active"><a href="manage_stock.php">Stock Management</a></li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <!-- Section for Stock Management -->
    <div class="container1">
        <div class="rounded p-3 bg-white shadow-lg" style="border: 2px solid rgb(109, 109, 109) !important;">

            <div class="icon-container">
                <!-- ปุ่มเลือกหัวข้อสต็อก -->
                <button class="btn btn-icon card-option" data-option="Next Orders">
                    <img src="assets/img/Next Orders.png" alt="Next Orders"
                        style="width: 65px; height: 65px; margin-right: 8px;"> Next Orders
                </button>
                <button class="btn btn-icon card-option" data-option="Reserved">
                    <img src="assets/img/Reserved.png" alt="Reserved"
                        style="width: 65px; height: 65px; margin-right: 8px;"> Reserved
                </button>
                <button class="btn btn-icon card-option" data-option="Process Adjust">
                    <img src="assets/img/Process Adjust.png" alt="Process Adjust"
                        style="width: 65px; height: 65px; margin-right: 8px;"> Process Adjust
                </button>
                <button class="btn btn-icon card-option" data-option="Claim warranty">
                    <img src="assets/img/Claim warranty.png" alt="Claim warranty"
                        style="width: 60px; height: 60px; margin-right: 8px;"> Claim warranty
                </button>
                <button class="btn btn-icon card-option" data-option="Borrow">
                    <img src="assets/img/Borrow.png" alt="Borrow"
                        style="width: 72px; height: 72px; margin-right: 8px;"> </i> Borrow
                </button>
                <button class="btn btn-icon card-option" data-option="Damage">
                    <img src="assets/img/Damage.png" alt="Damage"
                        style="width: 65px; height: 65px; margin-right: 8px;"> Damage
                </button>
                <button class="btn btn-icon card-option" data-option="Remark">
                    <img src="assets/img/Remark.png" alt="Remark"
                        style="width: 65px; height: 65px; margin-right: 8px;"> Remark
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
            <div class="row align-items-end stock-row" style="margin-top: 20px;">
                <div class="col-md-3 position-relative">
                    <label><strong>Part Name</strong></label>
                    <input type="text" class="form-control part-name" name="part_name" autocomplete="off">
                    <ul class="dropdown-list list-unstyled"></ul>
                </div>
                <div class="col-md-3 barcode-container">
                    <label><strong>Barcode</strong></label>
                    <input type="text" class="form-control barcode" name="barcode" readonly>
                </div>
                <div class="col-md-2 quantities-container">
                    <label><strong>Quantities</strong></label>
                    <input type="number" class="form-control quantities" name="quantities"min="1">
                </div>
                <!-- 🔹 ฟิลด์ Reserve (ซ่อนเริ่มต้น) -->
                <div class="col-md-2 reserve-container" style="display: none;">
                    <label><strong>Reserve</strong></label>
                    <input type="number" class="form-control reserve" name="reserve" readonly>
                </div>
                <div class="col-md-3">
                    <label><strong>Remark</strong></label>
                    <input type="text" class="form-control" name="remark">
                </div>
                <div class="col-auto nextorder">
                    <button class="btn btn-warning open-reserve" disabled>
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                </div>
                <div class="col-auto d-flex align-items-end" id="i-remove">
                    <button class="btn btn-danger remove-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Add buttons inside the container -->
            <div class="d-flex justify-content-between mt-3">
                <button class="btn btn-success custom-btn-size1" id="addStockRow"><i class="fas fa-plus"></i></button>
                <div>
                    <button class="btn btn-success btn-savepending" id="savepending"><i class="fas fa-save"></i> Save</button>
                    <button class="btn btn-danger btn-cancel"><i class="fas fa-times"></i> Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <!-- 🔹 Modal ฟอร์ม Reserve -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- ✅ เพิ่ม modal-dialog-centered -->
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Reserve</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reserveForm">
                        <div class="row mb-3">
                            <div class="col-md-6 existingNumbe">
                                <label><strong>Existing number</strong></label>
                                <input type="number" class="form-control" id="existingNumber" readonly>
                            </div>
                            <div class="col-md-6 reserveQuantity" >
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
                            <button type="button" class="btn btn-danger ms-2" data-bs-dismiss="modal">Cancel</button>
                        </div>
                        </div>
                    </form>
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
    $(document).on("input", "input[name='part_name'], input[name='barcode'], input[name='quantities'], input[name='remark']", function() {
        formChanged = true; // เมื่อมีการกรอกข้อมูลในช่องฟอร์ม จะตั้งสถานะว่าเปลี่ยนแปลง
    });

   // เมื่อคลิกปุ่มใน icon-container ให้แสดงฟอร์มแก้ไข
$(".card-option").click(function() {
    let selectedOption = $(this).data("option");

    // ถ้าหัวข้อที่เลือกซ้ำกับหัวข้อปัจจุบัน → ไม่ต้องทำอะไร
    if (selectedOption === currentOption) return;

    if (formChanged) {
        previousOption = currentOption;
        // ถ้ามีการเปลี่ยนแปลงข้อมูล จะแสดง SweetAlert ให้ยืนยัน
        Swal.fire({
            title: 'Are you sure?',
            text: "You have unsaved changes. Do you want to discard them?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, discard changes',
            cancelButtonText: 'No, stay here'
        }).then((result) => {
            if (result.isConfirmed) {
                resetForm(); // รีเซ็ตค่าฟอร์ม
                $(".stock-row").not(":first").remove(); // ✅ ลบทุกแถว ยกเว้นแถวแรก

                // ✅ ซ่อนช่อง Reserve และปรับขนาด Quantities
                $(".reserve-container").hide(); // ซ่อนช่อง Reserve
                $(".quantities-container").removeClass("col-md-2").addClass("col-md-2"); // ปรับขนาด Quantities
                $(".quantities").prop("readonly", false); // รีเซ็ตให้ Quantities แก้ไขได้
                $(".open-reserve").prop("disabled", true); // ปิดปุ่ม Reserve

                switchStockOption(selectedOption, $(this)); // เปลี่ยนหัวข้อ

                // ✅ รีเซ็ตข้อความแจ้งเตือน Exceeded available stock!
                $(".quantities-container label strong").html("Quantities");
                $(".quantities").removeClass("border-danger");
                $(".stock-row").removeAttr("data-exceeded"); // ✅ ลบ attribute ถ้าไม่มีปัญหา

                // ✅ ปลดล็อคปุ่ม Save
                $("#savepending").prop("disabled", false);
                
                checkAllRows(); // ✅ ตรวจสอบทุกแถวอีกครั้ง
            } else {
                // ถ้าเลือก No, stay here → คงค่า currentOption เดิมไว้
                currentOption = previousOption; // คืนค่าเดิม
                $(".card-option").removeClass("selected").addClass("dimmed"); // รีเซ็ตทุกปุ่ม
                $(".card-option[data-option='" + previousOption + "']")
                    .removeClass("dimmed") // ลบ dimmed ออกจากปุ่มเดิม
                    .addClass("selected"); // ใส่ selected ให้ปุ่มเดิม
            }
        });
    } else {
        switchStockOption(selectedOption, $(this)); // เปลี่ยนหัวข้อได้ทันทีถ้าไม่มีการเปลี่ยนแปลง

        // ✅ รีเซ็ตค่าต่าง ๆ ทันทีที่เปลี่ยนหมวดหมู่
        $(".quantities-container label strong").html("Quantities");
        $(".quantities").removeClass("border-danger");
        $(".stock-row").removeAttr("data-exceeded");
        $("#savepending").prop("disabled", false);
        checkAllRows();
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
        // ซ่อนหรือแสดงส่วนของ Quantities ตามค่า data-option
        if (option === "Remark") {
            $(".quantities-container").hide();
        } else {
            $(".quantities-container").show();
        }
        if (option === "Next Orders") {
            $(".nextorder").show();
        } else {
            $(".nextorder").hide();
        }
        // ไฮไลต์หัวข้อที่เลือก
        $(".card-option").removeClass("selected").addClass("dimmed");
        element.removeClass("dimmed").addClass("selected");
        return option;
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
        $("#savepending").prop("disabled", false);
    });

    // เมื่อกดปุ่ม "Save"
    $(".btn-savepending").click(function() {
        formChanged = false; // รีเซ็ตสถานะการเปลี่ยนแปลงเมื่อกดปุ่ม Save
        let allFilled = true;
        let formData = [];
        let barcodeSet = new Set();
         // ดึง user_id และ user_name จาก session
        let userId = $("#user-id").val();  // สมมติว่า user-id ถูกเก็บใน input hidden หรือ meta tag
        let userCreated = $("#user-name").val(); // สมมติว่า user_name ถูกเก็บใน input hidden หรือ meta tag
        $(".stock-row").each(function() {
            let part_name = $(this).find('input[name="part_name"]').val().trim();
            let barcode = $(this).find('input[name="barcode"]').val().trim();
            let quantities = $(this).find('input[name="quantities"]').val() ? $(this).find('input[name="quantities"]').val().trim() : "0";
            let remark = $(this).find('input[name="remark"]').val().trim();
            let reserve = $(this).find('input[name="reserve"]').val() ? $(this).find('input[name="reserve"]').val().trim() : "0"; // ✅ ถ้าไม่มีค่าให้เป็น "0"
            let column_name = $("#selectedColumnName").text();
             // ถ้า Reserve ไม่มีค่า, ตั้งค่าเป็น 0
            // ถ้า Reserve ไม่มีค่า, ตั้งค่าเป็น 0
            if (!reserve) {
                reserve = 0;
            }
            if(column_name !== 'Remark' && quantities <= 0){
                allFilled = false;
            }
            if (column_name === "Remark") {
                if (!part_name || !barcode || !remark || column_name === "") {
                    allFilled = false;
                }
            } else {
                if (!part_name || !barcode || !quantities || column_name === "") {
                    allFilled = false;
                }
            }

            if (barcodeSet.has(barcode)) {
                Swal.fire({
                    title: "Warning!",
                    text: "Barcode '" + barcode + "' Already used in this list.",
                    icon: "warning",
                    timer: 2000,
                    showConfirmButton: false
                });
                allFilled = false;
                return false;
            }

            barcodeSet.add(barcode);

            formData.push({
                part_name: part_name,
                barcode: barcode,
                quantities: quantities,
                reserve: reserve, // ส่งค่า Reserve ที่อาจจะเป็น 0
                remark: remark,
                column_name: column_name,
                user_id: userId,   // เพิ่ม user_id
                user_created: userCreated  // เพิ่ม user_created
            });
        });

        if (!allFilled) {
            Swal.fire({
                title: "Warning!",
                text: "Please fill in all the fields or check for duplicate Barcode or Quantities must be greater than 0.",
                icon: "warning",
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

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
                    url: "process/save_pending.php",
                    type: "POST",
                    data: {
                        stockData: JSON.stringify(formData)
                    },
                    success: function(response) {
                        console.log(response); // ตรวจสอบ response ใน console

                        if (response.status === "success") {
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
                        } else {
                            Swal.fire({
                                title: "An error occurred!",
                                text: response.message,
                                icon: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "An error occurred!",
                            text: "Unable to save the data. Please try again.",
                            icon: "error",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });

    // เรียกฟังก์ชัน fetchDropdown เมื่อพิมพ์ในช่อง Part_Name
    $(document).on("input", ".part-name", function() {
        let input = $(this);
        let query = input.val().trim(); // ดึงค่าจากช่อง Part Name
        let dropdown = input.siblings(".dropdown-list"); // เลือก dropdown ที่อยู่ใกล้ที่สุด
        let row = input.closest(".stock-row"); // หาค่าในแถวเดียวกัน
        let barcodeInput = input.closest(".stock-row").find(".barcode"); // หา input ของ barcode ในแถวเดียวกัน
        
    console.log(`🔎 Input Changed: Part Name: ${query}`);
        if (query.length >= 1) {
            fetchDropdown(query, dropdown, input); // เรียกฟังก์ชันดึงข้อมูลจากฐานข้อมูล
        } else {
            dropdown.hide(); // ถ้าไม่มีการพิมพ์คำค้นหาก็ซ่อน dropdown
            barcodeInput.val(""); // ✅ รีเซ็ตค่า barcode เป็นค่าว่างเมื่อ Part Name เป็นค่าว่าง
        }
    });

    // 🔹 เมื่อคลิกเลือกจาก Dropdown
    $(document).on("click", ".dropdown-item", function() {
        let selectedText = $(this).text(); // ดึงค่าที่คลิกเลือก
        let selectedBarcode = $(this).attr("data-barcode"); // ดึง Barcode ที่แนบมา
        let row = $(this).closest(".stock-row"); // หาแถวที่เลือกอยู่
        let input = row.find(".part-name"); // ช่อง Part Name ในแถวเดียวกัน
        let barcodeInput = row.find(".barcode"); // ช่อง Barcode ในแถวเดียวกัน

        input.val(selectedText); // ใส่ค่าใน Part Name
        barcodeInput.val(selectedBarcode); // อัปเดตค่าในช่อง Barcode
        $(this).parent().hide(); // ซ่อน Dropdown
    });

    function fetchDropdown(query, dropdown, input) {
        fetch("includes/fetch_part_name.php?query=" + encodeURIComponent(query))
            .then(response => response.json()) // ดึงข้อมูลจากฐานข้อมูล
            .then(data => {
                dropdown.html(''); // ล้างข้อมูลใน dropdown ก่อน

                if (data.success && data.data.length > 0) {
                    data.data.forEach(item => {
                        let li = $("<li>").text(item.part_name) // เพิ่มชื่อ part_name ลงใน li
                            .css({
                                "padding": "10px",
                                "cursor": "pointer",
                                "border-bottom": "1px solid #ddd"
                            })
                            .hover(function() {
                                $(this).css("background-color",
                                    "#d0d0d0"); // เมื่อ hover ให้เปลี่ยนสีพื้น
                            }, function() {
                                $(this).css("background-color", "#fff");
                            })
                            .click(function() {
                                input.val(item.part_name); // เมื่อคลิกเติมข้อมูลลงในฟอร์ม

                                // ตรวจสอบว่า `barcode` มีค่าหรือไม่
                                if (item.barcode) {
                                    input.closest(".stock-row").find(".barcode").val(item
                                        .barcode);
                                }

                                dropdown.hide(); // ซ่อน dropdown หลังจากเลือก
                            });

                        dropdown.append(li); // เพิ่มข้อมูลเข้า dropdown
                    });
                    dropdown.show(); // แสดง dropdown
                } else {
                    dropdown.append('<li class="text-muted text-center">No results found</li>')
                        .show(); // ถ้าไม่มีข้อมูล
                }
            })
            .catch(error => {
                console.error('Error fetching part names:', error);
                dropdown.append('<li class="text-muted text-center">Error fetching data</li>')
                    .show(); // หากเกิดข้อผิดพลาด
            });
    }

    // ปิด Dropdown เมื่อคลิกข้างนอก
    $(document).click(function(e) {
        if (!$(e.target).closest(".part-name, .dropdown-list").length) {
            $(".dropdown-list").hide();
        }
    });

    function createRow() {
        let quantitiesContainer = `
                <div class="col-md-2 quantities-container">
                    <label><strong>Quantities</strong></label>
                    <input type="number" class="form-control quantities" name="quantities">
                </div>
        `;

        let nextorder1 = '';
        let nextorder2 = ''; // ✅ กำหนดค่าเริ่มต้นให้ nextorder2
        if (currentOption === "Remark") {
            quantitiesContainer = ''; // ซ่อน Quantities ถ้าเป็น Remark
        }
        if (currentOption === "Next Orders") {
            nextorder1 = `
            <div class="col-auto nextorder">
                <button class="btn btn-warning open-reserve" disabled>
                    <i class="fas fa-shopping-cart"></i>
                </button>
            </div>
            `;
            nextorder2=`
                <!-- 🔹 ฟิลด์ Reserve (ซ่อนเริ่มต้น) -->
                <div class="col-md-2 reserve-container" style="display: none;">
                    <label><strong>Reserve</strong></label>
                    <input type="number" class="form-control reserve" name="reserve" readonly>
                </div>
            `;

        }

        return `
            <div class="row align-items-end stock-row" style="margin-top: 20px;">
                <div class="col-md-3 position-relative">
                    <label><strong>Part Name</strong></label>
                    <input type="text" class="form-control part-name" name="part_name" autocomplete="off">
                    <ul class="dropdown-list list-unstyled"></ul>
                </div>
                <div class="col-md-3 barcode-container">
                    <label><strong>Barcode</strong></label>
                    <input type="text" class="form-control barcode" name="barcode" readonly>
                </div>
                ${quantitiesContainer}
                ${nextorder2}
                <div class="col-md-3">
                    <label><strong>Remark</strong></label>
                    <input type="text" class="form-control" name="remark">
                </div>
                ${nextorder1}
                <div class="col-auto d-flex align-items-end">
                    <button class="btn btn-danger remove-row">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        `;
    }


    let currentRow;

    // 🔹 ตรวจสอบว่า Part Name, Barcode, Quantities กรอกครบหรือไม่
    $(document).on("input", ".part-name, .barcode, .quantities", function() {
        let row = $(this).closest(".row");
        let partName = row.find(".part-name").val().trim();
        let barcode = row.find(".barcode").val().trim();
        let quantities = row.find(".quantities").val();

        console.log(`Part Name: ${partName}, Barcode: ${barcode}, Quantities: ${quantities}`);

        if (partName !== "" && barcode !== "" && quantities !== "" && parseInt(quantities) > 0) {
            row.find(".open-reserve").prop("disabled", false); // เปิดปุ่ม
        } else {
            row.find(".open-reserve").prop("disabled", true); // ปิดปุ่ม
        }
    });

    // 🔹 เมื่อกดปุ่มสีเหลือง 🟡 (เปิดฟอร์ม)
    $(document).on("click", ".open-reserve", function() {
        currentRow = $(this).closest(".row");
        let existingNumber = currentRow.find(".quantities").val();

        // ตั้งค่าใน Modal
        $("#existingNumber").val(existingNumber);
        $("#reserveQuantity").val("");
        $("#saveReserve").prop("disabled", false);

        // เปิด Modal
        $("#reserveModal").modal("show");
    });

       // 🔹 ตรวจสอบค่าที่ป้อนใน Reserve
       $("#reserveQuantity").on("input", function() {
            let maxVal = parseInt($("#existingNumber").val(), 10);
            let currentVal = Number($(this).val().replace(/^0+/, '')) || 0;
            let isValidNumber = currentVal > 0; // ✅ ตรวจสอบว่าเป็นตัวเลขที่ถูกต้อง

            // ✅ อัปเดตค่าที่แสดง (ลบเลข 0 ที่นำหน้า)
            $(this).val(currentVal);

            // ✅ ตรวจสอบว่าค่าที่ป้อนถูกต้อง
            if (!isValidNumber && currentVal > maxVal) {
                let label = $(this).closest(".reserveQuantity").find("label strong");
                label.html("reserve <span style='color: red;'>Invalid number!</span>");
                $("#saveReserve").prop("disabled", true);
            } else {
                let label = $(this).closest(".reserveQuantity").find("label strong");           
                label.html("Quantity to reserve");
                $("#saveReserve").prop("disabled", false);
            }

            // ✅ แสดงข้อความแจ้งเตือนเมื่อค่ามากกว่า maxVal
            if (currentVal > maxVal) {
                $("#error-message").show();
                $("#saveReserve").prop("disabled", true);
            } else {
                $("#error-message").show();
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

    // let currentRow; // ตัวแปรเก็บแถวที่กดปุ่ม

    // ฟังก์ชันปรับขนาดช่องเมื่อแสดง Reserve
    function adjustColumnSize(row) {
        let reserveVisible = row.find(".reserve-container").is(":visible"); // เช็คว่า Reserve ถูกแสดงหรือไม่

        if (reserveVisible) {
            row.find(".quantities-container").removeClass("col-md-2").addClass("col-md-1"); // ลดขนาด Quantities
            row.find(".reserve-container").removeClass("col-md-2").addClass("col-md-1"); // ตั้งขนาด Reserve
            row.find(".quantities").prop("readonly", true); // ทำให้ Quantities เป็น readonly
        } else {
            row.find(".barcode-container").removeClass("col-md-2").addClass("col-md-3"); // กลับขนาด Barcode เดิม
            row.find(".quantities-container").removeClass("col-md-2").addClass("col-md-2"); // กลับขนาด Quantities เดิม
            row.find(".quantities").prop("readonly", false); // รีเซ็ตให้ Quantities แก้ไขได้
        }
    }

    // ✅ เมื่อกดปุ่มสีเหลือง (เปิด Modal เฉพาะแถวที่กด)
    $(document).on("click", ".open-reserve", function() {
        currentRow = $(this).closest(".stock-row"); // กำหนดว่าแถวไหนที่กดปุ่ม

        let existingNumber = currentRow.find(".quantities").val(); // ดึงค่า Quantities ของแถวนี้

        // ตั้งค่าใน Modal
        $("#existingNumber").val(existingNumber);
        $("#reserveQuantity").val("");
        $("#error-message").show(); // ✅ แสดงข้อความแจ้งเตือนตลอดเวลา
        $("#saveReserve").prop("disabled", false); // ✅ ปิดการ disable ปุ่ม Save

        // เปิด Modal
        $("#reserveModal").modal("show");
    });

    // ✅ เมื่อกด Save ใน Modal (เพิ่มค่าเฉพาะแถวที่กด)
    $("#reserveForm").on("submit", function(e) {
        e.preventDefault();
        let reserveValue = $("#reserveQuantity").val() || 0; // ดึงค่าที่ผู้ใช้กรอก

        // ✅ แสดงช่อง Reserve เฉพาะแถวที่กด Save
        currentRow.find(".reserve-container").show();
        currentRow.find(".reserve").val(reserveValue);

        // ✅ ปรับขนาดคอลัมน์เฉพาะแถวที่กด
        adjustColumnSize(currentRow);

        // ✅ ปิด Modal
        $("#reserveModal").modal("hide");
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
                        label.html("Quantities <br><span style='color: red;'>Exceeded available stock !</span>");
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
            let column_name = $("#selectedColumnName").text().trim(); // ✅ ใช้ `trim()` เพื่อลบช่องว่าง
        
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
        
            $("#savepending").prop("disabled", disableSave); // ✅ ปิดหรือปลดล็อคปุ่ม Save ตามเงื่อนไข
        }

        
        

});
</script>

<style>
#i-remove{
    margin-right: -19px;
}
.nextorder{
    margin-right: -19px;
    margin-left: 9px;
}
/* สไตล์การแสดงผลปุ่มที่เลือก */
.icon-container {
    display: flex;
    flex-wrap: wrap;
    /* ทำให้ขึ้นบรรทัดใหม่เมื่อจอเล็ก */
    gap: 20px;
    /* กำหนดระยะห่างระหว่างปุ่ม */
    justify-content: flex-start;
    /* เรียงชิดซ้าย */
    align-items: flex-start;
    /* จัดให้การ์ดเรียงแนวเดียวกันด้านบน */
}
.card-option.selected {
    border: 2px solid black;
}

/* สไตล์การทำให้ปุ่มที่ไม่ได้เลือกจาง */
/* Card ที่ไม่ได้เลือก */
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

/* สีพื้นหลังของ card ตามประเภท */
.card-option[data-option="Waiting to Receive"] {
    background-color: #63C719B2;
    color: black;
}

.card-option[data-option="Process Adjust"] {
    background-color: #8BADD3;
    color: black;
}

.card-option[data-option="Claim warranty"] {
    background-color: #FFA778;
    color: black;
}

.card-option[data-option="Borrow"] {
    background-color: #FF6962;
    color: black;
}

.card-option[data-option="Damage"] {
    background-color: #C2A8EB;
    color: black;
}

.card-option[data-option="Remark"] {
    background-color: #FFA7BD;
    color: black;
}

.card-option[data-option="Next Orders"] {
    background-color: #59B8F0EB;
    color: black;
}

.card-option[data-option="Reserved"] {
    background-color: #92E3DD;
    color: black;
}

.card-option[data-option="Used"] {
    background-color: #FFF777;
    color: black;
}

#selectedColumnName {
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
    color: #333;
}

/* เมื่อ hover */
.card-option:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    border-color: rgb(0, 0, 0);
}

/* Card ที่ถูกเลือก */

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
</style>