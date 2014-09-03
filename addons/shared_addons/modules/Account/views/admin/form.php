		<?php if ($this->method == 'edit'): ?>
			<?php  $post -> password = ''; ?>
		<?php endif ?>

   <div class="contentBlock">
   	<h4>
		<div style="width:91%;">
				Create Account</div> <span class="span1"> 
					<div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
					Create account for Amazon
                        </div>
                        </div>
             </span>
         </h4>
        <div class="contentBlockDetails">
	<?php echo form_open($this->uri->uri_string(), 'class="form-horizontal crud'.((isset($mode)) ? ' '.$mode : '').'" id="form_createedit" enctype="multipart/form-data" ') ?>
					<form class="form-horizontal">
						<div class="form_inputs" id="blog-content-tab">
							<fieldset>
								<div class="form-field">
										<div class="control-group">
											<label class="control-label" for="title"><?php echo lang('Account:title') ?><span>*</span></label>
											<div class="controls">
											<?php
											$attributes = array('id' => 'name', 'name' => 'name', 'value' => $post -> name, 'maxlength' => '100', 'data-val' => 'true', 'class' => 'input-xlarge', 'data-val-required' => lang('Account:error_name'), );
											echo form_input($attributes);
										?>
											<span class="serviceInfoHover infoConfig">
												<i class="serviceInfoIcon icons pull-right"></i>
													<span class="serviceInfoDetails">
													<?php echo lang('Account:icon_title') ?>
													</span>
											</span>
											</div>
										</div>

									<div class="control-group">
										<label class="control-label" for="cloudProvider"><?php echo lang('Account:cloudProvider') ?><span>*</span></label>
										<div class="controls">
											<select id="cloudProvider" name="cloudProvider" class="cloudProvider" >
												<?php
												$cloudProviders = $post->cloudProviders;
													foreach($cloudProviders as $key => $value)
													{
														echo '<option name="' . $key. '">'.$value.'</option>';
													}
 												?>
												
											</select>
										
										<span class="serviceInfoHover infoConfig">
												<i class="serviceInfoIcon icons pull-right"></i>
													<span class="serviceInfoDetails">
														<?php echo lang('Account:icon_cloudProvider') ?>
													</span>
										</span>
										</div>
									</div>

								<div class="populateFields">
								
								</div>
								<div class="control-group">
														<div class="controls">
															<button id="Account_save" name="Account_save" class="btn btn-success xervmon-btn-big"><?= lang('Account:save') ?></button>
															<?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel'))) ?>
															<input type="hidden" id="id" name="id" value="<?php
																if (isset($post -> id))
																	echo $post -> id;
																else
																	'';
 ?>" />
														</div>
													</div>

								</div>
							</fieldset>
						</div>
					</form>
		</div>
	</div>
<span class="pull-right" style="color:#FF0000">*Mandatory</span>
<style>
.form-field input[type="text"], .form-field input[type="password"], .form-field input[type="email"], .form-field input[type="number"], .tags {
    width: 50%;
}
h4 {
	display: flex;
	display: -webkit-box;
	display: -ms-flexbox;
}
</style>
<script>
	$(document ).ready(function() {
  		// Handler for .ready() called.
  		$(".cloudProvider").change(function() {
  			$('.populateFields').html(populateFields($(this).val()));
		});
	});
	
	populateFields = function (cloudProvider)
	{
		return 
		'<div class="control-group"> '+
				'<label class="control-label" for="title">API Access Key<span>*</span></label> '+
				'<div class="controls">'+
					'<input type="text" name="apiAccessKey" id="apiAccessKey" value=""  class="input-xlarge">'  +
				'</div>' +
		'</div>' +
		'<div class="control-group"> '+
				'<label class="control-label" for="title">Secret Access Key<span>*</span></label> '+
				'<div class="controls">'+
					'<input type="text" name="secretAccessKey" id="secretAccessKey" value=""  class="input-xlarge">'  +
				'</div>' +
		'</div>'		
	}
</script>