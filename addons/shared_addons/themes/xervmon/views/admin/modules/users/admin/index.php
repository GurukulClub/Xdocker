<style>h4{display:flex; display:-webkit-box;display: -ms-flexbox;}</style> 
 <div class="contentBlock">

 <h4>
  <div style="width:98%;">
    <?php echo lang('user:list_title') ?></div> 
     <div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
       <?php echo lang('user:icon_tooltip') ?>
                        </div>
                        </div>
        
         </h4>

<div class="accordion-body collapse in lst">
	<div class="content">
	
		<?php template_partial('filters') ?>
	
		<?php echo form_open('admin/users/action') ?>
		
			<div id="filter-stage">
				<?php template_partial('tables/users') ?>
			</div>
		
	
		<?php echo form_close() ?>
	</div>
</div>
</div>