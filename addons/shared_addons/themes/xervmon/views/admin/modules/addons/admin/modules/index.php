     <div class="contentBlock">
                        <h4><div style="width:91%;"><?php echo lang('addons:modules:addon_list');?></div>
							<span class="span1"> 
                        <div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
                        Details of all the modules
                        </div>
                        </div></span>
                        </h4>				

  <div class="contentBlockDetails" id="addOnsList">
                        <ul class="row-fluid addOnsList content"> 			
		<?php if ($addon_modules): ?>
			<?php foreach ($addon_modules as $key => $module): ?>
			<li class="span3">	
                <div class="addOns"> 
				<ul>
				<img src="<?= './addons/shared_addons/themes/xervmon/img/addons/'.$module['name'].'.png' ?>"></img>
				<h5><?php echo ($module['is_backend'] and $module['installed']) ? anchor('admin/'.$module['slug'], $module['name']) : $module['name'] ?></h5>
				<p>	<?php echo $module['description'] ?></p>
				<p>	<?php echo $module['version'] ?></p>
					
				<?php if ($module['installed']): ?>
					<!--
							<?php if ($module['enabled']): ?>   
							
							<li><a href="<?= site_url().'/admin/addons/modules/disable/'.$module['slug'] ?>" class="icons iconEye" title="<?php echo lang('addons:modules:confirm_disable') ?>"></a></li>								
							<?php else: ?>
								<li><a href="<?= site_url().'/admin/addons/modules/enable/'.$module['slug'] ?>" class="icons iconEye enabled" title="<?php echo lang('addons:modules:confirm_enable') ?>"></a></li>	
							<?php endif ?>
							-->
				<?php if ($module['is_current']): ?>
					<!--<li><a href="<?//= site_url().'/admin/addons/modules/uninstall/'.$module['slug'] ?>" class="icons iconClose" title="<?php //echo lang('addons:modules:confirm_uninstall') ?>"></a></li>
							<?php //else: ?> -->
			
			
				<li><a href="<?= site_url().'/admin/addons/modules/upgrade/'.$module['slug'] ?>" class="upgradeIcon" title="<?php echo lang('addons:modules:confirm_upgrade') ?>"></a></li>
				<?php endif ?>
							
			<?php else: ?>
						
				<li><a href="<?= site_url().'/admin/addons/modules/install/'.$module['slug'] ?>" class="icons iconClose" title="<?php echo lang('addons:modules:confirm_install') ?>"></a></li>
							
			<?php endif ?>
					<!--	<li><a href="<?= site_url().'/admin/addons/modules/upgrade/'.$module['slug'] ?>" class="upgradeIcon" title="<?php echo lang('addons:modules:confirm_upgrade') ?>"></a></li>-->
				</ul>	
  			
			</div>
		</li>
			<?php endforeach ?>
		<?php endif ?>
		</ul>
		<div class="tableBottomRow"></div>
                        <div class="page_navigation"></div>	
</div>
</div>

<style>h4{display:flex; display:-webkit-box;display: -ms-flexbox;}  .iconEye {
    background-position: -262px -125px !important;
}</style> 

<script>
	$(document).ready(function(){
				$('#addOnsList').pajinate();
			});
</script>
 
<script src="<?= './addons/shared_addons/themes/xervmon/js/script/script.js' ?>"></script> 
<script src="<?= './addons/shared_addons/themes/xervmon/js/script/jquery/jquery.pajinate.js' ?>"></script> 