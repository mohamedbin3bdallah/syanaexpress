 <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title"><?php echo get_phrase('artist_name') . "/".  get_phrase('booking_number'). ":".  $artists[0]->bookingId;  ?> </h4>
                   <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/saveBookingArtistSelect', $attributes); ?>
                    <div class="row">
                    <div class="col-md-12">
                      <div class="col-md-8">
                        <div class="form-group row">
                          <label class="col-sm-3 col-form-label"><?php echo get_phrase('artist_name'); ?></label>
                          <div class="col-sm-9">
                            <select class="form-control form-control-sm" name="artist_id">
                                <option disabled>-- Select Artist -- </option>
                                <?php
                                foreach($artists as $artist){
                                    echo "<option value='$artist->id'>$artist->userName</option>";
                                }



                                ?>
                            </select>
                            <input type="hidden" name="booking_id" value="<?php echo $artists[0]->bookingId ?>" class="form-control" required=""  />
                          </div>
                        </div>

                      </div>

                      </div>
                    </div>
                    <button type="submit" class="btn btn-success mr-2"><?php echo get_phrase('submit'); ?></button>
                  </form>
                </div>
              </div>
            </div>
  </div>
</div>