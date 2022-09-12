<body class="signup-page">
<div class="signup-box">
    <div class="logo">
        <a href="javascript:void(0);"><?php echo get_phrase('business'); ?><b><?php echo get_phrase('reviews'); ?></b></a>

<!--        <small>Admin BootStrap Based - Material Design</small>-->
    </div>
    <div class="card">
        <div class="body">
            <form id="sign_up" action="<?php echo base_url('Login/signup_Store') ?>"  method="POST">
                <div class="msg"><?php echo get_phrase('register_a_new_membership'); ?></div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons"><?php echo get_phrase('person'); ?></i>
                        </span>
                    <div class="form-line">
                        <input type="text" class="form-control" name="name" placeholder="Name Surname" required autofocus>
                    </div>
                </div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons"><?php echo get_phrase('email'); ?></i>
                        </span>
                    <div class="form-line">
                        <input type="email" class="form-control" name="email" placeholder="Email Address" required>
                    </div>
                </div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons"><?php echo get_phrase('lock'); ?></i>
                        </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="password" minlength="6" placeholder="Password" required>
                    </div>
                </div>
                <div class="input-group">
                        <span class="input-group-addon">
                            <i class="material-icons"><?php echo get_phrase('lock'); ?></i>
                        </span>
                    <div class="form-line">
                        <input type="password" class="form-control" name="confirm" minlength="6" placeholder="Confirm Password" required>
                    </div>
                </div>
                <div class="form-group">
                    <input type="checkbox" name="terms" id="terms" class="filled-in chk-col-pink">
                    <label for="terms">I read and agree to the <a href="javascript:void(0);"><?php echo get_phrase('terms_of_usage'); ?></a>.</label>
                </div>

                <button class="btn btn-block btn-lg bg-pink waves-effect" type="submit"><?php echo get_phrase('sign_up'); ?></button>

                <div class="m-t-25 m-b--5 align-center">
                    <a href="<?php echo base_url('Login') ?>"><?php echo get_phrase('you_already_have_a_membership'); ?></a>
                </div>
            </form>
        </div>
    </div>
</div>

