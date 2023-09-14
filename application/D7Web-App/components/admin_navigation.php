<header class="header">
   <section class="flex">
      <a href="dashboard.php" class="logo">D7 Admin Panel</a>
      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>
   </section>
</header>   

<div class="side-bar">
   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>
   <div class="profile">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE admin_id = ?");
               $select_profile->execute([$admin_id]);
               if($select_profile->rowCount() > 0){
                  $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
      <img src="../profile/<?= $fetch_profile['profile_picture']; ?>" alt="" class="image" alt="">
      <h3 class="name"><?= $fetch_profile['complete_name']; ?> </h3>
      <p class="role">Administrator</p>
      <a href="admin_view_profile.php" class="btn">view profile</a>
             <?php
               }else
            ?>
   </div>
   <nav class="navbar">
      <a href="../admin/admin_statistics.php"> <i class="fa-sharp fa-solid fa-chart-simple"></i> Statistics</a>
      <a href="../admin/admin_accounts-c.php"> <i class="fa-solid fa-users"></i> Manage Accounts</a>

      <a class="dropdown-btn">   
      Manage Reservations <i class="fa fa-caret-down"></i> </a>
      <!-- fix css of manage reservations to not move-->


      <div class="dropdown-container">
      <a href="../admin/admin_reservations-p.php">View Pending</a>
      <a href="../admin/admin_reservations-c.php">View Cancelled</a>
      <a href="../admin/admin_reservations-d.php">View Completed</a>
      </div>

      <a href="../admin/admin_services.php"> <i class="fa-solid fa-toolbox"></i>Manage Services</a>
      <a href="../admin/admin_gallery.php"> <i class="fa-solid fa-images"></i> Manage Gallery</a>
      <a href="../admin/admin_promos.php"><i class="fa-sharp fa-solid fa-tag"></i> Manage Promos</a>
      <a href="../admin/admin_faqs.php"> <i class="fa-solid fa-question"></i> Manage FAQs</a>
      <a href="../admin/admin_reviews.php"><i class="fa-solid fa-star"></i> Manage Reviews</a>
      <a href="../admin/admin_d7cares.php"><i class="fa-solid fa-comments"></i> D7Cares</a>
      
      <a href="../components/admin_logout.php" onclick="return confirm('Logout from this website?');" class="logout-btn">Logout</a>
   </nav>
</div>