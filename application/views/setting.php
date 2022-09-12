<div class="main-panel">
<div class="content-wrapper">
	<div class="container">

  <ul id="tabs" class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a id="tab-A" href="#pane-A" class="removeClass addClass-A nav-link active" data-toggle="tab" role="tab"><?php echo get_phrase('commision'); ?></a>
    </li>
    <li class="nav-item">
      <a id="tab-B" href="#pane-B" class="removeClass addClass-B nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('currency'); ?></a>
    </li>
   <!-- <li class="nav-item">
      <a id="tab-C" href="#pane-C" class="removeClass addClass-C nav-link" data-toggle="tab" role="tab">Referral Discount</a>
    </li> -->
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
          <h4 class="card-title"><?php echo get_phrase('commission_type'); ?></h4>
          <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/commissionSetting', $attributes); ?>
		  <input type="hidden" name="id" value="<?php echo $commission_setting->id; ?>" class="form-control" required=""/>
            <div class="row">
            <div class="col-md-12">
              <div class="col-md-8">
                <div class="form-group row">
                  <label class="col-sm-4 col-form-label"><?php echo get_phrase('commission_based_on'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-8">
                    <!--   -->
                    <input type="radio" id="flatCommission" value="1" name="commission_type" <?php if($commission_setting->commission_type==1){ echo "checked"; } ?> /><?php echo get_phrase('flat'); ?>
					<div><?php echo form_error('commission_type'); ?></div>
                  </div>
                </div>
              </div>
              <!-- <?php if($commission_setting->commission_type==0) { ?>  -->
              <!-- <div id="extra" style='display:none'>
              <?php } if($commission_setting->commission_type==1) { ?>  -->
              <div id="extra">
              <!-- <?php } ?> -->
                <div class="col-md-8">
                  <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><?php echo get_phrase('commission'); ?></label>
                      <div class="col-sm-8">
                      <input type="text" id="numberbox" name="flat_amount" value="<?php echo $commission_setting->flat_amount; ?>" class="form-control" required="" placeholder="Commission" />
					  <div><?php echo form_error('flat_amount'); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group row">
                    <label class="col-sm-4 col-form-label"><?php echo get_phrase('commission_type'); ?></label>
                    <div class="col-sm-8">
                      <select class="form-control" name="flat_type" required="">
                        <option value="1" <?php if ($commission_setting->flat_type == 1) echo 'selected'; ?>><?php echo get_phrase('percentage'); ?></option>
                       <!--  <option value="2" <?php if ($commission_setting->flat_type == 2) echo 'selected'; ?>>Flat Cost (<?php echo $currency_type; ?>)</option> -->
                      </select>
					  <div><?php echo form_error('flat_type'); ?></div>
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
             <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/currency_setting', $attributes); ?>
            <div class="row">
             <div class="col-md-12">
                <div class="row">
                  <label class="col-md-4 col-form-label"><?php echo get_phrase('currency_type'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-md-8">
                    <select class="form-control" name="currency">
                      <?php foreach ($currency_setting as $currency_setting) { ?> 
                      <option value="<?php echo $currency_setting->id; ?>"<?php if ($currency_setting->status == 1) echo ' selected="selected"'; ?>><?php echo $currency_setting->currency_name; ?> (<?php echo $currency_setting->currency_symbol; ?>)</option>
                      <?php } ?>
                    </select>
					<div><?php echo form_error('currency'); ?></div>
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

    <div id="pane-C" class="removeClass addClass-C card tab-pane fade" role="tabpanel" aria-labelledby="tab-C">
      <div class="card-header" role="tab" id="heading-C">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-C" data-parent="#content" aria-expanded="false" aria-controls="collapse-C">
          <?php echo get_phrase('currency'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-C" class="collapse show" role="tabpanel" aria-labelledby="heading-C">
        <div class="card-body">
          <div class="col-lg-12 grid-margin stretch-card">
             <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/set_discount', $attributes); ?>
            <div class="row">
             <div class="col-md-12">
                <div class="row">
                  <div class="col-md-8">
                   <div class="form-group row">
                      <label class="col-sm-4 col-form-label"><?php echo get_phrase('set_discount'); ?><span style="color:red;font-weight:bold"> *</span></label>
                      <div class="col-sm-8">
                      <input type="number" maxlength="2" name="discount" value="<?php echo $set_discount->discount; ?>" class="form-control" required="" placeholder="Set Discount" />
					  <div><?php echo form_error('discount'); ?></div>
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
  </div>
</div>
</div>
<script type="text/javascript">
  setTimeout(function() {
     $('#mydiv').fadeOut('fast');
  }, 5000); // <-- time in milliseconds
</script>