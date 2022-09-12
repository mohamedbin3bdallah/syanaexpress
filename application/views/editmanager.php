 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('edit_manager'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/edit_manager/'.$admin->id, $attributes); ?>
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
                            <label class="col-sm-3 col-form-label"><?php echo get_phrase('email'); ?><span style="color:red;font-weight:bold"> *</span></label>
                            <div class="col-sm-9">
                            <input type="email" name="email" value="<?php echo $admin->email; ?>" class="form-control" required="" placeholder="Enter Email" readonly />
							<div><?php echo form_error('email'); ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo get_phrase('password'); ?><span style="color:red;font-weight:bold"> *</span></label>
                            <div class="col-sm-9">
                            <input type="password" name="password" value="<?php echo $admin->password; ?>" class="form-control" required="" placeholder="Enter Password" />
							<div><?php echo form_error('password'); ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo get_phrase('role'); ?><span style="color:red;font-weight:bold"> *</span></label>
                            <div class="col-sm-9">
                            <select class="form-control" name="role">
                                <option value="">Select Role</option>
                                <?php 
                                
                                 foreach ($role as $roledata) {
                                     $selected = ($roledata->id == $admin->role) ? 'selected' : '';
                                     echo '<option value="'.$roledata->id.'" '.$selected.'>'.$roledata->name.'</option>';
                                 }
                                ?>
                            </select>
							<div><?php echo form_error('role'); ?></div>
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