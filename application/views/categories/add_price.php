<div class="main-panel">
  <div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('add_price'); ?></h4>
           <?php $attributes = array('id' => 'form_validation', 'class'=> 'form-sample'); echo form_open_multipart('Category/storePrice/'.$category->id, $attributes); ?>		   
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase("country"); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
					<select name="country_price" class="form-control" required>
						<option value="" currency=""><?php echo get_phrase('select_country'); ?></option>
						<?php
							foreach($countries as $country)
							{
								echo '<option value="'.$country->id.'" currency="'.$country->currency.'">'.$country->name.'</option>';
							}
						?>
					</select>
					<div><?php echo form_error('country_price'); ?></div>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('price'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-6">
                    <input type="number" step="0.01" min="0.01" name="price" class="form-control" value="<?php echo set_value('price'); ?>" required />
					<div><?php echo form_error('price'); ?></div>
                  </div>
				  <div class="col-sm-3" style="text-align:right;" id="currency">
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
						echo form_dropdown('status', ['0'=>get_phrase('not_active'), '1'=>get_phrase('active')], set_value('status'), 'class="form-control"');
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