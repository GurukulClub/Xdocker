<?php $this->template->append_css('common/form.css'); ?>
<div class="formwidget ui-widget ui-widget-content ui-helper-clearfix accordion-group" >
<h4 id="page_title" class="formHeader ui-widget-header ">
<?php echo $_user->display_name?>
</h4>
<section class="formContent collapse in">
	<div class="form-horizontal">
		<div class="form_inputs" id="blog-content-tab">
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:email') ?></label>
				<div class="control-label">
					<?php echo $_user->email?>
				</div>
				
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:username') ?></label>
				<div class="control-label">
					<?php echo $_user->username?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:group') ?></label>
				<div class="control-label">
					<?php echo $_user->group_description?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:last_login') ?></label>
				<div class="control-label">
					<?php echo date('M d,Y ',$_user->last_login)?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:display_name') ?></label>
				<div class="control-label">
					<?php echo $_user->display_name?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:updated_on') ?></label>
				<div class="control-label">
					<?php echo date('M d,Y ',$_user->updated_on)?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:created_on') ?></label>
				<div class="control-label">
					<?php echo date('M d,Y ',$_user->created_on)?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:first_name') ?></label>
				<div class="control-label">
					<?php echo $_user->first_name?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:last_name') ?></label>
				<div class="control-label">
					<?php echo $_user->last_name?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:company') ?></label>
				<div class="control-label">
					<?php echo $_user->company?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:bio') ?></label>
				<div class="control-label">
					<?php echo $_user->bio?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:lang') ?></label>
				<div class="control-label">
					<?php echo $_user->lang?>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="title"><?php echo lang('userprofile:dob') ?></label>
				<div class="control-label">
					<?php echo date('M d,Y ',$_user->dob)?>
				</div>
			</div>
		</div>
	</div>
</section>
</div>