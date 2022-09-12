<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php echo get_phrase('points'); ?></h4>
			<div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s._no.'); ?></th>
                  <th><?php echo get_phrase('artist'); ?></th>
				  <th><?php echo get_phrase('points'); ?></th>
				  <th><?php echo get_phrase('transactions'); ?></th>
                </tr>
              </thead>
              <tbody>
              <?php $i=0; foreach ($points as $point) {
                $i++; ?>
                <tr>
                  <td class="py-1"><?php echo $i; ?></td>
                  <td><?php echo $point->name; ?></td>
				  <td><?php echo $point->points; ?></td>
                  <td><a class="btn btn-success btn-sm" href="<?php echo base_url('Point/transactions/'.$point->artist_id);?>"><?php echo get_phrase('show'); ?></a></td>
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