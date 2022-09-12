   <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                       <img class="menu-icon" src="<?php echo base_url('/assets/images/menu_icons/money.png'); ?>" alt="menu icon" height="30">
                    </div>
                    <div class="float-right">

                      <p class="card-text text-right"><?php echo get_phrase('total_amount'); ?></p>
                      <div class="fluid-container">
                        <h6 class="card-title font-weight-bold text-right mb-0"> <?php echo $currency_type; if(isset($t_amount->total_amount)) {  echo round($t_amount->total_amount, 2); } else { echo "0"; } ?></h6>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3">
                    <?php echo get_phrase('artist_amount'); ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                       <img class="menu-icon" src="<?php echo base_url('/assets/images/menu_icons/money.png'); ?>" alt="menu icon" height="30">
                    </div>
                    <div class="float-right">
                      <p class="card-text text-right"><?php echo get_phrase('total_commission'); ?></p>
                      <div class="fluid-container">
                        <h6 class="card-title font-weight-bold text-right mb-0"><?php echo $currency_type; if(isset($c_amount->category_amount)) {  echo round($c_amount->category_amount, 2);  } else { echo "0"; } ?></h6>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3">
                    <?php echo get_phrase('commission_amount'); ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                       <img class="menu-icon" src="<?php echo base_url('/assets/images/menu_icons/money.png'); ?>" alt="menu icon" height="30">
                    </div>
                    <div class="float-right">
                      <p class="card-text text-right"><?php echo get_phrase('paid_amount'); ?></p>
                      <div class="fluid-container">
                        <h6 class="card-title font-weight-bold text-right mb-0"><?php echo $currency_type; echo round($p_amount->total_amount, 2) ?></h6>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3">
                    <?php echo get_phrase('total_paid_amount'); ?>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <div class="clearfix">
                    <div class="float-left">
                       <img class="menu-icon" src="<?php echo base_url('/assets/images/menu_icons/money.png'); ?>" alt="menu icon" height="30">
                    </div>
                    <div class="float-right">
                      <p class="card-text text-right"><?php echo get_phrase('unpaid_amount'); ?></p>
                      <div class="fluid-container">
                        <h6 class="card-title font-weight-bold text-right mb-0"><?php echo $currency_type; echo round($u_amount->total_amount, 2) ?></h6>
                      </div>
                    </div>
                  </div>
                  <p class="text-muted mt-3">
                    <?php echo get_phrase('total_unpaid_amount'); ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="row">
          <h4 class="col-md-6 card-title"><?php echo get_phrase('all_invoices'); ?></h4>
          <div class="col-md-6"><a href="https://expmaint.com/app/Admin/exportInvoiceCSV" style="max-width:200px;float:right;" class="btn btn-primary btn-sm"><?php echo get_phrase('export_all_invoices'); ?></a></div>
        </div>  
          <div class="table-responsive">
            <table id="example" class="table table-striped">
              <thead>
                <tr>
                  <th><?php echo get_phrase('s_no'); ?></th>
                  <th><?php echo get_phrase('invoice_id'); ?></th>
                  <th><?php echo get_phrase('user_name'); ?></th>
                  <th><?php echo get_phrase('artist Name'); ?></th>
                  <th><?php echo get_phrase('coupon_code'); ?></th>
                  <th><?php echo get_phrase('working_min'); ?></th>
                  <th><?php echo get_phrase('Commission_amount'); ?></th>
                  <th><?php echo get_phrase('artist_amount'); ?></th>
                  <th><?php echo get_phrase('total_amount'); ?></th>
                  <th><?php echo get_phrase('payment _type'); ?></th>
                  <th><?php echo get_phrase('description'); ?></th>
                  <th><?php echo get_phrase('status'); ?></th>
                  <th><?php echo get_phrase('action'); ?></th>
                </tr>
              </thead>
              <tbody>
               <?php $i=0; foreach ($getInvoices as $get_invoice) {
                $i++;
                ?>
                <tr>
                  <td ><?php echo $i; ?></td>
                  <td ><?php echo $get_invoice->invoice_id; ?></td>
                  <td ><?php echo $get_invoice->userName; ?></td>
                  <td ><?php echo $get_invoice->ArtistName; ?></td>
                  <td ><?php echo $get_invoice->coupon_code; ?></td>
                  <td><?php echo $get_invoice->working_min; ?></td>
                  <td><?php echo $currency_type; echo round($get_invoice->category_amount, 2); ?></td>
                  <td><?php echo $currency_type; echo $get_invoice->artist_amount; ?></td>
                  <td><?php echo $currency_type; echo $get_invoice->total_amount; ?></td>   
                   <td>
                   <?php if($get_invoice->payment_type==0)
                   {
                     ?>
                   <label class="badge badge-primary"><?php echo get_phrase('online'); ?></label>
                   <?php
                    }
                    elseif($get_invoice->payment_type==1) {
                       ?>
                   <label class="badge badge-primary"><?php echo get_phrase('Cash'); ?></label>
                   <?php } ?>
                  </td>   
                  <td>
                      <?php echo $get_invoice->description; ?>
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
                   <?php } ?>
                  </td>  
                  <td>
                 <div class="btn-group dropdown">
                  <button type="button" class="btn btn-teal dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <?php echo get_phrase('manage'); ?>
                  </button>
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_invoice');?>?id=<?php echo $get_invoice->id; ?>&status=1"><i class="fa fa-reply fa-fw"></i><?php echo get_phrase('paid'); ?></a>
                    <a class="dropdown-item" href="<?php echo base_url('/Admin/change_status_invoice');?>?id=<?php echo $get_invoice->id; ?>&status=0"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('cancel'); ?></a>
                    <a class="changeprice dropdown-item" href="#" data-invoiceids="<?php echo $get_invoice->id; ?>" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-history fa-fw"></i><?php echo get_phrase('edit_invoice'); ?></a>
                  </div>
                </div>
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




<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"> <?php echo get_phrase('change_invoice_price'); ?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <?php echo form_open(site_url('Admin/change_price') , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top' , 'enctype' => 'multipart/form-data'));?>
         <div class="form-group">
						<label><?php echo get_phrase('price');?></label>
						<input type="text" class="form-control" name="price" value="" required/>
					</div>

					
          <input type="hidden" value="" name="invoice_id" class="invoiceids">
          <button type="submit" class="btn btn-primary">
            <?php echo get_phrase('Change_price'); ?>
          </button>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo get_phrase('close'); ?></button>
      </div>
    </div>
  </div>
</div>