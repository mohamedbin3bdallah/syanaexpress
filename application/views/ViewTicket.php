<!-- partial -->
<div class="main-panel">
<div class="content-wrapper">
  <div class="row">
     <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"><?php echo get_phrase('add_comment'); ?></h4>
             <?php $attributes = array('id' => 'form_validation','name'=>'addComment','class'=>'form-sample'); echo form_open_multipart('Admin/addComment', $attributes); ?>
              <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  <div class="form-group row">
                      <label class="col-sm-3 col-form-label"><?php echo get_phrase('comments'); ?></label>
                      <div class="col-sm-9">
                      <textarea class="form-control" name="comment" required="" placeholder="Enter Comments"></textarea>
                      <input type="hidden" name="ticket_id" value="<?php echo $ticket->id; ?>">
                      <input type="hidden" name="user_id" value="<?php echo $ticket->user_id; ?>">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-success mr-2"><?php echo get_phrase('submit'); ?></button>
                </div>
              </div>
              </div>
            </form>
        </div>
      </div>
      </div>
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
                <div class="row ticket-card mt-3 pb-2">
                   <div class="col-1">
                    <img class="img-sm rounded-circle" src="<?php echo  base_url('/assets/images/faces-clipart/pic-1.png' ); ?>" alt="profile image">
                  </div>
                 <div class="ticket-details col-9">
                  <div class="d-flex" style="padding-bottom: 5px;">
                    <p class="text-primary font-weight-bold mr-2 mb-0 no-wrap"><?php echo $ticket->userName ?> :</p>
                    <p class="font-weight-medium mr-1 mb-0"><?php echo '#'.$ticket->id; ?></p>
                    <p class="mar-1 mb-0 ellipsis"><?php if($ticket->status==0) { echo "Pending"; } elseif($ticket->status==1) { echo "Resolving Issue"; } elseif($ticket->status==2) { echo "Close"; } ?></p>
                    <p class="font-weight-bold mb-0 ellipsis"></p>
                </div>
                  <p class="text-small text-gray"><?php echo $ticket->reason; ?></p>
                  <div class="row text-muted d-flex">
                    <div class="col-6 d-flex">
                      <p class="mb-0 mr-2"><?php echo get_phrase('ticket_time'); ?> :</p>
                      <p class="Last-responded mr-2 mb-0"><?php echo date('M d, Y h:i A', $ticket->craeted_at); ?></p>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>  
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">

          <table id="example" class="table table-bordered">
            <thead>
              <tr>
                <th>
                <?php echo get_phrase('s_no'); ?>
                </th>
                <th>
                <?php echo get_phrase('comment'); ?>
                </th>
                <th>
                <?php echo get_phrase('comment_by'); ?>
                </th>
                <th>
                <?php echo get_phrase('role'); ?>
                </th>
                <th>
                <?php echo get_phrase('commented_at'); ?>
                </th>
              </tr>
            </thead>
            <tbody>
            <?php $i=0; foreach ($ticket_comment as $ticket_comment) {
              $i++;
              if($i % 2 ==0)
              { ?>
            <tr class="table-info">
              <?php } else { ?> 
              <tr class="table-warning">
              <?php }?>
                 <td>
                  <?php echo $i; ?>
                </td>
                <td>
                  <?php echo $ticket_comment->comment; ?>
                </td>
                <td>
                   <?php echo $ticket_comment->userName; ?>
                </td>
                <td>
                   <?php if($ticket_comment->role==0){ echo "Admin"; } if($ticket_comment->role==1){ echo "User"; } ?>
                </td>
                 <td>
                   <?php echo date('M d, Y h:i A', $ticket_comment->created_at); ?>
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
