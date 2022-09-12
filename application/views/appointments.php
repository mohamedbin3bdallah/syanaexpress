<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">
  <div class="row">
  
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('appointments_of'); ?> <?php echo $artist_name; ?></h4>
 
          <table id="example" class="table table-striped">
            <thead>
              <tr>
                <th>
                  <?php echo get_phrase('customer_name'); ?>
                </th>
                <th>
                  <?php echo get_phrase('timing'); ?>
                </th>
                <th>
                  <?php echo get_phrase('appointment_date'); ?>
                </th>
                <th>
                  <?php echo get_phrase('status'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($get_appointments as $get_appointments) {
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $get_appointments->name; ?>
                </td>
                <td>
                  <?php echo $get_appointments->booking_time; ?>
                </td>
                <td>
                   <?php echo $get_appointments->booking_date; ?>
                </td>
                 <td>
                 <?php if($get_appointments->booking_flag==0)
                 {
                   ?>
                 <label class="badge badge-warning"><?php echo get_phrase('pending'); ?></label>
                 <?php
                  }
                  elseif($get_appointments->booking_flag==1) {
                     ?>
                 <label class="badge badge-primary"><?php echo get_phrase('accept'); ?></label>
                 <?php
                   } 
                   elseif($get_appointments->booking_flag==2) {
                     ?>
                 <label class="badge badge-danger"><?php echo get_phrase('decline'); ?></label>
                 <?php
                   } 
                   elseif($get_appointments->booking_flag==3) {
                     ?>
                 <label class="badge badge-info"><?php echo get_phrase('in_process'); ?></label>
                 <?php
                   } 
                   elseif($get_appointments->booking_flag==4) {
                     ?>
                 <label class="badge badge-success"><?php echo get_phrase('completed'); ?></label>
                 <?php
                   } ?>
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
