<header class="header">
    <section class="flex">
      <div id="menu-btn" class="fas fa-bars"></div>
      <img src="../images/logo.png" style="width:10%;">
      <nav class="navbar">
         <a class="navbar-item" href="../customer/home.php">Home</a>
         <a class="navbar-item" href="../customer/promos.php">Promos</a> 
         <a class="navbar-item" href="../customer/services.php">Services</a>
         <a class="navbar-item" href="../customer/reviews.php">Reviews</a>
         <a class="navbar-item" href="../customer/gallery.php">Gallery</a>
         <a class="navbar-item" href="../customer/faqs.php">FAQs</a>
         <a class="navbar-item" href="../customer/about.php">About Us</a>
         <a class="navbar-item" href="../customer/d7cares.php">D7Cares</a>
      </nav>

      <div class="icons">
         <a href="../customer/reservation.php" name="reservation"> <i class="fas fa-calendar"></i></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `customers` WHERE customer_id = ?");
               $select_profile->execute([$customer_id]);
               if($select_profile->rowCount() > 0){
                  $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>
            <img src="../profile/<?= $fetch_profile['profile_picture']; ?>" alt="">
            <p class="name"> <?= $fetch_profile['complete_name']; ?> </p>
               <a href="../customer/view_profile.php" class="profile-btn">View Profile</a>    
               <a class="dropbtn" onclick="myFunction()" style="cursor:pointer;">My Reservations <i class="fa fa-caret-down"></i></a>
               <div class="dropdown-content" id="myDropdown"> 
               <a href="../customer/transaction-p.php">Pending</a> <br>
               <a href="../customer/transaction-d.php">Cancelled</a> <br>
               <a href="../customer/transaction-c.php">Completed</a> <br>
               </div>
               <a href="../components/logout.php" onclick="return confirm('logout from this website?');" class="logout-btn">logout</a>  
            <?php
               }else{
            ?>
               <a href="../customer/login.php" class="profile-btn">login</a>
               <a href="../customer/register.php" class="profile-btn">register</a>
            <?php
               }
            ?>
      </div>
    </section>
</header>
