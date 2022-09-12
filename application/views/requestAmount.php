 <!-- partial -->
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
       <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php echo get_phrase('all_requests'); ?></h4>
           <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s._no'); ?></th>
                  <th><?php echo get_phrase('arist_name'); ?></th>
                  <th><?php echo get_phrase('email'); ?></th>
                  <th><?php echo get_phrase('amount'); ?></th>
                  <th><?php echo get_phrase('request_time'); ?></th>
                  <th><?php echo get_phrase('status'); ?></th>
                  <th><?php echo get_phrase('action'); ?></th>
                </tr>
              </thead>
              <tbody>
              <?php $i=0;
               foreach ($wallet_requests as $wallet_requests) {
                $i++;?>
                <tr>
                  <td class="py-1"><?php echo $i; ?></td>
                  <td><?php echo $wallet_requests->name; ?></td>
                  <td><!-- <?php echo $wallet_requests->email_id; ?> -->
                    <?php echo "Email"; ?>
                  </td>
                  <td><?php echo $wallet_requests->walletAmount; ?></td>
                  <td><?php echo date('M d, Y h:i A', $wallet_requests->created_at); ?></td>
                  <td><?php if($wallet_requests->status==1)  { ?>
                   <label class="badge badge-teal"><?php echo get_phrase('paid'); ?></label>
                   <?php } elseif($wallet_requests->status==0) { ?>
                   <label class="badge badge-danger"><?php echo get_phrase('pending'); ?></label>
                   <?php } ?>
                  </td>
                  <td>
                   <?php if($wallet_requests->status==0) { ?>
                   <a class="btn btn-primary" href="<?php echo base_url().'Admin/payusuccess?getUserId='.$wallet_requests->artist_id.'&getpost='.$wallet_requests->walletAmount.'&request_id='.$wallet_requests->id; ?>"><?php echo get_phrase('pay_now'); ?></a>
                   
                   <?php } else { ?> 
                   <label class="badge badge-teal"><?php echo get_phrase('paid'); ?></label>
                   <?php } ?>
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