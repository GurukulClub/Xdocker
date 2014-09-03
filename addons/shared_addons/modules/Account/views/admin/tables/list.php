	<div class="contentBlockDetails">
	
			<?php 
			if (empty($accounts)){ ?>
	 <td><div class="no_data">No Data.. Please Click <a href="<?php echo site_url('admin/Account/create') ?>" >here</a> to add an account
						</div> </td> 
			<?php  } else { ?>
	
	<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TableContainer1">
		<thead>
			<tr>
				<th><?php echo lang('Account:account_id') ?></th>
				<th><?php echo lang('Account:name') ?></th>
				<th><?php echo lang('Account:api_key') ?></th>
				<th><?php echo lang('Account:created_on') ?></th>
				<th><?php echo lang('Account:created_by') ?></th>
				
				<th width="180"><?php echo lang('global:actions') ?></th>
			</tr>
		</thead>
		<tfoot>

		</tfoot>
		<tbody>
			<?php 
				foreach ($accounts as $post) : ?>
				<tr>
					<td><?php echo $post->account_id ?></td>
					<td><?php echo $post->name ?></td>
					<td class="collapse"><?php echo $post->api_key ?></td>
					<td class="collapse"><?php echo format_date($post->created_on) ?></td>
					<td class="collapse">
					<?php if (isset($post->display_name)): ?>
						<?php echo anchor('admin/UserProfile/edit/'.$post->user_id, $post->display_name, 'target="_blank"') ?>
					<?php else: ?>
						<?php echo lang('Account:author_unknown') ?>
					<?php endif ?>
					</td>
					
					<td style="padding-top:10px;">
						<a href="<?php echo site_url('admin/Account/edit/' . $post->id) ?>" title="<?php echo lang('global:edit')?>" ><span class="edit"></span></a>
						<a href="<?php echo site_url('admin/Account/delete/' . $post->id) ?>" title="<?php echo lang('global:delete')?>" ><span class="delete"></span></a>
					</td>
					
				</tr>
			<?php endforeach ?>
		
		</tbody>
	</table><?php }?>
</div>

<script>
		$(document).ready(function() {
	var pageSize = <?= json_encode($pageSize) ?>
		;
		setupTableSorterChecked ( $ ( '#TableContainer1' ) ,  false ,  pageSize );
		});
</script>
