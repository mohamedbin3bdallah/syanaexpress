<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                <div class="card">
                <div class="card-body">
                    <div class="header">
                        <h2>
                        <?php echo get_phrase('send_notification'); ?>
                        </h2>
                    <div class="send_msg" style="float: right; padding: 5px 0;margin-top: -29px;">
                      <input id="send_msg" class="btn btn-success" type="submit" value="Send Message" data-target="#msgmodal" data-toggle="modal">
                  </div>
                        
                    </div>
                    <div role="dialog" class="modal fade" id="msgmodal" style="display: none;">
                      <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                          <div class="modal-header">
                            <button data-dismiss="modal" class="close" type="button">×</button>
                            <input type="hidden" class="txt_csrfname" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                          </div>
                          <div class="modal-body">
                          <label><?php echo get_phrase('title'); ?></label>
                            <div class="form-group clearfix">
                              <input id="textfield" class="col-md-12" type="text" name="title" maxlength="15"><br>
                              <span class="result">0 </span><span>/15</span>
                            </div>
                          <label><?php echo get_phrase('notification_message'); ?></label>
                            <div class="form-group clearfix">
                              <textarea id="textfield1" name="message" class="col-md-12" rows="4" maxlength="150"></textarea><br>
                              <span class="result1">0 </span><span>/150</span>
                            </div>
                          </div>
                          <div class="modal-footer">
                          <button data-dismiss="modal" class="btn btn-success" id="notify-user" type="button"><?php echo get_phrase('send'); ?></button>
                            <button data-dismiss="modal" class="btn btn-default" type="button"><?php echo get_phrase('close'); ?></button>
                          </div>
                        </div>

                      </div>
                    </div>
                    <div class="body">
                    <p><!-- <input id="select_all" type="checkbox" value="check" name="check" style="opacity:1;position:inherit;"> Select All --></p> 
                       <table id="example" class="table table-striped res_table">
                            <thead>
                            <tr>
                                <th><?php echo get_phrase('s_no'); ?></th>
                                <th><?php echo get_phrase('name'); ?></th>
                                <th><?php echo get_phrase('adress'); ?></th>
                                <th><?php echo get_phrase('role'); ?>e</th>
                                <th><?php echo get_phrase('email_id'); ?></th>
                                <th><?php echo get_phrase('send_message'); ?></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i=0;
                             foreach ($user as $val ){ 
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td style="text-transform:capitalize;"><?php echo $val->name; ?></td>
                                    <td ><?php echo $val->address; ?></td>
                                    <td><?php if($val->role==1){ echo "Artist"; } else { echo "User"; } ?></td>
                                    <td><?php if(empty($val->email_id)) { echo $val->mobile; } else { echo $val->email_id; } ?>
                                    </td>
                                  <td><input class="notification_check" type="checkbox" value="check" name="check" style="opacity:1;position:inherit;"></td>  
                                    
                                </tr>
                            <?php  } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function(){
        $('input#textfield').on('keyup',function(){
           var charCount = $(this).val().replace(/^(\s*)/g, '').length;
            $(".result").text(charCount + " ");
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('textarea#textfield1').on('keyup',function(){
           var charCount = $(this).val().replace(/^(\s*)/g, '').length;
            $(".result1").text(charCount + " ");
        });
    });
</script>
  <!-- notification -->
  <script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
    
  jQuery(document).ready(function() {
      jQuery('#allusers').dataTable({

      });

    jQuery('#notification_table1').dataTable({                    
      "lengthMenu": [ [10, 50, 100, -1], [10, 50, 100, "All"] ],
    });

    var data = 
    {
      mobile:[],msg:'',title:''
    }
      jQuery(document).on('click','#select_all',function(){
        
          if(jQuery(this).prop('checked') == true)
          {
              jQuery('.notification_check').each(function(index, el) {
              jQuery(this).prop('checked',true);
              var mobile = jQuery(this).parent().prev().text(); 
              data.mobile.push(mobile);             
            });  
          }
          else{
               jQuery('.notification_check').each(function(index, el) {
               jQuery(this).prop('checked',false);
               data.mobile=[];
            }); 
          }
      });

    Array.prototype.remove = function() {
        var what, a = arguments, L = a.length, ax;
        while (L && this.length) {
            what = a[--L];
            while ((ax = this.indexOf(what)) !== -1) {
                this.splice(ax, 1);
            }
        }
        return this;
    };

     jQuery(document).on('click','.notification_check',function(){

      if(jQuery(this).prop('checked') == true){
       
          var mobile = jQuery(this).parent().prev().text(); 
          data.mobile.push(mobile);       
        }
        else
        {mobile
          var mobile = jQuery(this).parent().prev().text();
          data.mobile.remove(mobile);           
        }
     });

    jQuery(document).on('click','#send_msg',function(){
      //console.log(data);
      if(data.mobile.length ==0){
           swal("Warning", "Please select user to send notification", "error");
      }
      else
      {
        jQuery('#msgmodal').modal('show');
      }
    });

    jQuery(document).on('click','#notify-user',function(){

      var msg = jQuery('#msgmodal textarea').val();
      data.msg= msg;

      var title = jQuery('#msgmodal input[name="title"]').val();
      data.title= title;
	  
	  var csrfName = $('.txt_csrfname').attr('name');
      var csrfHash = $('.txt_csrfname').val();
	  data[csrfName] = csrfHash;
//console.log(data);
          $.ajax({

          url: base_url+'Admin/firebase',
          type: 'POST',
          dataType: 'json',
          data: data,
          success:function(data)
          {
			//console.log(data.success);
			$('.txt_csrfname').val(data.token);
		   
		   if(data.success)
		   {
			swal("Success", "Notification send successfully.", "success");
			swal({
                  title: "Success",
                  text: "Notification send successfully.",
                  type: "success",
                  confirmButtonColor: "#DD6B55",
                  confirmButtonText: "OK",
                  closeOnConfirm: true,                
                },
			function(isConfirm){
				if (isConfirm) {
					window.location.href = base_url + "Admin/notifaction";
				} 
			});
		   }
		   else swal({title: "Warning", html: true, text: "Can't send notifactions to these users "+data.message, type: "error"});
      data.mobile = [];data.title ='';data.msg ='';
     }
    })
  });
});
</script> 