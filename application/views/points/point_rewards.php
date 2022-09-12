<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php echo get_phrase('point_rewards'); ?></h4>
            <p class="card-description">
              <a class="btn btn-primary" href="<?php echo base_url('Point/addPointReward') ?>"><?php echo get_phrase('add_point_reward'); ?></a>
            </p>
			<div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th>
                  <?php echo get_phrase('s._no.'); ?>
                  </th>
                  <th>
                  <?php echo get_phrase('country'); ?>
                  </th>
                  <th>
                  <?php echo get_phrase('point_reward_type'); ?>
                  </th>
                  <th>
                  <?php echo get_phrase('points_count'); ?>
                  </th>
                   <th>
                   <?php echo get_phrase('rewarded_balance'); ?>
                  </th>
                  <th><?php echo get_phrase('status'); ?></th>
                  <th><?php echo get_phrase('manage'); ?></th>
                </tr>
              </thead>
              <tbody>
              <?php $i=0; foreach ($point_rewards as $point_reward) {
                $i++; ?>
                <tr>
                  <td class="py-1">
                    <?php echo $i; ?>
                  </td>
                  <td>
                    <?php echo $point_reward->country; ?>
                  </td>
                  <td><?php echo $point_reward->pointRewardType; ?></td>
                  <td><?php echo $point_reward->points_count; ?> </td>
				  <td><?php echo $point_reward->rewarded_balance; ?> </td>
                  <td>
                   <?php if($point_reward->status==1)
                   {
                     ?>
                   <label class="badge badge-teal"><?php echo get_phrase('active'); ?></label>
                   <?php
                    }
                    elseif($point_reward->status==0) {
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
                        <a class="dropdown-item" href="<?php echo base_url('Point/changeStatus/pointRewards/point_rewards/'.$point_reward->id.'/1');?>"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('active'); ?></a>
                        <a class="dropdown-item" href="<?php echo base_url('Point/changeStatus/pointRewards/point_rewards/'.$point_reward->id.'/0');?>"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('deactivate'); ?></a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?php echo base_url('Point/editPointReward/').$point_reward->id ?>"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit'); ?></a>
						<a class="dropdown-item" href="<?php echo base_url('Point/deletePointReward/').$point_reward->id ?>" onclick="return confirm('Are you sure you want to delete this item?')"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('delete'); ?></a>
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