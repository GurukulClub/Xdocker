 <div class="contentBlock">
		<h4>
		<div style="width:98%;">
				<?php echo $group->description ?></div> 
					<div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
							Permission Details for <?php echo $group->description ?>
                        </div>
                        </div>
        
         </h4>

	
<div class="contentBlockDetails">
	<div class="content">
		<?php echo form_open(uri_string(), array('class'=> 'crud', 'id'=>'edit-permissions')) ?>
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TableContainer7">
			<thead>
				<tr>
					<!--		<th><?php echo form_checkbox(array('id'=>'check-all', 'name' => 'action_to_all', 'class' => 'check-all', 'title' => lang('permissions:checkbox_tooltip_action_to_all'))) ?></th> -->
					<th style="width:20% !important;"><?php echo lang('permissions:module') ?></th>
					<th><?php echo lang('permissions:roles') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($permission_modules as $module): ?>
					<?php //echo '<pre>'; print_r($module); die();?>
					<?php if(in_array($module['slug'], array('blog', 'comments', 'contacts', 
															'domains', 'files', 'keywords', 
															'navigation', 'pages', 'streams',
															'variables', 'widgets'
															
															))) continue;?>
					<tr>
						<td>
						<label class="inline" for="<?php echo $module['slug'] ?>">
							<?php echo form_checkbox(array(
								'id'=> $module['slug'],
								'class' => 'select-row',
								'value' => true,
								'name'=>'modules['.$module['slug'].']',
								'checked'=> array_key_exists($module['slug'], $edit_permissions),
								'title' => sprintf(lang('permissions:checkbox_tooltip_give_access_to_module'), $module['name']),
							)) ?>
								<?php echo $module['name'] ?>
						</label>
						</td>
						<td>
						<?php if ( ! empty($module['roles'])): ?>
							<?php foreach ($module['roles'] as $role): ?>
							<label class="inline">
								<?php echo form_checkbox(array(
									'class' => 'select-rule',
									'name' => 'module_roles['.$module['slug'].']['.$role.']',
									'value' => true,
									'checked' => isset($edit_permissions[$module['slug']]) AND array_key_exists($role, (array) $edit_permissions[$module['slug']])
								)) ?>
								<?php echo lang($module['slug'].':role_'.$role) ?>
							</label>
							<?php endforeach ?>
						<?php endif ?>
						</td>
					</tr>
					<?php //endif ?>
				<?php endforeach ?>
			</tbody>
		</table>
		<div class="buttons float-right padding-top">
			<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel'))) ?>
		</div>
		<?php echo form_close() ?>
	</div>

</div></div>


<script>
 $(document).ready(function() {
	
	setupTableSorterChecked($('#TableContainer7'), false, 20);
 });
 
</script>
<style>h4{display:flex; display:-webkit-box;display: -ms-flexbox;} label {
    font-weight: normal !important;
}</style> 
