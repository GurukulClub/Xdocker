<section class="title">
	<h4><?php echo lang('amznaccount:upload') ?></h4>
</section>

<section class="item one_half">
<div class="content">

<?php echo form_open($this->uri->uri_string(), 'class="crud'.((isset($mode)) ? ' '.$mode : '').'" id="uploadamznKeyForm" enctype="multipart/form-data" ') ?>
	<div class="form_inputs" id="blog-content-tab">
		<fieldset>
			<ul>
				<li>
					<label for="title"><?php echo lang('amznaccount:title') ?> <span>*</span></label>
					<div class="input">
						<?php 
						
						echo form_dropdown('amazon_account_id', $accounts, '1')
						
						?>
						 
					</div>
				</li>
				<li>
					<label for="title"><?php echo lang('amznaccount:key_pair_name') ?> <span>*</span></label>
					<div class="input">
						
						<?php
							$attributes = array('id' => 'key_pair_name', 'name' => 'key_pair_name', 'value' => $post -> key_pair_name, 'maxlength' => '100', 'data-val' => 'true', 'data-val-required' => lang('amznaccount:error_key_pair_name'), );

							echo form_input($attributes);
						?>
						 <span class="field-validation-valid" data-valmsg-for="key_pair_name" data-valmsg-replace="true"> </span>
					</div>
				</li>
				<li>
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="input-append">
						<div class="uneditable-input span3"><i class="icon-file fileupload-exists"></i> 
							<span class="fileupload-preview"></span></div><span class="btn btn-file">
								<span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span>
								<input type="file" accept="application/x-x509-user-cert"/></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">X</a>
						</div>
					</div>
					<span id="amzn_file_type_error"></span>
				</li>
			
	
			</ul>
		<?php //echo form_hidden('preview_hash', $post->preview_hash) ?>
		</fieldset>
	</div>

	

<div class="buttons">
	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('import', 'cancel'))) ?>
</div>

<?php echo form_close() ?>

</div>
</section>
<script>
	$ ( document ).ready ( function ( )
	{
		$ ( "form" ).validate ( );
		processKeyUploadForm ( );
	} ); 
</script>