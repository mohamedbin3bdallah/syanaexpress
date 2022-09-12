 <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="container-fluid clearfix">
          <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © 2018 <a href="http://www.profdeve.com/" target="_blank"><?php echo get_phrase('professional_developer'); ?></a>. <?php echo get_phrase('all_rights_reserved'); ?>.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><?php echo get_phrase('artist_customers'); ?> <i class="mdi mdi-heart text-danger"></i></span>
          </div>
        </footer>
        <!-- partial -->
      </div>
   <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

	<!-- Modal -->
	<div id="success" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content"  style="background-color: #58d8a3;">
				<div class="modal-body">
					<center style="color: #fff; font-size:25px;">
						<?php echo get_phrase($_SESSION['message']); ?>
					</center>
				</div>
			</div>
		</div>
	</div>
	<div id="failed" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-sm">
			<div class="modal-content"  style="background-color: #dc3545;">
				<div class="modal-body">
					<center style="color: #fff; font-size:25px;">
						<?php echo get_phrase($_SESSION['message']); ?>
					</center>
				</div>
			</div>
		</div>
	</div>

  <!-- plugins:js -->
  <script src="<?php echo base_url('assets/node_modules/jquery/dist/jquery.min.js'); ?>"></script>
   <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?php echo base_url('assets/node_modules/popper.js/dist/umd/popper.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/node_modules/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="<?php echo base_url('assets/node_modules/chart.js/dist/Chart.min.js'); ?>"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="<?php echo base_url('assets/js/off-canvas.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/misc.js'); ?>"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="<?php echo base_url('assets/js/dashboard.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/maps.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/chart.js'); ?>"></script>
  <script src="https://cdn.ckeditor.com/ckeditor5/19.0.0/classic/ckeditor.js"></script>
  
	<script>
	<?php if(isset($_SESSION['message'],$_SESSION['time'],$_SERVER['REQUEST_TIME']) && ($_SERVER['REQUEST_TIME'] < ($_SESSION['time']+3))) { ?>
	$(document).ready(function(){
		$("#<?php echo $_SESSION['modal']; ?>").modal('show');
		setTimeout(function() { $("#<?php echo $_SESSION['modal']; ?>").modal('hide'); }, 3000);
	});
	<?php } ?>
	</script>
	
    <!-- End custom js for this page-->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#example').DataTable();
	  $('#priceDataTable').DataTable();
      $('.res_table').DataTable();

        jQuery('#allusers').dataTable({ 
          });

        jQuery('#notification_table1').dataTable({                    
          "lengthMenu": [ [10, 50, 100, -1], [10, 50, 100, "All"] ],
          });
      } );

     $(document).ready(function () { 
        $("#catCommission").click(function() {
         $("#extra").hide();
        });
        $("#flatCommission").click(function() {
         $("#extra").show();
        });
      });
  </script>

    <script type="text/javascript">  
    var base_url = '<?php echo base_url(); ?>';
    $(document).ready(function()  {
       var id;
        $(document).on('click' ,'.active_rating',function(){ 
        jQuery(this).parent().addClass('tets'); 

        var rating_id= $('.tets').find('input[type=text]').val();     
        //=$(".yourClass : input").val();     
        //console.log(rating_id);
        var label_string=$(this).text().trim();
      if(label_string=="<?php echo get_phrase('approve'); ?>")  
        {      
          id=0; 
           $(this).toggleClass("btn-danger btn-success");
           $(this).text("<?php echo get_phrase('pending'); ?>"); 
        }  
        else if(label_string == "<?php echo get_phrase('pending'); ?>")    
        {    
          id=1; 
          $(this).toggleClass("btn-danger btn-success");
          $(this).text("<?php echo get_phrase('approve'); ?>");        
        } 
         var id_String = 'id='+ id; 

        $.ajax ({ 
           type: "POST", 
           url: base_url+'Admin/change_status_rating',

            data: {
                  id: id,
                  rating_id :rating_id,
				  '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val()
              },
              cache: false,      
            success: function(token) 
            {           
				//console.log(token);
				$('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val(token);
            }
         });     
         jQuery(this).parent().removeClass('tets');    
         //return false;  
        });      
    }); 

    $(document).ready(function()  {
       var id;
        $(document).on('click' ,'.active_user',function(){ 
        jQuery(this).parent().addClass('tets'); 

        var user_id= $('.tets').find('input[type=text]').val();     
        //=$(".yourClass : input").val();     
        //console.log(rating_id);
        var label_string=$(this).text().trim();
      if(label_string=="<?php echo get_phrase('active'); ?>")  
        {      
          id=0; 
           $(this).toggleClass("btn-danger btn-success");
           $(this).text("<?php echo get_phrase('deactive'); ?>"); 
        }  
        else if(label_string == "<?php echo get_phrase('deactive'); ?>")    
        {    
          id=1; 
          $(this).toggleClass("btn-danger btn-success");
          $(this).text("<?php echo get_phrase('active'); ?>");        
        } 
         var id_String = 'id='+ id; 

        $.ajax ({ 
           type: "POST", 
           url: base_url+'Admin/change_status_artist',

            data: {
                  id: id,
                  user_id :user_id,
				  '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val()
              },
              cache: false,      
            success: function(token) 
            {           
				//console.log(token);
				$('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val(token);
            }
         });     
         jQuery(this).parent().removeClass('tets');    
         //return false;  
        });      
    });

$(document).ready(function()  {
       var id;
        $(document).on('click' ,'.active_category',function(){ 
        jQuery(this).parent().addClass('tets'); 

        var user_id= $('.tets').find('input[type=text]').val();     
        //=$(".yourClass : input").val();     
        //console.log(rating_id);
        var label_string=$(this).text().trim();
      if(label_string=="<?php echo get_phrase('active'); ?>")  
        {      
          id=0; 
           $(this).toggleClass("btn-danger btn-success");
           $(this).text("<?php echo get_phrase('deactive'); ?>"); 
        }  
        else if(label_string == "<?php echo get_phrase('deactive'); ?>")    
        {    
          id=1; 
          $(this).toggleClass("btn-danger btn-success");
          $(this).text("<?php echo get_phrase('active'); ?>");        
        } 
         var id_String = 'id='+ id; 

        $.ajax ({ 
           type: "POST", 
           url: base_url+'Admin/change_status_category',

            data: {
                  id: id,
                  user_id :user_id,
				  '<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val()
              },
              cache: false,      
            success: function(token) 
            {           
				//console.log(token);
				$('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val(token);
            }
         });     
         jQuery(this).parent().removeClass('tets');    
         //return false;  
        });      
    });

$(document).ready(function () {

  $(".num_only").bind("keydown", function (event) {
  if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 ||
  event.keyCode == 190 ||  event.keyCode == 110 ||
  // Allow: Ctrl+A
  (event.keyCode == 65 && event.ctrlKey === true) ||
    // Allow: Ctrl+C
        (event.keyCode == 67 && event.ctrlKey === true) ||
        // Allow: Ctrl+V
                (event.keyCode == 86 && event.ctrlKey === true) ||
                // Allow: home, end, left, right
                        (event.keyCode >= 35 && event.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        } else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                event.preventDefault();
            }
        }
    });
});

$( ".changeprice" ).click(function() {  
       var id = $(this).data('invoiceids');
    $('.invoiceids').val(id);  
  });
  
$('#numberbox').keyup(function(){
  if ($(this).val() > 99){
    alert("No numbers above 99. Please use less then 100");
    $(this).val('99');
  }
});
</script>


<script>
	$(document).ready(function(){
		if($("input[type='checkbox'][name='check_cat']").is(':checked')) {
			getCategories();
		}
		
		$("input[type='checkbox'][name='check_cat']").change(function(){
			getCategories();
		});
		
		function getCategories()
		{
			if($("input[type='checkbox'][name='check_cat']").is(':checked'))
			{
				var parent = $("input[type='checkbox'][name='check_cat']").val();
				var category = $("input[type='checkbox'][name='check_cat']").attr('cat_id');
				//var price = $("input[type='checkbox'][name='check_cat']").attr('price');
				$.ajax({
					type: 'POST',
					url: '<?php echo base_url(); ?>Admin/getcategories',
					data: {
						'parent' : parent,
						'category' : category,
						//'price' : price,
						'<?php echo $this->security->get_csrf_token_name(); ?>' : $('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val()
					},
					success: function (response) {
						var response_data = JSON.parse(response);
						document.getElementById('parent_div').innerHTML = response_data.data;
						$('input[name="'+"<?php echo $this->config->item('csrf_token_name'); ?>"+'"]').val(response_data.token);
						/*var currency = $("select[name='country_id']").find(":selected").attr('currency');
						$("#currency").text(currency);*/
					}
				});
			}
			else
			{
				document.getElementById('parent_div').innerHTML = '';
			}
		}
		
		/*$("select[name='country_id']").change(function(){
			var currency = $("select[name='country_id']").find(":selected").attr('currency');
			$("#currency").text(currency);
		});*/
		
		$("select[name='country_price']").change(function(){
			var currency = $("select[name='country_price']").find(":selected").attr('currency');
			$("#currency").text(currency);
		});
	});
	
	$(document).ready(function(){
		<?php if($this->session->flashdata('addClass')) { ?>
			$('.removeClass').removeClass('active show');
			$('.'+"<?php echo $this->session->flashdata('addClass'); ?>").addClass('active show');
		<?php unset($_SESSION['addClass']); } ?>
	});
</script>

</body>

</html>