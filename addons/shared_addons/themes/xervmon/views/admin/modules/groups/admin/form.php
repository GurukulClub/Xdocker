<div class="contentBlock">
        <div class="contentBlockDetails">
<div class="span9 content">
	
								<?php echo form_open(uri_string(), 'class="form-horizontal crud"') ?>
									<fieldset>
											<div class="control-group">
											<label class="control-label" for="description"><?php echo lang('groups:name');?><span>*</span></label>
											<div class="controls">
											<?php echo form_input('description', $group->description);?>
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="name"><?php echo lang('groups:short_name');?> <span>*</span></label>
											<div class="controls">
											<?php if ( ! in_array($group->name, array('user', 'admin'))): ?>
											<?php echo form_input('name', $group->name);?>
											<?php else: ?>
												<p><?php echo $group->name ?></p>
											<?php endif ?>
											</div>
										</div>
										
										<div class="control-group">
											<div class="controls">
												<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )) ?>
											</div>
										</div>

									</fieldset>
								</form>
				
				</div> 
	
</div></div>

<span class="pull-right" style="color:#FF0000">*Mandatory</span>
<script type="text/javascript">
	jQuery(function($) {
		$('form input[name="description"]').keyup($.debounce(300, function(){

			var slug = $('input[name="name"]');

			$.post(SITE_URL + 'ajax/url_title', { title : $(this).val() }, function(new_slug){
				slug.val( new_slug );
			});
		}));
	});
</script>

