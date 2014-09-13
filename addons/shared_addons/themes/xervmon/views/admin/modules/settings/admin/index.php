<?php echo Asset::css('modules/settings/settings.css');?>
		<?php if ($setting_sections): ?>
			<?php echo form_open('admin/settings/edit', 'class="crud"');?>
		
				<div class="tabMain ">
		
					<div class="tabMainTop"><ul id="myTab" class="nav nav-tabs">
						<?php $firsttabmenu = 0; foreach ($setting_sections as $section_slug => $section_name): ?>
						<li <?php if($firsttabmenu == 0){echo 'class="active"';};$firsttabmenu++;?>>
							<a href="#<?php echo $section_slug ?>" title="<?php printf(lang('settings:section_title'), $section_name) ?>" data-toggle="tab">
								<span><?php echo $section_name ?></span>
							</a>
						</li>
						<?php endforeach ?>
					</ul></div>
                     <div id="myTabContent" class="tab-content">
					<?php $firsttabdiv = 0; foreach ($setting_sections as $section_slug => $section_name): ?>
					<div class="tab-pane <?php if($firsttabdiv == 0){echo "active";};$firsttabdiv++;?>" id="<?php echo $section_slug;?>">
						<div class="contentBlock">
							<div class="contentBlockDetails">
								<?php $section_count = 1; foreach ($settings[$section_slug] as $setting): ?>
								 <div class="form-horizontal">
								<div class="form-field">
									<div style="margin-bottom:15px !important;" class="control-group" >
										<div id="<?php echo $setting->slug ?>" class="<?php echo $section_count++ % 2 == 0 ? 'even' : '' ?>" >
										<label class="control-label" for="<?php echo $setting->slug ?>">
												<?php echo $setting->title ?>
										</label>
											<div class="controls">
											<span class="input <?php echo 'type-'.$setting->type ?>">
												<?php echo $setting->form_control ?>
											</span>
											<span class="serviceInfoHover infoConfig">
												<i class="serviceInfoIcon icons pull-left"></i>
													<span class="serviceInfoDetails">
														<?php echo $setting->description ?>
													</span>
											</span>
											</div>
											</div>
										</div>
										</div>
									</div>
									<?php endforeach ?>
				<div class="buttons padding-top">
					<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save') )) ?>
					<?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel') )) ?>
				</div>
							</div>
						</div>
					</div>
					<?php endforeach ?>
		
				</div>
		</div>
		
			<?php echo form_close() ?>
		<?php else: ?>
			<div>
				<p><?php echo lang('settings:no_settings');?></p>
			</div>
		<?php endif ?>
 </div>
 
 <style>
 	.chosen-container{max-width:500px;}
	.icons
	{
		background: url(addons/shared_addons/themes/xervmon/img/sprite.png) no-repeat;
		width:16px;
		height:16px;
		margin-right:8px;
		background-position:-145px -166px;
	}	
 </style>
 