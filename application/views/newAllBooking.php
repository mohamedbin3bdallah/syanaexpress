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
            <?php $selectedLang =  getSelectedLanguage();  ?>
          <div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s_no'); ?></th>
                  <th><?php echo get_phrase('order_no'); ?></th>
                  <th><?php echo get_phrase('user_name'); ?></th>
                  <th><?php echo get_phrase('user_details'); ?></th>
                  <th><?php echo get_phrase('artist_name'); ?></th>
                  <th><?php echo get_phrase('artist_details'); ?></th>
                  <th><?php echo get_phrase('category'); ?></th>
                  <th><?php echo get_phrase('detail'); ?></th>
                  <th><?php echo get_phrase('order_items'); ?></th>
                  <th><?php echo get_phrase('price'); ?></th>
                  <th><?php echo get_phrase('booking_date'); ?></th>
                  <th><?php echo get_phrase('booking_time'); ?></th>
                  <th><?php echo get_phrase('created_time'); ?></th>
                  <th> <?php echo get_phrase('status'); ?></th>
                  <th><?php echo get_phrase('action'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php $i=0; foreach ($getBookings as $getBookings) { $i++;
                    $orderDetails = $getBookings['order'];
                    $orderItems = $getBookings['items'];
                ?>
                <tr>
                  <td ><?php echo $i; ?></td>
                  <td ><?php echo $orderDetails->id; ?></td>
                  <td ><?php echo $orderDetails->userName; ?></td>
                  <td ><?php echo $orderDetails->userEmail; ?> <br> <?php echo $orderDetails->userMobile; ?> </td>
                  <td ><?php echo $orderDetails->artistName; ?></td>
                  <td ><?php echo $orderDetails->artistEmail; ?> <br> <?php echo $orderDetails->artistMobile; ?> </td>
                  <td ><?php echo ($selectedLang == 'arabic') ? $orderDetails->cat_name_ar : $orderDetails->cat_name  ?></td>
                  <td ><?php echo ($orderDetails->detail); ?></td>

                    <td>
                        <ul>
                            <?php
                            foreach($orderItems as $item){ ?>
                               <li> <?php echo ($selectedLang == 'arabic') ? $item->cat_name_ar : $item->cat_name  ?> (<?php echo $item->quantity  ?>) (<?php echo $item->cost_per_item * $item->quantity  ?>)</li>
                            <?php
                            }

                            ?>
                        </ul>
                    </td>

                  <td ><?php echo $orderDetails->price; ?></td>
                  
                  <td><?php echo  $orderDetails->booking_date ?></td>
                  <td><?php echo  $orderDetails->booking_time ?></td>
                   <td><?php echo $orderDetails->created_at; ?></td>
                  <td>
                      <?php if($orderDetails->status_id==0)
                      {
                          ?>
                          <label class="badge badge-warning"><?php echo get_phrase('pending'); ?></label>
                          <?php
                      }
                      elseif($orderDetails->status_id==1) {
                          ?>
                          <label class="badge badge-primary"><?php echo get_phrase('accepted'); ?></label>
                          <?php
                      }
                      elseif($orderDetails->status_id==2) {
                          ?>
                          <label class="badge badge-danger"><?php echo get_phrase('decline'); ?></label>
                          <?php
                      }
                      elseif($orderDetails->status_id==3) {
                          ?>
                          <label class="badge badge-success"><?php echo get_phrase('running'); ?></label>
                          <?php
                      }
                      elseif($orderDetails->status_id==4) {
                          ?>
                          <label class="badge badge-default"><?php echo get_phrase('completed'); ?></label>
                          <?php
                      }
                      ?>


                     </td>

                  <td>
                      <?php if($orderDetails->status_id==0)
                      { ?>
                          <div class="btn-group dropdown">
                              <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <?php echo get_phrase('manage'); ?>
                              </button>
                              <div class="dropdown-menu">
                                  <a class="dropdown-item" href="<?php echo base_url('/Admin/bookingArtistSelect');?>?id=<?php echo $orderDetails->id; ?>&request=1"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('select_artist'); ?></a>
<!--                                  <a class="dropdown-item" href="--><?php //echo base_url('/Admin/booking_operation');?><!--?id=--><?php //echo $getBookings->id; ?><!--&request=1"><i class="fa fa-reply fa-fw"></i>--><?php //echo get_phrase('accept'); ?><!--</a>-->
                                  <a class="dropdown-item" href="<?php echo base_url('/Admin/decline_booking');?>?id=<?php echo $orderDetails->id; ?>&request=0"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('decline'); ?></a>
                                  <a class="dropdown-item" href="<?php echo base_url('/Admin/editBookingRecord');?>?id=<?php echo $orderDetails->id; ?>&request=0"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit'); ?></a>
                              </div>
                          </div>
                      <?php } if($orderDetails->status_id==1)
                      { ?>
                          <div class="btn-group dropdown">
                              <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <?php echo get_phrase('manage'); ?>
                              </button>
                              <div class="dropdown-menu">
                                  <a class="dropdown-item" href="<?php echo base_url('/Admin/booking_operation');?>?id=<?php echo $orderDetails->id; ?>&request=2"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('start'); ?></a>
                                  <a class="dropdown-item" href="<?php echo base_url('/Admin/decline_booking');?>?id=<?php echo $orderDetails->id; ?>&request=0"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('decline'); ?></a>
                                  <a class="dropdown-item" href="<?php echo base_url('/Admin/editBookingRecord');?>?id=<?php echo $orderDetails->id; ?>&request=0"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit'); ?></a>

                              </div>
                          </div>
                      <?php } if($orderDetails->status_id==3)
                      { ?>
                          <div class="btn-group dropdown">
                              <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <?php echo get_phrase('manage'); ?>
                              </button>
                              <div class="dropdown-menu">
                                  <a class="dropdown-item" href="<?php echo base_url('/Admin/booking_operation');?>?id=<?php echo $orderDetails->id; ?>&request=3"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('complete'); ?></a>
                                  <a class="dropdown-item" href="<?php echo base_url('/Admin/editBookingRecord');?>?id=<?php echo $orderDetails->id; ?>&request=0"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit'); ?></a>

                              </div>
                          </div>
                      <?php } if($orderDetails->status_id==4) {?>
                          <label class="badge badge-default"><?php echo get_phrase('completed'); ?></label>
                      <?php } if($orderDetails->status_id==2) {?>
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