 <div class="contentBlock">

		<h4>
		<div style="width:98%;"><?php echo $module_details['name'] ?></div> 
					<div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
						Permission for different Accounts
                        </div>
                        </div>
        
         </h4>

	<div class="contentBlockDetails">
	<div class="content">
		<p><?php echo lang('permissions:introduction') ?></p>
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TableContainer7">
			<thead>
				<tr>
					<th><?php echo lang('permissions:group') ?></th>
					<th>Permission Details</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($groups as $group): ?>
				<tr>
					<td><?php echo $group->description ?></td>
					<td class="buttons actions">
						<?php if ($admin_group != $group->name):?>
						<?php echo anchor('admin/permissions/group/' . $group->id, lang('permissions:edit'), array('class'=>'button btn')) ?>
						<?php else: ?>
						<?php echo lang('permissions:admin_has_all_permissions') ?>
						<?php endif ?>
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
</div>

<script>
$(document).ready(function() {
	var pageSize = <?= json_encode($pageSize) ?>;
	setupTableSorterChecked($('#TableContainer7'), false, pageSize);
});
</script>
<style>h4{display:flex; display:-webkit-box;display: -ms-flexbox;}</style> 