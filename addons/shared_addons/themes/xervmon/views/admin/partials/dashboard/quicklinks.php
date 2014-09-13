 <?php if ($theme_options->pyrocms_quick_links == 'yes') : ?>
 
    <div class="one_half">
            <div class="accordion-group draggable ">
        <div class="accordion-heading">
            <h4><?php echo 'Getting Started with Quick Links.' ?></h4>
            <a class="pull-right toggle-accordion margin5 " data-toggle="tooltip" title="Toggle this element"><i class="icon-chevron-up"></i></a>
        </div>

        <section id="quick_links" class="item 5 ">
            <div class="accordion-body collapse in lst text-center"><div class="Quicklink">
            	
            	 <ul class="nav nav-tabs ul-icon-block plain-label">
                 	
                    <?php if(array_key_exists('users', $this->permissions) OR $this->current_user->group == 'admin'): ?>
                    <li>
                        <a class="launchpadIconsMain" title="<?php echo lang('cp:manage_users') ?>" href="<?php echo site_url('admin/users') ?>"><div class="launchpadIcons"><?php echo Asset::img('icons/xervmon_users.png', lang('cp:manage_users')) ?></div>
                            <label><?php echo lang('cp:manage_users') ?></label></a>
                    </li>
                    <?php endif ?>
                    
                   
                    <?php if((array_key_exists('Cloud', $this->permissions) OR $this->current_user->group == 'admin') AND module_enabled('Cloud')): ?>
                        <li>
	                        <a class="launchpadIconsMain" title="<?php echo lang('cp:manage_cloud') ?>" href="<?php echo site_url('admin/Cloud') ?>"><div class="launchpadIcons"><?php echo Asset::img('icons/manage-cloud.png', lang('cp:manage_cloud')) ?></div><label><?php echo lang('cp:manage_cloud') ?></label></a>
	                    </li>
	                     <li>
                        	<a class="launchpadIconsMain" title="<?php echo lang('cp:dplanner') ?>" href="<?php echo site_url('admin/Cloud/DeploymentDesigner/index') ?>"><div class="launchpadIcons"><?php echo Asset::img('icons/deployment-planner.png', lang('cp:dplanner')) ?></div><label><?php echo lang('cp:dplanner') ?></label></a>
                   		 </li>
	                    <?php if((array_key_exists('requisitions', $this->permissions) OR $this->current_user->group == 'admin') AND module_enabled('requisitions')): ?>
		                    <li>
		                        <a class="launchpadIconsMain" title="<?php echo lang('cp:requisitions') ?>" href="<?php echo site_url('admin/requisitions') ?>"><div class="launchpadIcons"><?php echo Asset::img('icons/requisitions.png', lang('cp:requisitions')) ?></div><label><?php echo lang('cp:requisitions') ?></label></a>
		                    </li>
	                    <?php endif ?>
                    <?php endif ?>
                    <?php if((array_key_exists('SystemMonitor', $this->permissions) OR $this->current_user->group == 'admin') AND module_enabled('SystemMonitor')): ?>
                    <li>
                        <a class="launchpadIconsMain" title="<?php echo lang('cp:systemmonitor') ?>" href="<?php echo site_url('admin/SystemMonitor') ?>"><div class="launchpadIcons"><?php echo Asset::img('icons/SystemMonitor.png', lang('cp:systemmonitor')) ?></div><label><?php echo lang('cp:systemmonitor') ?></label></a>
                    </li>
                    <?php endif ?>
                    <?php if($this->current_user->group == 'admin') :?>
                    <li>
                        <a class="launchpadIconsMain" title="Edit Profile" href="<?php echo site_url().'/admin/UserProfile/Edit'; ?>"><div class="launchpadIcons"><?php echo Asset::img('addons/UserProfile - Edit Profile.png','Edit Profile'); ?></div><label><?php echo 'Edit Profile' ?></label></a>
                    </li>	
					<?php endif ?>
                 
                </ul>
               
            </div></div>
        </section>
        </div>
    </div> 
    <?php endif ?>