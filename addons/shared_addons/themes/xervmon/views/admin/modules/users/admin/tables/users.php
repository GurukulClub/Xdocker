<?php if (!empty($users)): ?>
		<div class="contentBlockDetails">
                       
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TableContainer7">
		<thead>
			<tr>
				<th><?php echo lang('user:name_label');?></th>
				<th><?php echo lang('user:email_label');?></th>
				<th><?php echo lang('user:group_label');?></th>
				<th><?php echo lang('user:active') ?></th>
				<th><?php echo lang('user:joined_label');?></th>
				<th><?php echo lang('user:last_visit_label');?></th>
				<th><?php echo lang('user:actions');?></th>
			</tr>
		</thead>

		<tfoot>
		</tfoot>
		<tbody>
			<?php $link_profiles = Settings::get('enable_profiles') ?>
			<?php foreach ($users as $member): ?>
				<?php if ($member->display_name == 'Xervmon Admin') : ?>
				<tr></tr>
				<?php else: ?>
					<tr>
						<td>
						<?php if ($link_profiles) : ?>
							<?php echo anchor('admin/users/preview/' . $member->id, $member->display_name, 'target="_blank" class="modal-large"') ?>
						<?php else: ?>
							<?php echo $member->display_name ?>
						<?php endif ?>
						</td>
						<td ><?php echo mailto($member->email) ?></td>
						<td><?php echo $member->group_name ?></td>
						<td ><?php echo $member->active ? lang('global:yes') : lang('global:no')  ?></td>
						<td ><?php echo format_date($member->created_on) ?></td>
						<td ><?php echo ($member->last_login > 0 ? format_date($member->last_login) : lang('user:never_label')) ?></td>
						<td class="actions">
							<?php echo anchor('admin/users/edit/' . $member->id, '<span class="edit"></span>') ?>
							<?php echo anchor('admin/users/delete/' . $member->id, '<span class="delete"></span>') ?>
						</td>
						
					</tr>
				<?php endif ?>
			<?php endforeach ?>
		</tbody>
	</table></div>
<?php endif ?>



<script>
$(document).ready(function() {
	var pageSize = <?= json_encode($pageSize) ?>;
	setupTableSorterChecked($('#TableContainer7'), false, pageSize);
});
</script>