<?php include('header.php'); ?>
<?php include('navbar.php');?>
<?php include('sidebar.php'); ?>
<?php include('includes/db_connect.php');?>
<?php include 'includes/fetch_master.php'; // ใช้ไฟล์ดึงข้อมูลของ master ?>


<main id="main" class="main">

    <div class="pagetitle">
        <h1>Manage Master</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">User & Master</li>
                <li class="breadcrumb-item active">Manage Master</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->


        <div class="container">
            <div class="header-section2">
                <h1></h1>
                <button type="button" class="btn btn-light" onclick="Addgroup()"
                style = "padding: 10px 20px; font-size: 16px; font-weight: bold; text-align: center; border-radius: 15px; border: none; cursor: pointer; background-color: #42BD41; /* สีเขียว */ color: white; transition: background-color 0.3s ease;" 
                        onmouseover="this.style.backgroundColor='#218838'" onmouseout="this.style.backgroundColor='#28a745'">
                + Add Group</button>
            </div>

            <!-- Groups Table -->
            <table class="table table-bordered text-center mt-3">
                <thead class="tabletest" style="background-color:#01013C; color:white;">
                    <tr>
                        <th>Group Name</th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="groupTableBody">
    <?php if (!empty($groups_data)): ?>
        <?php foreach ($groups_data as $index => $group): ?>
            <tr>
                <td id="groupName_<?= $group['id'] ?>">
                    <?= htmlspecialchars($group['group_name']) ?>
                </td>

                <td>
                    <button type="button" class="btn btn-warning btn-sm"
                        style="background-color: <?= ($group["group_name"] === "ADMIN") ? '#a9a9a9' : '#4C6085' ?>; 
                               border-color: <?= ($group["group_name"] === "ADMIN") ? '#a9a9a9' : '#4C6085' ?>; 
                               color: white;"
                        onclick='Edituser(<?= $group["id"] ?>, "<?= htmlspecialchars($group["group_name"], ENT_QUOTES) ?>", <?= htmlspecialchars(json_encode($group["permissions"] ?? []), ENT_QUOTES, 'UTF-8') ?>)'
                        <?= ($group["group_name"] === "ADMIN") ? 'disabled' : '' ?>>
                        Edit
                    </button>

                    <button class="btn btn-danger btn-sm"
                        style="background-color: <?= ($group["group_name"] === "ADMIN") ? '#a9a9a9' : '#F05858' ?>;
                        border-color: <?= ($group["group_name"] === "ADMIN") ? '#a9a9a9' : '#4C6085' ?>;
                        color: white;"
                        onclick="deleteGroup(<?= $group['id'] ?>)"
                        <?= ($group["group_name"] === "ADMIN") ? 'disabled' : '' ?>>
                        Delete
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="3">No groups found</td>
        </tr>
    <?php endif; ?>
</tbody>

            </table>
        </div>
    

    <!-- Add Group Modal -->
    <div id="addGroupModal"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50%; background: white; border: 1px solid #ccc; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); z-index: 1000; padding: 20px; border-radius: 10px;">
        <h2
            style="background: #01012F; padding: 10px; text-align: center; color: white; font-weight: bold; margin: -20px -20px 20px -20px; border-bottom: 2px solid #ccc; border-radius: 10px 10px 0 0; font-size: 24px;">
            Add Group</h2>
        <form id="addGroupForm" method="POST" action="process/add_group.php">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <!-- Group Name -->
                <div style="grid-column: span 2;">
                    <label for="groupName" style="display: block; margin-bottom: 5px; font-weight: bold;">Group
                        Name</label>
                    <input type="text" id="groupName" name="groupName"
                        style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>
            </div>

            <!-- Access Permissions -->
            <br>
            <h style="margin-top: 5px; font-weight: bold;">Permissions</h>
            <table class="permission-table">
                <thead>
                    <tr>
                        <th>PAGE</th>
                        <th>Access</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $pages = [
                        "dashboard.php" => "Dashboard",
                        "all_product.php" => "All Product",
                        "manage_stock.php" => "Stock Management",
                        "add_new_balance.php" => "Sales Record",
                        "manage_balance.php" => "Balance Control",
                        "manage_minmax.php" => "Min-Max Management",
                        "add_product.php" => "Add & Delete Product",
                        "report.php" => "Report",
                        "manage_user.php" => "Manage User",
                        "manage_master.php" => "Manage Master",
                    ];

                    foreach ($pages as $page => $displayName) {
                        echo "<tr>";
                        echo "<td>$displayName</td>";
                        
                        if ($page === "dashboard.php") {
                            // ✅ Dashboard ถูกเลือกไว้และล็อค แต่ยังส่งค่าไปที่ Form
                            echo "<td>";
                            echo "<input type='checkbox' class='access' checked disabled>";
                            echo "<input type='hidden' name='access[$page]' value='1'>"; // ✅ ซ่อน input ที่ส่งค่าไปที่ POST
                            echo "</td>";
                        } else {
                            echo "<td><input type='checkbox' class='access' name='access[$page]' value='1'></td>";
                        }

                        echo "</tr>";
                    }
                ?>


                </tbody>
            </table>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit"
                    style="background: #FFC107; color: black; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">Save</button>
                <button type="button" onclick="closeModal('addGroupModal')"
                    style="background: gray; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Cancel</button>
            </div>
        </form>
    </div>


    <!-- Edit Group Modal -->
    <div id="editGroupModal"
        style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 50%; background: white; border: 1px solid #ccc; box-shadow: 0px 4px 10px rgba(0,0,0,0.1); z-index: 1000; padding: 20px; border-radius: 10px;">
        <h2
            style="background: #01012F; padding: 10px; text-align: center; color: white; font-weight: bold; margin: -20px -20px 20px -20px; border-bottom: 2px solid #ccc; border-radius: 10px 10px 0 0; font-size: 24px;">
            Edit Group</h2>
        <form id="editGroupForm" method="POST" action="process/edit_group.php">
            <input type="hidden" id="editGroupId" name="editGroupId">

            <!-- Group Name -->
            <div style="margin-bottom: 15px;">
                <label for="editGroupName" style="display: block; margin-bottom: 5px; font-weight: bold;">Group
                    Name</label>
                <input type="text" id="editGroupName" name="editGroupName"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;" required>
            </div>

            <!-- Access Permissions -->
            <h style="margin-top: 20px;font-weight: bold;">Permissions</h>
            <table class="permission-table">
                <thead>
                    <tr>
                        <th>PAGE</th>
                        <th>Access</th>
                    </tr>
                </thead>
                <tbody id="editPermissionsTable">
                    <!-- 🛠 ค่าจะถูกโหลดจาก JavaScript -->
                </tbody>
            </table>

            <div style="text-align: center; margin-top: 20px;">
                <button type="submit"
                    style="background: #42BD41; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-right: 10px;">Save</button>
                <button type="button" onclick="closeModal('editGroupModal')"
                    style="background: gray; color: white; padding: 10px 20px; border: none; border-radius: 5px;">Cancel</button>
            </div>
        </form>
    </div>


</main><!-- End #main -->

<script>
document.addEventListener("DOMContentLoaded", function() {
    function toggleEdit(checkbox, page) {
        let viewCheckbox = document.querySelector('input[name="can_view[' + page + ']"]');
        if (checkbox.checked) {
            viewCheckbox.checked = true; // ถ้าเลือก Edit ให้เลือก View อัตโนมัติ
        }
    }

    // ทำให้ toggleEdit ใช้งานได้ใน onclick
    window.toggleEdit = toggleEdit;
});
</script>
            
            <link rel="stylesheet" href="assets/css/user.css">
            <link rel="stylesheet" href="assets/css/table.css">
            <link rel="stylesheet" href="assets/css/master.css">
            <script src="assets/js/master.js"></script>

    <style>
.tabletest th{
    color:white;
}
        .container {
            /* max-width: 100%; เพิ่มความกว้างของคอนเทนเนอร์ */
            padding: 0 20px; /* เพิ่ม padding ซ้ายขวา */
        }
        
        .table {
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border: 2px solid #000;
            border-collapse: collapse;
            width: 100%;
            border-radius: 10px;
            overflow: hidden; /* ให้ขอบมน */
        }
        /* เพิ่มเส้นขอบให้กับทุกเซลล์ของตาราง */
        .table th, .table td {
            border: 1px solid #000; /* สีดำ */
            padding: 10px;
            text-align: center;
        }

        /* กำหนดสีพื้นหลังให้หัวตาราง */
        .table thead {
            background-color: #c8d6e5; /* สีฟ้าอ่อน */
            border-bottom: 2px solid #000;
        }

    </style>
            
<?php include('footer.php'); ?>