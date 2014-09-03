<?php $this->template->append_css('common/form.css'); ?>
<div class="contentBlock">
        <div class="contentBlockDetails">


	<?php if (validation_errors()):?>
	<div class="error-box">
		<?php echo validation_errors();?>
	</div>
	<?php endif;?>

	<?php echo form_open_multipart('', array('id'=>'user_edit'));?>
<section class="formContent collapse in">
<div class="form-horizontal">
<div class="form_inputs" id="blog-content-tab">
<div class="form-field">
	<div id="profile_fields">
		<div>
			<div class="control-group">
				<label class="control-label" for="display_name"><?php echo lang('profile_display_name') ?></label>
				<div class="controls">
				<?php echo form_input(array('name' => 'display_name',  'id' => 'display_name', 'class' => 'input-xlarge', 'value' => set_value('display_name', $display_name))) ?>
				</div>
			</div>

			<?php foreach($profile_fields as $field): ?>
				<?php if($field['input']): ?>
					<div class="control-group">
						<label class="control-label" for="<?php echo $field['field_slug'] ?>">
							<?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?>
							<?php if ($field['required']) echo '<span>*</span>' ?>
						</label>

						<?php if($field['instructions']) echo '<p class="instructions">'.$field['instructions'].'</p>' ?>
						
						<div class="controls">
							<?php echo $field['input'] ?>
						</div>
					</div>
				<?php endif ?>
			<?php endforeach ?>
		</div>
	</div>

	<div id="user_names">
		<legend><?php echo lang('global:email') ?></legend>
		<div>
			<div class="control-group">
				<label class="control-label" for="email"><?php echo lang('global:email') ?></label>
				<div class="controls">
					<?php echo form_input('email', $_user->email) ?>
				</div>
			</div>
		</div>
	</div>

	<div id="user_password">
		<legend><?php echo lang('user:password_section') ?></legend>
		<div>
			<div class="control-group">
				<label class="control-label" for="password"><?php echo lang('global:password') ?></label>
				<div class="controls">
				<?php echo form_password('password', '', 'autocomplete="off"') ?>
				</div>
			</div>
		</div>
	</div>

	<?php if (Settings::get('api_enabled') and Settings::get('api_user_keys')): ?>
		
	<script>
	jQuery(function($) {
		
		$('input#generate_api_key').click(function(){
			
			var url = "<?php echo site_url('admin/UserProfile/generate_key') ?>",
				$button = $(this);
			
			$.post(url, function(data) {
				$button.prop('disabled', true);
				$('span#api_key').text(data.api_key).parent('li').show();
				show_message('APIKey', data.api_key, 'OK');
				
			}, 'json');
			
		});
		
	});
	</script>
		
	<div>
		<legend><?php echo lang('profile_api_section') ?></legend>
		
		<div>
			<div class="control-group" <?php $api_key or print('style="display:none"') ?>><?php echo sprintf(lang('api:key_message'), '<span id="api_key">'.$api_key.'</span>') ?></div>
			<div class="control-group">
				<input type="button" class="btn btn-success btn-small"  id="generate_api_key" value="<?php echo lang('api:generate_key') ?>" />
			</div>
		</div>
	
	</div>
	</div>
	</div>
	</div>
	</section>
	<?php endif ?>
	<div class="control-group">
	<div class="controls">
	<?php echo form_submit('', lang('profile_save_btn'),'class="btn btn-success xervmon-btn-big"') ?>
	<?php echo form_close() ?>
	</div>
	</div>
</div>
</div>

<style>
.form-field input[type="text"], .form-field input[type="password"], .form-field input[type="email"], .form-field input[type="number"], input, textarea, .uneditable-input{width:50%;}
.form-field select{width:53%;}
input[type="submit"] {
    height: 30px;
}
</style>