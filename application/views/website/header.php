<!DOCTYPE html>
<html class="no-js">

    <head>
        <title> <?php echo $this->lang->line('syanaexpress'); ?> </title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">
        <link rel="icon" href="<?php echo base_url('/assets/website/images/favicon.png'); ?>" type="image/png">
		<link rel="stylesheet" href="<?php echo base_url('/assets/website/css/style.css'); ?>">
		<?php if($lang == 'ar') { ?><link rel="stylesheet" href="<?php echo base_url('/assets/website/css/style-ar.css'); ?>"><?php } ?>
		<script src="<?php echo base_url('/assets/website/js/jquery.min.js'); ?>"></script>

    </head>

    <body class="<?php echo $dir = ($lang == 'ar')? 'rtl':''; ?>">
        <div class="content">
            <!--==============================Start Header ==============================-->
            <header id="header2" class="clearfix">
<div class="container-xxl">
    <div class="row">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div class="logo">
                <img src="<?php echo base_url('/assets/website/images/logo.svg'); ?>">
            </div>
            <div>

                <a class="ico-sidemenu d-lg-none" href="#">
                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd">
                        <path d="M24 18v1h-24v-1h24zm0-6v1h-24v-1h24zm0-6v1h-24v-1h24z" fill="#1040e2"/>
                        <path d="M24 19h-24v-1h24v1zm0-6h-24v-1h24v1zm0-6h-24v-1h24v1z"/>
                    </svg>

                </a>
            </div>

            <div class="menu">
                <span class="icon-close d-lg-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M23.954 21.03l-9.184-9.095 9.092-9.174-2.832-2.807-9.09 9.179-9.176-9.088-2.81 2.81 9.186 9.105-9.095 9.184 2.81 2.81 9.112-9.192 9.18 9.1z"/>
                    </svg>
                </span>
                <div class="logo d-lg-none">
                    <img src="<?php echo base_url('/assets/website/images/logo.svg'); ?>">
                </div>
                <ul>
                    <li class="<?php if($page == '') echo 'active'; ?>">
                        <a href="<?php echo base_url($lang); ?>"><?php echo $this->lang->line('home'); ?></a>
                    </li>
                    <li class="<?php if($page == 'hero') echo 'active'; ?>">
                        <a href="<?php echo base_url($lang.'/hero'); ?>">
                            <?php echo $this->lang->line('hero'); ?>
                        </a>

                    </li>
                    <li>
                        <a href="<?php echo base_url($lang.'/#about'); ?>">
                            <?php echo $this->lang->line('aboutus'); ?> </a>

                    </li>
					<!--<li>
                        <a href="<?php //echo base_url($lang.'/register'); ?>">
                            Register </a>

                    </li>-->
                    <li class="<?php if($page == 'contact') echo 'active'; ?>">
                        <a href="<?php echo base_url($lang.'/contact'); ?>">
                            <?php echo $this->lang->line('contactus'); ?> </a>

                    </li>
					<li>
                        <a href="<?php echo $url = ($lang == 'ar')? base_url('/en/'.$page):base_url('/ar/'.$page); ?>"> <?php echo $word = ($lang == 'ar')? $this->lang->line('en'):$this->lang->line('ar'); ?> </a>

                    </li>
                    <li>
                        <a class="btn btn-solid-primary" href="<?php echo base_url($lang.'/hero#register-now'); ?>"> <?php echo $this->lang->line('registerasahero'); ?> </a>

                    </li>



                </ul>
            </div>
        </div>
    </div>
</div>
<div class="overlay"> </div>
<script>

    $(document).ready(function () {
        $(".ico-sidemenu").click(function () {
            $(".menu").toggleClass("active");
            $(".overlay").toggleClass("active");

        });
        $(".overlay").click(function () {
            $(".menu").removeClass("active");
            $(this).removeClass("active");


        });
        $(".icon-close").click(function () {
            $(".menu").removeClass("active");
            $(".overlay").removeClass("active");


        });
    });

</script>
</header>
            <!--==============================End Header ==============================-->
