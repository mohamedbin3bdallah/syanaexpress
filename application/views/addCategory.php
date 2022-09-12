 <!-- partial -->
 <div class="main-panel">
        <div class="content-wrapper">
		  
		  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		  
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('add_category'); ?></h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/addCategoryAction', $attributes); ?>
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
										echo '<option value="'.$country->id.'" currency="'.$country->currency.'">'.$country->name.'</option>';
									}*/
								?>
							</select>
                          </div>
                        </div>-->
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('category_name'); ?></label>
                          <div class="col-sm-9">
                            <input type="text" name="cat_name" value="<?php echo set_value('cat_name'); ?>" class="form-control" required="" placeholder="<?php echo get_phrase('category_name'); ?>" />
							<div><?php echo form_error('cat_name'); ?></div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('category_name_arabic'); ?></label>
                          <div class="col-sm-9">
                            <input type="text" name="cat_name_ar" value="<?php echo set_value('cat_name_ar'); ?>" class="form-control" required="" placeholder="<?php echo get_phrase('category_name_arabic'); ?>" />
							<div><?php echo form_error('cat_name_ar'); ?></div>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('child'); ?></label>
                          <div class="col-sm-9">
                            <input class="form-check-input" type="checkbox" name="check_cat" value="0" cat_id="0" price="0.00">
                          </div>
                        </div>
						<div id="parent_div">
                        </div>
						<div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('category_image'); ?></label>
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
          <h4 class="card-title"><?php echo get_phrase('all_categories'); ?></h4>
 
          <table id="example" class="table table-striped">
            <thead>
              <tr>
                <th>
                <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                 <?php echo get_phrase('category_name'); ?>
                </th>
				<th>
                 <?php echo get_phrase('category_name_arabic'); ?>
                </th>
				<th>
				 <?php echo get_phrase('parent'); ?>
				</th>
				<!--<th>
                  <?php //echo get_phrase('country'); ?>
                </th>
                <th>
                  <?php //echo get_phrase('commission_rate'); ?>
                </th>-->
                <th>
                  <?php echo get_phrase('price'); ?>
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
             foreach ($categories as $category) {
              $i++;  ?>
              <tr>
                <td class="py-1">
                  <?php echo $i; ?>
                </td>
                <td>
                  <?php echo $category['cat_name']; ?>
                </td>
				<td>
                  <?php echo $category['cat_name_ar']; ?>
                </td>
				<td>
                  <?php if($category['parent_id']) echo (getSelectedLanguage() == 'arabic')? $categories[$category['parent_id']]['cat_name_ar']:$categories[$category['parent_id']]['cat_name']; ?>
                </td>
				<!--<td>
                  <?php //echo $category->country; ?>
                </td>
                <td>
                 <?php //echo $currency_type; echo $category->price; ?>
				 <?php //echo $category->price.' '.$category->currency; ?>
                </td>-->
				<td>
				  <?php if($category['parent_id']) { ?>
				  <?php if(isset($category['prices'])&& !empty($category['prices'])) { ?>
				  <a data-toggle="modal" href="#details<?php echo $i; ?>">
					<?php echo get_phrase('details'); ?>
				  </a>

				  <div class="modal fade" id="details<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLongTitle"><?php echo $category['cat_name'].' - '.$category['cat_name_ar']; ?></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<table id="priceDataTable" class="table table-borderless">
									<thead style="background-color:#ccc;">
										<tr>
											<th><?php echo get_phrase('country'); ?></th>
											<th><?php echo get_phrase('price'); ?></th>
											<th><?php echo get_phrase('status'); ?></th>
											<th><?php echo get_phrase('manage'); ?></th>
										</tr>
									</thead>
									<tbody style="background-color:#e9ecef;">
										<?php foreach($category['prices'] as $price) { ?>
											<tr>
												<td><?php echo $price['country']; ?></td>
												<td><?php echo $price['price'].' '.$price['currency']; ?></td>
												<td><?php if($price['status']) echo get_phrase('active'); else echo get_phrase('not_active') ?></td>
												<td>
												  <div class="btn-group dropdown">
													<button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<?php echo get_phrase('manage'); ?>
													</button>
													<div class="dropdown-menu">
														<a class="dropdown-item" href="<?php echo base_url('Category/editPrice/').$price['id']; ?>"><?php echo get_phrase('edit'); ?></a>
														<a class="dropdown-item" href="<?php echo base_url('Category/deletePrice/').$price['id']; ?>" onclick="return confirm('<?php echo get_phrase('are_you_sure_you_want_to_delete_this_item_?'); ?>')"><?php echo get_phrase('delete'); ?></a>
													</div>
												  </div>
												</td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
							<div class="/*modal-footer*/" style="text-align:center;padding-bottom:15px;">
								 <?php if(count($category['prices']) < $count_countries) { ?><a class="btn btn-primary" href="<?php echo base_url('Category/addPrice/'.$category['id']) ?>" role="button"><?php echo get_phrase('add_price'); ?></a><?php } ?>
							</div>
						</div>
					</div>
				  </div>
				  <?php } else echo '<a href="'.base_url('Category/addPrice/'.$category['id']).'" role="button">'.get_phrase('add_price').'</a>'; ?>
				  <?php } ?>
                </td>
                 <td>
                 <?php  if( $category['status']){ ?><button class="btn active_category btn-success btn-sm"><?php echo get_phrase('active'); ?> </button><?php }else {  ?><button class="active_category btn-danger btn btn-sm"><?php echo get_phrase('deactive'); ?></button> <?php }?><input  type="text"  value="<?php echo $category['id'];?>" hidden>
                </td>
                 <td>
                 <div class="btn-group dropdown">
                  <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo get_phrase('manage'); ?>
                  </button>
                  <div class="dropdown-menu">
                   <!--  <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_category');?>?id=<?php echo $category['id']; ?>&status=1&request=1"><i class="fa fa-reply fa-fw"></i>Active</a>
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_category');?>?id=<?php echo $category['id']; ?>&status=0&request=1"><i class="fa fa-history fa-fw"></i>Deative</a>
                     <div class="dropdown-divider"></div> -->
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/editCategory');?>?id=<?php echo $category['id']; ?>"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit'); ?></a>
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