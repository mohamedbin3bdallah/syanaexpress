 <!-- partial -->
 <div class="main-panel">
        <div class="content-wrapper">
		
		  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('add_services'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/addServicesAction', $attributes); ?>
                    <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('services_name'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <input type="text" name="serv_name" value="<?php echo set_value('serv_name'); ?>" class="form-control" required="" placeholder="Services Name" />
							<div><?php echo form_error('serv_name'); ?></div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('services_name_arabic'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <input type="text" name="serv_name_ar" value="<?php echo set_value('serv_name_ar'); ?>" class="form-control" required="" placeholder="Services Name" />
							<div><?php echo form_error('serv_name_ar'); ?></div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('services_image'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <input type="file" id="image" name="image" size="33" required="" />
							<div><?php echo form_error('image'); ?></div>
                          </div>
                        </div>
                      </div>
                <!--       <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Commission Rate</label>
                            <div class="col-sm-9">
                            <input type="text" name="price" class="form-control num_only" required="" placeholder="Commission in <?php echo $currency_type;?>" maxlength="5" />
                          </div>
                        </div>
                      </div> -->
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
          <h4 class="card-title"><?php echo get_phrase('all_services'); ?></h4>
 
          <table id="example" class="table table-striped">
            <thead>
            <tr>
                <th>
                <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                 <?php echo get_phrase('services_name'); ?>
                </th>
                <th>
                  <?php echo get_phrase('commission_rate'); ?>
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
            <?php $i=0;
             foreach ($services as $services) {
              $i++;  ?>
              <tr>
                <td class="py-1">
                  <?php echo $i; ?>
                </td>
                <td>
                  <?php echo $services->serv_name; ?>
                </td>
                <td>
                 <?php echo $currency_type; echo $services->price; ?>
                </td>
                 <td>
                 <?php  if( $services->status){ ?><button class="btn active_services btn-success btn-sm"><?php echo get_phrase('active'); ?></button><?php }else {  ?><button class="active_services btn-danger btn btn-sm"><?php echo get_phrase('deactive'); ?></button> <?php }?><input  type="text"  value="<?php echo $services->id;?>" hidden>
                </td>
                 <td>
                 <div class="btn-group dropdown">
                  <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo get_phrase('manage'); ?>
                  </button>
                  <div class="dropdown-menu">
                   <!--  <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_services');?>?id=<?php echo $services->id; ?>&status=1&request=1"><i class="fa fa-reply fa-fw"></i>Active</a>
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_services');?>?id=<?php echo $services->id; ?>&status=0&request=1"><i class="fa fa-history fa-fw"></i>Deative</a>
                     <div class="dropdown-divider"></div> -->
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/editservices');?>?id=<?php echo $services->id; ?>"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit'); ?></a>
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