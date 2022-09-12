<div class="main-panel">
  <div class="content-wrapper">
     <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
             <div class="row">
          <h4 class="col-md-6 card-title"><?php echo get_phrase('all_booking'); ?></h4>
          <div class="col-md-6"><a href="https://expmaint.com/app/Admin/exportBooking" style="max-width:200px;float:right;" class="btn btn-primary btn-sm"><?php echo get_phrase('export_all_booking'); ?></a></div>
          
        </div>  
          <div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s_no'); ?></th>
                  <th><?php echo get_phrase('user_name'); ?></th>
                  <th><?php echo get_phrase('user_details'); ?></th>
                  <th><?php echo get_phrase('artist_name'); ?></th>
                  <th><?php echo get_phrase('artist_details'); ?></th>
                  <th><?php echo get_phrase('category'); ?></th>
                  <th><?php echo get_phrase('detail'); ?></th>
                  <th><?php echo get_phrase('price'); ?></th>
                  <th><?php echo get_phrase('booking_time'); ?></th>
                  <th><?php echo get_phrase('created_time'); ?></th>
                  <th> <?php echo get_phrase('status'); ?></th>
                  <th><?php echo get_phrase('action'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $i=0; foreach ($getBookings as $getBookings) { $i++;
                ?>
                <tr>
                  <td ><?php echo $i; ?></td>
                  <td ><?php echo $getBookings->userName; ?></td>
                  <td ><?php $userD = $this->Api_model->getUserDetail($getBookings->user_id); 
                  echo $userD[0]->email_id; echo '<br>'.$userD[0]->mobile;?></td>
                  <td ><?php echo $getBookings->artistName; ?></td>
                  <td ><?php $userD = $this->Api_model->getUserDetail($getBookings->artist_id); 
                  echo $userD[0]->email_id; echo '<br>'.$userD[0]->mobile;?></td>
                  <td ><?php echo $getBookings->category_name; ?></td>
                  <td ><?php print_r($getBookings->detail); ?></td>
                  <td ><?php echo $getBookings->price; ?></td>
                  
                  <td><?php echo date('d/m/Y h:i:s A', $getBookings->booking_timestamp); ?></td>
                   <td><?php echo date('d/m/Y h:i:s A', $getBookings->created_at); ?></td>
                  <td>
                  <?php if($getBookings->booking_flag==0)
                   {
                     ?>
                   <label class="badge badge-warning"><?php echo get_phrase('pending'); ?></label>
                   <?php
                    }
                    elseif($getBookings->booking_flag==1) {
                       ?>
                   <label class="badge badge-primary"><?php echo get_phrase('accepted'); ?></label>
                   <?php
                     } 
                     elseif($getBookings->booking_flag==2) {
                       ?>
                   <label class="badge badge-danger"><?php echo get_phrase('decline'); ?></label>
                   <?php
                     } 
                      elseif($getBookings->booking_flag==3) {
                       ?>
                   <label class="badge badge-success"><?php echo get_phrase('running'); ?></label>
                   <?php
                     } 
                      elseif($getBookings->booking_flag==4) {
                       ?>
                   <label class="badge badge-default"><?php echo get_phrase('completed'); ?></label>
                   <?php
                     } 
                     ?>
                     </td>         
                  <td>
                 <?php if($getBookings->booking_flag==0)
                   { ?>
                   <div class="btn-group dropdown">
                    <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo get_phrase('manage'); ?>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="<?php echo base_url('/Admin/booking_operation');?>?id=<?php echo $getBookings->id; ?>&request=1"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('accept'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('/Admin/decline_booking');?>?id=<?php echo $getBookings->id; ?>&request=0"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('decline'); ?></a>
                    </div>
                  </div>
                  <?php } if($getBookings->booking_flag==1)
                   { ?>
                  <div class="btn-group dropdown">
                    <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo get_phrase('manage'); ?>
                    </button>
                     <div class="dropdown-menu">
                      <a class="dropdown-item" href="<?php echo base_url('/Admin/booking_operation');?>?id=<?php echo $getBookings->id; ?>&request=2"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('start'); ?></a>
                      <a class="dropdown-item" href="<?php echo base_url('/Admin/decline_booking');?>?id=<?php echo $getBookings->id; ?>&request=0"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('decline'); ?></a>
                    </div>
                  </div>
                   <?php } if($getBookings->booking_flag==3)
                   { ?>
                   <div class="btn-group dropdown">
                    <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo get_phrase('manage'); ?>
                    </button>
                     <div class="dropdown-menu">
                      <a class="dropdown-item" href="<?php echo base_url('/Admin/booking_operation');?>?id=<?php echo $getBookings->id; ?>&request=3"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('complete'); ?></a>
                    </div>
                  </div>
                   <?php } if($getBookings->booking_flag==4) {?>
                     <label class="badge badge-default"><?php echo get_phrase('completed'); ?></label>
                   <?php } if($getBookings->booking_flag==2) {?>
                      <label class="badge badge-danger"><?php echo get_phrase('decline'); ?></label>
                   <?php } ?>
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
</div>
<!-- content-wrapper ends -->