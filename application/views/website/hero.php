<?php $dir = ($lang == 'ar')? 'right':'left'; ?>
<style>
.optionGroup {
    font-weight: bold;
    font-style: italic;
}

.optionChild {
	padding-<?php echo $dir; ?>: 25px;
}
</style>
			<!--==============================Start Main Section==============================-->
            <section class="download__banner pt-header">
                <div class="container-xxl">
                    <div class="row justify-content-between align-items-center flex-column-reverse flex-md-row">
                        <div class="col-md-6 col-lg-5">
                            <h3 class="fw-bold">
								<?php echo $this->lang->line('startworkinganddevelopyourcareer'); ?>
                            </h3>
                            <p class="grey-txt"><?php echo $this->lang->line('startworkinganddevelopyourcareercontent'); ?></p>
                            <div class="actions">
                                <a class="btn" target="_blank" href="https://play.google.com/store/apps/details?id=com.expamaintvendor.android"><img src="<?php echo base_url('/assets/website/images/playstore.svg'); ?>"></a>
                                <a class="btn" target="_blank" href="https://apps.apple.com/ma/app/%D8%B5%D9%8A%D8%A7%D9%86%D8%A9-%D8%A7%D9%83%D8%B3%D8%A8%D8%B1%D9%8A%D8%B3-%D8%A7%D8%B9%D9%85%D8%A7%D9%84/id1530728065?l=ar"> <img src="<?php echo base_url('/assets/website/images/appstore.svg'); ?>"></a>
                            </div>

                        </div>
                        <div class="col-md-5">
                            <div class="cover-banner text-center">
                                <img src="<?php echo base_url('/assets/website/images/Pacific.png'); ?>">
                                <div class="layer layer1 animate" data-animation="fadeInLeft"><img src="<?php echo base_url('/assets/website/images/layer1.svg'); ?>"></div>
                                <div class="layer layer2 animate" data-animation="fadeInRight"><img src="<?php echo base_url('/assets/website/images/layer2.svg'); ?>"></div>
                                <div class="layer layer3 animate" data-animation="zoomIn"><img src="<?php echo base_url('/assets/website/images/layer3.png'); ?>"></div>

                            </div>

                        </div>
                    </div>
                </div>

            </section>
            <!--==============================End Main Section ==============================-->
            <section class="express">
                <div class="container-xxl">
                    <div class="row justify-content-center">
                        <div class="col-10 text-center express-txt">
                            <h3 class="title w-line d-inline-block px-2 fw-bold mb-3">
                                <?php echo $this->lang->line('howdoestheappwork'); ?>
                            </h3>
                            <p class="grey-txt">
                                <?php echo $this->lang->line('howdoestheappworkcontent1'); ?>
								<br>
								<?php echo $this->lang->line('howdoestheappworkcontent2'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-md-3 text-end">
                            <div class="step animate" data-animation="zoomIn">
                                <div class="step-num">
                                    <span> 1 </span>
                                </div>
                                <h6 class="fw-bold"><?php echo $this->lang->line('acceptrequest'); ?></h6>
                                <p class="grey-txt"><?php echo $this->lang->line('acceptrequestcontent'); ?></p>


                            </div>
                            <div class="step animate" data-animation="zoomIn" data-duration="1000">
                                <div class="step-num">
                                    <span> 2 </span>
                                </div>
                                <h6 class="fw-bold"><?php echo $this->lang->line('followlocation'); ?></h6>
                                <p class="grey-txt"><?php echo $this->lang->line('followlocationcontent'); ?></p>


                            </div>

                        </div>
                        <div class="col-md-6 text-center">

                            <div class="ring d-inline-block">
                                <div class="coccoc-alo-phone coccoc-alo-green coccoc-alo-show">
                                    <div class="coccoc-alo-ph-circle"></div>
                                    <div class="coccoc-alo-ph-circle-fill"></div>
                                    <div class="coccoc-alo-ph-circle-fill fill2"></div>
                                    <div class="coccoc-alo-ph-circle-fill fill3"></div>

                                    <div class="coccoc-alo-ph-img-circle">
                                        <img src="<?php echo base_url('/assets/website/images/Graphite.png'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="step animate" data-animation="zoomIn" data-duration="2000">
                                <div class="step-num">
                                    <span> 3 </span>
                                </div>
                                <h6 class="fw-bold"><?php echo $this->lang->line('finishmaintenance'); ?></h6>
                                <p class="grey-txt"><?php echo $this->lang->line('finishmaintenancecontent'); ?></p>


                            </div>
                            <div class="step animate" data-animation="zoomIn" data-duration="3000">
                                <div class="step-num">
                                    <span> 4 </span>
                                </div>
                                <h6 class="fw-bold"><?php echo $this->lang->line('getpaid'); ?></h6>
                                <p class="grey-txt"><?php echo $this->lang->line('getpaidcontent'); ?></p>


                            </div>
                        </div>
                    </div>

                </div>

            </section>

            <section class="register mb__section mt-0" id="register-now">
                <div class="wrapper-register container-xxl">
                    <div class="row justify-content-between">

                        <div class="col-md-5">
                            <h3 class="title w-line text-white px-2">
                                <?php echo $this->lang->line('heroregisternow'); ?>
                            </h3>
                            <p>
                                <?php echo $this->lang->line('registerwithusasahero'); ?>
                            </p>
							<br>
							<p>
                                <?php echo $this->lang->line('youcanincreaseyourdailyincomewitheaintenanceexpress'); ?>
                            </p>
                        </div>
                        <div class="col-md-5 position-relative">
                            <div class="form-wrapper">
                                <h5 class="fw-bold mb-4"> <?php echo $this->lang->line('registernow'); ?> </h5>
								<?php //echo validation_errors('<div class="error">', '</div>'); ?>
								<?php
									$attributes = array('method' => 'POST', /*'data-parsley-validate' => '', */'class' => 'grey-form');
									echo form_open($lang.'/hero_post', $attributes);
									//echo validation_errors();
								?>
									<input type="hidden" id="lang" value="<?php echo $lang; ?>">
                                    <div class="mb-4">
										<?php
											$data = array(
												'type' => 'text',
												'name' => 'name',
												'placeholder' => $this->lang->line('name'),
												'class' => 'form-control',
												//'max' => 255,
												'required' => 'required',
												'value' => set_value('name')
											);
											echo form_input($data);
										?>
                                    </div>
									<div><?php echo form_error('name'); ?></div>
                                    <div class="mb-4">
										<label class="register_file_content"><?php echo $this->lang->line('enterjust10digits'); ?></label>
										<?php
											$data = array(
												'type' => 'number',
												'name' => 'mobile',
												'placeholder' => $this->lang->line('mobile'),
												'class' => 'form-control',
												//'max' => 255,
												'oninput' => 'javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);',
												'maxlength' => 10,
												'required' => 'required',
												'value' => set_value('mobile')
											);
											echo form_input($data);
										?>
                                    </div>
									<div><?php echo form_error('mobile'); ?></div>
									<div class="mb-4">
										<?php
											//$ourtypes[''] = $this->lang->line('chooseservices');
											/*if(!empty($services))
											{
												foreach($services as $service) {	$ourtypes[$service->id] = ($lang == 'ar')? $service->cat_name_ar:$service->cat_name; }
												echo form_dropdown('services[]', $ourtypes, set_value('services'), 'class="form-select form-control" required="required" multiple');
											}
											else echo form_dropdown('services[]', [''=>$this->lang->line('chooseservices')], '', 'class="form-select form-control" required="required"');*/
										?>
										<select name="categories[]" class="form-select form-control" multiple="multiple">
											<?php
											$name = ($lang == 'ar')? 'cat_name_ar':'cat_name'; 
											foreach($categories as $category) { ?>
												<option value="<?php echo $category['id']; ?>" class="optionGroup"><?php echo $category[$name]; ?></option>
												<?php foreach($category['children'] as $children) { ?>
												<option value="<?php echo $children['id']; ?>" class="optionChild"><?php echo $children[$name]; ?></option>
											<?php } } ?>
										</select>
                                    </div>
									<div><?php echo form_error('categories[]'); ?></div>
                                    <!--<div class="mb-4">
										<?php
											//echo form_dropdown('catigory', [''=>'Service type','1'=>'One','2'=>'Two','3'=>'Three'], set_value('catigory'), 'class="form-select form-control" required="required"');
										?>
                                    </div>-->
									<!--<div class="mb-4">
										<?php
											/*$ourtypes[''] = $this->lang->line('chooseservices');
											if(!empty($countries))
											{
												foreach($countries as $country) {	$ourtypes[$country->id] = ($lang == 'ar')? $country->name_ar:$country->name; }
												echo form_dropdown('country', $ourtypes, set_value('country'), 'id="country" class="form-select form-control" required="required"');
											}
											else echo form_dropdown('country', $ourtypes, set_value('country'), 'id="country" class="form-select form-control" required="required"');*/
										?>
                                    </div>
									<div class="mb-4" id="cities">
										<select class="form-select form-control" required="required" id="city" name="city">
											<option value=""><?php //echo $this->lang->line('choosecity'); ?></option>
										</select>
                                    </div>-->
									<?php
										$data = array(
											'class' => 'btn btn-solid-primary w-100',
											'type' => 'submit',
											//'disabled' => 'disabled',
											'content' => $this->lang->line('startregister'),
										);
										echo form_button($data);
									?>
                                <?php echo form_close(); ?>
                            </div>



                        </div>
                    </div>

                </div>
            </section>
			
<script>
	$('option').mousedown(function(e) {
		e.preventDefault();
		$(this).prop('selected', !$(this).prop('selected'));
		return false;
	});
	
	$(document).ready(function(){
		$("#country").change(function(){
			var val1 = $(this).val();
			var val2 = $("#lang").val();
			$.ajax({
				type: 'POST',
				url: '<?php echo base_url(); ?>Website/getcities',
				data: {
					'id' : val1,
					'lang' : val2,
				},
				success: function (response) { document.getElementById('cities').innerHTML = response; }
			});
		});
	});
</script>