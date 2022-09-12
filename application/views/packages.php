<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php echo get_phrase('all_packages'); ?></h4>
            <p class="card-description">
              <a class="btn btn-primary" href="<?php echo base_url('Admin/add_packages') ?>"><?php echo get_phrase('add_package'); ?></a>
            </p>
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th>
                  <?php echo get_phrase('s._no.'); ?>
                  </th>
                  <th>
                  <?php echo get_phrase('title'); ?>
                  </th>
                  <th>
                  <?php echo get_phrase('description'); ?>
                  </th>
                  <th>
                  <?php echo get_phrase('price'); ?>
                  </th>
                   <th>
                   <?php echo get_phrase('subscription_type'); ?>
                  </th>
                  <th><?php echo get_phrase('status'); ?></th>
                  <th><?php echo get_phrase('manage'); ?></th>
                </tr>
              </thead>
              <tbody>
              <?php $i=0; foreach ($packages as $packages) {
                $i++; ?>
                <tr>
                  <td class="py-1">
                    <?php echo $i; ?>
                  </td>
                  <td>
                    <?php echo $packages->title; ?>
                  </td>
                  <td><?php echo $packages->description; ?></td>
                  <td><?php echo $packages->price; ?> </td>
                  <td>
                   <?php if($packages->subscription_type==1)
                   { ?>
                   <span><?php echo get_phrase('monthly'); ?></span>
                   <?php }
                    elseif($packages->subscription_type==0) { ?>
                   <span ><?php echo get_phrase('free'); ?></span>
                   <?php
                     } 
                     elseif($packages->subscription_type==2) {
                       ?>
                   <span ><?php echo get_phrase('quarterly'); ?></span>
                   <?php
                     } 
                     elseif($packages->subscription_type==3) {
                       ?>
                   <span ><?php echo get_phrase('halfyearly'); ?></span>
                   <?php
                     } 
                     elseif($packages->subscription_type==4) {
                       ?>
                   <span ><?php echo get_phrase('yearly'); ?></span>
                   <?php
                     } ?>
                  </td>
                  <td>
                   <?php if($packages->status==1)
                   {
                     ?>
                   <label class="badge badge-teal"><?php echo get_phrase('active'); ?></label>
                   <?php
                    }
                    elseif($packages->status==0) {
                       ?>
                   <label class="badge badge-danger"><?php echo get_phrase('deactive'); ?></label>
                   <?php
                     } ?>
                  </td>
                   <td>
                      <div class="btn-group dropdown">
                      <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <?php echo get_phrase('manage'); ?>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_package');?>?id=<?php echo $packages->id; ?>&status=1"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('active'); ?></a>
                        <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_package');?>?id=<?php echo $packages->id; ?>&status=0"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('deactivate'); ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo base_url('Admin/edit_package/').$packages->id ?>"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit'); ?></a>
                      </div>

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
<!-- content-wrapper ends -->
<!-- partial:../../partials/_footer.html -->