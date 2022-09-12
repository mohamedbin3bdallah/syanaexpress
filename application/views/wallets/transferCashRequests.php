<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php echo get_phrase('transfer_cash_requests'); ?></h4>
			<div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s._no.'); ?></th>
                  <th><?php echo get_phrase('artist'); ?></th>
				  <th><?php echo get_phrase('email'); ?></th>
				  <th><?php echo get_phrase('mobile'); ?></th>
				  <th><?php echo get_phrase('amount'); ?></th>
				  <th><?php echo get_phrase('wallet'); ?></th>
				  <th><?php echo get_phrase('comment'); ?></th>
				  <th><?php echo get_phrase('time'); ?></th>
				  <th><?php echo get_phrase('status'); ?></th>
                </tr>
              </thead>
              <tbody>
              <?php $i=0; foreach ($transfer_cash_requests as $transfer_cash_request) {
                $i++; ?>
                <tr>
                  <td class="py-1"><?php echo $i; ?></td>
                  <td><?php echo $transfer_cash_request->name; ?></td>
				  <td><?php echo $transfer_cash_request->email_id; ?></td>
				  <td><?php echo $transfer_cash_request->mobile; ?></td>
				  <td><?php echo $transfer_cash_request->amount; ?></td>
				  <td><?php echo $transfer_cash_request->wallet; ?></td>
				  <td><?php echo $transfer_cash_request->comment; ?></td>
				  <td><?php echo $transfer_cash_request->created_at; ?></td>
				  <td><?php if($transfer_cash_request->status==1)  { ?>
                   <label class="badge badge-teal"><?php echo get_phrase('accepted'); ?></label>
                   <?php } elseif($transfer_cash_request->status==2) { ?>
                   <label class="badge badge-danger"><?php echo get_phrase('rejected'); ?></label>
				   <?php } elseif($transfer_cash_request->status==0) { ?>
                   <a class="btn btn-defualt btn-sm" href="<?php echo base_url('Wallet/editTransferCashRequest/').$transfer_cash_request->id ?>"><?php echo get_phrase('pend'); ?></a>
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
  </div>  
<!-- content-wrapper ends -->
<!-- partial:../../partials/_footer.html -->