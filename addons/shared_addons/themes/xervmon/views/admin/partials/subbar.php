<div class="row-fluid">
    <div class="span12" >
        <?php file_partial('breadcrumbs') ?>
		<?php if($module_details['slug'] == 'UserProfile') { ?>
		<h3><?php echo $module_details['name'] ? anchor('admin/'.$module_details['slug'].'/edit', $module_details['name']) : lang('global:dashboard') ?></h3>
		<?php } else { ?>
        <h3><?php echo $module_details['name'] ? anchor('admin/'.$module_details['slug'], $module_details['name']) : lang('global:dashboard') ?></h3>
		<?php } ?>
		
    </div>
</div>
