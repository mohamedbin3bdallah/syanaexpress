<body class="fp-page">
<div class="fp-box">
    <div class="logo">
        <a href="javascript:void(0);"><?php echo get_phrase('business'); ?><b><?php echo get_phrase('reviews'); ?></b></a>
<!--        <small>Admin BootStrap Based - Material Design</small>-->
    </div>
    <div class="card">
        <div class="body">
            <form id="forgot_password" method="POST">
                <div class="msg">
                    <?php echo get_phrase("enter_your_email_address_that_you_used"); ?>
                </div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons"><?php echo get_phrase('email'); ?></i>
                        </span>
                    <div class="form-line">
                        <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>
                    </div>
                </div>

                <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit"><?php echo get_phrase('reset_my_password'); ?></button>

                <div class="row m-t-20 m-b--5 align-center">
                    <a href="<?php echo base_url('Login'); ?>"><?php echo get_phrase('sign_in'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>