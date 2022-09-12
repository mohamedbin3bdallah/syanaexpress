            <!--==============================Start Discussions Section==============================-->
            <section class="login-form pt-header">
                <div class="content__section">
                    <div class="container-xxl">
                        <div class="row">
                            <div class="col-md-8 col-lg-6">
                                <div class="box">
                                    <div class="wrapper__box w-100">
                                        <div class="title__box">
                                            <h5 class="main-color w-meduim">
                                                <?php echo $this->lang->line('contactus'); ?>
                                            </h5>
                                            <p class="text-start">
                                                <?php echo $this->lang->line('contactuscontent'); ?>
                                            </p>
                                            <div class="social-links">
                                                <a href="https://www.facebook.com/Express-maintenance-1182193388567602" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32.192" height="31.997" viewBox="0 0 32.192 31.997">
                                                        <path id="Icon_simple-facebook" data-name="Icon simple-facebook" d="M32.192,16.1A16.1,16.1,0,1,0,13.581,32V20.749H9.494V16.1h4.087V12.55c0-4.034,2.4-6.262,6.08-6.262a24.754,24.754,0,0,1,3.6.314v3.961h-2.03a2.326,2.326,0,0,0-2.623,2.514V16.1h4.464l-.714,4.653H18.611V32A16.1,16.1,0,0,0,32.192,16.1Z" fill="#315891"/>
                                                    </svg>

                                                </a>
                                                <a href="https://www.linkedin.com/company/expressmaintenance/" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                                        <g id="Group_76" data-name="Group 76" transform="translate(0 -0.179)">
                                                            <circle id="Ellipse_118" data-name="Ellipse 118" cx="16" cy="16" r="16" transform="translate(0 0.179)" fill="#1274a5"/>
                                                            <path id="Icon_awesome-linkedin-in" data-name="Icon awesome-linkedin-in" d="M4.016,17.943H.3V5.964h3.72ZM2.154,4.33A2.165,2.165,0,1,1,4.309,2.155,2.173,2.173,0,0,1,2.154,4.33ZM17.939,17.943H14.227V12.112c0-1.39-.028-3.172-1.934-3.172-1.934,0-2.23,1.51-2.23,3.072v5.932H6.346V5.964H9.914V7.6h.052a3.909,3.909,0,0,1,3.52-1.934c3.765,0,4.457,2.479,4.457,5.7v6.58Z" transform="translate(7.344 5.393)" fill="#fff"/>
                                                        </g>
                                                    </svg>



                                                </a>
                                                <a href="https://twitter.com/expmaint" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
                                                        <g id="Group_77" data-name="Group 77" transform="translate(0 -0.179)">
                                                            <circle id="Ellipse_119" data-name="Ellipse 119" cx="16" cy="16" r="16" transform="translate(0 0.179)" fill="#f39200"/>
                                                            <path id="Icon_awesome-twitter" data-name="Icon awesome-twitter" d="M19.175,7.707c.014.19.014.38.014.57A12.377,12.377,0,0,1,6.726,20.739,12.378,12.378,0,0,1,0,18.773a9.061,9.061,0,0,0,1.058.054A8.772,8.772,0,0,0,6.5,16.955a4.388,4.388,0,0,1-4.1-3.038,5.524,5.524,0,0,0,.827.068,4.633,4.633,0,0,0,1.153-.149,4.381,4.381,0,0,1-3.512-4.3V9.483a4.411,4.411,0,0,0,1.98.556A4.387,4.387,0,0,1,1.492,4.181a12.451,12.451,0,0,0,9.032,4.584,4.945,4.945,0,0,1-.108-1,4.384,4.384,0,0,1,7.581-3,8.624,8.624,0,0,0,2.78-1.058A4.368,4.368,0,0,1,18.85,6.12a8.781,8.781,0,0,0,2.522-.678,9.416,9.416,0,0,1-2.2,2.265Z" transform="translate(5.648 4.055)" fill="#fff"/>
                                                        </g>
                                                    </svg>



                                                </a>
												<span style="font-size:20px;">
													<?php echo $this->lang->line('freenumber'); ?> 8002440250
												</span>
                                            </div>
                                        </div>
										<?php
											$attributes = array('method' => 'POST');
											echo form_open($lang.'/contact_post', $attributes);
											//echo validation_errors();
										?>
                                            <div class="mb-4">
                                                <label class="mb-3"> <?php echo $this->lang->line('name'); ?> </label>
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
												<div><?php echo form_error('name'); ?></div>
                                            </div>
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
												<div><?php echo form_error('email'); ?></div>
                                            </div>
                                            <div class="mb-4">
                                                <label class="mb-3"> <?php echo $this->lang->line('message'); ?> </label>
												<?php
													$data = array(
														'name' => 'message',
														'id' => 'message',
														'placeholder' => $this->lang->line('message'),
														'class' => 'form-control',
														'required' => 'required',
														'value' => set_value('message')
													);
													echo form_textarea($data);
												?>
												<div><?php echo form_error('message'); ?></div>
                                            </div>

                                            <?php
												$data = array(
													'class' => 'btn btn-form w-100',
													'type' => 'submit',
													//'disabled' => 'disabled',
													'content' => $this->lang->line('send')
												);
												echo form_button($data);
											?>
                                        <?php echo form_close(); ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--==============================End Discussions Section==============================-->