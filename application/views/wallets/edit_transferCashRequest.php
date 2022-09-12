<div class="main-panel">
  <div class="content-wrapper">
  <div class="row">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('edit_transfer_cash_request'); ?></h4>
           <?php $attributes = array('id' => 'form_validation', 'class'=> 'form-sample'); echo form_open_multipart('Wallet/updatetransferCashRequest/'.$transfer_cash_request->id, $attributes); ?>
           <!--  <p class="card-description">
              Personal info
            </p> -->
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('artist'); ?></label>
                  <div class="col-sm-9">
                    <?php echo $transfer_cash_request->name; ?>
                  </div>
                </div>
              </div>
			</div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('amount'); ?></label>
                  <div class="col-sm-9">
                    <?php echo $transfer_cash_request->amount; ?>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('wallet'); ?></label>
                  <div class="col-sm-9">
                    <?php echo $transfer_cash_request->wallet; ?>
                  </div>
                </div>
              </div>
            </div>
			
			<div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-3 col-form-label"><?php echo get_phrase('comment'); ?><span style="color:red;font-weight:bold"> *</span></label>
                  <div class="col-sm-9">
                    <textarea name="comment" class="form-control" required><?php echo $transfer_cash_request->comment; ?></textarea>
					<div><?php echo form_error('comment'); ?></div>
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
						echo form_dropdown('status', ['1'=>get_phrase('accept'), '2'=>get_phrase('reject')], array(), 'class="form-control"');
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