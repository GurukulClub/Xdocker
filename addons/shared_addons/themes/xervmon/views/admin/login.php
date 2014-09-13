<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title> <?=lang('global:product_name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <?php echo Asset::css('bootstrap/bootstrap.css'); ?>
	<?php echo Asset::css('bootstrap/bootstrap-responsive.css'); ?>
	<?php echo Asset::css('common/style.css'); ?>
	<?php echo Asset::css('jquery/atooltip.css'); ?>
	<?php  Asset::js('jquery/jquery.js'); ?>
    <?php  Asset::js('bootstrap/bootstrap.js'); ?>
    <?php  Asset::js('bootstrap/bootbox.min.js'); ?>
    <?php  Asset::js('jquery/jquery.form.js'); ?>
    <?php  Asset::js('jquery/jquery.mask.js'); ?>
    <?php  Asset::js('jquery/jquery.validate.js'); ?>
    <?php Asset::js('admin/login.js'); ?>
    <?php  Asset::js('jquery/jquery.atooltip.js'); ?>
    <?php  Asset::js('misc/selectivizr-min.js'); ?>
    <?php Asset::js('pnotify/jquery.pnotify.min.js'); ?>
	<?php Asset::css('pnotify/jquery.pnotify.default.css'); ?>
	<?php  Asset::js('misc/xervmon_utils.js'); ?>
	<?php echo Asset::render() ?>
    <script type="text/javascript">
		$(function(){
			$('a.loginToolTip').aToolTip();
		});
		var SITE_URL                    = "<?php echo rtrim(site_url(), '/').'/';?>";
	</script>

  </head>

  <body id="login-body" class="login-body">

    <div class="container-fluid">
		<div class="row-fluid">
		<div id="xervmon-loginRegisterBox" class="span9 xervmon-loginRegisterBox">
		<div class="row-fluid"><div class="span12"><logo><?php echo  Asset::img('logo.png', 'view site')?> <small> <?php echo CMS_VERSION . ' ' . CMS_EDITION; ?></small></logo></div></div>
		<div id="xervmon-loginBox" class="xervmon-loginBox">
			<a class="loginToolTip" title="Login/Register Details">?</a>
			<ul class="nav nav-tabs xervmon-logRegTab" id="xervmon-logRegTab">
				<li class="active"><a href="#xervmon-login" data-toggle="tab"><?php echo lang('login_tab_label'); ?></a></li>
				<!--<li><a href="#xervmon-register" data-toggle="tab"><?php echo lang('register_label'); ?></a></li>-->
			</ul>
			<div class="tab-content">
				<div class="tab-pane active xervmon-login" id="xervmon-login">
					<div class="xervmon-content clearfix">
						<div class="xervmon-contentLeft span6">
							<h3><?php echo  Asset::img('lockIcon.png', 'view site')?><?php echo lang('login_to_account_label'); ?></h3>
							<div class="xervmon-contentBottom">
								<?php $this->load->view('admin/partials/notices') ?>
								<?php echo form_open('admin/login'); ?>
								<form>
									<input id="username" name="email"  title="Minimum 5 letters or numbers." required="" type="text" placeholder="Your user name or Email">
									<input id="password" name="password" pattern=".{5,}" title="Minimum 5 letters or numbers." required="" type="password"  placeholder="Your Password">

									<span class="xervmon-inputCheckbox"><input type="checkbox" name="remember" id="remember-check" class="checkbox remember-check"><label for="xervmon-rememberMe"><?php echo lang('user:remember'); ?></label></span>
									<button value="Log In" name="submit" type="submit" ontouchstart="" class="btn login-submit" id="login-submit">
								<span><?php echo lang('login_label'); ?></span>
								</button><a onclick="javascript:forgotPasswordModal(); return false;" href="#"><?php echo lang('forgot_password_label');?></a>
								</form>
							</div><!--xervmon-contentBottom Ends-->
						</div><!--xervmon-contentLeft Ends-->
						<div class="xervmon-contentRight span6">
							<h3><?php echo  Asset::img('whoAreWeIcon.png', 'view site')?><?php echo lang('who_are_we_label');?></h3>
							<div class="xervmon-contentBottom">
								<p><?php echo lang('about_us_label'); ?></p>
								<h4><?php echo lang('connect_with_us_label'); ?></h4>
								<a target="_blank" href="https://www.facebook.com/Xervmon" class="xervmon-facebookIcon xervmon-socialIcons"></a>
								<a target="_blank" href="http://www.twitter.com/xervmon" class="xervmon-twitterIcon xervmon-socialIcons"></a>
								<a target="_blank" href="http://www.linkedin.com/company/xervmon-inc" class="xervmon-socialIconsL"><?php //echo  Asset::img('linkedin.png', 'view site')?></a>
							</div><!--xervmon-contentBottom Ends-->
						</div><!--xervmon-contentRight Ends-->
				</div><!--xervmon-content Ends-->
			</div><!-- xervmon-login tab details ends-->
			<div class="tab-pane xervmon-register" id="xervmon-register">
				<div class="xervmon-content clearfix">
					<div class="xervmon-contentRegister span6">
						<h3><?php echo lang('register_label'); ?></h3>
							<div class="xervmon-contentBottom">
								<form>
									<label><?php echo lang('first_name_label'); ?></label> <input type="text">
									<label><?php echo lang('last_name_label'); ?></label> <input  type="text">
									<label><?php echo lang('email_label'); ?></label> <input  type="email">
									<label><?php echo lang('password_label'); ?></label> <input  type="password">
									<label>&nbsp;</label> <button><?php echo lang('register_label'); ?></button>
								</form>
							</div><!--xervmon-contentBottom Ends-->
					</div><!--xervmon-contentLeft Ends-->
			</div><!--xervmon-content Ends-->
		</div>
	</div>
    <div class="row-fluid"></div>
    </div><!--/span-->
    <footer>
    <p><?=lang('global:xervmon_copyright'); ?></p>
    </footer>
    </div>
    </div><!--/row-->

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


  </body>

</html>
