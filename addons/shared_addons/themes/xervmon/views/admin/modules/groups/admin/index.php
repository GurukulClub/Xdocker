<style>h4{display:flex; display:-webkit-box;display: -ms-flexbox;}</style> 
 <div class="contentBlock">


		<h4><div style="width:94%;"><?php echo $module_details['name'] ?></div><div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
							List of Groups
                        </div>
                        </div>
	
</h4>


	<div class="content">
		<?php if ($groups): ?>
			<div class="contentBlockDetails">
                     
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TableContainer1">
				<thead>
					<tr>
						<th width="40%"><?php echo lang('groups:name');?></th>
						<th><?php echo lang('groups:short_name');?></th>
							<th width="300">Actions</th>
					</tr>
				</thead>
				<tfoot>
				
				</tfoot>
				<tbody>
				<?php foreach ($groups as $group):?>
					<tr>
						<td><?php echo $group->description ?></td>
						<td><?php echo $group->name ?></td>
						<td class="actions">
						<?php echo anchor('admin/groups/edit/'.$group->id, '<span class="edit"></span>') ?>
						<?php if ( ! in_array($group->name, array('user', 'admin'))): ?>
						<?php echo anchor('admin/groups/delete/'.$group->id, '<span class="delete"></span>') ?>
						<?php endif ?>
						<?php echo anchor('admin/permissions/group/'.$group->id, '<span class="permissionsIcon"></span>') ?>
						</td>
									
					</tr>
				<?php endforeach;?>
				</tbody>
			</table></div>
		
		<?php else: ?>
			<section class="title">
				<p><?php echo lang('groups:no_groups');?></p>
			</section>
		<?php endif;?>
	</div>
</div>








<script>
$(document).ready(function() {
	var pageSize = <?= json_encode($pageSize) ?>;
	setupTableSorterChecked($('#TableContainer1'), false, pageSize);
});
</script>

