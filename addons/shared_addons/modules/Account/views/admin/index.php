
<style>
	h4 {
		display: flex;
		display: -webkit-box;
		display: -ms-flexbox;
	}
</style> 
 <div class="contentBlock">
		<h4>
		<div style="width:98%;">
				<?php echo lang('account:list_title') ?></div> 
					<div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
							<?php echo lang('amznaccount:icon_tooltip') ?>
                        </div>
                        </div>
        
         </h4>
		<?php echo $this->load->view('admin/tables/list') ?>
</div>