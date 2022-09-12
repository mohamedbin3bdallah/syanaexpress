<div class="main-panel">
  <div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('add_country'); ?></h4>
           <?php $attributes = array('id' => 'form_validation', 'class'=> 'form-sample'); echo form_open_multipart('Place/storeCountry', $attributes); ?>
           <!--  <p class="card-description">
              Personal info
            </p> -->
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('english_name'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <input type="text" name="name" class="form-control" value="<?php echo set_value('name'); ?>" required />
					<div><?php echo form_error('name'); ?></div>
                  </div>
                </div>
              </div>
			</div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('arabic_name'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <input type="text" name="name_ar" class="form-control" value="<?php echo set_value('name_ar'); ?>" required />
					<div><?php echo form_error('name_ar'); ?></div>
                  </div>
                </div>
              </div>
			</div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('iso2'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <input type="text" name="iso" class="form-control" value="<?php echo set_value('iso'); ?>" maxlength="2" required />
					<div><?php echo form_error('iso'); ?></div>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('iso3'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <input type="text" name="iso3" class="form-control" value="<?php echo set_value('iso3'); ?>" maxlength="3" required />
					<div><?php echo form_error('iso3'); ?></div>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('num_code'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <input type="number" name="numcode" class="form-control" value="<?php echo set_value('numcode'); ?>" required />
					<div><?php echo form_error('numcode'); ?></div>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('phone_code'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <input type="number" name="phonecode" class="form-control" value="<?php echo set_value('phonecode'); ?>" required />
					<div><?php echo form_error('phonecode'); ?></div>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase("icon"); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
					<input type="file" name="icon" required />
					<div><?php echo form_error('icon'); ?></div>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase("status"); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
					<?php
						echo form_dropdown('active', ['0'=>get_phrase('not_active'), '1'=>get_phrase('active')], set_value('active'), 'class="form-control"');
					?>
					<div><?php echo form_error('active'); ?></div>
                  </div>
                </div>
              </div>
            </div>
			
            <div class="row">
              <div class="col-md-6">
                <div class="form-group row">                         
                    <input class="btn btn-primary" type="submit" name="submit" value="<?php echo get_phrase('save'); ?>" />
                </div>
              </div>
            </div>
            </form>
            </div>
         </div>
      </div>
    </div>
</div>