<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php echo get_phrase('wallets'); ?></h4>
			<div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s._no.'); ?></th>
                  <th><?php echo get_phrase('artist'); ?></th>
				  <th><?php echo get_phrase('wallet'); ?></th>
				  <th><?php echo get_phrase('transactions'); ?></th>
                </tr>
              </thead>
              <tbody>
              <?php $i=0; foreach ($wallets as $wallet) {
                $i++; ?>
                <tr>
                  <td class="py-1"><?php echo $i; ?></td>
                  <td><?php echo $wallet->name; ?></td>
				  <td><?php echo $wallet->amount; ?></td>
                  <td><a class="btn btn-success btn-sm" href="<?php echo base_url('Wallet/transactions/'.$wallet->artist_id);?>"><?php echo get_phrase('show'); ?></a></td>
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