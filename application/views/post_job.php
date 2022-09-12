<div class="main-panel">
  <div class="content-wrapper">
     <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
            <div class="row">
          <h4 class="col-md-6 card-title"><?php echo get_phrase('all_jobs'); ?></h4>
          <div class="col-md-6"><a href="https://expmaint.com/app/Admin/exportJob" style="max-width:200px;float:right;" class="btn btn-primary btn-sm"><?php echo get_phrase('export_all_jobs'); ?></a></div>
          
        </div> 
          <div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s._no.'); ?></th>
                  <th><?php echo get_phrase('user_name'); ?></th>
                  <th><?php echo get_phrase('job_price'); ?></th>
                  <th><?php echo get_phrase('artist_price'); ?></th>
                  <th><?php echo get_phrase('title'); ?></th>
                  <th><?php echo get_phrase('detail'); ?></th>
                  <th><?php echo get_phrase('category'); ?></th>
                  <th><?php echo get_phrase('time'); ?></th>
                  <th><?php echo get_phrase('status'); ?></th>
                  <th><?php echo get_phrase('view_more'); ?></th>
                </tr>
              </thead>
               <tbody>
                <?php $i=0; foreach ($job_list as $job_list) { $i++;
                ?>
                <tr>
                  <td ><?php echo $i; ?></td>
                  <td ><?php echo $job_list->user_name; ?></td>
                  <td ><?php echo $job_list->price; ?></td>
                  <td ><?php echo $job_list->artist_price; ?></td>
                  <td ><?php echo $job_list->description; ?></td>
                  <td ><?php echo $job_list->category_name; ?></td>
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
                   <label class="badge badge-primary"><?php echo get_phrase('deactivate'); ?></label>
                   <?php } ?> 
                  </td>    
                  <td> 
                    <div class="btn-group">
                      <a href="<?php echo base_url('Admin/ViewJobDetails') ?>?job_id=<?php echo $job_list->job_id; ?>" class="btn btn-teal btn-sm"><?php echo get_phrase('view_artist'); ?></a>
                    </div>
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