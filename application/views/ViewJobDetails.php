<div class="main-panel">
  <div class="content-wrapper">
     <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('all_applied_user'); ?>r</h4>
          <div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s_no'); ?></th>
                  <th><?php echo get_phrase('artist'); ?></th>
                  <th><?php echo get_phrase('email_id'); ?></th>
                  <th><?php echo get_phrase('mobile_no'); ?>.</th>
                  <th><?php echo get_phrase('applied_date'); ?>e</th>
                  <th><?php echo get_phrase('status'); ?></th>
                </tr>
              </thead>
               <tbody>
                <?php $i=0; foreach ($job_list as $job_list) { $i++;
                ?>
               <tr>
                  <td ><?php echo $i; ?></td>
                  <td ><?php echo $job_list->user_name; ?></td>
                  <td ><?php echo $job_list->user_email; ?></td>
                  <td ><?php echo $job_list->user_mobile; ?></td>
                  <td ><?php echo $job_list->created_at; ?></td>  
                  <td>
                  <?php if($job_list->status==0) { ?>
                   <label class="badge badge-warning"><?php echo get_phrase('pending'); ?></label>
                   <?php } elseif($job_list->status==1) { ?>
                   <label class="badge badge-primary"><?php echo get_phrase('confirm'); ?></label>
                   <?php } elseif($job_list->status==2) { ?>
                   <label class="badge badge-success"><?php echo get_phrase('completed'); ?></label>
                   <?php } elseif($job_list->status==3) { ?>
                   <label class="badge badge-danger"><?php echo get_phrase('rejected'); ?></label>
                   <?php } elseif($job_list->status==4) { ?>
                   <label class="badge badge-danger"><?php echo get_phrase('deleted'); ?></label>
                   <?php } ?> 
                  </td>                  
                </tr> 
              <?php } ?>  
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>           
  </div>
</div>
<!-- content-wrapper ends -->