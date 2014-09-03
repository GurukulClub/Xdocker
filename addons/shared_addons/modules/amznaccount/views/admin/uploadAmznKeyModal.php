<style>
	#profile_form td.label {
		width: 120px !important;
		padding: 2px;
	}
	#profile_form td.label {
		width: 120px !important;
		padding: 2px;
	}
	#profile_form td.value {
		width: 300px !important;
	}
	#profile_form td#email {disabled="disabled";
	}
	#profile_form td.label_first_name {
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;!important;
		font-size: 13px;!important;
	}
	.modal-header {
		background: none repeat scroll 0 0 #0099CC;
		border-bottom: 1px solid #CCCCCC;
		border-bottom-left-radius: 0;
		border-color: white white #CCCCCC;
		font-size: 18px;
		padding: 5px 15px;
		position: relative;
		text-decoration: none;
		display: inline-block;
		display: block;
		border: 1px solid #666666;
		font-weight: bold;
		border-top-left-radius: 4px;
		border-top-right-radius: 4px;
		border-bottom-left-radius: 4px;
		border-bottom-right-radius: 4px;
	}
	.header {
		border: 0px solid #AAAAAA;
		color: #404040;
		padding: 0.2em;
		font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
		font-size: 13px;
	}}
</style>

<div id="uploadKey_modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="header">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"><?=lang('amznaccount:import_key_pair') ?></h3>
  </div>
 </div>
 
  <form id="uploadKey_form">
  	  <div class="modal-body">
	    <p>
		    	<div id="uploadKey_import_form">
		    		
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
	    </p>
	  </div>
	  <div id="import_save_status"> </div>
	  <div class="modal-footer">
	   
	    <button id="import" class="btn"><?=lang('amznaccount:keyimport') ?></button>
	    <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true"><?=lang('amznaccount:cancel') ?></button>
	   
	  </div>
  </form>
</div>


