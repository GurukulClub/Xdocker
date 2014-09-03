<div id="sidebarAccordion" class="accordion2 accordion">
    <div class="nav-header">
        MAIN MENU
    </div>

    <div class="accordion-group <?php echo $module_details['slug']==''?'selectedAccordian':''; ?>">
        <div class="accordion-heading clean">
            <a class="accordion-toggle" href="<?php echo site_url('admin'); ?>"> <span class="sideBarIcon dashboardIcon"></span> <?php echo lang('global:dashboard') ?>
            </i></a>
        </div>
        <div class="accordion-body collapse" id="collapseFirst">
            <div class="accordion-inner"></div>
        </div>
    </div>

<?php

    function getIconClassName($key) {
        // getIconClassName
        return lcfirst(preg_replace('/\s+/', '', $key)) . "Icon";
    }


    // Display the menu items.
    // We have already vetted them for permissions
    // in the Admin_Controller, so we can just
    // display them now.
    $menuIndex = 0;
    foreach ($menu_items as $key => $menu_item) {
    	if(is_array($menu_item)) 
    	{
	        $is_selected = in_array('admin/'.$module_details['slug'], array_values($menu_item));
	        echo '<div class="accordion-group '.($is_selected?'selectedAccordian':'').'">';
	        if (is_array($menu_item) and count($menu_item) > 0) {
	            echo ' <div class="accordion-heading clean"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#sidebarAccordion" href="#sidebarAccordionCollapse' . $menuIndex . '">';
	            echo ' <span class="sideBarIcon ' . getIconClassName($key) . '"></span> ' . lang_label($key) . ' <i class="arrow"></i>';
	            echo ' </a> </div>';
	            echo '<div class="accordion-body collapse '.($is_selected?'in':'').'" id="sidebarAccordionCollapse' . $menuIndex . '">';
	
	            foreach ($menu_item as $lang_key => $uri) {
					$lang_keyGlobal = strstr($lang_key,'global');
					$lang_keycp = strstr($lang_key,'cp');
	                if(!empty($lang_keyGlobal))
					{
						echo '<a href="' . site_url($uri) . '" class="accordion-inner clean" style="display:block"> <span class="sideBarIcon ' . getIconClassName(substr($uri,(strpos($uri,'addons')+7))) . '"></span> ' . lang_label($lang_key) . '</a>';
	
					}
					else if(!empty($lang_keycp))
					{
						if(substr($uri,(strpos($uri,'/'))) === 'edit-profile')
						{
							echo '<a href="' . site_url('admin/UserProfile/edit') . '" class="accordion-inner clean" style="display:block"> <span class="sideBarIcon ' . getIconClassName(substr($uri,(strpos($uri,'/')+1))) . '"></span> ' . lang_label($lang_key) . '</a>';
						}
						else
						{
							echo '<a href="' . site_url($uri) . '" class="accordion-inner clean" style="display:block"> <span class="sideBarIcon ' . getIconClassName(substr($uri,(strpos($uri,'/')+1))) . '"></span> ' . lang_label($lang_key) . '</a>';
						}
					}
					else
					{
						echo '<a href="' . site_url($uri) . '" class="accordion-inner clean" style="display:block"> <span class="sideBarIcon ' . getIconClassName($lang_key) . '"></span> ' . lang_label($lang_key) . '</a>';
					}
	
	            }
	
	            echo '</div>';
	
	        } elseif (is_array($menu_item)) {
	            foreach ($menu_item as $lang_key => $uri) {
	                echo ' <div class="accordion-heading clean"> <a class="display-block" href="' . site_url($uri) . '#" >';
	                echo ' <span class="sideBarIcon ' . getIconClassName($lang_key) . '"></span> ' . lang_label($lang_key);
	                echo ' </a> </div>';
	            }
	        } elseif (is_string($menu_item)) {
					//getIconClassName(substr($menu_item,(strpos($menu_item,'/')+1))) is when we get $menu_item as string
	            echo ' <div class="accordion-heading clean"> <a class="display-block" href="' . site_url($menu_item) . '#" >';
	            echo ' <span class="sideBarIcon ' . getIconClassName(substr($menu_item,(strpos($menu_item,'/')+1))) . '"></span> ' . lang_label($key);
	            echo ' </a> </div>';
	        }
	        echo '</div>';
	        $menuIndex++;
	      }
    }
    ?>
</div>