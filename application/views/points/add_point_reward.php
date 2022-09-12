<div class="main-panel">
  <div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('add_point_reward'); ?></h4>
           <?php $attributes = array('id' => 'form_validation', 'class'=> 'form-sample'); echo form_open_multipart('Point/storePointReward', $attributes); ?>
           <!--  <p class="card-description">
              Personal info
            </p> -->
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('points_count'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <input type="number" name="points_count" class="form-control" value="<?php echo set_value('points_count'); ?>" required />
					<div><?php echo form_error('points_count'); ?></div>
                  </div>
                </div>
              </div>
			</div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('rewarded_balance'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <input type="text" name="rewarded_balance" class="form-control" value="<?php echo set_value('rewarded_balance'); ?>" required />
					<div><?php echo form_error('rewarded_balance'); ?></div>
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
						if($settings->value == 'english') $name = 'name';
						else $name = 'name_ar';
						
						$count[''] = get_phrase('select_country');
						foreach($countries as $country) {	$count[$country->id] = $country->$name; }
						echo form_dropdown('country', $count, set_value('country'), 'class="form-control" required');
					?>
					<div><?php echo form_error('country'); ?></div>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase("point_reward_type"); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
					<?php
						if($settings->value == 'english') $name = 'name_en';
						else $name = 'name_ar';
						
						$types[''] = get_phrase('select_type');
						foreach($point_reward_types as $point_reward_type) {	$types[$point_reward_type->id] = $point_reward_type->$name; }
						echo form_dropdown('point_reward_type', $types, set_value('point_reward_type'), 'class="form-control" required');
					?>
					<div><?php echo form_error('point_reward_type'); ?></div>
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
						echo form_dropdown('status', ['0'=>get_phrase('not_active'), '1'=>get_phrase('active')], set_value('status'), 'class="form-control" required');
					?>
					<div><?php echo form_error('status'); ?></div>
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