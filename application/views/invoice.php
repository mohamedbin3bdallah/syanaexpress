<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">
  <div class="row">
  
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('invoice_of '); ?><?php echo $artist_name; ?></h4>
 
          <table id="example" class="table table-striped">
            <thead>
              <tr>
                <th>
                <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                <?php echo get_phrase('user_name'); ?>
                </th>
                <th>
                  <?php echo get_phrase('booking_date'); ?>
                </th>
                <th>
                  <?php echo get_phrase('working_min'); ?>
                </th>
                <th>
                  <?php echo get_phrase('total_amount'); ?>
                </th>
                <th>
                <?php echo get_phrase('status'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
             <?php $i=0; foreach ($get_invoice as $get_invoice) {
              $i++;
              ?>
              <tr>
               <td class="py-1">
                  <?php echo $i; ?>
                </td>
                <td class="py-1">
                  <?php echo $get_invoice->userName; ?>
                </td>
                <td>
                  <?php echo date('M d, Y H:i', $get_invoice->created_at); ?>
                </td>
                <td>
                  <?php echo $get_invoice->working_min; ?>
                </td>
                <td>
                  <?php echo $get_invoice->total_amount; ?>&nbsp;<?php echo $get_invoice->currency_type; ?>
                </td>
               
                <td>
                 <?php if($get_invoice->flag==0)
                 {
                   ?>
                 <label class="badge badge-warning"><?php echo get_phrase('pending'); ?></label>
                 <?php
                  }
                  elseif($get_invoice->flag==1) {
                     ?>
                 <label class="badge badge-primary"><?php echo get_phrase('paid'); ?></label>
                 <?php
                   } 
                   ?>
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
<!-- content-wrapper ends -->