 <!-- ====
  === Header ======= -->
 <header id="header" class="header fixed-top d-flex align-items-center">

     <div class="d-flex align-items-center justify-content-between">
         <a href="dashboard.php" class="logo d-flex align-items-center">
             <span class="d-none d-lg-block">Stock IT</span>
         </a>
         <i class="bi bi-list toggle-sidebar-btn"></i>
     </div><!-- End Logo -->


     <!-- ตรวจสอบว่าหน้าเว็บไม่ใช่ all_product.php -->
     <?php if (!in_array(basename($_SERVER['SCRIPT_NAME']), ["all_product.php", "dashboard.php", "manage_stock.php", "add_new_balance.php", "manage_balance.php", "add_product.php", "manage_user.php", "manage_master.php", "manage_minmax.php", "manage_pending.php", "manage_success.php", "manage_pending_balance.php", "manage_success_balance.php" ])) : ?>
     <div class="search-bar">
         <form class="search-form d-flex align-items-center" method="POST" action="#">
             <input type="text" name="query" placeholder="Search" title="Enter search keyword">
             <button type="submit" title="Search"><i class="bi bi-search"></i></button>
         </form>
     </div><!-- End Search Bar -->
     <?php endif; ?>

     <nav class="header-nav ms-auto">
         <ul class="d-flex align-items-center">

             <li class="nav-item d-block d-lg-none">
                 <a class="nav-link nav-icon search-bar-toggle " href="#">
                     <i class="bi bi-search"></i>
                 </a>
             </li><!-- End Search Icon-->

             <li class="nav-item dropdown pe-3">

                 <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                     <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                     <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo htmlspecialchars($name); ?></span>
                 </a><!-- End Profile Iamge Icon -->

                 <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                     <li class="dropdown-header">
                         <h6><?php echo htmlspecialchars($name); ?></h6>
                         <span><?php echo htmlspecialchars($group); ?></span>
                     </li>
                     <li>
                         <hr class="dropdown-divider">
                     </li>
                     <li>
                         <a class="dropdown-item d-flex align-items-center" href="process/logout.php">
                             <i class="bi bi-box-arrow-right"></i>
                             <span>Sign Out</span>
                         </a>
                     </li>

                 </ul><!-- End Profile Dropdown Items -->
             </li><!-- End Profile Nav -->

         </ul>
     </nav><!-- End Icons Navigation -->

 </header><!-- End Header -->


 dasdasdas