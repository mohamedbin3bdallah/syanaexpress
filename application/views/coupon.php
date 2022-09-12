 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('add_coupons'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/add_coupon', $attributes); ?>
                    <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('coupon_code'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <input type="text" name="coupon_code" value="<?php echo set_value('coupon_code'); ?>" class="form-control" required="" placeholder="Coupon Code" />
							<div><?php echo form_error('coupon_code'); ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('discount_type'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <select class="form-control" name="discount_type">
                              <option value="1" <?php if(set_value('discount_type') == 1) echo 'selected'; ?>><?php echo get_phrase('percentage'); ?></option>
                              <option value="2" <?php if(set_value('discount_type') == 2) echo 'selected'; ?>><?php echo get_phrase('flat_cost'); ?>(<?php echo $currency_type; ?>)</option>
                            </select>
							<div><?php echo form_error('discount_type'); ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo get_phrase('discount'); ?><span style="color:red;font-weight:bold"> *</span></label>
                            <div class="col-sm-9">
                            <input type="number" name="discount" value="<?php echo set_value('discount'); ?>" class="form-control" required="" placeholder="Discount" min="0" />
							<div><?php echo form_error('discount'); ?></div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"><?php echo get_phrase('descripton'); ?><span style="color:red;font-weight:bold"> *</span></label>
                            <div class="col-sm-9">
                            <textarea class="form-control" name="description" required="" placeholder="Descripton"><?php echo set_value('description'); ?></textarea>
							<div><?php echo form_error('description'); ?></div>
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
          <h4 class="card-title"><<?php echo get_phrase('all_coupons'); ?>/h4>
 
          <table id="example" class="table table-striped">
            <thead>
              <tr>
                <th>
                <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                <?php echo get_phrase('code'); ?>
                </th>
                <th>
                  <?php echo get_phrase('type'); ?>
                </th>
                <th>
                  <?php echo get_phrase('discount'); ?>
                </th>
                <th>
                  <?php echo get_phrase('descripton'); ?>
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
             foreach ($coupon as $coupon) {
              $i++;
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $i; ?>
                </td>
                <td>
                  <?php echo $coupon->coupon_code; ?>
                </td>
                <td>
                 <?php if($coupon->discount_type==1)
                 {
                   echo "%";
                   ?>
                 <?php
                  }
                  elseif($coupon->discount_type==2) {
                    echo $currency_type;
                     ?>
                 <?php
                   } ?>
                </td>
                 <td>
                <?php echo $coupon->discount; ?>
                </td>
                <td>
                <?php echo $coupon->description; ?>
                </td>
                 <td>
                 <?php if($coupon->status==1)
                 {
                   ?>
                 <label class="badge badge-teal"><?php echo get_phrase('active'); ?></label>
                 <?php
                  }
                  elseif($coupon->status==0) {
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
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_coupon');?>?id=<?php echo $coupon->id; ?>&status=1&request=1"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('active'); ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_coupon');?>?id=<?php echo $coupon->id; ?>&status=0&request=1"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('deactive'); ?></a>
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