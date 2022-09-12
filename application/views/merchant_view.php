<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>
                <?php echo get_phrase('all_data'); ?>
            </h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            <?php echo get_phrase('merchant_details'); ?>
                        </h2>
                        <ul class="header-dropdown m-r--5">
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons"><?php echo get_phrase('more_vert'); ?></i>
                                </a>
                                <ul class="dropdown-menu pull-right">
                                    <li><a href="javascript:vo<?php echo get_phrase('id'); ?>(0);"><?php echo get_phrase('action'); ?></a></li>
                                    <li><a href="javascript:vo<?php echo get_phrase('id'); ?>(0);"><?php echo get_phrase('another_action'); ?></a></li>
                                    <li><a href="javascript:vo<?php echo get_phrase('id'); ?>(0);"><?php echo get_phrase('something_else_here'); ?></a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                            <thead>
                            <tr>
                                <th><?php echo get_phrase('id'); ?></th>
                                <th><?php echo get_phrase('fullname'); ?></th>
                                <th><?php echo get_phrase('email'); ?></th>
                                 <th><?php echo get_phrase('mobile_no'); ?></th>
                                <th><?php echo get_phrase('image'); ?></th>
                                <th><?php echo get_phrase('delete') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php  foreach ($merchant as $val ){ ?>
                                <tr>
                                    <td><?php echo $val->id; ?></td>
                                    <td><?php echo $val->fullname; ?></td>
                                    <td><?php echo $val->email; ?></td>
                                    <td><?php echo $val->mobile_no; ?></td>
                                    <td><img src="<?php echo $val->image;  ?>" height="100px" width="100px" alt="Merchant Img"></td>
                                    <td><a href="<?php echo  base_url('Urls/urls_delete/').$val->id; ?>"><? echo get_phrase('delete') ?></a></td>
                                </tr>
                            <?php  } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>