 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('add_role'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/add_role', $attributes); ?>
                    <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('name'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <input type="text" name="name" value="<?php echo set_value('name'); ?>" class="form-control" required="" placeholder="Enter Name" />
							<div><?php echo form_error('name'); ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo get_phrase('permissions'); ?><span style="color:red;font-weight:bold"> *</span></label>
                            <div class="col-sm-9">
                                <select name="permission[]" class="form-control" multiple>
                                    <option value="user" <?php if(set_select('permission[]', 'user')) echo 'selected'; ?>>Manage User</option>
                                    <option value="category" <?php if(set_select('permission[]', 'category')) echo 'selected'; ?>>Manage Category</option>
                                    <option value="services" <?php if(set_select('permission[]', 'services')) echo 'selected'; ?>>Manage Services</option>
                                    <option value="features" <?php if(set_select('permission[]', 'features')) echo 'selected'; ?>>Manage Features</option>
                                    <option value="payout" <?php if(set_select('permission[]', 'payout')) echo 'selected'; ?>>Manage Payout</option>
                                    <option value="support" <?php if(set_select('permission[]', 'support')) echo 'selected'; ?>>Manage Support</option>
                                    <option value="coupon" <?php if(set_select('permission[]', 'coupon')) echo 'selected'; ?>>Manage Coupon</option>
                                </select>
								<div><?php echo form_error('permission[]'); ?></div>
                          </div>
                        </div>
                      </div>

                      </div>
                    </div>
                    <button type="submit" class="btn btn-success mr-2"><?php echo get_phrase('submit'); ?></button>
                  </form>
                </div>
              </div>
            </div>
             <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('all_managers'); ?></h4>
 
         <table id="example" class="table table-striped">
            <thead>
              <tr>
                <th>
                <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                <?php echo get_phrase('name'); ?>
                </th>
                <th>
                  <?php echo get_phrase('permission'); ?>
                </th>
                <th>
                  <?php echo get_phrase('status'); ?>
                </th>
                <th>
                  <?php echo get_phrase('action'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
            <?php
            $i=0;
             foreach ($admin as $admin) {
              $i++;
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $i; ?>
                </td>
                <td>
                  <?php echo $admin->name; ?>
                </td>
                <td>
              <?php echo $admin->permission; ?>
                </td>
                <td>
                 <?php if($admin->status==1)
                 {
                   ?>
                 <label class="badge badge-teal"><?php echo get_phrase('active'); ?></label>
                 <?php
                  }
                  elseif($admin->status==0) {
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
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_role');?>?id=<?php echo $admin->id; ?>&status=1&request=2"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('active'); ?></a>
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_role');?>?id=<?php echo $admin->id; ?>&status=0&request=2"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('deactive'); ?></a>
                    <a class="dropdown-item" href="<?php echo base_url('Admin/editrole/').$admin->id ?>"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit'); ?></a>
                  </div>
                </div>
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