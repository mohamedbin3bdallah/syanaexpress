 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('send_notification'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/add_broadcast', $attributes); ?>
                    <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('message_for'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <select name="msg_for" class="form-control" required="">
                             <option value="1" <?php if(set_value('msg_for') == 1) echo 'selected'; ?>><?php echo get_phrase('customer'); ?></option>
                             <option value="2" <?php if(set_value('msg_for') == 2) echo 'selected'; ?>><?php echo get_phrase('artist'); ?></option>  
                            </select>
							<div><?php echo form_error('msg_for'); ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('title'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <input type="text" name="title" value="<?php echo set_value('title'); ?>" class="form-control" required="" placeholder="Enter Title" />
							<div><?php echo form_error('title'); ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo get_phrase('message'); ?><span style="color:red;font-weight:bold"> *</span></label>
                            <div class="col-sm-9">
                            <textarea class="form-control" name="msg" required="" placeholder="Enter Message"><?php echo set_value('msg'); ?></textarea>
							<div><?php echo form_error('msg'); ?></div>
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
          <h4 class="card-title"><?php echo get_phrase('all_broadcast'); ?></h4>
 
          <table id="example" class="table table-striped">
            <thead>
              <tr>
                <th>
                <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                <?php echo get_phrase('title'); ?>
                </th>
                <th>
                <?php echo get_phrase('message'); ?>
                </th>
                <th>
                  <?php echo get_phrase('send_at'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
            <?php
            $i=0;
             foreach ($coupon as $coupon) {
              $i++;
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $i; ?>
                </td>
                <td>
                  <?php echo $coupon->title; ?>
                </td>
                <td>
                <?php echo $coupon->msg; ?>
                </td>
                <td>
                <?php echo date('M d, Y H:i:s', $coupon->created_at); ?>
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