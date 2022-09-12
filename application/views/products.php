<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">
  <div class="row">
  
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title"><?php echo get_phrase('products_of'); ?> <?php echo $artist_name; ?></h4>
 
          <table id="example" class="table table-striped">
            <thead>
              <tr>
                <th>
                <?php echo get_phrase('product_name'); ?>
                </th>
                <th>
                <?php echo get_phrase('price'); ?>
                </th>
                <th>
                <?php echo get_phrase('product_image'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
             <?php foreach ($get_products as $get_products) {
              ?>
              <tr>
                <td class="py-1">
                  <?php echo $get_products->product_name; ?>
                </td>
                <td>
                  <?php echo $get_products->price; ?>
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
</div>
<!-- content-wrapper ends -->
