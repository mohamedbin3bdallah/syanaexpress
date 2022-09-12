 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('edit_services'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/editServicesAction/'.$services->id, $attributes); ?>
				   <input type="hidden" name="id" value="<?php echo $services->id ?>" class="form-control" required=""  />
                    <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('services_name'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <input type="text" name="serv_name" value="<?php echo $services->serv_name ?>" class="form-control" required="" placeholder="Services Name" />
							<div><?php echo form_error('serv_name'); ?></div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('services_name_arabic'); ?><span style="color:red;font-weight:bold"> *</span></label>
                          <div class="col-sm-9">
                            <input type="text" name="serv_name_ar" value="<?php echo $services->serv_name_ar ?>" class="form-control" required="" placeholder="Services Name Arabic" />
							<div><?php echo form_error('serv_name_ar'); ?></div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('services_image'); ?></label>
                          <image src="<?php echo $services->image ?>" width="100">
                          <div class="col-sm-9">
                            <input type="file" id="image" name="image" size="33" />
							<div><?php echo form_error('image'); ?></div>
                          </div>
                        </div>
                      </div>
                  <!--     <div class="col-md-8">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Price</label>
                            <div class="col-sm-9">
                            <input type="number" name="price" value="<?php echo $services->price ?>" class="form-control" required="" placeholder="Price" />
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
  </div>
</div>