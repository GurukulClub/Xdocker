<!--
<div class="contentBlock">
	 	<h4>
		<div style="width:91%;">
				<?php echo lang('maintenance:export_data') ?></div> <span class="span1"> 
					<div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
							Export data
                        </div>
                        </div>
             </span>
         </h4>
	 
	 
<div class="contentBlockDetails">
			<?php if ( ! empty($tables)): ?>
				    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TableContainer7">
					<thead>
						<tr>
							<th><?php echo lang('maintenance:table_label') ?></th>
							<th class="align-center"><?php echo lang('maintenance:record_label') ?></th>
								<th>Actions</th>
						</tr>
					</thead>
					
					<tbody>
						<?php foreach ($tables as $table): ?>
						<tr>
							<td><?php echo $table['name'] ?></td>
							<td class="align-center"><?php echo $table['count'] ?></td>
							<td class="buttons buttons-small align-center actions">
								<?php if ($table['count'] > 0):
									echo anchor('admin/maintenance/export/'.$table['name'].'/xml', lang('maintenance:export_xml'), array('class'=>'button btn')).' ';
									echo anchor('admin/maintenance/export/'.$table['name'].'/csv', lang('maintenance:export_csv'), array('class'=>'button btn')).' ';
									echo anchor('admin/maintenance/export/'.$table['name'].'/json', lang('maintenance:export_json'), array('class'=>'button btn')).' ';
								endif ?>
							</td>
						</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif;?>


 </div>
 </div>	
-->
</br>
	
<div class="contentBlock">
  <h4>
		<div style="width:91%;">
				<?php echo lang('maintenance:list_label') ?></div> <span class="span1"> 
					<div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
							Cache Maintenance 
                        </div>
                        </div>
             </span>
   </h4>

		
	<div class="contentBlockDetails">
			<?php if ( ! empty($folders)): ?>
				 	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TableContainer1">
					<thead>
						<tr>
							<th><?php echo lang('name_label') ?></th>
							<th class="align-center"><?php echo lang('maintenance:count_label') ?></th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($folders as $folder): ?>
							
							<?php if(!in_array($folder['name'], array('codeigniter', 'navigation_m', 'theme_m',
																	  'page_m', 'streams_m', 'page_type_m'))) :?>
						<tr>
							<td><?php echo ucfirst($folder['name']) ?></td>
							<td class="align-center"><?php echo $folder['count'] ?></td>
							<!--<td class="buttons buttons-small align-center actions">
								<?php //if ($folder['count'] > 0) echo anchor('admin/maintenance/cleanup/'.$folder['name'], lang('global:empty'), array('class'=>'button empty btn')) ?>
								<?php //if ( ! $folder['cannot_remove']) echo anchor('admin/maintenance/cleanup/'.$folder['name'].'/1', lang('global:remove'), array('class'=>'button remove btn btn-danger' )) ?>
							</td>-->
						<td class="buttons buttons-small align-center actions">
								<?php if ($folder['count'] > 0) ?>
							  <a href="<?php echo site_url('admin/maintenance/cleanup/'.$folder['name'].'/1') ?>"> 
							<span class="empty" style="cursor:pointer" title="Empty"></span></a>	
								
							<?php if ( ! $folder['cannot_remove']) ?>
						   <a href="<?php echo site_url('admin/maintenance/cleanup/'.$folder['name'].'/1') ?>"> 
							<span class="delete" style="cursor:pointer" title="Delete"></span></a>	
							</td>
						</tr>
						<?php endif ?>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php else: ?>
				<div class="blank-slate">
					<h2><?php echo lang('maintenance:no_items') ?></h2>
				</div>
			<?php endif;?>
	
		</div>
	</div>
 


	
<script>
$(document).ready(function() {
	setupTableSorterChecked($('.table'), false, 20);
});


</script>

<style>
h4 {
	display: flex;
	display: -webkit-box;
	display: -ms-flexbox;
}
</style> 

