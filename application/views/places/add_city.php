<div class="main-panel">
  <div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('add_city'); ?></h4>
           <?php $attributes = array('id' => 'form_validation', 'class'=> 'form-sample'); echo form_open_multipart('Place/storeCity', $attributes); ?>
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
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase("country"); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
					<?php
						$ourtypes[''] = get_phrase("choose_country");
						if(!empty($countries))
						{
							foreach($countries as $country) {	$ourtypes[$country->id] = ($settings->value == 'arabic')? $country->name_ar:$country->name; }
						}
						echo form_dropdown('country', $ourtypes, set_value('country'), 'class="form-control"');
					?>
					<div><?php echo form_error('country'); ?></div>
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