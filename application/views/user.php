<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">
  
  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
  
  <div class="row">
  
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
          
        <div class="card-body">
            
        <div class="row">
          <h4 class="col-md-6 card-title"><?php echo get_phrase('all_users'); ?></h4>
          <div class="col-md-6"><a href="https://expmaint.com/app/Admin/exportCSV?role=2" style="max-width:200px;float:right;" class="btn btn-primary btn-sm"><?php echo get_phrase('export_all_usres'); ?></a></div>
          
        </div>  
        
          <div class="table-responsive">
          <table id="example" class="table table-striped">
            <thead>
              <tr>
                <th><?php echo get_phrase('s._no.'); ?>.</th>
                <th><?php echo get_phrase('image'); ?></th>
                <th><?php echo get_phrase('name'); ?></th>
                <th><?php echo get_phrase('created'); ?></th>
                <th><?php echo get_phrase('email'); ?></th>
                <th><?php echo get_phrase('referral_code'); ?></th>
                <th><?php echo get_phrase('used_referral_code'); ?></th>
                <th><?php echo get_phrase('wallet_amount'); ?></th>
                <th>
                <?php echo get_phrase('status'); ?>
                </th>
                <th>
                <?php echo get_phrase('warning'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
            <?php $i=0; foreach ($user as $user) {
             $i++; ?>
              <tr>
                <td><?php echo $i; ?></td>
                 <td class="py-1">
                <?php if($user->image)
                { ?>
                  <img src="<?php echo  base_url().$user->image; ?>" width="42" height="42" alt="image"/>
                  <?php }
                else { ?>
                  <img src="<?php echo  base_url('/assets/images/faces-clipart/pic-1.png' ); ?>" alt="image"/>
                  <?php } ?>
                </td> 
                <td><?php echo $user->name; ?></td>
                <td><?php echo date('d/m/Y h:i:s A', $user->created_at); ?></td>
                <td>
					<p><strong><?php echo get_phrase('email'); ?> :</strong> <?php echo $user->email_id; ?> </p>
					<p><strong><?php echo get_phrase('mobile'); ?>: </strong> <?php echo $user->mobile; ?> </p>
                  </td>

                <!-- <td><?php echo date('M d, Y h:i A', $user->created_at); ?></td> -->
                <td><?php echo $user->referral_code; ?></td>
                <td><?php echo $user->user_referral_code; ?></td>
                <td><?php echo $user->amount; ?></td>
                <td>
                  <?php  if($user->status == 1){ ?><button class="btn active_user btn-success btn-sm"><?php echo get_phrase('active'); ?></button><?php }else {  ?><button class="active_user btn-danger btn btn-sm"><?php echo get_phrase('deactive'); ?></button> <?php }?><input  type="text"  value="<?php echo $user->user_id;?>" hidden>
                </td>
                <td>
                  <a class="btn-danger btn btn-sm" onclick="return confirm('Are you sure? Want to warn this user.')" href="<?php echo base_url('Admin/warningUser/')?>?user_id=<?php echo $user->user_id; ?>"><?php echo get_phrase('warning'); ?></a>
                </td>
              </tr>
            <?php
              }
            ?>      
            </tbody>
          </table>
          </div>
        </div>
      </div>
    </div>           
  </div>
</div>
<!-- content-wrapper ends -->
  