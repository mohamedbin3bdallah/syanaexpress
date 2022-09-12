<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

<div class="main-panel">
<div class="content-wrapper">
  <div class="container">
  <ul id="tabs" class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a id="tab-A" href="#pane-A" class="nav-link active" data-toggle="tab" role="tab"><?php echo get_phrase('profile'); ?></a>
    </li>
    <li class="nav-item">
      <a id="tab-B" href="#pane-B" class="nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('appointments'); ?></a>
    </li>
    <li class="nav-item">
      <a id="tab-C" href="#pane-C" class="nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('services'); ?></a>
    </li>
    <li class="nav-item">
      <a id="tab-D" href="#pane-D" class="nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('invoice'); ?></a>
    </li>
    <li class="nav-item">
      <a id="tab-E" href="#pane-E" class="nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('rating'); ?></a>
    </li>
    <li class="nav-item">
      <a id="tab-F" href="#pane-F" class="nav-link" data-toggle="tab" role="tab"><?php echo get_phrase('wallet_amount'); ?></a>
    </li>
  </ul>

  <div id="content" class="tab-content" role="tablist">
    <div id="pane-A" class="card tab-pane fade show active" role="tabpanel" aria-labelledby="tab-A">
      <div class="card-header" role="tab" id="heading-A">
        <h5 class="mb-0">
          <a data-toggle="collapse" href="#collapse-A" data-parent="#content" aria-expanded="true" aria-controls="collapse-A">
            Profile
          </a>
        </h5>
      </div>
      <div id="collapse-A" class="collapse show" role="tabpanel" aria-labelledby="heading-A">
        <div class="card-body">
          <div class="col-12 grid-margin">
                  <div class="fluid-container">
                    <div class="row ticket-card mt-3 pb-2 border-bottom">
                      <div class="col-1">

                      <?php if($user->image) { ?> 
                        <img class="img-sm rounded-circle" src="<?php echo base_url().$user->image; ?>" alt="profile image">
                      <?php } else { ?>
                        <img class="img-sm rounded-circle" src="<?php echo base_url('/assets/images/faces/face1.jpg'); ?>" alt="profile image">
                      <?php } ?>
                        
                      </div>
                      <div class="ticket-details col-12">
                         <div class="d-flex">
                          <p class="text-primary font-weight-bold mr-2 mb-0 no-wrap"><?php echo ucfirst($user->name); ?> :</p>
                          <p class="font-weight-bold mr-1 mb-0"><?php echo get_phrase('description'); ?></p>
                          <p class="font-weight-medium mb-0 ellipsis"><?php echo ucfirst($user->about_us); ?></p>
                        </div> 
                        <div class="row text-muted d-flex">
                            <div class="col-4 d-flex">
                            <p class="mb-0 mr-2 font-weight-bold"><?php echo get_phrase('category'); ?></p>
                            <p class="Last-responded mr-2 mb-0"><?php echo $user->categoryname; ?></p>
                            </div>
                        </div>    
                        <div class="row text-muted d-flex">
                        <div class="col-4 d-flex">
                            <p class="mb-0 mr-2 font-weight-bold"><?php echo get_phrase('gender'); ?></p>
                            <p class="Last-responded mr-2 mb-0"><?php if($user->gender==0) echo 'Female'; if($user->gender==1) echo 'Male'; if($user->gender==2) echo 'Other'; ?></p>
                          </div>
                          <div class="col-4 d-flex">
                            <p class="mb-0 mr-2 font-weight-bold"><?php echo get_phrase('address'); ?></p>
                            <p class="Last-responded mr-2 mb-0"><?php echo ucfirst($user->location); ?></p>
                          </div>
                        </div>

                        <div class="row text-muted d-flex">
                        <div class="col-4 d-flex">
                            <p class="mb-0 mr-2 font-weight-bold"><?php echo get_phrase('price'); ?></p>
                            <p class="Last-responded mr-2 mb-0">$ <?php echo $user->price; ?></p>
                          </div>
                          <div class="col-4 d-flex">
                            <p class="mb-0 mr-2 font-weight-bold"><?php echo get_phrase('total_job_done'); ?></p>
                            <p class="Last-responded mr-2 mb-0"><?php print_r($jobDone); ?></p>
                          </div>
                          <div class="col-4 d-flex">
                            <p class="mb-0 mr-2 font-weight-bold"><?php echo get_phrase('success'); ?></p>
                            <p class="Last-responded mr-2 mb-0"><?php echo round($percentages, 2); ?> %</p>
                          </div>
                        </div>

                        <div class="row text-muted d-flex">
                        <div class="col-4 d-flex">
                            <p class="mb-0 mr-2 font-weight-bold"><?php echo get_phrase('total Request'); ?></p>
                            <p class="Last-responded mr-2 mb-0"><?php print_r($total); ?></p>
                          </div>
                        </div>
                      </div>
                      
                    </div>
                  </div>      
            </div>
        </div>
        <div class="card-body">
        <h4><?php echo get_phrase('gallery'); ?></h4>
          <div class="col-lg-12 grid-margin stretch-card">
          <table id="example" class="table table-striped res_table">
            <thead>
              <tr>
              <th>
              <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                  <?php echo get_phrase('image'); ?>
                </th>
                <th>
                  <?php echo get_phrase('added_date'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
            <?php $i=0; foreach ($get_gallery as $get_gallery) { $i++;
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $i; ?>
                </td>
                <td>
                <a href="<?php echo base_url().$get_gallery->image; ?>" target="_blank"><img height="50" width="50" src="<?php echo base_url().$get_gallery->image; ?>"></a>
                </td>
                <td>
                   <?php echo date('d M, Y', $get_gallery->created_at); ?>
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

    <div id="pane-B" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-B">
      <div class="card-header" role="tab" id="heading-B">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-B" data-parent="#content" aria-expanded="false" aria-controls="collapse-B">
             <?php echo get_phrase('appointments_of'); ?><?php echo $artist_name; ?>
          </a>
        </h5>
      </div>
      <div id="collapse-B" class="collapse show" role="tabpanel" aria-labelledby="heading-B">
        <div class="card-body">
          <div class="col-lg-12 grid-margin stretch-card">
          <table id="example" class="table table-striped res_table">
            <thead>
              <tr>
               <th>
               <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                  <?php echo get_phrase('customer_name'); ?>
                </th>
                <th>
                <?php echo get_phrase('timing'); ?>
                </th>
                <th>
                  <?php echo get_phrase('appointment_date'); ?>
                </th>
                <th>
                <?php echo get_phrase('status'); ?>
                </th>

                
              </tr>
            </thead>
            <tbody>
            <?php $i=0; foreach ($get_appointments as $get_appointments) {
            $i++;  ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $get_appointments->name; ?></td>
                <td>
                  <?php echo $get_appointments->booking_time; ?>
                </td>
                <td>
                   <?php echo $get_appointments->booking_date; ?>
                </td>
                 <td>
                 <?php if($get_appointments->booking_flag==0)
                 {
                   ?>
                 <label class="badge badge-warning"><?php echo get_phrase('pending'); ?></label>
                 <?php
                  }
                  elseif($get_appointments->booking_flag==1) {
                     ?>
                 <label class="badge badge-primary"><?php echo get_phrase('accept'); ?></label>
                 <?php
                   } 
                   elseif($get_appointments->booking_flag==2) {
                     ?>
                 <label class="badge badge-danger"><?php echo get_phrase('decline'); ?></label>
                 <?php
                   } 
                   elseif($get_appointments->booking_flag==3) {
                     ?>
                 <label class="badge badge-info"><?php echo get_phrase('in_process'); ?></label>
                 <?php
                   } 
                   elseif($get_appointments->booking_flag==4) {
                     ?>
                 <label class="badge badge-success"><?php echo get_phrase('completed'); ?></label>
                 <?php
                   } ?>
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

    <div id="pane-C" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-C">
      <div class="card-header" role="tab" id="heading-C">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-C" data-parent="#content" aria-expanded="false" aria-controls="collapse-C">
             <?php echo get_phrase('products_of'); ?><?php echo $artist_name; ?>
          </a>
        </h5>
      </div>
      <div id="collapse-C" class="collapse show" role="tabpanel" aria-labelledby="heading-C">
        <div class="card-body">
         <div class="col-lg-12 grid-margin stretch-card"> 
          <table id="example" class="table table-striped res_table">
            <thead>
              <tr>
                <th><?php echo get_phrase('s_no'); ?></th>
                <th><?php echo get_phrase('service_name'); ?></th>
                <th><?php echo get_phrase('price'); ?></th>
                <th> <?php echo get_phrase('service_image'); ?></th>
              </tr>
            </thead>
            <tbody>
             <?php $i=0; foreach ($get_products as $get_products) {
              $i++; ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td>
                  <?php echo $get_products->product_name; ?>
                </td>
                <td>
                  <?php echo $currency_type; echo $get_products->price; ?>
                </td>
                <td>
                   <img height="50" width="50" src="<?php echo base_url().$get_products->product_image; ?>">
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
    <div id="pane-D" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-D">
      <div class="card-header" role="tab" id="heading-D">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-D" data-parent="#content" aria-expanded="false" aria-controls="collapse-D">
            <?php echo get_phrase('invoice'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-D" class="collapse show" role="tabpanel" aria-labelledby="heading-D">
        <div class="card-body">
           <div class="col-lg-12 grid-margin stretch-card" style="overflow-x: scroll;">
          <table id="example" class="table table-striped res_table">
            <thead>
              <tr>

                <th><?php echo get_phrase('s_no'); ?></th>
                  <th><?php echo get_phrase('invoice_id'); ?></th>
                  <th><?php echo get_phrase('user_name'); ?></th>
                  <th><?php echo get_phrase('coupon_code'); ?></th>
                  <th><?php echo get_phrase('working_min'); ?></th>
                  <th><?php echo get_phrase('Commission_amount'); ?></th>
                  <th><?php echo get_phrase('artist_amount'); ?></th>
                  <th><?php echo get_phrase('total_amount'); ?></th>
                  <th><?php echo get_phrase('payment _type'); ?></th>
                  <th><?php echo get_phrase('description'); ?></th>
                   <th>
                  <?php echo get_phrase('booking_date'); ?>
                </th>
                  <th><?php echo get_phrase('status'); ?></th>
              </tr>
            </thead>
            <tbody>
             <?php $i=0; foreach ($get_invoice as $get_invoice) {
             $i++; ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $get_invoice->invoice_id; ?></td>
                <td><?php echo $get_invoice->userName; ?></td>
                <td><?php echo $get_invoice->coupon_code; ?></td>
                <td>
                  <?php echo $get_invoice->working_min; ?>
                </td>
                <td>
                  <?php echo $get_invoice->commission_amount; ?>
                </td>
                <td>
                  <?php echo $get_invoice->artist_amount; ?>
                </td>
                <td>
                  <?php echo $get_invoice->total_amount; ?>
                </td>
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
                  <?php echo date('M d, Y H:i', $get_invoice->created_at); ?>
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

    <div id="pane-E" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-E">
      <div class="card-header" role="tab" id="heading-E">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-E" data-parent="#content" aria-expanded="false" aria-controls="collapse-E">
            <?php echo get_phrase('rating'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-E" class="collapse show" role="tabpanel" aria-labelledby="heading-E">
        <div class="card-body">
           <div class="col-lg-12 grid-margin stretch-card">
          <table id="example" class="table table-striped res_table">
            <thead>
              <tr>
              <th><?php echo get_phrase('s_no'); ?></th>
              <th><?php echo get_phrase('user_name'); ?></th>
                <th>
                <?php echo get_phrase('rating'); ?>
                </th>
                <th>
                  <?php echo get_phrase('comment'); ?>
                </th>
                <th>
                <?php echo get_phrase('comment'); ?>
                </th>
                <th><?php echo get_phrase('status'); ?></th>
              </tr>
            </thead>
            <tbody>
             <?php $i=0; foreach ($get_reviews as $get_reviews) { $i++;
              ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $get_reviews->name; ?></td>
                <td>
                  <?php echo $get_reviews->rating; ?>
                </td>
                <td>
                  <?php echo $get_reviews->comment; ?>
                </td>
                <td>
                  <?php echo date('M d, Y h:i A', $get_reviews->created_at); ?>
                </td>
                <td>
                <?php  if( $get_reviews->status){ ?><button class="btn active_rating btn-success"><?php echo get_phrase('approve'); ?></button><?php }else {  ?><button class="active_rating btn-danger btn"><?php echo get_phrase('pending'); ?></button>    <?php }?><input  type="text"  value="<?php echo $get_reviews->id;?>" hidden>
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
       <div id="pane-F" class="card tab-pane fade" role="tabpanel" aria-labelledby="tab-F">
      <div class="card-header" role="tab" id="heading-F">
        <h5 class="mb-0">
          <a class="collapsed" data-toggle="collapse" href="#collapse-F" data-parent="#content" aria-expanded="false" aria-controls="collapse-F">
            <?php echo get_phrase('wallet_amount'); ?>
          </a>
        </h5>
      </div>
      <div id="collapse-F" class="collapse show" role="tabpanel" aria-labelledby="heading-F">
          
        <div class="card-body">
           <div class="col-lg-12 grid-margin stretch-card">
          <table id="" class="table table-striped ">
            <thead>
              <tr>
              <th><?php echo get_phrase('user_name'); ?></th>
                <th>
                <?php echo get_phrase('wallet_amount'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
             <?php foreach ($get_wallet_amount as $get_wallet_amount) { 
              ?>
              <tr>
                <td><?php echo $get_wallet_amount->name; ?></td>
                <td>
                  <?php echo $get_wallet_amount->amount; ?>
                </td>           
              </tr>
            <?php
              }
            ?>      
            </tbody>
          </table>
          </div>
          <div class="col-lg-12 grid-margin stretch-card">
          <table id="" class="table table-striped ">
            <thead>
              <tr>
              <th><?php echo get_phrase('detail'); ?></th>
                <th>
                <?php echo get_phrase('amount'); ?>
                </th>
                <th>
                <?php echo get_phrase('credit/debit'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
             <?php foreach ($user->wallet_history as $wallet_history) { 
              ?>
              <tr>
                <td><?php echo $wallet_history->description; ?></td>
                <td>
                  <?php echo $wallet_history->amount; ?>
                </td>    
                <td>
                  <?php if($wallet_history->status == 0) { echo 'credit'; } else { echo 'debit'; }; ?>
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
</div>