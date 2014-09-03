<?php if(!empty($templates)): ?>
     <div class="contentBlock">
 <!--<h4>  <div class="span10"><?php echo lang('templates:default_title') ?></div><div class="span2" style="text-align: right;"><a href="<?php echo site_url('/admin/templates/create') ?>" class="btn btn-info"><?= lang('templates:create_title')?></a></div></h4> -->
 
   <h4>
  <div style="width:98%;">
    <?php echo lang('templates:default_title') ?></div> 
     <div class="serviceInfoHover pull-right tooltipQuest">
                        <i class="serviceInfoIcon icons pull-right"></i>
                        <div class="serviceInfoDetails">
       Emails of the users
                        </div>
                        </div>
        
         </h4>
  <div class="contentBlockDetails">	
<div class="accordion-group ">
	
	<?php echo form_open('admin/templates/delete') ?>
	   
	<div class="accordion-body collapse in lst">
		<div class="content">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="TableContainer1">
		        <thead>
		            <tr>
		               <!-- <th><?php echo form_checkbox(array('name' => 'action_to_all', 'class' => 'check-all'));?></th>-->
		                <th><?php echo lang('name_label') ?></th>
		                <th><?php echo lang('global:description') ?></th>
		                <th><?php echo lang('templates:language_label') ?></th>
		                <th width="220">Actions</th>
		            </tr>
		        </thead>
		
		        <tbody>
			
		    <?php foreach ($templates as $template): ?>
				<?php if(!$template->is_default): ?>
		            <tr>
						<!-- <td><?php echo form_checkbox('action_to[]', $template->id);?></td>-->
		                <td><?php echo $template->name ?></td>
		                <td><?php echo $template->description ?></td>
		                <td><?php echo $template->lang ?></td>
		                <td class="actions">
						<div class="buttons buttons-small align-center">
							<?php echo anchor('admin/templates/edit/'.$template->id, '<span class="edit"></span>') ?>
							<?php echo anchor('admin/templates/delete/' . $template->id, '<span class="delete"></span>') ?>
						</div>
		                </td>
		            </tr>
				<?php endif ?>
		    <?php endforeach ?>
			
			
		        </tbody>
		    </table>
		
			<div class="table_action_buttons">
				<?php //$this->load->view('admin/partials/buttons', array('buttons' => array('delete') )) ?>
			</div>
		
		    <?php echo form_close() ?>
		</div>
	</div>
</div>
	
<?php else: ?>

<div class="accordion-group ">
<div class="accordion-heading">
		<div class="content">
	    <p><?php echo lang('templates:currently_no_templates') ?></p>
		</div>
	</div>
</div>

<?php endif ?>
</div></div>

<style>h4{display:flex; display:-webkit-box;display: -ms-flexbox;}</style> 

<script>
$(document).ready(function() {
	//var pager = '<div class="clearfix showmoreBtn-wrapper"><div class="showmoreBtn"></div></div>';
	var pageSize = '<?= $pageSize; ?>'; 
	setupTableSorterChecked($('#TableContainer1'), false, pageSize);
});
</script>