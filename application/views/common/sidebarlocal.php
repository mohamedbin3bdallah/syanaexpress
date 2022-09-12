 <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
     <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="profile-image"> <img src="http://localhost/artist/assets/images/faces/face4.jpg" alt="image"> <span class="online-status online"></span> </div>
              <div class="profile-name">
                <p class="name"><?php echo get_phrase('features'); ?></p>
                <p class="designation"><?php echo get_phrase('admin'); ?></p>
                <div class="badge badge-teal mx-auto mt-3"><?php echo get_phrase('online'); ?></div>
              </div>
            </div>
          </li>
          <li class="nav-item"><a class="nav-link" href="http://localhost/artist/index.php/Admin/home"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/size.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('dashboard'); ?></span></a></li>


          <!-- Users tab -->
           <li class="nav-item" >
            <a class="nav-link collapsed" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
             <img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/users.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('users'); ?></span>
              <i class="menu-arrow"></i>
            </a>
             <div class="collapse" id="ui-basic" style="">
              <ul class="nav flex-column sub-menu">
               <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/user"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/users.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('users'); ?></span></a></li>
               <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/artists"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/artist.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('artist'); ?></span></a></li>
               <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/warning"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/warning.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('warning_user'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <!-- End Users Tab -->
          <!-- Admin Utils -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic1" aria-expanded="false" aria-controls="ui-basic1">
             <img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/diagram.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('utils'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic1">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/category"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/diagram.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('categories'); ?></span></a></li>
               <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/skills"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/skills.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('skills'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <!-- End Admin Utils -->

          <!-- Features Tab-->
          <li class="nav-item"); background-repeat:no-repeat; background-position: right;">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic2" aria-expanded="false" aria-controls="ui-basic2">
             <img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/computer.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('features'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic2">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/booking"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/appointment.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('current_booking'); ?></span></a></li>
               <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/jobs"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/jobs.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('jobs'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <!-- End Features Tab -->

          <!-- Payout Tab -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic3" aria-expanded="false" aria-controls="ui-basic3">
             <img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/bill.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('payout'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic3">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/requestAmount"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/application.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('artist_request'); ?></span></a></li>
                <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/allInvoice"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/bill.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('invoice'); ?></span></a></li>
                 <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/packages"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/bill.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('packages'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <!-- End Payout Tab -->

          <!-- Admin Support -->
            <li class="nav-item" >
            <a class="nav-link" data-toggle="collapse" href="#ui-basic4" aria-expanded="false" aria-controls="ui-basic4">
             <img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/online-support.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('admin_support'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic4">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/ticket"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/ticket.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('tickets'); ?></span></a></li>
                <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/notifaction"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/notification.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('notifications'); ?></span></a></li>
               <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/broadcast_msg"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/chat.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('broadcast'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <!-- End Admin Support -->

          <!-- Settings -->
           <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic5" aria-expanded="false" aria-controls="ui-basic5">
             <img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/settings-gears.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('settings'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic5">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/setting"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/settings.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('commissions'); ?></span></a></li>
                <li class="nav-item"><a class="nav-link" href="http://localhost/artist/index.php/Admin/manager"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/manager.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('manager'); ?></span></a></li> 
              </ul>
            </div>
          </li>
          <!-- End Settings -->
          <li class="nav-item "><a class="nav-link" href="http://localhost/artist/index.php/Admin/coupon"><img class="menu-icon" src="http://localhost/artist/assets/images/menu_icons/coupon.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('coupons'); ?></span></a></li> 
        </ul>
      </nav>
      <!-- partial -->