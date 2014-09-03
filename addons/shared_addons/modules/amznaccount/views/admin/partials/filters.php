<fieldset id="filters">
	<?php echo form_open('', '', array('f_module' => $this->module_details['slug']))
	?>
	<ul>
		<li  class="" >
			<?php echo form_input('f_keywords', '', 'placeholder="Name of your Amazon Account"style="width: 210px;margin-left:720px;" ')
			?>
		</li>
	</ul>
	<?php echo form_close()
	?>
</fieldset>

