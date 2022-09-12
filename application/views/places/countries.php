<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php echo get_phrase('countries'); ?></h4>
            <p class="card-description">
              <a class="btn btn-primary" href="<?php echo base_url('Place/addCountry') ?>"><?php echo get_phrase('add_country'); ?></a>
            </p>
			<div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s._no.'); ?></th>
				  <th><?php echo get_phrase('icon'); ?></th>
                  <th><?php echo get_phrase('english_name'); ?></th>
				  <th><?php echo get_phrase('arabic_name'); ?></th>
				  <th><?php echo get_phrase('iso2'); ?></th>
				  <th><?php echo get_phrase('iso3'); ?></th>
				  <th><?php echo get_phrase('num_code'); ?></th>
				  <th><?php echo get_phrase('phone_code'); ?></th>
                  <th><?php echo get_phrase('status'); ?></th>
                  <th><?php echo get_phrase('manage'); ?></th>
                </tr>
              </thead>
              <tbody>
              <?php $i=0; foreach ($countries as $country) {
                $i++; ?>
                <tr>
                  <td class="py-1"><?php echo $i; ?></td>
				  <td><img src="<?php echo base_url().$country->icon; ?>"></td>
                  <td><?php echo $country->name; ?></td>
				  <td><?php echo $country->name_ar; ?></td>
				  <td><?php echo $country->iso; ?></td>
				  <td><?php echo $country->iso3; ?></td>
				  <td><?php echo $country->numcode; ?></td>
				  <td><?php echo $country->phonecode; ?></td>
                  <td>
                   <?php if($country->active==1)
                   {
                     ?>
                   <label class="badge badge-teal"><?php echo get_phrase('active'); ?></label>
                   <?php
                    }
                    elseif($country->active==0) {
                       ?>
                   <label class="badge badge-danger"><?php echo get_phrase('deactive'); ?></label>
                   <?php
                     } ?>
                  </td>
                   <td>
                      <div class="btn-group dropdown">
                      <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <?php echo get_phrase('manage'); ?>
                      </button>
                      <div class="dropdown-menu">
                        <a class="dropdown-item" href="<?php echo base_url('Place/changeStatus/countries/countries/'.$country->id.'/1');?>"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('active'); ?></a>
                        <a class="dropdown-item" href="<?php echo base_url('Place/changeStatus/countries/countries/'.$country->id.'/0');?>"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('deactivate'); ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo base_url('Place/editCountry/').$country->id ?>"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit'); ?></a>
						<!--<a class="dropdown-item" href="<?php //echo base_url('Place/deleteCountry/').$country->id ?>" onclick="return confirm('Are you sure you want to delete this item?')"><i class="fa fa-history fa-fw"></i><?php //echo get_phrase('delete'); ?></a>-->
                      </div>

                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
			</div>
          </div>
        </div>
      </div>
    </div>
  </div>  
<!-- content-wrapper ends -->
<!-- partial:../../partials/_footer.html -->