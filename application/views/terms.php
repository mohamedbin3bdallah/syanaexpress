<div class="main-panel">
<div class="content-wrapper">
	<div class="container">
   <?php if ($this->session->flashdata('terms')) { ?>
      <div id="mydiv" class="alert alert-success"><?= $this->session->flashdata('terms_success') ?>
      <?php echo get_phrase('updated_successfully'); ?>
      </div>
  <?php } ?>

  <ul id="tabs" class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a id="tab-A" href="#pane-A" class="nav-link active" data-toggle="tab" role="tab"><?php echo get_phrase('terms_details'); ?></a>
    </li>
  </ul>

  <div id="content" class="tab-content" role="tablist">
    <div id="pane-A" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-A">
      <div class="card-header" role="tab" id="heading-A">
        <h5 class="mb-0">
          <a data-toggle="collapse" href="#collapse-A" data-parent="#content" aria-expanded="true" aria-controls="collapse-A">
          <?php echo get_phrase('commission'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-A" class="collapse show" role="tabpanel" aria-labelledby="heading-A">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('terms'); ?></h4>
          <?php $attributes = array('id' => 'form_validation','name'=>'add_coupon','class'=>'form-sample'); echo form_open_multipart('Admin/editTerms', $attributes); ?>
            <div class="row">
            <div class="col-md-12">
              <div id="extra">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"><?php echo get_phrase('terms_title'); ?><span style="color:red;font-weight:bold"> *</span></label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?php echo $terms->title; ?>" name="title" placeholder="Enter Key">
						<div><?php echo form_error('title'); ?></div>
                      </div>
                    </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('terms_description'); ?><span style="color:red;font-weight:bold"> *</span></label>
                      <div class="col-sm-9">
                      <textarea class="form-control" rows="5" id="pageDescription" name="description" required="" placeholder="Enter Key"><?php echo $terms->description; ?></textarea>
					  <div><?php echo form_error('description'); ?></div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label"><?php echo get_phrase('terms_title_arabic'); ?><span style="color:red;font-weight:bold"> *</span></label>
                        <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?php echo $terms->title_ar; ?>" name="title_ar" required="" placeholder="Enter Key">
						<div><?php echo form_error('title_ar'); ?></div>
                      </div>
                    </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('terms_description_arabic'); ?><span style="color:red;font-weight:bold"> *</span></label>
                      <div class="col-sm-9">
                      <textarea class="form-control" rows="5" id="pageDescriptionAr" name="description_ar" required="" placeholder="Enter Key"><?php echo $terms->description_ar; ?></textarea>
					  <div><?php echo form_error('description_ar'); ?></div>
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