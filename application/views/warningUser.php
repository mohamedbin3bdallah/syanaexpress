<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">
  <div class="row">
  
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('warning_users'); ?></h4>
 
          <table id="example" class="table table-striped">
            <thead>
              <tr>
                <th>
                  <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                <?php echo get_phrase('user'); ?>
                </th>
                <th>
                <?php echo get_phrase('name'); ?>
                </th>
                <th>
                <?php echo get_phrase('email'); ?>
                </th>
                <th>
                <?php echo get_phrase('status'); ?>
                </th>
                <th>
                <?php echo get_phrase('warning'); ?> 
                </th>
                 <th>
                 <?php echo get_phrase('action'); ?> 
                </th>
              </tr>
            </thead>
            <tbody>
            <?php $i=0; foreach ($user as $user) { $i++;
              ?>
              <tr>
               <td><?php echo $i; ?></td>
                <td class="py-1">
                <?php if($user->image)
                {
                  ?>
                   <img src="<?php echo  base_url().$user->image; ?>" width="42" height="42" alt="image"/>
                  <?php
                  }
                  else {
                    ?>
                    <img src="<?php echo  base_url('/assets/images/faces-clipart/pic-1.png' ); ?>" alt="image"/>
                    <?php
                     # code...
                   } 
                  ?>
                  
                </td>
                <td>
                  <?php echo ucfirst($user->name); ?><br>
                  <span style="color: #7d7b7b; font-size: 10px;"><?php if($user->role==1) { echo "Artist"; } if($user->role==2) { echo "User"; } ?></span>
                </td>
                <td>
                  <!--  <?php echo $user->email_id; ?> -->
                   <?php echo "Email"; ?>
                </td>
                 <td>
                 <?php if($user->status==1)
                 {
                   ?>
                 <label class="badge badge-teal"><?php echo get_phrase('active'); ?></label>
                 <?php
                  }
                  elseif($user->status==0) {
                     ?>
                 <label class="badge badge-danger"><?php echo get_phrase('deactive'); ?></label>
                 <?php
                   } ?>
                </td>
                <td>
                   <?php echo $user->count; ?>
                </td>
                <td>
                      <a class="btn-danger btn btn-sm" onclick="return confirm('Are you sure? Want to clear this user.')" href="<?php echo base_url('Admin/deleteWarningUser/')?>?user_id=<?php echo $user->user_id; ?>"><?php echo get_phrase('remove'); ?></a>
                </td>
              </tr>
            <?php
              }
            ?>      
            </tbody>
          </table>
        </div>
      </div>
    </div>           
  </div>
</div>
<!-- content-wrapper ends -->
  