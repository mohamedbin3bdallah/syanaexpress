 <!-- partial -->
 <?php $profile = profile_detail(); ?>
 <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
     <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <div class="nav-link">
              <div class="profile-image"> <img src="<?php echo base_url(); ?>assets/images/faces/face4.jpg" alt="image"> <span class="online-status online"></span> </div>
              <div class="profile-name">
                <p class="name"><?php if(!empty($profile['name'])) { echo $profile['name'];} else { echo 'Admin';}; ?></p>
                <p class="designation"><?php if(!empty($profile['role'])) { echo $profile['role'];} else { echo 'Admin';}; ?></p>
                <div class="badge badge-teal mx-auto mt-3"><?php echo get_phrase('online'); ?></div>
              </div>
            </div>
          </li>
          <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>Admin/home"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/size.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('dashboard'); ?></span></a></li>
          <!-- Users tab -->
           <?php if(hide_menu('user')) { ?>
           <li class="nav-item" >
            <a class="nav-link collapsed" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
             <img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/users.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('users'); ?></span>
              <i class="menu-arrow"></i>
            </a>
             <div class="collapse" id="ui-basic" style="">
              <ul class="nav flex-column sub-menu">
               <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/user"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/users.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('users'); ?></span></a></li>
               <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/artists"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/artist.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('artist'); ?></span></a></li>
               <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/warning"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/warning.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('warning_users'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <?php } ?>
		  <!-- Pleaces Tab -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic7" aria-expanded="false" aria-controls="ui-basic7">
             <img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/bill.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('places'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic7">
              <ul class="nav flex-column sub-menu">
				<li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Place/countries"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/application.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('countries'); ?></span></a></li>
				<li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Place/cities"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/application.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('cities'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <!-- End Users Tab -->
          <?php if(hide_menu('category')) { ?>
            <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/category"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/diagram.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('categories'); ?></span></a></li> 
          <!-- End Admin Utils -->
          <!-- End Users Tab -->
          <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/services"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/skills.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('services'); ?></span></a></li> 
		  
		  <!-- Wallets Tab -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic8" aria-expanded="false" aria-controls="ui-basic8">
             <img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/bill.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('wallets'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic8">
              <ul class="nav flex-column sub-menu">
				<li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Wallet/index"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/skills.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('wallets'); ?></span></a></li> 
				<li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Wallet/transferCashRequests"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/skills.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('transfer_cash_requests'); ?></span></a></li> 
              </ul>
            </div>
          </li>
		  <!-- End Wallets Tab -->
		  
		  <!-- Points Tab -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic6" aria-expanded="false" aria-controls="ui-basic6">
             <img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/bill.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('points'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic6">
              <ul class="nav flex-column sub-menu">
				<li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Point/index"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/skills.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('points'); ?></span></a></li>
				<li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Point/pointRewards"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/application.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('point_rewards'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <!-- End Points Tab -->
		   
          <!-- End Admin Utils -->
            <?php } ?>
          <!-- Features Tab-->
          <?php if(hide_menu('features')) { ?>
          <li class="nav-item"); background-repeat:no-repeat; background-position: right;">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic2" aria-expanded="false" aria-controls="ui-basic2">
             <img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/computer.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('features'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic2">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/newBooking"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/appointment.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('current_booking'); ?></span></a></li>
               <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/jobs"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/jobs.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('jobs'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <!-- End Features Tab -->

        <?php } if(hide_menu('payout')) { ?>
          <!-- Payout Tab -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic3" aria-expanded="false" aria-controls="ui-basic3">
             <img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/bill.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('payout'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic3">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/requestAmount"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/application.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('artist_request'); ?></span></a></li>
                <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/allInvoice"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/bill.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('invoice'); ?></span></a></li>
                <!-- <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/packages"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/bill.png" alt="menu icon"><span class="menu-title">Packages</span></a></li> -->
              </ul>
            </div>
          </li>
        <?php } if(hide_menu('support')) { ?>
          <!-- Admin Support -->
            <li class="nav-item" >
            <a class="nav-link" data-toggle="collapse" href="#ui-basic4" aria-expanded="false" aria-controls="ui-basic4">
             <img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/online-support.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('admin_support'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic4">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/ticket"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/ticket.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('tickets'); ?></span></a></li>
                <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/notifaction"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/notification.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('notifications'); ?></span></a></li>
               <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/broadcast_msg"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/chat.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('broadcast'); ?></span></a></li>
              </ul>
            </div>
          </li>
          <!-- End Admin Support -->
            <?php } if(hide_menu('settings')) { ?>
          <!-- Settings -->
           <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic5" aria-expanded="false" aria-controls="ui-basic5">
             <img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/settings-gears.png" alt="menu icon">
              <span class="menu-title"><?php echo get_phrase('settings'); ?></span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic5">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/setting"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/settings.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('commissions'); ?></span></a></li>
                <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/app_setting"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/settings.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('app_settings'); ?></span></a></li>
                <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/terms"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/settings.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('terms_details'); ?></span></a></li>
                <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/policy"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/settings.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('policy_details'); ?></span></a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>Admin/manager"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/manager.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('manager'); ?></span></a></li> 
                <li class="nav-item"><a class="nav-link" href="<?php echo base_url(); ?>Admin/role"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/users.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('role'); ?></span></a></li> 
              </ul>
            </div>
          </li>
          <?php } if(hide_menu('coupon')) { ?>
          <!-- End Settings -->
          <li class="nav-item "><a class="nav-link" href="<?php echo base_url(); ?>Admin/coupon"><img class="menu-icon" src="<?php echo base_url(); ?>assets/images/menu_icons/coupon.png" alt="menu icon"><span class="menu-title"><?php echo get_phrase('Coupons'); ?></span></a></li> <?php } ?>
        </ul>
      </nav>
      <!-- partial -->