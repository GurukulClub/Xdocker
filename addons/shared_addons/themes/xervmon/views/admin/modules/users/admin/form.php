
<div>

	<?php if ($this->method === 'create'): ?>
		<?php //echo lang('user:add_title') ?>
	
	<?php else: ?>
		<?php if (!empty($member->profile_id)): ?>
		<?php //echo sprintf(lang('user:edit_title'), $member->username) ?>
		<?php echo form_hidden('row_edit_id', isset($member->row_edit_id) ? $member->row_edit_id : $member->profile_id); ?>
		<?php endif ?>
	<?php endif ?>

		<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal crud'.((isset($mode)) ? ' '.$mode : '').'" id="form_createedit" enctype="multipart/form-data" ') ?>
			<div class="tabMain">
				<div class="tabMainTop">
					<ul id="myTab" class="nav nav-tabs">
						<li class="active"><a href="#user-basic-data-tab" data-toggle="tab"><span><?php echo lang('profile_user_basic_data_label') ?></span></a></li>
						<li><a href="#user-profile-fields-tab" data-toggle="tab"><span><?php echo lang('user:profile_fields_label') ?></span></a></li>
					</ul>
				</div>
			</div>
			<!-- Content tab -->
			<div class="contentBlock">
			<div class="contentBlockDetails">
			  <div class="control-group">
                      <div id="myTabContent" class="tab-content">
                          <div class="tab-pane fade  in active" id="user-basic-data-tab">
				
				<fieldset>
					
						<div class="control-group">
							<label class="control-label" for="email"><?php echo lang('global:email') ?> <span>*</span></label>
							<div class="controls">
								  <?php
							               $attributes = array (
							              'id'        			=> 'email',
							              'name'          		=> 'email',
							              'type'          		=> 'email',
							              'value'       		=> $member->email,
							              'maxlength'   		=> '100',
							              'data-val'  			=> 'true',
							              'placeholder' 		=>  "Ex: info@xervmon.com",
							              'required'			=> 'true',
							              'class'				=> 'input-xlarge',
							              'data-validation-required-message' => "This field is mandatory",
							              'data-validation-email-message'   => 'Not a valid email address',
							           );

									echo form_input($attributes) ;
				                  ?>
								
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="display_name"><?php echo lang('profile_display_name') ?> <span>*</span></label>
							<div class="controls">
								<?php //echo form_input('display_name', $display_name, 'id="display_name"') ?>
								<?php
							               $attributes = array (
							              'id'        			=> 'display_name',
							              'name'          		=> 'display_name',
							              'value'       		=> $display_name,
							              'maxlength'   		=> '100',
							              'data-val'  			=> 'true',
							              'placeholder' 		=>  "Ex:Xervmon User",
							              'required'			=> 'true',
							              'class'				=> 'input-xlarge',
							              'data-validation-required-message' => "This field is mandatory",
							    
							           );

									echo form_input($attributes) ;
				                  ?>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="username"><?php echo lang('user:username') ?> <span>*</span></label>
							<?php if ($this->method === 'create'): ?>
							<div class="controls">
								 <?php
							               $attributes = array (
							              'id'        			=> 'username',
							              'name'          		=> 'username',
							              'type'          		=> 'text',
							              'value'       		=> $member->username,
							              'maxlength'   		=> '100',
							              'data-val'  			=> 'true',
							              'placeholder' 		=>  "Ex: xervmon_user",
							              'required'			=> 'true',
							              'class'				=> 'input-xlarge',
							              'data-validation-required-message' => "This field is mandatory",
							         
							           );

									echo form_input($attributes) ;
				                  ?>
								
							</div>
							<?php else: ?>
							<div class="controls">
								 <?php
							               $attributes = array (
							              'id'        			=> 'username',
							              'name'          		=> 'username',
							              'type'          		=> 'text',
							              'value'       		=> $member->username,
							              'maxlength'   		=> '100',
							              'data-val'  			=> 'true',
							              'required'			=> 'true',
							               'readonly'			=> 'true',
							              'class'				=> 'input-xlarge',
							              'data-validation-required-message' => "This field is mandatory",
							         
							           );

									echo form_input($attributes) ;
				                  ?>
								
							</div>
							<?php endif ?>
						</div>
						
						<?php foreach($profile_fields as $field): ?>
							<?php  if ($field['required']){  ?>
						<div class="control-group">
							<label class="control-label" for="<?php echo $field['field_slug'] ?>">
								<?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?>
								<?php if ($field['required']){ ?> <span>*</span><?php } ?>
							</label>
							<div class="controls">
								<?php //print_r($field); die(); echo $field['input'] ?>
								
								<?php
										if( $field['required'] == 1) $required = true; else $required=false;
							               $attributes = array (
							              'id'        			=> $field['field_slug'],
							              'name'          		=> $field['field_slug'],
							              'type'          		=> $field['field_type'],
							              'value'       		=> $field['value'],
							              'maxlength'   		=> '100',
							              'data-val'  			=> 'true',
							              'placeholder' 		=> $field['field_name'],
							              'required'			=> $required,
							              'class'				=> 'input-xlarge',
							              'data-validation-required-message' => "This field is mandatory",
							         
							           );

									echo form_input($attributes) ;
				                  ?>
							</div>
						</div>
						<?php } ?>
						<?php endforeach ?>
	
						<div class="control-group">
							<label class="control-label" for="group_id"><?php echo lang('user:group_label') ?><span>*</span></label>
							<div class="controls">
								<?php echo form_dropdown('group_id', array(0 => lang('global:select-pick')) + $groups_select, $member->group_id, 'id="group_id" ') ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="active"><?php echo lang('user:activate_label') ?></label>
							<div class="controls">
								<?php $options = array(0 => lang('user:do_not_activate'), 1 => lang('user:active'), 2 => lang('user:send_activation_email')) ?>
								<?php echo form_dropdown('active', $options, $member->active, 'id="active" ') ?>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="password">
								<?php echo lang('global:password') ?>
								<?php if ($this->method == 'create'): ?> <span>*</span><?php endif ?>
							</label>
							<div class="controls" >
								 <?php
								
								 if ($this->method == 'create')
								 {
								 	$attributes = array (
							              'id'        			=> 'password',
							              'name'          		=> 'password',
							              'type'          		=> 'password',
							              'maxlength'   		=> '100',
							              'data-val'  			=> 'true',
							              'required'			=> 'true',
							              'class'				=> 'input-xlarge',
							              'autocomplete'		=> 'off',
							              'data-validation-required-message' => "This field is mandatory",
							         
							           );
										echo form_input($attributes) ;
								 }
								 else
								 	{
								 		echo form_password('password', '', 'id="password" autocomplete="off"');
									}
				                  ?>
				                  
							</div>
						</div>
					
				<div class ="buttons">
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?>
				
				</div>	
					
				
				</fieldset>
				
				
				
                          </div>
             
	
			<div class="tab-pane fade" id="user-profile-fields-tab">
	
				<fieldset>
					<ul>
	
					<?php foreach($profile_fields as $field): ?>
						<?php  if (!$field['required']) { ?>
						<div class="control-group">
							<label class="control-label" for="<?php echo $field['field_slug'] ?>">
								<?php echo (lang($field['field_name'])) ? lang($field['field_name']) : $field['field_name'];  ?>
								<?php if ($field['required']){ ?> <span>*</span><?php } ?>
							</label>
							<div class="controls">
								<?php echo $field['input'] ?>
							</div>
						</div>
						<?php } ?>
						<?php endforeach ?>
	
					</ul>
					
				</fieldset>
			</div>
		</div>
		</div>
		</div>
		</div>
		
		
		
		
		
	
	<?php echo form_close() ?>

</div>
<span class="pull-right" style="color:#FF0000">*Mandatory</span>
<style>
	.chosen-container{width:30% !important;}.chosen-drop{width:100% !important;}.chosen-search input{width:90% !important;}
	input, textarea, .uneditable-input {
    width: 50%;
   }
.input-xlarge {
    width: 50%;
}
.control-group.warning .help-block {
    color: #FF0000;
    margin-left: 55%;
    margin-top: -2.7%;
}

.ui-pnotify-text {
    display: -moz-deck !important;
    line-height: 10px;
}
</style>
<script>
$(document).ready(function() {
	  $(function () { $("input,select,textarea").not("[type=submit]").jqBootstrapValidation(); } );
});
</script>
