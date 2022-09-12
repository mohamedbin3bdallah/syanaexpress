 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('edit_category'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/editCategoryAction/'.$category->id, $attributes); ?>
                   <input type="hidden" name="id" value="<?php echo $category->id ?>" class="form-control" required=""  />
					<div class="row">
                    <div class="col-md-12">
                      <div class="col-md-8">
						<!--<div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php //echo get_phrase('country'); ?></label>
                          <div class="col-sm-9">
							<select name="country_id" class="form-control" required>
								<option value="" currency=""><?php //echo get_phrase('select_country'); ?></option>
								<?php
									/*foreach($countries as $country)
									{
										$selected = ($country->id == $category->country_id)? 'selected':'';
										echo '<option value="'.$country->id.'" currency="'.$country->currency.'" '.$selected.'>'.$country->name.'</option>';
									}*/
								?>
							</select>
                          </div>
                        </div>-->
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('category_name'); ?></label>
                          <div class="col-sm-9">
                            <input type="text" name="cat_name" value="<?php echo $category->cat_name ?>" class="form-control" required="" placeholder="<?php echo get_phrase('category_name'); ?>" />
                            <div><?php echo form_error('cat_name'); ?></div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('category_name_arabic'); ?></label>
                          <div class="col-sm-9">
                            <input type="text" name="cat_name_ar" value="<?php echo $category->cat_name_ar ?>" class="form-control" required="" placeholder="<?php echo get_phrase('category_name_arabic'); ?>" />
							<div><?php echo form_error('cat_name_ar'); ?></div>
                          </div>
                        </div>
						<?php if(!$is_parent) { ?>
						<div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('child'); ?></label>
                          <div class="col-sm-9">
                            <input class="form-check-input" type="checkbox" name="check_cat" value="<?php echo $category->parent_id ?>" cat_id="<?php echo $category->id ?>" price="<?php echo $category->price ?>" <?php if($category->parent_id) echo 'checked'; ?>>
                          </div>
                        </div>
						<?php } ?>
						<div id="parent_div">
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('category_image'); ?></label>
                          <image src="<?php echo $category->image ?>" width="100">
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
                            <input type="number" name="price" value="<?php echo $category->price ?>" class="form-control" required="" placeholder="Price" />
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