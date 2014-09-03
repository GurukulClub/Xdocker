<script type="text/javascript">
    jQuery(function($) {"use strict";

        <?php if ($this->session->flashdata('error')): ?>
        <?php log_message('error', $this -> session -> flashdata('error')); ?>
            $.pnotify({
                title : 'Error',
                text : '<?php echo json_encode($this -> session -> flashdata('error')); ?>',
                type : 'error',
                hide: false,
                closer_hover: false,
                history : false
            });
        <?php endif; ?>

        <?php if (validation_errors()): ?>
          <?php log_message('error', 'Validation Error ' .validation_errors()); ?>
            $.pnotify({
                title : 'Validation Error',
                text : '<?php echo json_encode(validation_errors()); ?>',
                type : 'error',
                hide: false,
                closer_hover: false,
                history : false
            });
        <?php endif; ?>

        <?php if ( ! empty($messages['error'])): ?>
        	<?php log_message('error', $messages['error']); ?>
          
            $.pnotify({
                title : 'Error',
                text : '<?php echo json_encode($messages['error']); ?>',
                type : 'error',
                hide: false,
                closer_hover: false,
                history : false
            });
        <?php endif; ?>

       <?php if ($this->session->flashdata('success')): ?>
       	<?php log_message('debug', $this->session->flashdata('success')); ?>
          
            $.pnotify({
                title : 'Success',
                text : '<?php echo json_encode($this -> session -> flashdata('success')); ?>',
                type : 'success',
                hide: true,
                closer_hover: false,
                history : false
            });
        <?php endif; ?>

        <?php if ( ! empty($messages['success'])): ?>
        <?php log_message('debug', $messages['success']); ?>
            $.pnotify({
                title : 'Success',
                text : '<?php echo json_encode($messages['success']); ?>',
                type : 'success',
                hide: true,
                closer_hover: false,
                history : false
            });
        <?php endif; ?>

        <?php if ($this->session->flashdata('warning')): ?>
             $.pnotify({
                title : 'Warning',
                text : '<?php echo json_encode($this -> session -> flashdata('warning')); ?>',
                type : 'warning',
                hide: false,
                closer_hover: false,
                history : false
            });
        <?php endif; ?>
        
        <?php if ($this->session->flashdata('info')): ?>
         
            $.pnotify({
                title : 'info',
                text : '<?php echo json_encode($this -> session -> flashdata('info')); ?>',
                type : 'info',
                hide: false,
                closer_hover: false,
                history : false
            });
        <?php endif; ?>

        <?php if ( ! empty($messages['warning'])): ?>
            $.pnotify({
                title : 'Warning',
                text : '<?php echo json_encode($messages['warning']); ?>',
                type : 'warning',
                hide: false,
                closer_hover: false,
                history : false
            });
        <?php endif; ?>
       

    });
</script>

<?php if ($this->session->flashdata('notice')): ?>
<div class="alert warning animated fadeIn">
	<p><?php echo $this->session->flashdata('notice');?></p>
</div>
<?php endif; ?>

<?php if ( ! empty($messages['notice'])): ?>
<div class="alert warning animated fadeIn">
	<p><?php echo $messages['notice']; ?></p>
</div>
<?php endif; ?>

 <!-- In case Admins need to send a notifications we will use 'global_admin_notice' as param in session -->
        <?php if ( ! empty($messages['global_admin_notice'])): ?>
          <div class="alert warning animated fadeIn">
			<p><?php echo $this->session->flashdata('global_admin_notice');?></p>
		  </div>
        <?php endif; ?>

<?php

    /**
     * Admin Notification Event
     */
    Events::trigger('admin_notification');
?>