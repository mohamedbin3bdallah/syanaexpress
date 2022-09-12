<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">

  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

  <div class="row">
  
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <p><a class="btn btn-primary mr-2" href="<?php echo base_url('Admin/addArtist') ?>"><?php echo get_phrase('add_artist'); ?></a></p>
         <div class="row">
          <h4 class="col-md-6 card-title"><?php echo get_phrase('all_artists'); ?></h4>
          <div class="col-md-6"><a href="https://expmaint.com/app/Admin/exportCSV?role=1" style="max-width:200px;float:right;" class="btn btn-primary btn-sm"><?php echo get_phrase('export_all_artis'); ?></a></div>
        </div>  
          
          <div class="table-responsive">
          <table id="example" class="table table-bordered">
            <thead>
              <tr>
              <th><?php echo get_phrase('s_no'); ?></th>
                <th> <?php echo get_phrase('name'); ?></th>
                <th> <?php echo get_phrase('created'); ?></th>
                <th> <?php echo get_phrase('category'); ?></th>
                <th><?php echo get_phrase('email'); ?></th>
                <th><?php echo get_phrase('referral_code'); ?></th>
                <th><?php echo get_phrase('used_referral_code'); ?></th>
                <th> <?php echo get_phrase('wallet_amount'); ?></th>
				<th> <?php echo get_phrase('download_files'); ?></th>
                <th> <?php echo get_phrase('profile_complete'); ?></th>
                <th> <?php echo get_phrase('featured'); ?></th>
                <th><?php echo get_phrase('status'); ?></th>
                <th><?php echo get_phrase('profile_approval'); ?></th>
                <th><?php echo get_phrase('action'); ?></th>
              </tr>
            </thead>
            <tbody>
            <?php $i=0; foreach ($artist as $artist) {
              $i++; ?>
            <tr>
                <td class="py-1">
                  <?php echo $i; ?>
                </td>
                <td>
                  <?php echo $artist->name; ?>
                </td>
                <td><?php echo date('d/m/Y h:i:s A', $artist->created_at); ?></td>
                <td>
                  <?php echo $artist->categoryname; ?>
                </td>
                <td>
               <p><strong><?php echo get_phrase('email'); ?>:</strong> <?php echo $artist->email_id; ?> </p>
					<p><strong> <?php echo get_phrase('mobile'); ?>:</strong> <?php echo $artist->mobile; ?> </p>
                </td>
                <td><?php echo $artist->referral_code; ?></td>
                <td><?php echo $artist->user_referral_code; ?></td>
                <td><?php echo $artist->amount; ?></td>
				<td>
				<?php
					if($artist->is_artist) echo '<a href="'.base_url('en/register/'.$artist->id).'" target="_blank">'.get_phrase('add_data').'</a><br><br>';
					foreach($artist->attachs as $attach)
					{
						echo '<a href="'.base_url($attach->attachment).'" target="_blank">'.get_phrase($attach->name).'</a><br>';
					}
				?>
				</td>
                <td>
                  <?php if($artist->is_artist==1) { ?>
                  <label class="badge badge-teal">Yes</label>
                  <?php } elseif($artist->is_artist==0) { ?>
                  <label class="badge badge-danger"><?php echo get_phrase('no'); ?></label>
                  <?php } ?>
                </td>
                <td>
                  <?php if($artist->featured==1) { ?>
                  <label class="badge badge-teal">Yes</label>
                  <?php } elseif($artist->featured==0) { ?>
                  <label class="badge badge-danger"><?php echo get_phrase('no'); ?></label>
                  <?php } ?>
                </td>
                 <td>
                  <?php  if( $artist->status == 1){ ?><button class="btn active_user btn-success btn-sm"><?php echo get_phrase('active'); ?></button><?php }else {  ?><button class="active_user btn-danger btn btn-sm"><?php echo get_phrase('deactive'); ?></button> <?php }?><input  type="text"  value="<?php echo $artist->user_id;?>" hidden>
                </td>
                <td>
                   <?php if($artist->approval_status==1) { ?>
                   <label class="badge badge-teal"><?php echo get_phrase('approved'); ?></label>
                   <?php } elseif($artist->approval_status==0) { ?>
                   <label class="badge badge-danger"><?php echo get_phrase('pending'); ?></label>
                   <?php } ?>
                </td>
                <td>
                 <div class="btn-group dropdown">
                  <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo get_phrase('manage'); ?>
                  </button>
                    <div class="dropdown-menu">
                      <?php if($artist->is_artist==1){ ?> 
                      <a class="dropdown-item" href="<?php echo base_url('/Admin/artistDetails');?>?id=<?php echo $artist->user_id; ?>&role=1&artist_name=<?php echo $artist->name; ?>"><?php echo get_phrase('view_more'); ?></a>
                      <?php if($artist->featured==0){ ?>
                      <div class="dropdown-divider"></div>
                         <a class="dropdown-item" onclick="return confirm('Are you sure want to make Featured this Artist?')" href="<?php echo base_url('/Admin/change_status_featured');?>?id=<?php echo $artist->user_id; ?>&status=1&request=2"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('make_featured'); ?></a>
                          <?php } ?>
                          <?php if($artist->featured==1){ ?>
                        <div class="dropdown-divider"></div>
                         <a class="dropdown-item" onclick="return confirm('Are you sure want to remove from Featured this artist?')" href="<?php echo base_url('/Admin/change_status_featured');?>?id=<?php echo $artist->user_id; ?>&status=0&request=1"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('remove_featured'); ?></a>
                          <?php } ?>
                         <div class="dropdown-divider"></div>
                      <?php } ?>
                      <a class="dropdown-item" onclick="return confirm('Are you sure? Want to warn this episode user.')" href="<?php echo base_url('Admin/warningUser/')?>?user_id=<?php echo $artist->user_id; ?>"><?php echo get_phrase('warning'); ?></a>
                      <?php if($artist->approval_status==0){ ?>
                      <div class="dropdown-divider"></div>
                         <a class="dropdown-item" onclick="return confirm('Are you sure want to approve?')" href="<?php echo base_url('/Admin/artist_approve');?>?id=<?php echo $artist->user_id; ?>"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('profile_approve'); ?></a>
                      <?php } ?>
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