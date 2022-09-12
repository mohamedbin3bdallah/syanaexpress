 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('booking_number'). ":".  $bookingDetails->id;  ?> </h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/saveAddBookingItem', $attributes); ?>

                    <div class="row">

                        <div class="col-md-6">
                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td>Booking Number</td>
                                    <td><?php echo $bookingDetails->id ?></td>
                                </tr>
                                <tr>
                                    <td>Customer Name</td>
                                    <td><?php echo $bookingDetails->cst_name ?></td>
                                </tr>
                                <tr>
                                    <td>Customer Mobile</td>
                                    <td><?php echo $bookingDetails->mobile ?></td>
                                </tr>
                                <tr>
                                    <td>Price</td>
                                    <td><?php echo $bookingDetails->price ?></td>
                                </tr>
                                <tr>
                                    <td>Booking Date</td>
                                    <td><?php echo $bookingDetails->booking_date ?></td>
                                </tr>
                                <tr>
                                    <td>Booking Time</td>
                                    <td><?php echo $bookingDetails->booking_time ?></td>
                                </tr>
                                <tr>
                                    <td>Category</td>
                                    <td><?php echo $bookingDetails->cat_name ?></td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td><?php echo $bookingDetails->status_name ?></td>
                                </tr>
                                <tr>
                                    <td>Payment Method</td>
                                    <td><?php echo $bookingDetails->payment_name ?></td>
                                </tr>
                                <tr>
                                    <td>Details</td>
                                    <td><?php echo $bookingDetails->details ?></td>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td><?php echo $bookingDetails->city_name ?></td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td><?php echo $bookingDetails->address ?></td>
                                </tr>
                                <tr>
                                    <td>Building</td>
                                    <td><?php echo $bookingDetails->building ?></td>
                                </tr>
                                <tr>
                                    <td>Floor</td>
                                    <td><?php echo $bookingDetails->floor ?></td>
                                </tr>
                                <tr>
                                    <td>Apartment</td>
                                    <td><?php echo $bookingDetails->apartment ?></td>
                                </tr>
                                <tr>
                                    <td>Landmark</td>
                                    <td><?php echo $bookingDetails->landmark ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <h3>Booking Order Items</h3><br>
                    <div class="row">

                        <div class="col-md-6">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <th>Sub Category Name</th>
                                    <th>Quantity</th>
                                    <th>Cost Per Item</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </thead>
                                <tbody>
                                <?php
                                    foreach($bookingItems as $item){
                                        echo "<tr>";
                                        echo "<td>". $item->cat_name ."//". $item->cat_name_ar."</td>";
                                        echo "<td>". $item->quantity."</td>";
                                        echo "<td>". $item->cost_per_item."</td>";
                                        echo "<td>". $item->cost_per_item * $item->quantity."</td>";
                                        echo '<td> <a href="'. base_url() .'Admin/deleteBookingItem?itemId='. $item->id .'&bookingId='. $item->booking_order_id .'">Delete</a></td>';
                                        echo "</tr>";

                                    }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if($bookingDetails->status_id != 2 && $bookingDetails->status_id != 4 ){

                    ?>
                    <div class="row" style="margin-top: 50px">
                    <div class="col-md-9">
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('sub_category_name'); ?></label>
                          <div class="col-sm-9">
                            <select class="form-control form-control-sm" name="sub_cat_id">
                                <option disabled>-- Select Sub Category -- </option>
                                <?php
                                foreach($subCategories as $subCategory){
                                    echo "<option value='$subCategory->id'>$subCategory->cat_name // $subCategory->cat_name_ar ($subCategory->price)</option>";
                                }

                                ?>
                            </select>
							<div><?php echo form_error('sub_cat_id'); ?></div>

                            <input type="hidden" name="booking_id" value="<?php echo $bookingDetails->id ?>" class="form-control" required=""  />
							<div><?php echo form_error('booking_id'); ?></div>
                          </div>



                        </div>

                      </div>

                      </div>



                    </div>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"><?php echo get_phrase('quantity'); ?></label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control form-control-sm" name="quantity" value="1" />
											<div><?php echo form_error('quantity'); ?></div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <button type="submit" class="btn btn-success mr-2"><?php echo get_phrase('submit'); ?></button>
                  </form>
                    <?php } ?>
                </div>
              </div>
            </div>
  </div>
</div>