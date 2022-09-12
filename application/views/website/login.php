            <!--==============================Start Discussions Section==============================-->
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
                                                Log in with your data that you entered during Your registration.
                                            </p>
                                        </div>
                                        <?php
											$attributes = array('method' => 'POST');
											echo form_open($lang.'/login_post', $attributes);
											echo validation_errors();
										?>
                                            <div class="mb-4">
                                                <label class="mb-3"> <?php echo $this->lang->line('emailaddress'); ?> </label>
												<?php
													$data = array(
														'type' => 'email',
														'name' => 'email',
														'placeholder' => $this->lang->line('emailaddress'),
														'class' => 'form-control',
														//'max' => 255,
														'required' => 'required',
														'value' => set_value('email')
													);
													echo form_input($data);
												?>
                                            </div>
                                            <div class="mb-5">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <label> <?php echo $this->lang->line('password'); ?> </label>
                                                    <a href="javascript:;" class="link-color"> Forgot Password</a>
                                                </div>
                                                <div class="input-pass">
													<?php
														$data = array(
															'type' => 'password',
															'name' => 'password',
															'id' => 'password-field',
															'placeholder' => $this->lang->line('password'),
															'class' => 'form-control',
															'required' => 'required',
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
                                            <button class="btn btn-form w-100"> <?php echo $this->lang->line('login'); ?> </button>
                                        <?php echo form_close(); ?>
                                        <!--<div class="cs-hr text-center">
                                            <h5 class="c-grey"> OR </h5>
                                        </div>
                                        <div class="mb-4">
                                            <button class="btn form-control btn-google c-grey">
                                                <img src="images/google.svg">
                                                Sign with Google
                                            </button>
                                        </div>-->
                                        <div>
                                            <span class="c-grey"> <?php echo $this->lang->line('donthaveanaccount'); ?> <a href="<?php echo base_url($lang.'/hero#register-now'); ?>" class="link-color"><?php echo $this->lang->line('register'); ?></a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--==============================End Discussions Section==============================-->