<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php echo get_phrase('transactions'); ?></h4>
			<div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s._no.'); ?></th>
                  <th><?php echo get_phrase('name'); ?></th>
				  <th><?php echo get_phrase('amount'); ?></th>
				  <th><?php echo get_phrase('time'); ?></th>
                </tr>
              </thead>
              <tbody>
              <?php $i=0; foreach ($transactions as $transaction) {
                $i++; ?>
                <tr>
                  <td class="py-1"><?php echo $i; ?></td>
                  <td><?php echo $transaction->name; ?></td>
				  <td><?php echo $transaction->amount; ?></td>
				  <td><?php echo $transaction->created_at; ?></td>
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