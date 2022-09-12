<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="row">
  	<div style="text-align: center; padding-top: 30px;">
    <?php if($password === false) { ?> 
      <img width="200" src="<?php echo base_url('assets/images/fail.png'); ?>">
      <h2><?php echo $heading; ?></h2>
    <?php } else { ?>
  		<img src="<?php echo base_url('assets/images/thanks.png'); ?>">
      <h2><?php echo $heading; ?></h2>
      <div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
		    
    <form id="register-form" role="form" autocomplete="off" class="form" method="post">
    <?php if(!empty($error)) { ?>
    <div class="alert alert-danger" role="alert">
      <?php echo $error; ?>
    </div>
    <?php } ?>
    <?php if(empty($success)) { ?>
    
            <input type="hidden" value="<?php echo $_GET['code'] ?>" name="code">
		       <label><?php echo get_phrase('new_password'); ?></label>
            <div class="form-group pass_show"> 
                <input name="password1" type="password" value="" class="form-control" placeholder="New Password"> 
            </div> 
		       <label><?php echo get_phrase('confirm_password'); ?></label>
            <div class="form-group pass_show"> 
                <input name="password2" type="password" value="" class="form-control" placeholder="Confirm Password"> 
            </div> 
            
            <input name="recover" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
    <?php } else { ?>   
      <div class="alert alert-success" role="alert">
      <?php echo $success; ?>
      </div>
    <?php } ?>
    </form>        
		</div>  
	</div>
</div>
    <?php } ?>
  	</div>
    
  </div>
</body>
</html>
