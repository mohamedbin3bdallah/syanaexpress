<section class="login-form pt-header">
                <div class="content__section">
                    <div class="container-xxl">
                        <div class="row justify-content-center">
                            <div class="col-md-8 col-lg-6">
                                <div class="box">
                                    <div class="wrapper__box">
                                        <div class="title__box">
                                            <h5 class="main-color w-meduim text-center">
                                                <?php echo $this->lang->line('hello'); ?>
                                            </h5>
                                            <p>
												<?php echo $this->lang->line('registercontent'); ?>
											</p>
                                        </div>
                                        <nav class="nav-signup">
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <button class="nav-link active" id="personal-data-tab" data-bs-toggle="tab" data-bs-target="#personal-data" type="button" role="tab" aria-controls="personal-data" aria-selected="true"><?php echo $this->lang->line('personaldata'); ?></button>
                                                <button class="nav-link" id="work-data-tab" data-bs-toggle="tab" data-bs-target="#work-data" type="button" role="tab" aria-controls="work-data" aria-selected="false"><?php echo $this->lang->line('workdata'); ?></button>
                                            </div>
                                        </nav>
                                        <div class="tab-content" id="nav-tabContent">
                                            <div class="tab-pane fade show active" id="personal-data" role="tabpanel" aria-labelledby="personal-data-tab">
                                                <?php
													$attributes = array('method' => 'POST', 'enctype' => 'multipart/form-data');
													echo form_open($lang.'/register_post', $attributes);
													//echo validation_errors();
													//echo form_radio('gender', 'F', NULL, 'id="female" '.set_radio('gender', 'F'));
												?>
												<input type="hidden" name="artist" value="<?php echo $artist; ?>">
												<input type="hidden" id="lang" value="<?php echo $lang; ?>">
                                                    <div class="mb-4">
                                                        <label class="mb-3"> <?php echo $this->lang->line('country'); ?> </label>
														<?php
															$ourtypes[''] = $this->lang->line('choosecountry');
															if(!empty($countries))
															{
																foreach($countries as $country) {	$ourtypes[$country->id] = ($lang == 'ar')? $country->name_ar:$country->name; }
																echo form_dropdown('country', $ourtypes, set_value('country'), 'id="country" class="form-select form-control"');
															}
															else echo form_dropdown('country', $ourtypes, set_value('country'), 'id="country" class="form-select form-control"');
														?>
                                                    </div>
													<div><?php echo form_error('country'); ?></div>
                                                    <div class="mb-4">
                                                        <label class="mb-3"> <?php echo $this->lang->line('city'); ?> </label>
														<select class="form-select form-control" id="cities" name="city">
															<option value=""><?php echo $this->lang->line('choosecity'); ?></option>
														</select>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="mb-3"> <?php echo $this->lang->line('emailaddress'); ?> </label>
														<?php
															$data = array(
																'type' => 'email',
																'name' => 'email',
																'placeholder' => 'Exampl@email.com',
																'class' => 'form-control',
																//'max' => 255,
																//'required' => 'required',
																'value' => set_value('email')
															);
															echo form_input($data);
														?>
                                                    </div>
													<div><?php echo form_error('email'); ?></div>
                                                    <div class="mb-4 d-flex">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="nationality" value="1" id="flexRadioDefault1">
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                                <?php echo $this->lang->line('saudi'); ?>
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="nationality" value="0" id="flexRadioDefault2" checked="checked">
                                                            <label class="form-check-label" for="flexRadioDefault2">
                                                                <?php echo $this->lang->line('otherpersonalities'); ?>
                                                            </label>
                                                        </div>
                                                    </div>
													<div><?php echo form_error('nationality'); ?></div>
                                                    <div class="mb-4">
                                                        <label class="mb-3"> <?php echo $this->lang->line('password'); ?> </label>
                                                        <div class="input-pass">
															<?php
																$data = array(
																	'type' => 'password',
																	'name' => 'password',
																	'id' => 'password-field',
																	'placeholder' => $this->lang->line('password'),
																	'class' => 'form-control',
																	//'required' => 'required',
																	'value' => set_value('password')
																);
																echo form_input($data);
															?>
                                                            <a href="javascript:;" class="toggle-password" toggle="#password-field"> <svg id="Eyes" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                                    <g id="eye-off">
                                                                        <rect id="Rectangle_217" data-name="Rectangle 217" width="24" height="24" fill="#cac6d4" opacity="0"/>
                                                                        <path id="Path_201" data-name="Path 201" d="M4.71,3.29A1,1,0,1,0,3.29,4.71l5.63,5.63a3.5,3.5,0,0,0,4.74,4.74l5.63,5.63a1,1,0,1,0,1.42-1.42ZM12,13.5A1.5,1.5,0,0,1,10.5,12v-.07l1.56,1.56Z" fill="#cac6d4"/>
                                                                        <path id="Path_202" data-name="Path 202" d="M12.22,17c-4.3.1-7.12-3.59-8-5A13.7,13.7,0,0,1,6.46,9.28L5,7.87A15.89,15.89,0,0,0,2.13,11.5a1,1,0,0,0,0,1c.63,1.09,4,6.5,9.89,6.5h.25a9.48,9.48,0,0,0,3.23-.67l-1.58-1.58a7.74,7.74,0,0,1-1.7.25Z" fill="#cac6d4"/>
                                                                        <path id="Path_203" data-name="Path 203" d="M21.87,11.5C21.23,10.39,17.7,4.82,11.73,5a9.48,9.48,0,0,0-3.23.67l1.58,1.58A7.74,7.74,0,0,1,11.78,7c4.29-.11,7.11,3.59,8,5a13.7,13.7,0,0,1-2.29,2.72L19,16.13a15.89,15.89,0,0,0,2.91-3.63,1,1,0,0,0-.04-1Z" fill="#cac6d4"/>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
													<div><?php echo form_error('password'); ?></div>
                                                    <div class="mb-5">
                                                        <label class="mb-3"> <?php echo $this->lang->line('re-enterpassword'); ?> </label>
                                                        <div class="input-pass">
															<?php
																$data = array(
																	'type' => 'password',
																	'name' => 'cnfpassword',
																	'id' => 'password-field1',
																	'placeholder' => $this->lang->line('re-enterpassword'),
																	'class' => 'form-control',
																	//'required' => 'required',
																	'value' => set_value('cnfpassword')
																);
																echo form_input($data);
															?>
                                                            <a href="javascript:;" class="toggle-password" toggle="#password-field1"> <svg id="Eyes" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                                                    <g id="eye-off">
                                                                        <rect id="Rectangle_217" data-name="Rectangle 217" width="24" height="24" fill="#cac6d4" opacity="0"/>
                                                                        <path id="Path_201" data-name="Path 201" d="M4.71,3.29A1,1,0,1,0,3.29,4.71l5.63,5.63a3.5,3.5,0,0,0,4.74,4.74l5.63,5.63a1,1,0,1,0,1.42-1.42ZM12,13.5A1.5,1.5,0,0,1,10.5,12v-.07l1.56,1.56Z" fill="#cac6d4"/>
                                                                        <path id="Path_202" data-name="Path 202" d="M12.22,17c-4.3.1-7.12-3.59-8-5A13.7,13.7,0,0,1,6.46,9.28L5,7.87A15.89,15.89,0,0,0,2.13,11.5a1,1,0,0,0,0,1c.63,1.09,4,6.5,9.89,6.5h.25a9.48,9.48,0,0,0,3.23-.67l-1.58-1.58a7.74,7.74,0,0,1-1.7.25Z" fill="#cac6d4"/>
                                                                        <path id="Path_203" data-name="Path 203" d="M21.87,11.5C21.23,10.39,17.7,4.82,11.73,5a9.48,9.48,0,0,0-3.23.67l1.58,1.58A7.74,7.74,0,0,1,11.78,7c4.29-.11,7.11,3.59,8,5a13.7,13.7,0,0,1-2.29,2.72L19,16.13a15.89,15.89,0,0,0,2.91-3.63,1,1,0,0,0-.04-1Z" fill="#cac6d4"/>
                                                                    </g>
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </div>
													<div><?php echo form_error('cnfpassword'); ?></div>
                                                    <!--<button class="btn btn-form w-100"> Register </button>
                                                </form>-->
                                            </div>
                                            <div class="tab-pane fade" id="work-data" role="tabpanel" aria-labelledby="work-data-tab">
                                                <!--<form>-->
													<div class="mb-4">
                                                        <label class="mb-3"> <?php echo $this->lang->line('companyname'); ?> </label>
														<?php
															$data = array(
																'type' => 'text',
																'name' => 'company',
																'placeholder' => $this->lang->line('companyname'),
																'class' => 'form-control',
																//'max' => 255,
																//'required' => 'required',
																'value' => set_value('company')
															);
															echo form_input($data);
														?>
                                                    </div>
													<div><?php echo form_error('company'); ?></div>
                                                    <div class="mb-4" id="commercial_div">
                                                        <label class="mb-3"> <?php echo $this->lang->line('commercialrecord'); ?> </label>
														<p class="register_file_content"><?php echo $this->lang->line('commercialrecordcontent'); ?></p>
                                                        <!--<div id="commercial" action="/upload-target" class="dropzone">
														</div>-->
														<?php
															$data = array(
																'type' => 'file',
																'name' => 'commercial',
																'class' => 'form-control col-md-7 col-xs-12',
																//'required' => 'required'
															);
															echo form_upload($data);
														?>
                                                    </div>
													<div><?php echo form_error('commercial'); ?></div>
                                                    <div class="mb-4">
                                                        <label class="mb-3"> <?php echo $this->lang->line('identity'); ?> </label>
														<p class="register_file_content"><?php echo $this->lang->line('identitycontent'); ?></p>
                                                        <!--<div id="identity" action="/upload-target" class="dropzone">
                                                        </div>-->
														<?php
															$data = array(
																'type' => 'file',
																'name' => 'identity',
																'class' => 'form-control col-md-7 col-xs-12',
																'required' => 'required'
															);
															echo form_upload($data);
														?>
                                                    </div>
													<div><?php echo form_error('identity'); ?></div>
                                                    <div class="mb-5" id="authorization_div">
                                                        <label class="mb-3"> <?php echo $this->lang->line('authorization'); ?> </label>
														<p class="register_file_content"><?php echo $this->lang->line('authorizationcontent'); ?></p>
                                                        <!--<div id="auth" action="/upload-target" class="dropzone">
                                                        </div>-->
														<?php
															$data = array(
																'type' => 'file',
																'name' => 'authorization',
																'class' => 'form-control col-md-7 col-xs-12',
																//'required' => 'required'
															);
															echo form_upload($data);
														?>

                                                    </div>
													<div><?php echo form_error('authorization'); ?></div>

													<?php
														$data = array(
															'class' => 'btn btn-form w-100',
															'type' => 'submit',
															//'disabled' => 'disabled',
															'content' => $this->lang->line('register'),
														);
														echo form_button($data);
													?>
                                                <?php echo form_close(); ?>
                                            <!--</div>-->
                                        </div>

                                        <!--<div class="cs-hr text-center">
                                            <h5 class="c-grey"> OR </h5>
                                        </div>
                                        <div class="mb-4">
                                            <button class="btn form-control btn-google c-grey">
                                                <img src="<?php //echo base_url('/assets/website/images/google.svg'); ?>">
                                                Sign up with Google
                                            </button>
                                        </div>-->
                                        <!--<div>
                                            <span class="c-grey"><?php //echo $this->lang->line('alreadyhaveanaccount'); ?> <a href="<?php //echo base_url($lang.'/login'); ?>" class="link-color"><?php //echo $this->lang->line('login'); ?></a></span>
                                        </div>-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
			
<script>
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
					'<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val()
				},
				success: function (response) {
					var response_data = JSON.parse(response);
					document.getElementById('cities').innerHTML = response_data.data;
					$('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val(response_data.token);
				}
			});
		});
		
		$("input[type='radio'][name='nationality']").change(function(){
			if($("input[type='radio'][name='nationality']:checked").val() == 0)
			{
				$("input[type='file'][name='commercial']").prop('required',true);
				$("input[type='file'][name='authorization']").prop('required',true);
				$("#commercial_div").show();
				$("#authorization_div").show();
			}
			else
			{
				$("input[type='file'][name='commercial']").prop('required',false);
				$("input[type='file'][name='authorization']").prop('required',false);
				$("#commercial_div").hide();
				$("#authorization_div").hide();
			}
		});
	});
</script>