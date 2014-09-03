<div class="accordion-group ">

	<div class=" title accordion-heading">
		<h4><?php echo lang('global:plugins');?></h4>
	</div>


	<div class="accordion-body collapse in lst">
		<div class="content">
		<h4><?php echo lang('addons:plugins:add_on_plugins');?></h4>
		<?php echo $this->load->view('admin/plugins/_table', array('plugins' => $plugins), true) ?>
		
		<h4><?php echo lang('addons:plugins:core_plugins');?></h4>
		<?php echo $this->load->view('admin/plugins/_table', array('plugins' => $core_plugins), true) ?>
		
		</div>
	</div>

	<section id="plugin-docs" style="display:none">
		<?php echo $this->load->view('admin/plugins/_docs', array('plugins' => array($plugins, $core_plugins)), true) ?>
	</section>
<div>