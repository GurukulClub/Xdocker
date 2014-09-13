<!doctype html>

<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]> <html class="no-js" lang="en">            <![endif]-->
<html>
<head>
    <meta charset="utf-8">

    <!-- You can use .htaccess and remove these lines to avoid edge case issues. -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

    <title><?php echo $template['title'].' - '.lang('cp:admin_title');?></title>

    <base href="<?php echo base_url(); ?>" />

    <!-- Mobile viewport optimized -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    <!-- CSS. No need to specify the media attribute unless specifically targeting a media type, leaving blank implies media=all -->
    <?php echo Asset::css('plugins.css'); ?>
    <?php echo Asset::css('jquery/chosen.css'); ?>

    <?php echo Asset::css('jquery/no-theme/jquery-ui-1.10.3.custom.min.css'); ?>
    <?php echo Asset::css('bootstrap/bootstrap.css'); ?>
    <?php echo Asset::css('bootstrap/bootstrap-responsive.css'); ?>
    <?php  echo Asset::css('bootstrap/flat-ui.css'); ?>
    <?php echo Asset::css('bootstrap/bootstrap-override.css'); ?>
    <?php echo Asset::css('common/flat-ui-override.css'); ?>
    <?php echo Asset::css('bootstrap/font-awesome.css'); ?>

    <?php Asset::css('jquery/sDashboard.css'); ?>

    <?php echo Asset::css('bootstrap/bootstrap-colorpicker.min.css'); ?>


    <!-- End CSS-->

    <!-- Load up some favicons -->
    <link rel="shortcut icon" href="<?php echo base_url().'addons/shared_addons/themes/xervmon/img/favicon.ico';?>">
    <link rel="apple-touch-icon" href="<?php echo base_url().'addons/shared_addons/themes/xervmon/img/favicon.ico';?>">

    <!-- metadata needs to load before some stuff -->
    <?php file_partial('metadata'); ?>

</head>
<body>
    <div id="xervmon-container-wrapper">
        <section id="content">
            <header class="hide-on-ckeditor-maximize">
            <?php file_partial('header'); ?>
            </header>
            <div class="xervmon-container row-fluid">
                <button type="button" class="btn btn-navbar tabIcon" data-toggle="collapse" data-target=".nav-collapse"></button>
                <div class="xervmon-sidebar">
                    <div class=" sidebar-nav">
                        <?php file_partial('sidebar'); ?>
                    </div>
                 </div>
                 <div class="xervmon-mainContent"> <span class="expdBtn"></span>
                    <div class="xervmon-dashboardContent">
                        <ul class="topStrip"><?php file_partial('sections'); ?></ul>
                        <div class="container-fluid">
                            <?php file_partial('subbar'); ?>
                            <div class="launchpadContent">
                                <a class="tooltipContent" title="launchpad details"></a>
                                <?php file_partial('notices'); ?>
                                <?php echo $template['body']; ?>
                            </div>
                        </div>
                    </div>
                 </div>
            </div>

        </section>

    </div>
    <footer>
<!--    <div class="wrapper" style="padding-top: 14px; text-align: center;">-->
            <p style="padding: 10px 0 0 20px">Copyright &copy; 2012 - <?php echo date('Y'); ?> Xervmon Inc <span class="footerRight"> Rendered in {elapsed_time} sec. using {memory_usage}.</span></p><p style="padding: 10px 0 0 20px" class="version">Version <?php echo CMS_VERSION . ' ' . CMS_EDITION; ?></p>

    <!--        <ul id="lang">
                            <div class="drop1" id="dropchange" style="padding-top: 9px;">
                <form action="<?php //echo current_url(); ?>" id="change_language" method="get">
                    <select class="chzn" name="lang" onchange="this.form.submit();">
                        <?php //foreach(config_item('supported_languages') as $key => $lang): ?>
                            <option value="<?php //echo $key; ?>" <?php //echo CURRENT_LANGUAGE == $key ? ' selected="selected" ' : ''; ?>>
                                 <?php //echo $lang['name']; ?>
                            </option>
                        <?php //endforeach; ?>
                    </select>
                </form>
                            </div>
            </ul>
        </div>-->
    </footer>

    <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6. chromium.org/developers/how-tos/chrome-frame-getting-started -->
    <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
    <![endif]-->

</body>
</html>
