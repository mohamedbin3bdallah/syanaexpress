<div class="main-panel">
  <div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('edit_country'); ?></h4>
           <?php $attributes = array('id' => 'form_validation', 'class'=> 'form-sample'); echo form_open_multipart('Place/updateCountry/'.$country->id, $attributes); ?>
           <!--  <p class="card-description">
              Personal info
            </p> -->
			
			<input type="hidden" name="table" value="countries">
			<input type="hidden" name="id" value="<?php echo $country->id; ?>">
			<input type="hidden" name="image" value="<?php echo $country->icon; ?>">
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('english_name'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <input type="text" name="name" class="form-control" value="<?php echo $country->name; ?>" required />
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
                    <input type="text" name="name_ar" class="form-control" value="<?php echo $country->name_ar; ?>" required />
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
                    <input type="text" name="iso" class="form-control" value="<?php echo $country->iso; ?>" maxlength="2" required />
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
                    <input type="text" name="iso3" class="form-control" value="<?php echo $country->iso3; ?>" maxlength="3" required />
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
                    <input type="number" name="numcode" class="form-control" value="<?php echo $country->numcode; ?>" required />
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
                    <input type="number" name="phonecode" class="form-control" value="<?php echo $country->phonecode; ?>" required />
					<div><?php echo form_error('phonecode'); ?></div>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
			  <div class="col-md-12">
				<div class="form-group row">
					<div class="col-sm-3"></div>
					<div class="col-sm-9">
						<img src="<?php echo base_url().$country->icon; ?>" height="90" width="150">
					</div>
				</div>
			  </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase("icon"); ?></label>
                  <div class="col-sm-9">
					<input type="file" name="icon" />
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
						echo form_dropdown('active', ['0'=>get_phrase('not_active'), '1'=>get_phrase('active')], array('active'=>$country->active), 'class="form-control"');
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