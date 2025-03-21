<?php include('header.php'); ?>
<?php include('navbar.php');?>
<?php include('sidebar.php'); ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Balance Control</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
          <li class="breadcrumb-item">Stock</li>
          <li class="breadcrumb-item active"><a href="manage_balance.php">Balance Control</a></li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <!-- Section for Stock Management -->
    <div class="container1">
        <div class="rounded p-3 bg-white shadow-sm" style="border: 2px solid rgb(109, 109, 109) !important;">
            <div class="d-flex align-items-center">
                <!-- ปุ่ม Add Product -->
                <button class="btn btn-warning text-black me-2 " id="showpendingproduct" style="font-size: 18px;">
                    <i class="bi bi-hourglass-split text-black"></i> <strong>Pending</strong>
                </button>

                <button class="btn btn-success me-2" id="showsuccessBtn"style="font-size: 18px;">
                    <i class="bi bi-check-circle-fill text-white"></i> <strong>Success</strong>
                </button>

                <button class="btn btn-danger " id="showdeleteBtn"style="font-size: 18px;">
                    <i class="bi bi-x-octagon-fill"></i> <strong>Delete</strong>
                </button>
            </div>
        </div>
    </div>
    <br>

    <!--show pending Table -->
    <div class="container1" id="showpendingTable" style="display: none;">
        <h4 class="mb-3"><strong>Pending</strong></h4>
        <div class="table-responsive">
            <table id="pendingTable" class="table table-striped table-bordered table-hover">
                <thead class="tabletest">
                    <tr>
                        <th>Document ID</th>
                        <th>Column Name</th>
                        <th>Total Quantities</th>                   
                        <th>Balance</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody> <!-- ข้อมูลจะถูกโหลดผ่าน AJAX -->
            </table>
        </div>
    </div>

    <!--show success Table -->
    <div class="container1" id="showsuccessTable" style="display: none;">
        <h4 class="mb-3"><strong>Success</strong></h4>
        <div class="table-responsive">
            <table id="successTable" class="table table-striped table-bordered table-hover">
                <thead class="tabletest">
                    <tr>
                        <th>Document ID</th>
                        <th>DO_NO</th>
                        <th>Balance</th>
                        <th>Total Quantities</th>
                        <th>Column Name</th>
                        <th>Date</th>  
                        <th>Status</th>
                       
                    </tr>
                </thead>
                <tbody></tbody> <!-- ข้อมูลจะถูกโหลดผ่าน AJAX -->
            </table>
        </div>
    </div>

    <!--show delete Table -->
    <div class="container1" id="showdeleteTable" style="display: none;">
        <h4 class="mb-3"><strong>Delete</strong></h4>
        <div class="table-responsive">
            <table id="deleteTable" class="table table-striped table-bordered table-hover">
                <thead class="tabletest">
                    <tr>
                        <th>Document ID</th>
                        <th>Column Name</th>
                        <th>Total Quantities</th>                   
                        <th>Balance</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody></tbody> <!-- ข้อมูลจะถูกโหลดผ่าน AJAX -->
            </table>
        </div>
    </div>

    


  </main><!-- End #main -->

<!-- Include DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
<script src="https://cdn.datatables.net/fixedheader/3.3.2/js/dataTables.fixedHeader.min.js"></script>

<script>
$(document).ready(function() {
    setTimeout(function() {
        console.log("✅ Auto-clicking Pending button...");
        $("#showpendingproduct").trigger("click").addClass("active"); // ✅ คลิกและทำให้ปุ่มเป็น Active
    }, 100);
    let pendingTable, successTable, deleteTable;

    // ✅ กดปุ่ม "Pending"
    $("#showpendingproduct").click(function() {
        $("#showpendingTable").show();  // แสดงตาราง Pending
        $("#showsuccessTable").hide();  // ซ่อนตาราง Success
        $("#showdeleteTable").hide();  // ซ่อนตาราง delete
        $("#editStockContainer").hide();  // ซ่อนฟอร์มแก้ไขสต็อก
        $("#stockFieldsContainer").hide();
        $("#select-status").val("");
        $("#showStockFields").prop("disabled", true);
        if (!pendingTable) {
            pendingTable = $("#pendingTable").DataTable({
                "destroy": true, // ลบ DataTable เก่าก่อนโหลดใหม่
                "columnDefs": [{ "targets": "_all", "className": "text-center" }],
                "ajax": {
                    "url": "includes/fetch_pending.php",
                    "type": "GET",
                    "dataSrc": ""
                },
                "columns": [
                    { 
                        "data": "document_number",
                        "render": function(data, type, row) {
                            console.log("🔍 Status Balance (Raw):", row.status_balance); // ✅ ตรวจสอบค่าที่ได้รับจาก JSON
                            
                            let balance = row.status_balance ? row.status_balance.trim().toLowerCase() : "";
                            console.log("🔍 Cleaned Balance:", `"${balance}"`); // ✅ ดูค่าหลังจาก trim().toLowerCase()

                            let redirectToBalance = ["new branch", "replace", "additionnal", "new dvr"];

                            if (redirectToBalance.includes(balance)) {
                                console.log("✅ Redirecting to: manage_pending_balance.php");
                                return `<a href="manage_pending_balance.php?doc=${btoa(data)}" class="text-primary fw-bold">${data}</a>`;
                            } else {
                                console.log("❌ Redirecting to: manage_pending.php");
                                return `<a href="manage_pending.php?doc=${btoa(data)}" class="text-primary fw-bold">${data}</a>`;
                            }

                        }
                    },
                    {
                        "data": "column_names",
                        "render": function(data, type, row) {
                            let colorMap = {
                                "Waiting to Receive": "#63C719B2",
                                "Process Adjust": "#8BADD3",
                                "Claim warranty": "#FFA778",
                                "Borrow": "#FF6962",
                                "Damage": "#C2A8EB",
                                "Remark": "#FFA7BD",
                                "Next Orders": "#59B8F0EB",
                                "Reserved": "#92E3DD",
                                "Used": "#FFF777",
                                "Minimum": "#63C719B2",
                                "Maximum": "#8BADD3"
                            };

                            let badgeColor = colorMap[data] || "#CCCCCC"; // สีเริ่มต้นถ้าไม่มีในเงื่อนไข

                            return `<span class="badge text-dark" style="background-color:${badgeColor};font-size:14px">${data}</span>`;
                        }
                    },
                    { "data": "total_quantities" },
                    {
                        "data": "status_balance",
                        "render": function(data, type, row) {
                            let colorMap = {
                                "New Branch": "#FFF777",
                                "Replace": "#FF6962D1",
                                "Additionnal": "#8BADD3",
                                "New DVR": "#92CA68",
                                "Receive": "#92CA68",
                                "Adjustment": "#8BADD3",
                            };

                            let badgeColor = colorMap[data] || "#CCCCCC"; // สีเริ่มต้นถ้าไม่มีในเงื่อนไข

                            return `<span class="badge text-dark" style="background-color:${badgeColor};font-size:14px">${data}</span>`;
                        }
                    },
                    {
                        "data": "date",
                        "render": function(data, type, row) {
                            return row.created_at ? row.created_at : "-"; // แสดงค่า created_at หรือ "-" หากไม่มีค่า
                        }
                    },
                    { 
                        "data": "status",
                        "render": function(data) {
                            return data === "pending" ? '<span class="badge bg-warning text-dark" style="font-size:14px;">Pending</span>' :
                                                        '<span class="badge bg-success text-white">Success</span>';
                        }
                    },
                    {
                        "data": "document_number",
                        "render": function(data, type, row) {
                            if (row.column_name === "Waiting to Receive") {
                                return `<button class="btn btn-danger delete-btn disabled-btn" data-document="${data}" disabled>Delete</button>`;
                            } else {
                                return `<button class="btn btn-danger delete-btn" data-document="${data}">Delete</button>`;
                            }
                        }
                    }
                ],
                "order": [[4, "desc"]], // ✅ จัดเรียงตามวันที่ ล่าสุดขึ้นก่อน (index 0 คือคอลัมน์ "document_id")
                // "scrollY": "370px",
                "scrollX": true,
                "scrollCollapse": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "fixedHeader": {
                    header: true,
                    headerOffset: 60},
                "lengthMenu": [
                    [10, 25, 50, 100, 500, -1],
                    ["10", "25", "50", "100", "500", "All"]
                ],
                "pageLength": -1,
                "language": {
                    "search": "🔍",
                    "lengthMenu": " _MENU_ rows/page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ rows",
                    "infoEmpty": "No data available",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
            // ปรับ placeholder ของช่องค้นหา
            $('.dataTables_filter input')
                .attr("placeholder", " Search...")
                .addClass("search-placeholder");
        } else {
            pendingTable.ajax.reload();  // รีโหลดข้อมูลใหม่
        }
    });

    // ✅ กดปุ่ม "Success"
    // ✅ กดปุ่ม "Success"
    $("#showsuccessBtn").click(function() {
        $("#showsuccessTable").show();  // แสดงตาราง Success
        $("#showpendingTable").hide();  // ซ่อนตาราง Pending
        $("#showdeleteTable").hide();  // ซ่อนตาราง delete
        $("#editStockContainer").hide();  // ซ่อนฟอร์มแก้ไขสต็อก
        $("#stockFieldsContainer").hide();
        $("#select-status").val("");
        $("#showStockFields").prop("disabled", true);
        if (!successTable) {
            successTable = $("#successTable").DataTable({
                "destroy": true, // ลบ DataTable เก่าก่อนโหลดใหม่
                "columnDefs": [{ "targets": "_all", "className": "text-center" }],
                "ajax": {
                    "url": "includes/fetch_success.php",
                    "type": "GET",
                    "dataSrc": ""
                },
                "columns": [
                { 
                    "data": "document_number",
                    "render": function(data, type, row) {
                        console.log("🔍 Status Balance (Raw):", row.status_balance); // ✅ ตรวจสอบค่าที่ได้รับจาก JSON
                        
                        let balance = row.status_balance ? row.status_balance.trim().toLowerCase() : "";
                        console.log("🔍 Cleaned Balance:", `"${balance}"`); // ✅ ดูค่าหลังจาก trim().toLowerCase()

                        let redirectToBalance = ["new branch", "replace", "additionnal", "new dvr"];

                        if (redirectToBalance.includes(balance)) {
                            console.log("✅ Redirecting to: manage_success_balance.php");
                            return `<a href="manage_success_balance.php?doc=${btoa(data)}" class="text-primary fw-bold">${data}</a>`;
                        } else {
                            console.log("❌ Redirecting to: manage_success.php");
                            return `<a href="manage_success.php?doc=${btoa(data)}" class="text-primary fw-bold">${data}</a>`;
                        }

                    }
                },
                { "data": "do_no" },
                {
                        "data": "status_balance",
                        "render": function(data, type, row) {
                            let colorMap = {
                                "New Branch": "#FFF777",
                                "Replace": "#FF6962D1",
                                "Additionnal": "#8BADD3",
                                "New DVR": "#92CA68",
                                "Receive": "#92CA68",
                                "Adjustment": "#8BADD3",
                            };

                            let badgeColor = colorMap[data] || "#CCCCCC"; // สีเริ่มต้นถ้าไม่มีในเงื่อนไข

                            return `<span class="badge text-dark" style="background-color:${badgeColor};font-size:14px">${data}</span>`;
                        }
                    },
                { "data": "total_quantities" },
                {
                    "data": "column_name",
                    "render": function(data, type, row) {
                        let colorMap = {
                            "Waiting to Receive": "#63C719B2",
                            "Process Adjust": "#8BADD3",
                            "Claim warranty": "#FFA778",
                            "Borrow": "#FF6962",
                            "Damage": "#C2A8EB",
                            "Remark": "#FFA7BD",
                            "Next Orders": "#59B8F0EB",
                            "Reserved": "#92E3DD",
                            "Used": "#FFF777",
                            "Minimum": "#63C719B2",
                            "Maximum": "#8BADD3"
                        };

                        let badgeColor = colorMap[data] || "#CCCCCC"; // ใช้สีเทาถ้าไม่มีค่านั้นใน colorMap

                        return `<span class="badge text-dark" style="background-color:${badgeColor};font-size:14px">${data}</span>`;
                    }
                }
                , 

                        { "data": "date", 
                    "render": function(data) {
                        // ถ้าค่าของ date เป็น null หรือว่าง ให้แสดงเป็น "-"
                        return data ? data : "-";
                            }
                        },
               
                { 
                    "data": "status",
                    "render": function(data) {
                        return '<span class="badge bg-success text-white" style="font-weight: bold; font-size: 14px;">Success</span>';
                    }
                },
                
            ],
                "order": [[5, "desc"]], // ✅ จัดเรียงตามวันที่ ล่าสุดขึ้นก่อน
                // "scrollY": "370px",
                "scrollX": true,
                "scrollCollapse": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "fixedHeader": {
                    header: true,
                    headerOffset: 60},
                "lengthMenu": [[10, 25, 50, 100, 500, -1], ["10", "25", "50", "100", "500", "All"]],
                "pageLength": -1,
                "language": {
                    "search": "",
                    "lengthMenu": " _MENU_ rows/page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ rows",
                    "infoEmpty": "No data available",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
            // ปรับ placeholder ของช่องค้นหา
            $('.dataTables_filter input')
                .attr("placeholder", " Search...")
                .addClass("search-placeholder");
        } else {
            successTable.ajax.reload();  // รีโหลดข้อมูลใหม่
        }
    });

    $("#showdeleteBtn").click(function() {
        $("#showdeleteTable").show();  // แสดงตาราง delete
        $("#showsuccessTable").hide();  // ซ่อนตาราง Success
        $("#showpendingTable").hide();  // ซ่อนตาราง pending
        $("#editStockContainer").hide();  // ซ่อนฟอร์มแก้ไขสต็อก
        $("#stockFieldsContainer").hide();
        $("#select-status").val("");
        $("#showStockFields").prop("disabled", true);
        if (!deleteTable) {
            deleteTable = $("#deleteTable").DataTable({
                "destroy": true, // ลบ DataTable เก่าก่อนโหลดใหม่
                "columnDefs": [{ "targets": "_all", "className": "text-center" }],
                "ajax": {
                    "url": "includes/fetch_delete_pending.php",
                    "type": "GET",
                    "dataSrc": ""
                },
                "columns": [
                    { 
                        "data": "document_number",
                        "render": function(data, type, row) {
                            console.log("🔍 Status Balance (Raw):", row.status_balance); // ✅ ตรวจสอบค่าที่ได้รับจาก JSON
                            
                            let balance = row.status_balance ? row.status_balance.trim().toLowerCase() : "";
                            console.log("🔍 Cleaned Balance:", `"${balance}"`); // ✅ ดูค่าหลังจาก trim().toLowerCase()

                            let redirectToBalance = ["new branch", "replace", "additionnal", "new dvr"];

                            if (redirectToBalance.includes(balance)) {
                                console.log("✅ Redirecting to: manage_delete_pending_balance.php");
                                return `<a href="manage_delete_pending_balance.php?doc=${btoa(data)}" class="text-primary fw-bold">${data}</a>`;
                            } else {
                                console.log("❌ Redirecting to: manage_delete_pending.php");
                                return `<a href="manage_delete_pending.php?doc=${btoa(data)}" class="text-primary fw-bold">${data}</a>`;
                            }

                        }
                    },
                    {
                        "data": "column_names",
                        "render": function(data, type, row) {
                            let colorMap = {
                                "Waiting to Receive": "#63C719B2",
                                "Process Adjust": "#8BADD3",
                                "Claim warranty": "#FFA778",
                                "Borrow": "#FF6962",
                                "Damage": "#C2A8EB",
                                "Remark": "#FFA7BD",
                                "Next Orders": "#59B8F0EB",
                                "Reserved": "#92E3DD",
                                "Used": "#FFF777",
                                "Minimum": "#63C719B2",
                                "Maximum": "#8BADD3"
                            };

                            let badgeColor = colorMap[data] || "#CCCCCC"; // สีเริ่มต้นถ้าไม่มีในเงื่อนไข

                            return `<span class="badge text-dark" style="background-color:${badgeColor};font-size:14px">${data}</span>`;
                        }
                    },
                    { "data": "total_quantities" },
                    {
                        "data": "status_balance",
                        "render": function(data, type, row) {
                            let colorMap = {
                                "New Branch": "#FFF777",
                                "Replace": "#FF6962D1",
                                "Additionnal": "#8BADD3",
                                "New DVR": "#92CA68",
                                "Receive": "#92CA68",
                                "Adjustment": "#8BADD3",
                            };

                            let badgeColor = colorMap[data] || "#CCCCCC"; // สีเริ่มต้นถ้าไม่มีในเงื่อนไข

                            return `<span class="badge text-dark" style="background-color:${badgeColor};font-size:14px">${data}</span>`;
                        }
                    },
                    {
                        "data": "date",
                        "render": function(data, type, row) {
                            return row.created_at ? row.created_at : "-"; // แสดงค่า created_at หรือ "-" หากไม่มีค่า
                        }
                    },
                    { 
                        "data": "status",
                        "render": function(data) {
                            return data === "delete" ? '<span class="badge bg-danger text-white" style="font-size:14px;">delete</span>' :
                                                        '<span class="badge bg-success text-white">Success</span>';
                        }
                    }
                ],
                "order": [[4, "desc"]], // ✅ จัดเรียงตามวันที่ ล่าสุดขึ้นก่อน (index 0 คือคอลัมน์ "document_id")
                // "scrollY": "370px",
                "scrollX": true,
                "scrollCollapse": true,
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": false,
                "fixedHeader": {
                    header: true,
                    headerOffset: 60},
                "lengthMenu": [
                    [10, 25, 50, 100, 500, -1],
                    ["10", "25", "50", "100", "500", "All"]
                ],
                "pageLength": -1,
                "language": {
                    "search": "",
                    "lengthMenu": " _MENU_ rows/page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ rows",
                    "infoEmpty": "No data available",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
            // ปรับ placeholder ของช่องค้นหา
            $('.dataTables_filter input')
                .attr("placeholder", " Search...")
                .addClass("search-placeholder");
        } else {
            deleteTable.ajax.reload();  // รีโหลดข้อมูลใหม่
        }
   
    });


    // ✅ ตรวจจับปุ่มลบข้อมูล
    $(document).on("click", ".delete-btn1", function() {
        var documentId = $(this).data("document");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "process/delete_success.php",
                    type: "POST",
                    data: { document_id: documentId },
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                title: "Deleted!",
                                text: response.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $("#successTable").DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }   
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong. Please try again.",
                            icon: "error",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });

    // ✅ ตรวจจับปุ่มลบข้อมูล
    $(document).on("click", ".delete-btn", function() {
        var documentId = $(this).data("document");

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "process/delete_pending.php",
                    type: "POST",
                    data: { document_id: documentId },
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                title: "Deleted!",
                                text: response.message,
                                icon: "success",
                                timer: 2000,
                                showConfirmButton: false
                            });

                            $("#pendingTable").DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: response.message,
                                icon: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }   
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Something went wrong. Please try again.",
                            icon: "error",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    });
    // ตรวจจับเมื่อกดย่อหรือขยาย Sidebar
    $(document).on('click', '.toggle-sidebar-btn', function() {
        setTimeout(function() {
            if ($.fn.DataTable.isDataTable('#pendingTable')) {
                var table = $('#pendingTable').DataTable();
                if (table) {
                    table.columns.adjust().draw(); // ปรับขนาดคอลัมน์
                }
            }
        }, 300); // ให้เวลาหน้าเว็บปรับ Layout ก่อน
    });

    // ตรวจจับการเปลี่ยนขนาดหน้าจอ
    $(window).on('resize', function() {
        if ($.fn.DataTable.isDataTable('#pendingTable')) {
            var table = $('#pendingTable').DataTable();
            if (table) {
                table.columns.adjust().draw();
            }
        }
    });

    // ตรวจจับเมื่อกดย่อหรือขยาย Sidebar
    $(document).on('click', '.toggle-sidebar-btn', function() {
        setTimeout(function() {
            if ($.fn.DataTable.isDataTable('#successTable')) {
                var table = $('#successTable').DataTable();
                if (table) {
                    table.columns.adjust().draw(); // ปรับขนาดคอลัมน์
                }
            }
        }, 300); // ให้เวลาหน้าเว็บปรับ Layout ก่อน
    });

    // ตรวจจับการเปลี่ยนขนาดหน้าจอ
    $(window).on('resize', function() {
        if ($.fn.DataTable.isDataTable('#successTable')) {
            var table = $('#successTable').DataTable();
            if (table) {
                table.columns.adjust().draw();
            }
        }
    });

    $(window).on('scroll', function() {
    if ($(this).scrollTop() === 0) {
        setTimeout(function() {
            if (pendingTable) pendingTable.fixedHeader.adjust();
            if (successTable) successTable.fixedHeader.adjust();
            if (deleteTable) deleteTable.fixedHeader.adjust();
        }, 0);
    }
});


    // $.ajax({
    //     url: "includes/fetch_products.php",
    //     type: "GET",
    //     dataType: "json",
    //     success: function(response) {
    //         console.log("✅ Data fetched successfully", response);
    //         // ✅ คุณสามารถใช้ข้อมูลนี้ในการประมวลผลต่อไปได้ (เช่น นำไปเก็บในตัวแปร)
    //     },
    //     error: function(xhr, status, error) {
    //         console.error("❌ Error fetching data:", error);
    //     }
    // });

});
</script>


<style>
/* กำหนดความกว้างให้กับ container2 */
.tabletest{
    background-color:#01013C;
}
.tabletest th{
    color:white;
}

#productTable td:nth-child(3) { 
    word-wrap: break-word;
    white-space: normal;
    max-width: 200px; /* ปรับความกว้างของคอลัมน์ part_name */
}
#productTable td:nth-child(12) { 
    word-wrap: break-word;
    white-space: normal;
    max-width: 200px; /* ปรับความกว้างของคอลัมน์ part_name */
}

/* จัดข้อความในตารางให้อยู่กึ่งกลาง */
#productTable th,
#productTable td {
    text-align: center; /* จัดให้อยู่กึ่งกลางแนวนอน */
    vertical-align: middle; /* จัดให้อยู่กึ่งกลางแนวตั้ง */
}

#productTable thead th {
    text-align: center; /* จัดหัวข้อคอลัมน์ให้อยู่กลาง */
}

#productTable tbody td {
    text-align: center; /* จัดข้อมูลในแถวให้อยู่กลาง */
    vertical-align: middle; /* จัดให้อยู่กึ่งกลางแนวตั้ง */
}


/* Add smooth transitions to table header cells */
#productTable thead th {
    transition: width 0.3s ease-in-out;
    white-space: nowrap;
}

/* Ensure the header container also has smooth transitions */
.dataTables_scrollHead {
    transition: width 0.3s ease-in-out;
}

/* Optional: Add transitions to the table body cells for consistency */
#productTable tbody td {
    transition: width 0.3s ease-in-out;
    white-space: nowrap;
}
/* ปรับขนาดตัวอักษรในข้อมูลของตาราง */
#productTable tbody td {
    font-size: 15px; /* ปรับขนาดตัวอักษรให้เล็กลง */
    padding: 5px; /* ปรับระยะห่างของเซลล์ */
}

  /* จัดระยะห่างของส่วนควบคุม DataTables */
.dataTables_wrapper {
    padding: 10px 15px; /* เพิ่ม padding รอบๆ */
}

/* ปรับช่องค้นหา (Search Box) */
.dataTables_filter {
    margin-bottom: 15px; /* เพิ่มระยะห่างด้านล่าง */
}

/* ปรับระยะห่างของ Pagination */
.dataTables_paginate {
    margin-top: 15px !important;
}

/* ปรับตำแหน่งของ "แสดง x รายการต่อหน้า" */
.dataTables_length {
    margin-bottom: 15px;
}

/* ปรับระยะห่างของ Info */
.dataTables_info {
    margin-top: 15px;
}

/* Custom Light Scrollbar */
.dataTables_wrapper ::-webkit-scrollbar {
    width: 8px; /* กำหนดความกว้างของ Scrollbar */
    height: 8px;
}

.dataTables_wrapper ::-webkit-scrollbar-track {
    background: #f8f9fa; /* สีพื้นหลังของ Track เป็นสีเทาอ่อน */
    border-radius: 10px;
}

.dataTables_wrapper ::-webkit-scrollbar-thumb {
    background: #d6d6d6; /* สีของ Scrollbar เป็นสีเทาอ่อน */
    border-radius: 10px;
    border: 2px solid #f8f9fa; /* ทำให้มีช่องว่างรอบๆ */
}

.dataTables_wrapper ::-webkit-scrollbar-thumb:hover {
    background: #bfbfbf; /* สีของ Scrollbar เมื่อ Hover */
}

/* ปรับ Scrollbar บน Firefox */
.dataTables_wrapper {
    scrollbar-color: #d6d6d6 #f8f9fa; /* thumb และ track */
    scrollbar-width: thin;
}


/* ลดขนาดของ Search Box */
.dataTables_filter input {
    width: 170px; /* ลดความกว้าง */
    font-size: 14px; /* ลดขนาดตัวอักษร */
    padding: 4px;
}

/* ลดขนาดของ Length Menu */
.dataTables_length select {
    width: 70px; /* ลดความกว้าง */
    font-size: 14px;
    padding: 2px;
}

/* ลดขนาดของ Info */
.dataTables_info {
    font-size: 13px;
    margin-top: 10px;
}

/* ลดขนาดของ Pagination */
.dataTables_paginate {
    font-size: 13px;
    margin-top: 10px;
}

.dataTables_paginate .paginate_button {
    padding: 5px 8px;
    font-size: 12px;
    border-radius: 5px; /* ทำปุ่มโค้ง */
}

/* ปรับขนาดปุ่ม Pagination เมื่อ Hover */
.dataTables_paginate .paginate_button:hover {
    background-color: #007bff !important;
    color: white !important;
}

/* ทำให้หัวตาราง (thead) มีมุมโค้ง */
#productTable thead {
    background: #007bff; /* สีพื้นหลังของ thead */
    color: white; /* สีตัวอักษร */
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

/* ทำให้มุมโค้งแสดงผลได้ดีขึ้น */
#productTable thead th:first-child {
    border-top-left-radius: 12px;
}

#productTable thead th:last-child {
    border-top-right-radius: 12px;
}



/* ใส่เส้นขอบภายในตาราง */
#productTable th,
#productTable td {
    border: 5px solid #ddd /* สีเทาอ่อน */
}

/* ปรับหัวตาราง (thead) ให้ดูโดดเด่น */
#productTable thead {
    background:rgb(6, 12, 17);
    color: white;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

/* ทำให้ขอบมุมแสดงผลได้ดีขึ้น */
.dataTables_wrapper {
    border: 2px solid rgb(109, 109, 109) !important;;
    background : white;
    border-radius: 8px;
    padding: 10px;
}


.btn-primary{
    margin-left: 8px;   
}

/* ปรับ dropdown ให้มีขนาดเท่ากับช่อง Part Name */
.dropdown-list {
    width: 94%; /* ✅ ปรับให้กว้างเท่าช่อง input */
    position: absolute; /* ✅ ทำให้ dropdown ไม่ดันเนื้อหาอื่น */
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1050; /* ✅ ปรับ z-index ให้สูงขึ้นเพื่อแสดงผลถูกต้อง */
    top: 100%; /* ✅ ให้ dropdown อยู่ถัดลงมาจาก input */
    display: none; /* ✅ ซ่อนไว้ก่อนจนกว่าจะพิมพ์ */
}

/* ✅ ปรับรายการ dropdown */
.dropdown-list li {
    padding: 10px;
    cursor: pointer;
    border-bottom: 1px solid #ddd;
    list-style-type: none;
}

/* ✅ เปลี่ยนสีเมื่อ hover */
.dropdown-list li:hover {
    background-color:rgb(201, 201, 201);
}

/* ✅ ให้ .stock-row มี position: relative เพื่ออ้างอิงตำแหน่ง dropdown */
.stock-row {
    position: relative;
}

.custom-col{
    width: 7%;
}
.custom1-col{
    width: 13%;
}
.custom2-col{
    width: 11%;
}
.custom3-col{
    width: 8%;
}
.custom4-col{
    width: 11%;
}

.container2 {
    width: 82%;
    padding: 10px;
    margin-left: 100px;
    border-radius: 10px;
    margin-bottom: 20px;
    
}

/* ลบเส้นขอบของเซลล์ใน DataTable */
.dataTable td {
    border: none !important; 
}

/* ปรับ padding ของเซลล์ให้ดูดีขึ้น */
.dataTable td {
    padding: 10px 15px; 
}

/* ปรับเส้นขอบตารางให้เหลือแค่ขอบล่าง */
.dataTable {
    border-collapse: collapse;
    border-bottom: 2px solid #ddd; /* ให้เหลือแค่ขอบล่าง */
}
.disabled-btn {
    background-color: #CCCCCC !important; /* สีเทา */
    border-color: #CCCCCC !important; /* สีเทา */
    color: #666666 !important; /* สีเทาเข้ม */
    cursor: not-allowed !important; /* เปลี่ยน cursor */
}
</style>


  <?php include('footer.php'); ?>

