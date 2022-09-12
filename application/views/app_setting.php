<div class="main-panel">
<div class="content-wrapper">
	<div class="container">

  <ul id="tabs" class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a id="tab-A" href="#pane-A" class="removeClass addClass-A nav-link active" data-toggle="tab" role="tab"><?php echo get_phrase('firebase_key'); ?></a>
    </li>
    <li class="nav-item">
      <a id="tab-B" href="#pane-B" class="removeClass addClass-B nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('paypal'); ?></a>
    </li>
    <li class="nav-item">
      <a id="tab-C" href="#pane-C" class="removeClass addClass-C nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('tap'); ?></a>
    </li> 
    <!-- <li class="nav-item">
      <a id="tab-D" href="#pane-D" class="removeClass addClass-D nav-link" data-toggle="tab" role="tab">SMTP Setting</a>
    </li> -->
    <li class="nav-item">
      <a id="tab-E" href="#pane-E" class="removeClass addClass-E nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('referral_setting'); ?></a>
    </li> 
      <li class="nav-item">
      <a id="tab-F" href="#pane-F" class="removeClass addClass-F nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('sms_setting'); ?></a>
    </li> 
  </ul>

  <div id="content" class="tab-content" role="tablist">
    <div id="pane-A" class="removeClass addClass-A card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-A">
      <div class="card-header" role="tab" id="heading-A">
        <h5 class="mb-0">
          <a data-toggle="collapse" href="#collapse-A" data-parent="#content" aria-expanded="true" aria-controls="collapse-A">
            <?php echo get_phrase('commission'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-A" class="collapse show" role="tabpanel" aria-labelledby="heading-A">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('set_firebase_key'); ?></h4>
          <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/firebaseSetting', $attributes); ?>
            <div class="row">
            <div class="col-md-12">
              <div id="extra">
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><?php echo get_phrase('firebase_server_key_for_artist_app'); ?></label>
                      <div class="col-sm-8">
                      <textarea class="form-control" rows="5" name="artist_key" required="" placeholder="Enter Key"><?php echo $firebase_setting->artist_key; ?></textarea>
					  <div><?php echo form_error('artist_key'); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-4 col-form-label">Firebase Server Key For Customer App<?php echo get_phrase('firebase_server_key_for_customer_app'); ?></label>
                      <div class="col-sm-8">
                      <textarea class="form-control" rows="5" name="customer_key" required="" placeholder="Enter Key"><?php echo $firebase_setting->customer_key; ?></textarea>
					  <div><?php echo form_error('customer_key'); ?></div>
                    </div>
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

    <div id="pane-B" class="removeClass addClass-B card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
      <div class="card-header" role="tab" id="heading-B">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-B" data-parent="#content" aria-expanded="false" aria-controls="collapse-B">
            <?php echo get_phrase('currency'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-B" class="collapse show" role="tabpanel" aria-labelledby="heading-B">
        <div class="card-body">
          <div class="col-lg-12 grid-margin stretch-card">
             <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/paySetting', $attributes); ?>
              <div class="row">
              <div class="col-md-12">
                <div id="extra">
                  <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"><?php echo get_phrase('name'); ?></label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?php echo $paypal_setting->name; ?>" name="paypal_name" required="" placeholder="Enter Key">
						<div><?php echo form_error('paypal_name'); ?></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"><?php echo get_phrase('paypal_username'); ?></label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?php echo $paypal_setting->paypal_id; ?>" name="paypal_username" required="" placeholder="Enter Key">
						<div><?php echo form_error('paypal_username'); ?></div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"><?php echo get_phrase('account_type'); ?></label>
                        <div class="col-sm-9">
                        <input type="radio" name="paypal_type" value="1" <?php if($paypal_setting->type==1) { echo "checked"; } ?>><?php echo get_phrase('sendbox'); ?>
                        <input type="radio" name="paypal_type" value="2" <?php if($paypal_setting->type==2) { echo "checked"; } ?>><?php echo get_phrase('live'); ?>
						<div><?php echo form_error('paypal_type'); ?></div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>
              </div>
            <button type="submit" class="btn btn-success mr-2" ><?php echo get_phrase('submit'); ?></button>
          </form>
        </div>
        </div>
      </div>
    </div>

    <div id="pane-C" class="removeClass addClass-C card tab-pane fade" role="tabpanel" aria-labelledby="tab-C">
      <div class="card-header" role="tab" id="heading-C">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-C" data-parent="#content" aria-expanded="false" aria-controls="collapse-C">
            <?php echo get_phrase('stripe'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-C" class="collapse show" role="tabpanel" aria-labelledby="heading-C">
        <div class="card-body">
          <div class="col-lg-12 grid-margin stretch-card">
            <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/StripSetting', $attributes); ?>
            <div class="row">
            <div class="col-md-12">
              <div id="extra">
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('api_key'); ?></label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" value="<?php echo $stripe_keys->api_key; ?>" name="strip_key" required="" placeholder="Enter Key">
					  <div><?php echo form_error('strip_key'); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('merchant_id'); ?></label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" value="<?php echo $stripe_keys->publishable_key; ?>" name="strip_publishable_key" required="" placeholder="Enter Key">
					  <div><?php echo form_error('strip_publishable_key'); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('username'); ?></label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" value="<?php echo $stripe_keys->username; ?>" name="strip_username" required="" placeholder="Enter Key">
					  <div><?php echo form_error('strip_username'); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('password'); ?></label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" value="<?php echo $stripe_keys->password; ?>" name="strip_password" required="" placeholder="Enter Key">
					  <div><?php echo form_error('strip_password'); ?></div>
                    </div>
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

    <div id="pane-D" class="removeClass addClass-D card tab-pane fade" role="tabpanel" aria-labelledby="tab-D">
      <div class="card-header" role="tab" id="heading-D">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-D" data-parent="#content" aria-expanded="false" aria-controls="collapse-D">
            <?php echo get_phrase('smtp_setting'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-D" class="collapse show" role="tabpanel" aria-labelledby="heading-D">
        <div class="card-body">
          <div class="col-lg-12 grid-margin stretch-card">
            <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/smtpSetting', $attributes); ?>
            <div class="row">
            <div class="col-md-12">
              <div id="extra">
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('email_address'); ?></label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" value="<?php echo $smtp_setting->email_id; ?>" name="smtp_email" required="" placeholder="Enter Key">
					  <div><?php echo form_error('smtp_email'); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('password'); ?></label>
                      <div class="col-sm-9">
                      <input type="password" class="form-control" value="<?php echo $smtp_setting->password; ?>" name="smtp_password" required="" placeholder="Enter Key">
					  <div><?php echo form_error('smtp_password'); ?></div>
                    </div>
                  </div>
                </div>
              </div>
              </div>
            </div>
            <p><b><u><?php echo get_phrase('note'); ?></u>: <?php echo get_phrase('you_should_need_to_change_your'); ?> <a href="<?php echo base_url('assets/email/gmail.png') ?>" target="_blank"> click here</a></b></p>
            <button type="submit" class="btn btn-success mr-2" ><?php echo get_phrase('submit'); ?></button>
          </form>
        </div>

        </div>
      </div>
    </div>

    <div id="pane-E" class="removeClass addClass-E card tab-pane fade" role="tabpanel" aria-labelledby="tab-E">
      <div class="card-header" role="tab" id="heading-E">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-E" data-parent="#content" aria-expanded="false" aria-controls="collapse-E">
            <?php echo get_phrase('referral_setting'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-E" class="collapse show" role="tabpanel" aria-labelledby="heading-E">
        <div class="card-body">
          <div class="col-lg-12 grid-margin stretch-card">
            <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/referral_setting', $attributes); ?>
            <div class="row">
            <div class="col-md-12">
              <div id="extra">
              <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('give_credits'); ?></label>
                      <div class="col-sm-9">
                      <input type="radio" name="referral_type" value="1" onclick="show2();" <?php if($referral_setting->type==1) { echo "checked"; } ?> ><?php echo get_phrase('yes'); ?>
                      <input type="radio" name="referral_type" value="0" <?php if($referral_setting->type==0) { echo "checked"; } ?> onclick="show1();"><?php echo get_phrase('no'); ?>
					  <div><?php echo form_error('referral_type'); ?></div>
                    </div>
                  </div>
                </div>

                <div id="hide1"  <?php if($referral_setting->type==0) { ?> style="display: none;"  <?php } ?> >
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('no_of_usages'); ?></label>
                      <div class="col-sm-9">
                      <input type="number" class="form-control" value="<?php echo $referral_setting->no_of_usages; ?>" name="no_of_usages" required="" placeholder="Enter Key">
					  <div><?php echo form_error('no_of_usages'); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('flat_amount_credit'); ?></label>
                      <div class="col-sm-9">
                      <input type="number" class="form-control" value="<?php echo $referral_setting->amount; ?>" name="referral_amount" required="" placeholder="Enter Key">
					  <div><?php echo form_error('referral_amount'); ?></div>
                    </div>
                  </div>
                </div>
                </div>
              </div>
              </div>
              <p><b><?php echo get_phrase('note'); ?></b> :  <?php echo get_phrase("if_total_number_of_user_increase_with_this_count"); ?></p>
            <button type="submit" class="btn btn-success mr-2" ><?php echo get_phrase('submit'); ?></button>

          </form>
          </div>
        </div>

        </div>
      </div>
    </div>


 <div id="pane-F" class="removeClass addClass-F card tab-pane fade" role="tabpanel" aria-labelledby="tab-D">
      <div class="card-header" role="tab" id="heading-F">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-D" data-parent="#content" aria-expanded="false" aria-controls="collapse-F">
          </a>
        </h5>
      </div>
      <div id="collapse-D" class="collapse show" role="tabpanel" aria-labelledby="heading-D">
        <div class="card-body">
          <div class="col-lg-12 grid-margin stretch-card">
            <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/KeySetting', $attributes); ?>
            <div class="row">
            <div class="col-md-12">
              <div id="extra">
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('user_id'); ?></label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" value="<?php echo $key_setting->user_id; ?>" name="sms_user" required="" placeholder="Enter Key">
					  <div><?php echo form_error('sms_user'); ?></div>
                    </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('password'); ?></label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" value="<?php echo $key_setting->password; ?>" name="sms_password" required="" placeholder="Enter Key">
					  <div><?php echo form_error('sms_password'); ?></div>
                    </div>
                  </div>
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('sender_id'); ?></label>
                      <div class="col-sm-9">
                      <input type="text" class="form-control" value="<?php echo $key_setting->sender; ?>" name="sms_sender" required="" placeholder="Enter Key">
					  <div><?php echo form_error('sms_sender'); ?></div>
                    </div>
                  </div>
                </div>
              </div>
              </div>
            </div>
            <button type="submit" class="btn btn-success mr-2" ><?php echo get_phrase('submit'); ?></button>
          </form>
        </div>
        <p><b><u><?php echo get_phrase('note'); ?></u>:-  <?php echo get_phrase('we_are_using_here_msg91_that_is_an_enterprise'); ?></a></b></p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- <p><b>Note:</b> That setting feature disable in demo version for the security reason. Don't worry about that work well on purchase code. Thank you :)</p> -->
</div>
<script type="text/javascript">
  setTimeout(function() {
     $('#mydiv').fadeOut('fast');
  }, 5000); // <-- time in milliseconds

 function show1(){
    document.getElementById('hide1').style.display ='none';
  }
  function show2(){
    document.getElementById('hide1').style.display = 'block';
  }
</script>