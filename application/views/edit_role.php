 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('edit_role'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/edit_role/'.$admin->id, $attributes); ?>
                    <input type="hidden" name="id" value="<?php echo $admin->id; ?>" class="form-control" required="" placeholder="Enter Name" />
					<div class="row">
                    <div class="col-md-12">
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('name'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <input type="text" name="name" value="<?php echo $admin->name; ?>" class="form-control" required="" placeholder="Enter Name" />
                            <div><?php echo form_error('name'); ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo get_phrase('permissions'); ?><span style="color:red;font-weight:bold"> *</span></label>
                            <div class="col-sm-9">
                                <select name="permission[]" class="form-control" multiple>
                                    <option value="user" <?php if(strpos($admin->permission, 'user') !== false) { echo 'selected'; }; ?>>Manage User</option>
                                    <option value="category" <?php if(strpos($admin->permission, 'category') !== false) { echo 'selected'; }; ?>>Manage Category</option>
                                    <option value="services" <?php if(strpos($admin->permission, 'services') !== false) { echo 'selected'; }; ?>>Manage Services</option>
                                    <option value="features" <?php if(strpos($admin->permission, 'features') !== false) { echo 'selected'; }; ?>>Manage Features</option>
                                    <option value="payout" <?php if(strpos($admin->permission, 'payout') !== false) { echo 'selected'; }; ?>>Manage Payout</option>
                                    <option value="support" <?php if(strpos($admin->permission, 'support') !== false) { echo 'selected'; }; ?>>Manage Support</option>
                                    <option value="coupon" <?php if(strpos($admin->permission, 'coupon') !== false) { echo 'selected'; }; ?>>Manage Coupon</option>
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
  </div>
</div>