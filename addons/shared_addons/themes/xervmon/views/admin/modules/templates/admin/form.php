     <div class="contentBlock">
<?php if($this->method == 'edit' and ! empty($email_template)): ?>

    	<h4><?php echo sprintf(lang('templates:edit_title'), $email_template->name) ?></h4>

<?php else: ?>

    	<h4><?php echo lang('templates:create_title') ?></h4>

<?php endif ?>


	   <div class="contentBlockDetails">
	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal crud'.((isset($mode)) ? ' '.$mode : '').'" id="form_createedit" enctype="multipart/form-data" ') ?>
	
			<form class="form-horizontal">
		<div class="form_inputs" id="blog-content-tab">
							<fieldset>
								<div class="form-field">
	
				<div class="control-group">
				<?php if ( ! $email_template->is_default): ?>

					<label class="control-label" for="name"><?php echo lang('name_label') ?> <span>*</span></label>
					<div class="controls"><?php echo form_input('name', $email_template->name) ?></div>
				
				</div>
				
					<div class="control-group">
						<label class="control-label" for="slug"><?php echo lang('templates:slug_label') ?> <span>*</span></label>
					<div class="controls"><?php echo form_input('slug', $email_template->slug) ?></div>
					</div>
				
					<div class="control-group">
						<label class="control-label" for="lang"><?php echo lang('templates:language_label') ?></label>
						<div class="controls"><?php echo form_dropdown('lang', $lang_options, array($email_template->lang)) ?>
					</div>
					</div>
				
					<div class="control-group">
						<label class="control-label" for="description"><?php echo lang('desc_label') ?> <span>*</span></label>
						<div class="controls"><?php echo form_input('description', $email_template->description) ?></div>
					</div>
				
				<?php endif ?>
			
					<div class="control-group">
						<label class="control-label" for="subject"><?php echo lang('templates:subject_label') ?> <span>*</span></label>
						<div class="controls"><?php echo form_input('subject', $email_template->subject) ?></div>
					</div>
		
					<div class="control-group">
					<label class="control-label" for="body"><?php echo lang('templates:body_label') ?> <span>*</span></label>
								<div class="controls"><?php echo form_textarea('body', $email_template->body, 'class="templates wysiwyg-advanced"') ?>
					</div>	</div>
		
			

		<div class="control-group" >
			<div class="controls">
			<div class="buttons padding-top">
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?>
			</div></div>
		</div>
		</form>
				
	<?php echo form_close() ?>
	</div>
	</div></fieldset></div>

</div></div>

<style>h4{display:flex; display:-webkit-box;display: -ms-flexbox;}
  .form-inline input, .form-horizontal input, .form-search textarea, .form-inline textarea, .form-horizontal textarea { width:50%;}
  #sel47T_chzn { width:50% !important;}
  
.form-horizontal .control-label {
    font-size: 18px !important;
	font-weight:normal !important;
}
</style> 